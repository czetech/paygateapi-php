<?php
namespace PayGateApi\Objects;

use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Type;

trait AbstractMsgIdObject
{

    /**
     *
     * @SerializedName("msg-id")
     * @Type("string")
     *
     * @var string|null $msgId
     */
    private $msgId;

    public function getMsgId(): ?string
    {
        return $this->msgId;
    }

    public function setMsgId(?string $msgId): void
    {
        $this->msgId = $msgId;
    }

    public function validateMsgId(): void
    {
        if ($this->msgId === null) {
            $this->msgId = strval(round(microtime(true) * 1000));
        }
    }
}
