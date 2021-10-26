<?php
namespace PayGateApi;

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

interface PaymentGateInterface
{

    /**
     * Checks the availability of the payment gateway.
     *
     * @return bool True if payment gateway is awailable.
     */
    public function callIsActive(): bool;

    /**
     * Downloads the payment gateway certificate.
     *
     * @return resource An X.509 certificate resource.
     */
    public function callGetCertificate();

    /**
     * Makes a payment request call.
     *
     * @param mixed $key
     *            key can be one of the following:
     *            1. A string having the format file://path/to/file.pem. The named file must contain a PEM encoded
     *            certificate/private key (it may contain both).
     *            2. A PEM formatted private key.
     */
    public function callPaymentRequest(PaymentRequest $paymentRequest, $key): string;

    /**
     * Makes a payment status request call
     *
     * @param mixed $key
     *            key can be one of the following:
     *            1. A string having the format file://path/to/file.pem. The named file must contain a PEM encoded
     *            certificate/private key (it may contain both).
     *            2. A PEM formatted private key.
     * @param mixed $cert
     *            cert can be one of the following:
     *            1. An X.509 certificate resource.
     *            2. A string having the format file://path/to/file.pem. The named file must contain a PEM encoded
     *            certificate/public key (it may contain both).
     *            3. A PEM formatted public key.
     */
    public function callCheckStatePayment(CheckStatePaymentRequest $checkStatePaymentRequest, $key, $cert): array;

    /**
     * Makes a refund request call
     *
     * @param mixed $key
     *            key can be one of the following:
     *            1. A string having the format file://path/to/file.pem. The named file must contain a PEM encoded
     *            certificate/private key (it may contain both).
     *            2. A PEM formatted private key.
     * @param mixed $cert
     *            cert can be one of the following:
     *            1. An X.509 certificate resource.
     *            2. A string having the format file://path/to/file.pem. The named file must contain a PEM encoded
     *            certificate/public key (it may contain both).
     *            3. A PEM formatted public key.
     */
    public function callRefundPayment(RefundPaymentRequest $refundPaymentRequest, $key, $cert): RefundPaymentResult;

    /**
     * Makes a refund status request call
     *
     * @param mixed $key
     *            key can be one of the following:
     *            1. A string having the format file://path/to/file.pem. The named file must contain a PEM encoded
     *            certificate/private key (it may contain both).
     *            2. A PEM formatted private key.
     * @param mixed $cert
     *            cert can be one of the following:
     *            1. An X.509 certificate resource.
     *            2. A string having the format file://path/to/file.pem. The named file must contain a PEM encoded
     *            certificate/public key (it may contain both).
     *            3. A PEM formatted public key.
     */
    public function callCheckStateRefundPayment(CheckStateRefundPaymentRequest $checkStateRefundPaymentRequest, $key,
        $cert): RefundStateResponse;

    /**
     * Makes a report request call
     *
     * @param mixed $key
     *            key can be one of the following:
     *            1. A string having the format file://path/to/file.pem. The named file must contain a PEM encoded
     *            certificate/private key (it may contain both).
     *            2. A PEM formatted private key.
     */
    public function callGenerateReports(ReportRequest $reportRequest, $key): ResultCreateReport;

    /**
     * Makes a get report request call
     *
     * @param mixed $key
     *            key can be one of the following:
     *            1. A string having the format file://path/to/file.pem. The named file must contain a PEM encoded
     *            certificate/private key (it may contain both).
     *            2. A PEM formatted private key.
     */
    public function callGetReport(GetReportRequest $getReportRequest, $key): ReportResultObject;
}
