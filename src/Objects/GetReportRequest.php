<?php
namespace PayGateApi\Objects;

use PayGateApi\PaymentGateApiException;

final class GetReportRequest extends AbstractSignableObject
{
    use AbstractMsgIdObject;

    /**
     *
     * @var string $mid
     */
    private $mid;

    /**
     *
     * @var int $reportId
     */
    private $reportId;

    public function __construct(string $mid, string $reportId, ?string $msgId = null)
    {
        $this->setMid($mid);
        $this->setReportId($reportId);
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

    public function getReportId(): int
    {
        return $this->reportId;
    }

    public function setReportId(int $reportId): void
    {
        $this->reportId = $reportId;
    }

    public function getCheckString(): string
    {
        return $this->reportId . '|' . $this->msgId . '|' . $this->mid;
    }

    public function getPathParameter(): ?string
    {
        return strval($this->reportId);
    }

    public function getQuery(): string
    {
        return '?' .
            http_build_query(
                array(
                    'msg-id' => $this->getMsgid(),
                    'mid' => $this->getMid(),
                    'sign' => $this->getSign()
                ));
    }
}
