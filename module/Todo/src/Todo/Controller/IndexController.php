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
                "todoEntities" => $todoEntities,
                'messages'     => $this->flashMessenger()->getMessages()
            )
        );
    }

    public function formAction()
    {
        $form = new TodoForm();

        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getServiceLocator()->get("doctrine.entitymanager.orm_default");

        $id = $this->params()->fromRoute('id', false);
        if (!$id) {

            $todo = new TodoEntity();

        } else {

            $todo = $em->getRepository('Todo\Entity\Todo')->find($id);
            if (!$todo) {
                throw new \InvalidArgumentException("Invalid ID parameter");
            }

        }

        $form->bind($todo);

        if ($this->request->isPost()) {

            $form->setData($this->request->getPost());

             // Validate the form
             if ($form->isValid()) {


                 $em->persist($todo);
                 $em->flush();

                 return $this->redirect()->toRoute('todo');
             }
        }

        return array('form' => $form);
    }

    public function deleteAction()
    {
        $id = $this->params()->fromRoute('id');

        $em = $this->getServiceLocator()->get("doctrine.entitymanager.orm_default");
        $todo = $em->getRepository('Todo\Entity\Todo')->find($id);
        if (!$todo) {
            throw new \InvalidArgumentException("Invalid ID parameter");
        }

        $this->flashMessenger()->addMessage("Todo " . $todo->getTodo() . " removed");

        $em->remove($todo);
        $em->flush();

        return $this->redirect()->toRoute('todo');
    }

}
