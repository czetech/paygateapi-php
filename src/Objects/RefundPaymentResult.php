<?php
namespace PayGateApi\Objects;

use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Type;

final class RefundPaymentResult extends AbstractSignableObject
{
    use AbstractMsgIdObject;

    /**
     *
     * @SerializedName("refund-id")
     * @Type("integer")
     *
     * @var int $refundId
     */
    private $refundId;

    public function getRefundId(): int
    {
        return $this->refundId;
    }

    public function getCheckString(): string
    {
        return $this->msgId . '|' . $this->refundId;
    }
}
