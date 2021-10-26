<?php
namespace PayGateApi\Objects;

use PayGateApi\PaymentGateApiException;

final class CheckStatePaymentRequest extends AbstractSignableObject
{
    use AbstractMsgIdObject;

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
     * @var int|null $orderno
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

    public function __construct(string $mid, string $amt, ?int $orderno = null, string $vs = '', string $ss = '',
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

    public function getOrderno(): ?int
    {
        return $this->orderno;
    }

    public function setOrderno(?int $orderno): void
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
        return $this->ss !== null ? $this->ss : '';
    }

    public function setSs(string $ss): void
    {
        $this->ss = $ss;
    }

    public function getKs(): string
    {
        return $this->ks !== null ? $this->ks : '';
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

    public function getPathParameter(): ?string
    {
        return null;
    }

    public function getQuery(): string
    {
        return '?' .
            http_build_query(
                array(
                    'msg-id' => $this->msgId,
                    'mid' => $this->mid,
                    'amt' => $this->amt,
                    'vs' => $this->vs,
                    'ks' => $this->ks,
                    'ss' => $this->ss,
                    'orderno' => $this->orderno,
                    'sign' => $this->sign
                ));
    }
}
