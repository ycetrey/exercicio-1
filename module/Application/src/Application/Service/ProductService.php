<?php

namespace Application\Service;

use Application\Entity\Product;

use Doctrine\Common\Collections\Criteria as DoctrineCriteria;
use DoctrineModule\Paginator\Adapter\Selectable as SelectableAdapter;
use Zend\Paginator\Paginator;

class ProductService
{
    private $products = [];

    public function getProducts($service, $controller, $page)
    {
        $adapter = new SelectableAdapter($service->getRepository("Application\Entity\Product"));

        $itemsPerPage = 5;
        $paginator = new Paginator($adapter);
        $paginator->setCurrentPageNumber($page)
            ->setItemCountPerPage($itemsPerPage)
            ->setPageRange(7);

        $this->products = $paginator;

        return array(
            'products' => $this->products,
            'page' => $page,
            'paginator' => $paginator,
            'controller' => $controller,
            'pageAction' => 'post',
            'totalRecord' => $paginator->getTotalItemCount(),
        );
    }

    public function createProduct($product)
    {
        try{
            $name = $product->getPost("name");
            $category = $product->getPost("category");
            $price = $product->getPost("price");

            $new_product = new Product();
            $new_product->setName($name);
            $new_product->setCategory($category);
            $new_product->setPrice($price);

            $service = $this->getServiceLocator()->get("Doctrine\ORM\EntityManager");
            $service->persist($new_product);
            $service->flush();

            return true;

        }  catch (Exception $e) {
            return false;
        }
    }

    public function editProduct($product, $edit_product)
    {
        try{
            $name = $product->getPost("name");
            $category = $product->getPost("category");
            $price = $product->getPost("price");

            $edit_product->setName($name);
            $edit_product->setCategory($category);
            $edit_product->setPrice($price);

            $service = $this->getServiceLocator()->get("Doctrine\ORM\EntityManager");
            $service->merge($edit_product);
            $service->flush();

            return $edit_product;
        }  catch (Exception $e){
            return false;
        }
    }

    public function deleteProduct($product)
    {
        throw new \Exception('Not implemented yet');
    }
}