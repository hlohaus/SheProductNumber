<?php

namespace SheProductNumber\Components;

use Doctrine\Common\Annotations\Reader;
use Shopware\Models\Article\Detail;
use Shopware_Components_Config;
use Symfony\Component\Validator\Constraints\Regex;

class AnnotationsReader implements Reader
{
    /**
     * @var Reader
     */
    private $innerService;

    /**
     * @var Shopware_Components_Config
     */
    private $config;

    public function __construct(Reader $innerService, Shopware_Components_Config $config)
    {
        $this->innerService = $innerService;
        $this->config = $config;
    }

    public function getClassAnnotations(\ReflectionClass $class)
    {
        return $this->innerService->getClassAnnotations($class);
    }

    public function getClassAnnotation(\ReflectionClass $class, $annotationName)
    {
        return $this->innerService->getClassAnnotation($class, $annotationName);
    }

    public function getMethodAnnotations(\ReflectionMethod $method)
    {
        return $this->innerService->getMethodAnnotations($method);
    }

    public function getMethodAnnotation(\ReflectionMethod $method, $annotationName)
    {
        return $this->innerService->getMethodAnnotation($method, $annotationName);
    }

    public function getPropertyAnnotations(\ReflectionProperty $property)
    {
        if ($property->class !== Detail::class) {
            return $this->innerService->getPropertyAnnotations($property);
        }
        if ($property->getName() !== 'number') {
            return $this->innerService->getPropertyAnnotations($property);
        }

        $result = $this->innerService->getPropertyAnnotations($property);

        foreach ($result as $key => $annotation) {
            if($annotation instanceof Regex) {
                $annotation->pattern = $this->config->getByNamespace('SheProductNumber', 'regex');
            }
        }

        return $result;
    }

    public function getPropertyAnnotation(\ReflectionProperty $property, $annotationName)
    {
        return $this->innerService->getPropertyAnnotation($property, $annotationName);
    }
}
