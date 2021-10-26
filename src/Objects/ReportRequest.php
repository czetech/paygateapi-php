<?php
namespace PayGateApi\Objects;

use JMS\Serializer\Annotation\Type;
use PayGateApi\PaymentGateApiException;
use DateTime;

final class ReportRequest extends AbstractSignableObject
{
    use AbstractMsgIdObject, AbstractSerializableObject;

    /**
     *
     * @var string $mid
     */
    private $mid;

    /**
     *
     * @Type("DateTime<'Y-m-d'>")
     *
     * @var DateTime $date
     */
    private $date;

    public function __construct(string $mid, DateTime $date, ?string $msgId = null)
    {
        $this->setMid($mid);
        $this->setDate($date);
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

    public function getDate(): DateTime
    {
        return $this->date;
    }

    public function setDate(DateTime $date): void
    {
        $this->date = $date;
    }

    public function getCheckString(): string
    {
        return $this->msgId . '|' . $this->mid . '|' . $this->date->format('Y-m-d');
    }
}
