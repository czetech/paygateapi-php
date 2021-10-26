<?php
namespace PayGateApi\Objects;

use JMS\Serializer\Annotation\Type;

final class RefundStateResponse extends AbstractSignableObject
{
    use AbstractMsgIdObject;

    /**
     *
     * @Type("string")
     *
     * @var string $result
     */
    private $result;

    public function getResult(): string
    {
        return $this->result;
    }

    public function getCheckString(): string
    {
        return $this->msgId . '|' . $this->result;
    }
}
