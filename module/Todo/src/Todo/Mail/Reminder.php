<?php

namespace Todo\Mail;

use Todo\Entity\Todo;

use Zend\Mail\Message;

class Reminder extends Message
{

    public function __construct(Todo $todo)
    {
        $this->setFrom('todo-app@example.com', 'Todo-App');
        $this->addTo($todo->getUser()->getEmail());
        $this->setSubject('Reminder: ' . $todo->getTodo());

        $body = <<<BODY
Hello!

This is a reminder for your Todo

"%s"

Greetings,
Todo-App
BODY;

        $this->setBody(sprintf($body, $todo->getTodo()));
    }

}
