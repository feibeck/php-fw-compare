<?php

namespace Todo\Form;

use Zend\Form;
use Zend\InputFilter\InputFilter;
use Zend\Validator\NotEmpty;

class Todo extends Form\Form
{
    public function __construct()
    {
        parent::__construct('Todo');

        $name = new Form\Element\Text('todo');
        $name->setLabel('Your todo');

        $this->add($name);

        $reminderDate = new Form\Element\DateTime('reminderDate');
        $reminderDate
            ->setLabel('Remind me')
            ->setAttributes(array(
                'id' => 'reminderDate'
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