<?php

namespace SheProductNumber;

use SheProductNumber\Components\ValidatorFactory;
use Shopware\Components\Plugin;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SheProductNumber extends Plugin
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new ValidatorFactory());
    }
}
