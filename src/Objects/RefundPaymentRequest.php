<?php
namespace PayGateApi\Objects;

use PayGateApi\PaymentGateApiException;

final class RefundPaymentRequest extends AbstractSignableObject
{
    use AbstractMsgIdObject, AbstractSerializableObject;

    /**
     *
     * @var string $mid
     */
    private $mid;

    /**
     *
     * @var string $amt
     */
    private $amt;

    /**
     *
     * @var int $orderno
     */
    private $orderno;

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

    public function __construct(string $mid, string $amt, int $orderno, string $vs = '', string $ss = '',
        string $ks = '', ?string $msgId = null)
    {
        $this->setMid($mid);
        $this->setAmt($amt);
        $this->setOrderno($orderno);
        $this->setVs($vs);
        $this->setSs($ss);
        $this->setKs($ks);
        $this->setMsgId($msgId);
    }

    public function getMid(): string
    {
        return $this->mid;
    }

    public function setMid(string $mid): void
    {
        if ($mid === '') {
            throw new PaymentGateApiException('$mid is empty');
        }
        $this->mid = $mid;
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

    public function getOrderno(): int
    {
        return $this->orderno;
    }

    public function setOrderno(int $orderno): void
    {
        $this->orderno = $orderno;
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
        return $this->msgId . '|' . $this->mid . '|' . $this->amt . '|' . $this->vs . '|' . $this->ks . '|' . $this->ss .
            '|' . $this->orderno;
    }
}
