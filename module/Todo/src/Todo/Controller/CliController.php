<?php

namespace Todo\Controller;

use Todo\Mail\Reminder as ReminderMail;
use Todo\Entity\TodoRepository;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Console\Request as ConsoleRequest;
use Zend\Mail\Transport\TransportInterface as MailTransport;

class CliController extends AbstractActionController
{

    /**
     * @var TodoRepository
     */
    private $repository;

    /**
     * @var MailTransport
     */
    private $mailTransport;

    /**
     * Constructor setting the EntityManager
     *
     * @param TodoRepository $repository
     * @param MailTransport  $transport
     */
    public function __construct(TodoRepository $repository, MailTransport $transport)
    {
        $this->repository = $repository;
        $this->mailTransport = $transport;
    }

    public function emailReminderAction()
    {
        $request = $this->getRequest();
        if (!$request instanceof ConsoleRequest){
            throw new \RuntimeException(
                'You can only use this action from a console!'
            );
        }

        $todos = $this->repository->getReminders(new \DateTime());

        if (count($todos) == 0) {
            return "No reminders to send this hour.\n";
        }

        foreach ($todos as $todo) {
            $mail = new ReminderMail($todo);
            $this->mailTransport->send($mail);
        }

        return sprintf("Finished with %d reminders\n", count($todos));
    }

}

