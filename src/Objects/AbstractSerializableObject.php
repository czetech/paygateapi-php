<?php
namespace PayGateApi\Objects;

use JMS\Serializer\SerializerBuilder;

trait AbstractSerializableObject
{

    public function serialize(): string
    {
        $serializer = SerializerBuilder::create()->build();
        return $serializer->serialize($this, 'json');
    }
}
