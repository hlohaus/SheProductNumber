<?php

namespace SheProductNumber\Components;

use Doctrine\Common\Annotations\Reader;
use Shopware\Bundle\FormBundle\DependencyInjection\Factory\ConstraintValidatorFactory;
use Shopware\Components\OrderNumberValidator\OrderNumberValidatorInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Validator\Validation;

class ValidatorFactory implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('validator');
        $definition->setFactory([ValidatorFactory::class, 'createValidator']);
        $definition->replaceArgument(0, new Reference('models.annotations_reader'));

        if ($container->hasDefinition(OrderNumberValidatorInterface::class)) {
            $container->getDefinition('she_product_number.validator')->setDecoratedService(OrderNumberValidatorInterface::class);
        }
    }

    public static function createValidator(Reader $annotationsReader, ConstraintValidatorFactory $validatorFactory)
    {
        $validatorBuilder = Validation::createValidatorBuilder();

        $validatorBuilder->enableAnnotationMapping($annotationsReader);
        $validatorBuilder->setConstraintValidatorFactory($validatorFactory);

        return $validatorBuilder->getValidator();
    }
}