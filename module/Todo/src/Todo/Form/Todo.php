<?php

namespace Todo\Form;

use Zend\Form;

class Todo extends Form\Form
{
    public function __construct()
    {
        parent::__construct('Todo');

        $name = new Form\Element('todo');
        $name->setLabel('Your todo');
        $name->setAttributes(array(
            'type'  => 'text'
        ));

        $this->add($name);


        $reminderDate = new Form\Element('reminderDate');
        $reminderDate
            ->setLabel('Remind me')
            ->setAttributes(array(
                'id' => 'reminderDate',
                'type'  => 'text'
            ));

        $this->add($reminderDate);

        $submit = new Form\Element('submit');
        $submit->setValue('Speichern');
        $submit->setAttributes(array(
            'type'  => 'submit'
        ));

        $this->add($submit);


    }

}