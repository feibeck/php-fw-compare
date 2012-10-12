<?php
/**
 */

namespace Todo\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getServiceLocator()->get("doctrine.entitymanager.orm_default");
        $todoEntities = $em->getRepository("Todo")->findAll();

        return new ViewModel(
            array(
                "todoEntities" => $todoEntities
            )
        );
    }
}
