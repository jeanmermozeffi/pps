<?php

namespace PS\MobileBundle\Service;

use JMS\Serializer\Naming\PropertyNamingStrategyInterface;
use JMS\Serializer\Metadata\PropertyMetadata;

class IdenticalUnlessSpecifiedPropertyNamingStrategy implements PropertyNamingStrategyInterface
{
    public function translateName(PropertyMetadata $property)
    {
        echo 100;
        $name = $property->serializedName;

        if (null !== $name) {
            return $name;
        }

        return $property->name;
    }
}