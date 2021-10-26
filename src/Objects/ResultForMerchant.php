<?php
namespace PayGateApi\Objects;

use PayGateApi\PaymentGateApiException;

final class ResultForMerchant extends AbstractSignableObject
{

    /**
     *
     * @var string $result
     */
    private $result;

    /**
     *
     * @var string $id
     */
    private $id;

    /**
     *
     * @var string $amt
     */
    private $amt;

    /**
     *
     * @var string $vs
     */
    private $vs;

    /**
     *
     * @var string $ss
     */
    private $ss;

    /**
     *
     * @var string $ks
     */
    private $ks;

    public function __construct(string $result, string $id, string $amt, string $vs = '', string $ss = '',
        string $ks = '', ?string $sign = null)
    {
        $this->setResult($result);
        $this->setId($id);
        $this->setAmt($amt);
        $this->setVs($vs);
        $this->setSs($ss);
        $this->setKs($ks);
        $this->setSign($sign);
    }

    public function getResult(): string
    {
        return $this->result;
    }

    public function setResult(string $result): void
    {
        $this->result = $result;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getAmt(): string
    {
        return $this->amt;
    }

    public function setAmt(string $amt): void
    {
        if (! is_numeric($amt)) {
            throw new PaymentGateApiException('$amt is not numeric');
        }
        $this->amt = $amt;
    }

    public function getVs(): string
    {
        return $this->vs;
    }

    public function setVs(string $vs): void
    {
        $this->vs = $vs;
    }

    public function getSs(): string
    {
        return $this->ss;
    }

    public function setSs(string $ss): void
    {
        $this->ss = $ss;
    }

    public function getKs(): string
    {
        return $this->ks;
    }

    public function setKs(string $ks): void
    {
        $this->ks = $ks;
    }

    public function getCheckString(): string
    {
        return $this->result . '|' . $this->id . '|' . $this->vs . '|' . $this->ss . '|' . $this->ks . '|' . $this->amt;
    }
}
