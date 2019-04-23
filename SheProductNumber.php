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

    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PostDispatchSecure_Backend_Article' => 'onArticlePostDispatch'
        ];
    }

    public function onArticlePostDispatch(\Enlight_Controller_ActionEventArgs $args)
    {
        $controller = $args->getSubject();
        $view = $controller->View();
        $request = $controller->Request();

        $view->addTemplateDir($this->getPath() . '/Resources/views');

        if ($request->getActionName() == 'load') {
            $view->extendsTemplate('backend/article/view/detail/she_product_number.js');
            $view->extendsTemplate('backend/article/view/variant/she_list.js');
        }
    }
}
