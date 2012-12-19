<?php

namespace Todo\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use \Zend\I18n\Translator\Translator;
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
     * @var \Doctrine\ORM\EntityRepository
     */
    private $repository;

    /**
     * @var Translator
     */
    private $translator;

    /**
     * Constructor setting the EntityManager
     *
     * @param EntityManager $em
     * @param Translator    $translator
     */
    public function __construct(EntityManager $em, Translator $translator)
    {
        $this->em         = $em;
        $this->repository = $em->getRepository('Todo\Entity\Todo');
        $this->translator = $translator;
    }

    public function indexAction()
    {
        $user = $this->zfcUserAuthentication()->getIdentity();
        $this->em->merge($user);

        $todos = $user->getTodos();
        $sharedTodos = $user->getSharedTodos();

        return array(
            'todos'    => $todos,
            'sharedTodos' => $sharedTodos,
            'messages' => $this->flashMessenger()->getMessages()
        );
    }

    public function formAction()
    {
        $id = $this->params()->fromRoute('id');
        if ($id) {
            $todo = $this->repository->find($id);
            if (!$todo) {
                throw new \InvalidArgumentException("Invalid ID parameter");
            }
        } else {
            $todo = new TodoEntity();
        }

        $form = new TodoForm();
        $form->bind($todo);

        if ($this->request->isPost()) {
            $form->setData($this->request->getPost());
            if ($form->isValid()) {
                $user = $this->zfcUserAuthentication()->getIdentity();
                $todo = $form->getData();
                $this->em->merge($user);
                $user->addTodo($todo);
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
        $todo = $this->repository->find($id);
        if (!$todo) {
            throw new \InvalidArgumentException("Invalid ID parameter");
        }

        $this->em->remove($todo);
        $this->em->flush();

        $this->flashMessenger()->addMessage(
            sprintf(
                $this->translator->translate('Todo "%s" removed'),
                $todo->getTodo()
            )
        );

        return $this->redirect()->toRoute('todo');
    }

    public function shareAction()
    {
        $id = $this->params()->fromRoute('id');
        $todo = $this->repository->find($id);
        if (!$todo) {
            throw new \InvalidArgumentException("Invalid ID parameter");
        }

        $error = false;
        $errorMessage = "";

        if ($this->request->isPost()) {

            $email = $this->request->getPost('user', '');
            $userRepository = $this->em->getRepository('\Todo\Entity\User');
            $user = $userRepository->findByEmail($email);

            if (!$user) {
                $error = true;
                $errorMessage = "User not found";
            }

            $currentUser = $this->zfcUserAuthentication()->getIdentity();
            if ($user == $currentUser) {
                $error = true;
                $errorMessage = "You cannot share a todo item with yourself";
            }

            if (!$error) {

                $user->share($todo);
                $todo->share($user);

                $this->em->persist($todo);
                $this->em->persist($user);

                $this->em->flush();

                $this->flashMessenger()->addMessage(
                    sprintf(
                        $this->translator->translate('Todo "%s" shared with user %s'),
                        $todo->getTodo(),
                        $user->getEmail()
                    )
                );

                return $this->redirect()->toRoute('todo');

            }

        }

        return array(
            'todo' => $todo,
            'error' => $error,
            'errorMessage' => $errorMessage
        );
    }

}
