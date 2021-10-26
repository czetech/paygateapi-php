<?php
namespace PayGateApi\Objects;

use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Type;
use PayGateApi\PaymentGateApiException;
use DateTime;

final class PaymentRequest extends AbstractSignableObject
{
    use AbstractSerializableObject, AbstractSerializableObject;

    /**
     *
     * @var string $mid
     */
    private $mid;

    /**
     *
     * @Type("DateTime<'Y-m-d\TH:i:s'>")
     *
     * @var DateTime $timestamp
     */
    private $timestamp;

    /**
     *
     * @var string $rurl
     */
    private $rurl;

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

    /**
     *
     * @SerializedName("add-info")
     *
     * @var string $addInfo
     */
    private $addInfo;

    /**
     *
     * @var string $lang
     */
    private $lang;

    public function __construct(string $mid, DateTime $timestamp, string $rurl, string $amt, ?int $orderno = null,
        string $vs = '', string $ss = '', string $ks = '', string $addInfo = '', string $lang = '')
    {
        $this->setMid($mid);
        $this->setTimestamp($timestamp);
        $this->setRurl($rurl);
        $this->setAmt($amt);
        $this->setOrderno($orderno);
        $this->setVs($vs);
        $this->setSs($ss);
        $this->setKs($ks);
        $this->setAddInfo($addInfo);
        $this->setLang($lang);
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

    public function getTimestamp(): DateTime
    {
        return $this->timestamp;
    }

    public function setTimestamp(DateTime $timestamp): void
    {
        $this->timestamp = $timestamp;
    }

    public function getRurl(): string
    {
        return $this->rurl;
    }

    public function setRurl(string $rurl): void
    {
        if ($rurl === '') {
            throw new PaymentGateApiException('$rurl is empty');
        }
        $this->rurl = $rurl;
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

    public function getAddInfo(): string
    {
        return base64_decode($this->addInfo);
    }

    public function setAddInfo(string $addInfo): void
    {
        $this->addInfo = base64_encode($addInfo);
    }

    public function getLang(): string
    {
        return $this->lang;
    }

    public function setLang(string $lang): void
    {
        $this->lang = $lang;
    }

    public function getCheckString(): string
    {
        return $this->mid . '|' . $this->amt . '|' . $this->vs . '|' . $this->ks . '|' . $this->ss . '|' . $this->rurl .
            '|' . $this->addInfo . '|' . $this->timestamp->format('Y-m-d\TH:i:s');
    }
}
