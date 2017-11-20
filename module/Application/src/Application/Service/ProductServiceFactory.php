<?php

namespace Application\Service;


use Application\Entity\Product;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\I18n\View\Helper\CurrencyFormat;

class ProductServiceFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {

        $products = [];

        return new ProductService($products);
    }
}