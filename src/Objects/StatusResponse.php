<?php
namespace PayGateApi\Objects;

use JMS\Serializer\Annotation\Type;

final class StatusResponse extends AbstractSignableObject
{

    /**
     *
     * @Type("string")
     *
     * @var string $result
     */
    private $result;

    /**
     *
     * @Type("string")
     *
     * @var string $created
     */
    private $created;

    /**
     *
     * @Type("string")
     *
     * @var string $amt
     */
    private $amt;

    /**
     *
     * @Type("string")
     *
     * @var string $orderno
     */
    private $orderno;

    /**
     *
     * @Type("string")
     *
     * @var string $vs
     */
    private $vs;

    /**
     *
     * @Type("string")
     *
     * @var string $ss
     */
    private $ss;

    /**
     *
     * @Type("string")
     *
     * @var string $ks
     */
    private $ks;

    /**
     *
     * @Type("boolean")
     *
     * @var bool $refundable
     */
    private $refundable;

    /**
     *
     * @Type("string")
     *
     * @var string $refunded
     */
    private $refunded;

    public function getResult(): string
    {
        return $this->result;
    }

    public function getCreated(): string
    {
        return $this->created;
    }

    public function getAmt(): string
    {
        return $this->amt;
    }

    public function getOrderno(): string
    {
        return $this->orderno;
    }

    public function getVs(): string
    {
        return $this->vs;
    }

    public function getSs(): string
    {
        return $this->ss;
    }

    public function getKs(): string
    {
        return $this->ks;
    }

    public function isRefundable(): bool
    {
        return $this->refundable;
    }

    public function getRefunded(): string
    {
        return $this->refunded;
    }

    public function getCheckString(): string
    {
        return $this->result . '|' . number_format($this->amt, 2, '.', '') . '|' . $this->vs . '|' . $this->ks . '|' .
            $this->ss . '|' . $this->orderno . '|' . $this->created . '|' . $this->refunded . '|' .
            var_export($this->refundable, true);
    }
}
