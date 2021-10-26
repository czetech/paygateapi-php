<?php
namespace PayGateApi;

use PHPUnit\Framework\TestCase;
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
use PayGateApi\Objects\ResultForMerchant;
use DateTime;

final class PaymentGateTest extends TestCase
{

    public function setUp(): void
    {
        date_default_timezone_set('Europe/Bratislava');
        $envUrl = getenv('PAYGATEAPI_URL');
        $envKey = getenv('PAYGATEAPI_KEY');
        $this->key = 'file://' . ($envKey ? $envKey : 'key.pem');
        $this->paymentGate = new PaymentGate($envUrl ? $envUrl : PaymentGate::URL_API);
    }

    public function testCallIsActive(): void
    {
        $status = $this->paymentGate->callIsActive();
        $this->assertTrue($status);
    }

    public function testCallGetCertificate()
    {
        $cert = $this->paymentGate->callGetCertificate();
        $this->assertTrue(is_resource($cert));

        return $cert;
    }

    public function testCallPaymentRequest(): void
    {
        $paymentRequest = new PaymentRequest('MID00005', new DateTime(), 'http://localhost:4200/', '5');
        $paymentRequest->setVs('1234567891');
        $paymentRequest->setSs('1111');
        $paymentRequest->setKs('1111');
        $paymentRequest->setAddInfo('{"name":"Janko Mrkvicka","email":"janko.mrkvicka@zahradka.io"}');
        $paymentRequest->setLang(PaymentGate::PAYMENT_GATE_LANGUAGE_SK);
        $location = $this->paymentGate->callPaymentRequest($paymentRequest, $this->key);
        $this->assertTrue(is_string($location) && $location !== '');
    }

    /**
     *
     * @depends testCallGetCertificate
     */
    public function testCallCheckStatePayment($cert): void
    {
        $checkStatePaymentRequest = new CheckStatePaymentRequest("MID00005", '78');
        $checkStatePaymentRequest->setVs('1234567891');
        $arrayStatusResponse = $this->paymentGate->callCheckStatePayment($checkStatePaymentRequest, $this->key, $cert);
        $this->assertTrue(is_array($arrayStatusResponse));
    }

    /**
     *
     * @depends testCallGetCertificate
     */
    public function testCallRefundPayment($cert): RefundPaymentResult
    {
        $refundPaymentRequest = new RefundPaymentRequest("MID00005", '0.5', 147741);
        $refundPaymentRequest->setVs('1234567891');
        $refundPaymentResult = $this->paymentGate->callRefundPayment($refundPaymentRequest, $this->key, $cert);
        $this->assertInstanceOf(RefundPaymentResult::class, $refundPaymentResult);

        return $refundPaymentResult;
    }

    /**
     *
     * @depends testCallGetCertificate
     * @depends testCallRefundPayment
     */
    public function testCallCheckStateRefundPayment($cert, $refundPaymentResult): void
    {
        $checkStateRefundPaymentRequest = new CheckStateRefundPaymentRequest("MID00005",
            $refundPaymentResult->getRefundId());
        $refundStateResponse = $this->paymentGate->callCheckStateRefundPayment($checkStateRefundPaymentRequest,
            $this->key, $cert);
        $this->assertInstanceOf(RefundStateResponse::class, $refundStateResponse);
    }

    public function testCallGenerateReports(): ResultCreateReport
    {
        $reportRequest = new ReportRequest("MID00005", new DateTime());
        $resultCreateReport = $this->paymentGate->callGenerateReports($reportRequest, $this->key);
        $this->assertInstanceOf(ResultCreateReport::class, $resultCreateReport);

        return $resultCreateReport;
    }

    /**
     *
     * @depends testCallGenerateReports
     */
    public function testCallGetReport($resultCreateReport): void
    {
        $reportRequest = new GetReportRequest("MID00005", $resultCreateReport->getReportId());
        $reportResultObject = $this->paymentGate->callGetReport($reportRequest, $this->key);
        $this->assertInstanceOf(ReportResultObject::class, $reportResultObject);
        if (! $reportResultObject->getReady()) {
            sleep($reportResultObject->getTryAgain());
            $reportRequest = new GetReportRequest("MID00005", $resultCreateReport->getReportId());
            $reportResultObject = $this->paymentGate->callGetReport($reportRequest, $this->key);
            $this->assertInstanceOf(ReportResultObject::class, $reportResultObject);
            $this->assertTrue($reportResultObject->getReady());
        }
    }

    /**
     *
     * @depends testCallGetCertificate
     */
    public function testResultForMerchant($cert): void
    {
        $resultForMerchant = new ResultForMerchant('OK',
            '2b78e0dbae852e939ffd6afc176832dc4374977fc3f3ea5ed490bc1a433b6838', '5');
        $resultForMerchant->setVs('325423423');
        $resultForMerchant->setSign(
            'jFgC7HeFpgdNwq6KqQcvYgI8HZVilIHJrjD1zsnNC8ilQFYqTuV1E3Ad0ho0UCBa9cXRPhYpzhLDVHZ2B4vFODq4XaIIokkA6s1DMxje' .
            'lfQBuWu6g06mKPc/x5N9K4OhJZdUO0L0mCbL/CHuJIActSsLPJaTNwsrHJWXp+h81Sezo3D1wCw52LD2E7aPrE6QTM+LMXgz9tATY9iG' .
            'aNj00wfvl/Oe+qPPgKIsT4eLpMtvJ4++nUFH7Wy6a1/1NL+CVt9HeLhMIRivw4MlorujwKjvfBFDq0WwM0CMWwMxpfAYKtUv6KBk0Wrj' .
            'OjPa8hDNNeUzsyjPn01jvOuMoxLrWQ==');
        $validity = $resultForMerchant->verifySign($cert);
        $this->assertTrue($validity);
    }
}
