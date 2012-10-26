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

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * Constructor setting the EntityManager
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function indexAction()
    {
        $todoEntities = $this->em->getRepository('Todo\Entity\Todo')->findAll();

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

        $id = $this->params()->fromRoute('id', false);
        if (!$id) {

            $todo = new TodoEntity();

        } else {

            $todo = $this->em->getRepository('Todo\Entity\Todo')->find($id);
            if (!$todo) {
                throw new \InvalidArgumentException("Invalid ID parameter");
            }

        }

        $form->bind($todo);

        if ($this->request->isPost()) {

            $form->setData($this->request->getPost());

             // Validate the form
             if ($form->isValid()) {


                 $this->em->persist($todo);
                 $this->em->flush();

                 return $this->redirect()->toRoute('todo');
             }
        }

        return array('form' => $form);
    }

    public function deleteAction()
    {
        $id = $this->params()->fromRoute('id');

        $todo = $this->em->getRepository('Todo\Entity\Todo')->find($id);
        if (!$todo) {
            throw new \InvalidArgumentException("Invalid ID parameter");
        }

        $this->flashMessenger()->addMessage("Todo " . $todo->getTodo() . " removed");

        $this->em->remove($todo);
        $this->em->flush();

        return $this->redirect()->toRoute('todo');
    }

}
