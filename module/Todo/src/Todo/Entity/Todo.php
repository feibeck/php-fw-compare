<?php

namespace Todo\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\HydratorInterface;
use \ArrayObject;

/**
 *
 * @ORM\Entity
 *
 */
class Todo implements InputFilterAwareInterface
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $todo;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="\Todo\Entity\User", inversedBy="todos")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     */
    protected $user;

    protected $inputFilter;

    /**
     * @ORM\Column(type="string")
     * @var \String
     */
    protected $reminderDate;

    /**
     * äh
     */
    public function __construct() {
        $date = new \DateTime('now');
        $this->reminderDate = '2012-02-02 15:00:00';
    }

    /**
     * @param int $id
     * @return \Todo\Entity\Todo
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $todo
     * @return \Todo\Entity\Todo
     */
    public function setTodo($todo)
    {
        $this->todo = $todo;
        return $this;
    }

    /**
     * @return string
     */
    public function getTodo()
    {
        return $this->todo;
    }


    /**
     * Set input filter
     *
     * @param  InputFilterInterface $inputFilter
     *
     * @throws \Exception
     * @return InputFilterAwareInterface
     */
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception('not used');
    }

    /**
     * Retrieve input filter
     *
     * @return InputFilterInterface
     */
    public function getInputFilter()
    {
         if (!$this->inputFilter) {
            $filter = new InputFilter();
            $filter->add(array(
                 'name' => 'todo',
                 'required' => true,
                 'filters' => array(
                     array('name' => 'Zend\Filter\StringTrim'),
                     array('name' => 'Zend\Filter\StripTags'),
                 ),
                 'validators' => array(
                     new \Zend\Validator\NotEmpty(),
                 ),
             ));
            /*$filter->add(array(
                 'name' => 'reminderDate',
                 'required' => true,
                 'filters' => array(
                 ),
                 'validators' => array(
                     new \Zend\Validator\NotEmpty(),
                 ),
             ));*/
            $this->inputFilter = $filter;
        }

        return $this->inputFilter;
    }

    /**
     * @param $data
     */
    public function exchangeArray($data)
    {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->todo = (isset($data['todo'])) ? $data['todo'] : null;
    }

    /**
     * @return array
     */
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return \Datetime
     */
    public function getReminderDate()
    {
        return $this->reminderDate;
    }

    /**
     * @param \Datetime $reminderDate
     * @return \Todo\Entity\Todo
     */
    public function setReminderDate($reminderDate)
    {
        \Zend\Debug\Debug::dump($reminderDate);exit;
        $this->reminderDate = $reminderDate;
        return $this;
    }
}