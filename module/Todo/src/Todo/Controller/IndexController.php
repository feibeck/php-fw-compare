<?php
/**
 */

namespace Todo\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use Todo\Form\Todo as TodoForm;
use Todo\Entity\Todo as TodoEntity;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {

        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getServiceLocator()->get("doctrine.entitymanager.orm_default");

        $todoEntities = $em->getRepository('Todo\Entity\Todo')->findAll();


        return new ViewModel(
            array(
                "todoEntities" => $todoEntities
            )
        );
    }

    public function formAction()
    {
        $form = new TodoForm();

        $todo = new TodoEntity();
        $form->bind($todo);

        if ($this->request->isPost()) {

            $form->setData($this->request->getPost());

             // Validate the form
             if ($form->isValid()) {
                 /** @var $em \Doctrine\ORM\EntityManager */
                 $em = $this->getServiceLocator()->get("doctrine.entitymanager.orm_default");
                 $em->persist($todo);
                 $em->flush();

                 return $this->redirect()->toRoute('todo');
             }
        }

        return array('form' => $form);
    }
}
