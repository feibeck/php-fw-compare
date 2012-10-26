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

        $name = new Form\Element('todo');
        $name->setLabel('Your todo');
        $name->setAttributes(array(
            'type'  => 'text'
        ));


        $this->add($name);

        $submit = new Form\Element('submit');
        $submit->setValue('Anlegen');
        $submit->setAttributes(array(
            'type'  => 'submit'
        ));

        $this->add($submit);


    }

}