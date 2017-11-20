<?php

namespace Application\Controller;

use Application\Entity\Product;
use Application\Service\ProductService;
use Zend\I18n\View\Helper\CurrencyFormat;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Doctrine\Common\Collections\Criteria as DoctrineCriteria;
use DoctrineModule\Paginator\Adapter\Selectable as SelectableAdapter;
use Zend\Paginator\Paginator;

class ProductsController extends AbstractActionController
{
    public function indexAction()
    {
        $service = $this->getServiceLocator()->get("Doctrine\ORM\EntityManager");
        $product_service = new ProductService();
        $page = $this->params()->fromRoute('page') ? (int) $this->params()->fromRoute('page') : 1;
        $controller = $this->params()->fromRoute('controller') ? $this->params()->fromRoute('controller') : '';
        $list = $product_service->getProducts($service, $controller, $page);

        return new ViewModel($list);
    }

    public function addAction()
    {
        $request = $this->getRequest();
        if($request->isPost()) {
            if (ProductService::createProduct($request)) {
                $this->flashMessenger()->addSuccessMessage('Success. You have added a new product!');
                return $this->redirect()->toRoute('products', array('controller' => 'products', 'action' => 'index'));
            } else {
                $this->flashMessenger()->addErrorMessage('Error!! try again later!');
                return $this->redirect()->toRoute('products', array('controller' => 'products', 'action' => 'index'));
            }
        }
        return new ViewModel();
    }

    public function editAction()
    {
        $request = $this->getRequest();
        $id = $this->params()->fromRoute("id", 0);
        $service = $this->getServiceLocator()->get("Doctrine\ORM\EntityManager");
        $product = $service->find("Application\Entity\Product", $id);
        if($request->isPost()) {
            $product = ProductService::editProduct($request, $product);
            if ($product) {
                $this->flashMessenger()->addSuccessMessage('Success. Edited ' . $product->getName() . '!');
                return $this->redirect()->toRoute('products', array('controller' => 'products', 'action' => 'index'));
            } else {
                $this->flashMessenger()->addErrorMessage('Error!! try again later!');
                return $this->redirect()->toRoute('products', array('controller' => 'products', 'action' => 'index'));
            }
        }
        return new ViewModel(array('product' => $product));
    }

    public function deleteAction()
    {
        $id = $this->params()->fromRoute("id", 0);
        $service = $this->getServiceLocator()->get("Doctrine\ORM\EntityManager");
        $product = $service->find("Application\Entity\Product", $id);
        $service->remove($product);
        $service->flush();

        $this->flashMessenger()->addSuccessMessage('Success. Deleted '.$product->getName().'!');

        return $this->redirect()->toRoute('products', array('controller' => 'products', 'action' => 'index'));
    }
}
