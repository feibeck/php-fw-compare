<?php
/**
 */

namespace Todo\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Todo\Entity\Todo;
use Doctrine\ORM\EntityManager;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {

        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getServiceLocator()->get("doctrine.entitymanager.orm_default");
        //\Zend\Debug\Debug::dump($em->getConfiguration());

        //return new \Zend\View\Helper\ViewModel();

        $todoEntities = $em->getRepository('Todo\Entity\Todo')->findAll();


        return new ViewModel(
            array(
                "todoEntities" => $todoEntities
            )
        );
    }
}
