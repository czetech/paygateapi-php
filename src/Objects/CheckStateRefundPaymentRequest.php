<?php
namespace PayGateApi\Objects;

use PayGateApi\PaymentGateApiException;

final class CheckStateRefundPaymentRequest extends AbstractSignableObject
{
    use AbstractMsgIdObject;

    /**
     *
     * @var string $mid
     */
    private $mid;

    /**
     *
     * @var int $refundId
     */
    private $refundId;

    public function __construct(string $mid, string $refundId, ?string $msgId = null)
    {
        $this->setMid($mid);
        $this->setRefundId($refundId);
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

    public function getRefundId(): int
    {
        return $this->refundId;
    }

    public function setRefundId(int $refundId): void
    {
        $this->refundId = $refundId;
    }

    public function getCheckString(): string
    {
        return $this->refundId . '|' . $this->msgId . '|' . $this->mid;
    }

    public function getPathParameter(): ?string
    {
        return strval($this->refundId);
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
