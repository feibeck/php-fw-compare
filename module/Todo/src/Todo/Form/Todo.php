<?php

namespace Todo\Form;

use Zend\Form;
use Zend\InputFilter\InputFilter;
use Zend\Validator\NotEmpty;

use Todo\Entity\Todo as TodoEntity;

class Todo extends Form\Form
{
    public function __construct()
    {
        parent::__construct('Todo');

        $name = new Form\Element\Text('todo');
        $name->setLabel('Your todo');

        $this->add($name);

        $priority = new Form\Element\Radio('priority');
        $priority->setValueOptions(array(
            TodoEntity::PRIORITY_LOW => 'Low',
            TodoEntity::PRIORITY_NORMAL => 'Normal',
            TodoEntity::PRIORITY_HIGH => 'High',
        ));
        $priority->setLabel('Priority');
        $this->add($priority);

        $reminderDate = new Form\Element\DateTime('reminderDate');
        $reminderDate
            ->setLabel('Remind me')
            ->setAttributes(array(
                'id' => 'reminderDate'
            ));

        $this->add($reminderDate);

        $tags = new Form\Element\Text('tags');
        $tags->setLabel('Tags');
        $this->add($tags);

        $submit = new Form\Element('submit');
        $submit->setValue('Speichern');
        $submit->setAttributes(array(
            'type'  => 'submit'
        ));

        $this->add($submit);
    }

}