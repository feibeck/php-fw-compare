<?php

namespace Todo\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\HydratorInterface;
use \ArrayObject;

/**
 *
 * @ORM\Entity(repositoryClass="Todo\Entity\TodoRepository")
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
     * @ORM\Column(type="datetime",nullable=true)
     *
     * @var \DateTime
     */
    protected $reminderDate;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="\Todo\Entity\User", inversedBy="todos")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @ORM\ManyToMany(targetEntity="\Todo\Entity\User", mappedBy="sharedTodos")
     **/
    private $sharedBy;

    protected $inputFilter;

    public function __construct() {
        $this->users = new ArrayCollection();
        $this->sharedBy = new ArrayCollection();
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
        if (isset($data['reminderDate'])) {
            $this->setReminderDate($data['reminderDate']);
        }
    }

    /**
     * @param \DateTime|string $reminderDate
     */
    public function setReminderDate($reminderDate)
    {
        if (is_string($reminderDate)) {
            $reminderDate = \DateTime::createFromFormat(
                \DateTime::RFC3339,
                $reminderDate
            );
        }
        $this->reminderDate = $reminderDate;
    }

    /**
     * Returns the reminder date.
     *
     * Either returns the \DateTime instance, null or if a format specifier
     * is given a string.
     *
     * @param string|boolean $format
     *
     * @return \DateTime|string
     */
    public function getReminderDate($format = false)
    {
        if ($format && $this->reminderDate) {
            return $this->reminderDate->format($format);
        } elseif ($format) {
            return "";
        }
        return $this->reminderDate;
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
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return User[]
     */
    public function getSharedBy()
    {
        return $this->sharedBy;
    }

    /**
     * Shares this todo with a user
     *
     * @param User $user
     */
    public function share(User $user)
    {
        $this->sharedBy[] = $user;
    }
}