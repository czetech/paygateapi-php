<?php
namespace PayGateApi\Objects;

use JMS\Serializer\Annotation\Type;

final class ResultReport
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
     * @Type("integer")
     *
     * @var int $orderno
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

    public function getOrderno(): int
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
}
