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

    const PRIORITY_LOW    = 0;
    const PRIORITY_NORMAL = 1;
    const PRIORITY_HIGH   = 2;

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

    /**
     * @var \Todo\Enity\Tag[]
     *
     * @ORM\ManyToMany(targetEntity="\Todo\Entity\Tag", mappedBy="todos", indexBy="name")
     */
    private $tags;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $done = false;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $priority = self::PRIORITY_NORMAL;


    protected $inputFilter;

    /**
     * @var TagFactoryInterface
     */
    protected $tagFactory;

    public function __construct() {
        $this->users = new ArrayCollection();
        $this->sharedBy = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    public function setTagFactory(TagFactoryInterface $factory)
    {
        $this->tagFactory = $factory;
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
        $this->priority = (isset($data['priority'])) ? $data['priority'] : null;
        if (isset($data['reminderDate'])) {
            $this->setReminderDate($data['reminderDate']);
        }
        if (isset($data['tags'])) {
            $tags = explode(",", $data['tags']);
            foreach ($tags as $tag) {
                $tag = trim($tag);
                $this->addTag($tag);
            }
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
        $out = array(
            'id' => $this->id,
            'todo' => $this->todo,
            'reminderDate' => $this->reminderDate,
            'tags' => $this->getTagsAsString(),
            'done' => $this->done,
            'priority' => $this->priority
        );
        return $out;
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

    public function isDone()
    {
        return $this->done;
    }

    public function setDone($done)
    {
        $this->done = $done;
    }

    /**
     * @param int $priority
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
    }

    /**
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }

    public function getTagsAsString()
    {
        $tags = array();
        foreach ($this->tags as $tag) {
            $tags[] = $tag->getName();
        }
        return implode(", ", $tags);
    }

    /**
     * Adds a tag
     *
     * @param string|Tag $tag
     *
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function addTag($tag)
    {
        if ($this->tagFactory == null) {
            throw new \RuntimeException(
                "No factory for tags set. Could not create tags"
            );
        }

        if (is_string($tag)) {
            $tag = $this->tagFactory->factory($tag);
        }

        if (!$tag instanceof Tag) {
            throw new \InvalidArgumentException(
                "Argument needs to be of type \Todo\Entity\Tag or string"
            );
        }

        if (isset($this->tags[$tag->getName()])) {
            return;
        }

        $tag->addTodo($this);
        $this->tags[$tag->getName()] = $tag;
    }

}