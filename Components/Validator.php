<?php

namespace SheProductNumber\Components;

use Shopware\Components\OrderNumberValidator\OrderNumberValidatorInterface;
use Shopware\Components\OrderNumberValidator\RegexOrderNumberValidator;
use Shopware_Components_Config;

class Validator implements OrderNumberValidatorInterface
{
    /**
     * @var OrderNumberValidatorInterface
     */
    private $innerValidator;

    public function __construct(Shopware_Components_Config $config)
    {
        $this->innerValidator = new RegexOrderNumberValidator($config->getByNamespace('SheProductNumber', 'regex'));
    }

    public function validate(string $orderNumber): bool
    {
        return $this->innerValidator->validate($orderNumber);
    }
}
