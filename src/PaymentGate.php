<?php
namespace PayGateApi;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use JMS\Serializer\SerializerBuilder;
use PayGateApi\Objects\CheckStatePaymentRequest;
use PayGateApi\Objects\CheckStateRefundPaymentRequest;
use PayGateApi\Objects\GetReportRequest;
use PayGateApi\Objects\PaymentRequest;
use PayGateApi\Objects\RefundPaymentRequest;
use PayGateApi\Objects\RefundPaymentResult;
use PayGateApi\Objects\RefundStateResponse;
use PayGateApi\Objects\ReportRequest;
use PayGateApi\Objects\ReportResultObject;
use PayGateApi\Objects\ResultCreateReport;

final class PaymentGate implements PaymentGateInterface
{

    private const ENDPOINT_GATE_CERTIFICATE = "gate/certificate";

    private const ENDPOINT_GATE_STATUS = "gate/status";

    private const ENDPOINT_PAYMENT = "payment-requests";

    private const ENDPOINT_PAYMENT_CHECK = "payment-requests/check-status";

    private const ENDPOINT_PAYMENT_REFUND = "payment-requests/refunds";

    private const ENDPOINT_PAYMENT_REPORT = "payment-requests/reports";

    public const PAYMENT_GATE_LANGUAGE_EN = "en";

    public const PAYMENT_GATE_LANGUAGE_SK = "sk";

    public const URL_API = "https://apipay.pokladnica.sk";

    private $client;

    private $serializer;

    /**
     *
     * @param string|null $url
     *            Optional API base URL.
     */
    public function __construct(?string $url = null)
    {
        $this->client = new Client(
            [
                'base_uri' => $url === null ? self::URL_API : $url,
                'allow_redirects' => false,
                'http_errors' => false
            ]);

        $this->serializer = SerializerBuilder::create()->build();
    }

    private function apiGet(string $endpoint, $requestObject = null): Response
    {
        $url = $endpoint;
        if ($requestObject !== null) {
            $urlComponents = array(
                $url
            );
            $pathParameter = $requestObject->getPathParameter();
            if ($pathParameter !== null) {
                array_push($urlComponents, $pathParameter);
            }
            $url = implode('/', $urlComponents);
            $url = $url . $requestObject->getQuery();
        }
        return $this->client->get($url);
    }

    private function apiPost(string $endpoint, $requestObject): Response
    {
        return $this->client->post($endpoint,
            [
                'body' => $requestObject->serialize(),
                'headers' => [
                    'Content-Type' => 'application/json; utf-8'
                ]
            ]);
    }

    private function deserializeResponse(Response $response, $type)
    {
        return $this->serializer->deserialize($response->getBody(), $type, 'json');
    }

    private function verifyResponseObject($responseObject, $cert): void
    {
        if (! $responseObject->verifySign($cert)) {
            $sign = $responseObject->getSign();
            $checkString = $responseObject->getCheckString();
            throw new PaymentGateApiException(
                "Server sign does not match (status response sign = $sign, check string = $checkString)");
        }
    }

    private function throwResponse(Response $response): void
    {
        $reasonPhrase = $response->getReasonPhrase();
        if ($reasonPhrase === null) {
            $msg = "Status code $response->getStatusCode()";
        } else {
            $msg = $reasonPhrase;
        }
        throw new PaymentGateApiException($msg);
    }

    public function callIsActive(): bool
    {
        $response = $this->apiGet(self::ENDPOINT_GATE_STATUS);
        return $response->getStatusCode() == 200;
    }

    public function callGetCertificate()
    {
        $response = $this->apiGet(self::ENDPOINT_GATE_CERTIFICATE);

        if ($response->getStatusCode() == 200) {
            $certificate = openssl_x509_read($response->getBody());
            if ($certificate === - 1) {
                throw new PaymentGateApiException(openssl_error_string());
            }
            return $certificate;
        } else {
            $this->throwResponse($response);
        }
    }

    public function callPaymentRequest(PaymentRequest $paymentRequest, $key): string
    {
        $paymentRequest->generateSign($key);

        $response = $this->apiPost(self::ENDPOINT_PAYMENT, $paymentRequest);
        $statusCode = $response->getStatusCode();
        $reasonPhrase = $response->getReasonPhrase();

        if ($statusCode == 302 || $statusCode == 303) {
            $location = $response->getHeader('Location')[0];
            if ($location !== null) {
                return $location;
            } else {
                throw new PaymentGateApiException("Header field Location is null");
            }
        } else {
            throw new PaymentGateApiException(
                "Returned status $statusCode ($reasonPhrase), but expected are 302 (HTTP_MOVED_TEMP) " .
                "or 303 (HTTP_SEE_OTHER)");
        }
    }

    public function callCheckStatePayment(CheckStatePaymentRequest $checkStatePaymentRequest, $key, $cert): array
    {
        $checkStatePaymentRequest->validateMsgId();
        $checkStatePaymentRequest->generateSign($key);

        $response = $this->apiGet(self::ENDPOINT_PAYMENT_CHECK, $checkStatePaymentRequest);

        if ($response->getStatusCode() == 200) {
            $statusResponses = $this->deserializeResponse($response, 'array<PayGateApi\Objects\StatusResponse>');
            foreach ($statusResponses as $statusResponse) {
                $this->verifyResponseObject($statusResponse, $cert);
            }
            return $statusResponses;
        } else {
            $this->throwResponse($response);
        }
    }

    public function callRefundPayment(RefundPaymentRequest $refundPaymentRequest, $key, $cert): RefundPaymentResult
    {
        $refundPaymentRequest->validateMsgId();
        $refundPaymentRequest->generateSign($key);

        $response = $this->apiPost(self::ENDPOINT_PAYMENT_REFUND, $refundPaymentRequest);

        if ($response->getStatusCode() == 200) {
            $refundPaymentResult = $this->deserializeResponse($response, RefundPaymentResult::class);
            $this->verifyResponseObject($refundPaymentResult, $cert);
            return $refundPaymentResult;
        } else {
            $this->throwResponse($response);
        }
    }

    public function callCheckStateRefundPayment(CheckStateRefundPaymentRequest $checkStateRefundPaymentRequest, $key,
        $cert): RefundStateResponse
    {
        $checkStateRefundPaymentRequest->validateMsgId();
        $checkStateRefundPaymentRequest->generateSign($key);

        $response = $this->apiGet(self::ENDPOINT_PAYMENT_REFUND, $checkStateRefundPaymentRequest);

        if ($response->getStatusCode() == 200) {
            $refundStatusResponse = $this->deserializeResponse($response, RefundStateResponse::class);
            $this->verifyResponseObject($refundStatusResponse, $cert);
            return $refundStatusResponse;
        } else {
            $this->throwResponse($response);
        }
    }

    public function callGenerateReports(ReportRequest $reportRequest, $key): ResultCreateReport
    {
        $reportRequest->validateMsgId();
        $reportRequest->generateSign($key);

        $response = $this->apiPost(self::ENDPOINT_PAYMENT_REPORT, $reportRequest);

        if ($response->getStatusCode() == 200) {
            return $this->deserializeResponse($response, ResultCreateReport::class);
        } else {
            $this->throwResponse($response);
        }
    }

    public function callGetReport(GetReportRequest $getReportRequest, $key): ReportResultObject
    {
        $getReportRequest->validateMsgId();
        $getReportRequest->generateSign($key);

        $response = $this->apiGet(self::ENDPOINT_PAYMENT_REPORT, $getReportRequest);

        if ($response->getStatusCode() == 200) {
            $reportResultObject = new ReportResultObject(true,
                $this->deserializeResponse($response, 'array<PayGateApi\Objects\ResultReport>'), null);
        } elseif ($response->getStatusCode() == 204) {
            $reportResultObject = new ReportResultObject(false, null, $response->getHeader('Retry-After')[0]);
        } else {
            $this->throwResponse($response);
        }

        return $reportResultObject;
    }
}
