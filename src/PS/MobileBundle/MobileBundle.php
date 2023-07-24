<?php

namespace PS\MobileBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;


class MobileBundle extends Bundle
{
     public function build(ContainerBuilder $container)
    {
        parent::build($container);

         \JMS\Serializer\SerializerBuilder::create()
        ->setPropertyNamingStrategy(
            new \JMS\Serializer\Naming\SerializedNameAnnotationStrategy(
                new \JMS\Serializer\Naming\IdenticalPropertyNamingStrategy()
            )
        )
        ->build();
    }
    
}
