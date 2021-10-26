<?php
namespace PayGateApi\Objects;

use JMS\Serializer\Annotation\Type;
use PayGateApi\PaymentGateApiException;

abstract class AbstractSignableObject
{

    /**
     *
     * @Type("string")
     *
     * @var string|null $sign
     */
    protected $sign;

    public function getSign(): ?string
    {
        return $this->sign;
    }

    public function setSign(?string $sign): void
    {
        $this->sign = $sign;
    }

    public function generateSign($key): void
    {
        $priv_key_id = openssl_pkey_get_private($key);
        if (! $priv_key_id) {
            throw new PaymentGateApiException(openssl_error_string());
        }
        $signature = '';
        if (! openssl_sign($this->getCheckString(), $signature, $priv_key_id, 'sha256WithRSAEncryption')) {
            throw new PaymentGateApiException(openssl_error_string());
        }
        $this->sign = base64_encode($signature);
    }

    public function verifySign($cert): bool
    {
        $checkString = $this->getCheckString();
        $pub_key_id = openssl_pkey_get_public($cert);
        if ($pub_key_id === false) {
            throw new PaymentGateApiException(openssl_error_string());
        }
        $verify = openssl_verify($checkString, base64_decode($this->sign), $pub_key_id, 'sha256WithRSAEncryption');
        if ($verify === - 1) {
            throw new PaymentGateApiException(openssl_error_string());
        }
        return boolval($verify);
    }

    abstract public function getCheckString(): string;
}
