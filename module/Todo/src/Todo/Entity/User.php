<?php

namespace Todo\Entity;

use \ZfcUser\Entity\UserInterface;

use \Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="Todo\Entity\UserRepository")
 * @ORM\Table(name="todo_user")
 */
class User implements UserInterface
{

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string",length=255,unique=true,nullable=true)
     */
    protected $username;

    /**
     * @var string
     *
     * @ORM\Column(type="string",length=255,unique=true)
     */
    protected $email;

    /**
     * @var string
     *
     * @ORM\Column(name="display_name",type="string",length=50,nullable=true)
     */
    protected $displayName;

    /**
     * @var string
     *
     * @ORM\Column(type="string",length=128)
     */
    protected $password;

    /**
     * @var
     *
     * @ORM\OneToMany(targetEntity="\Todo\Entity\Todo", mappedBy="user")
     */
    protected $todos;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint")
     */
    protected $state = 0;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $hash;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="\Todo\Entity\Todo", inversedBy="sharedBy")
     * @ORM\JoinTable(name="shares")
     **/
    protected $sharedTodos;

    public function __construct() {
        $this->todos = new ArrayCollection();
        $this->sharedTodos = new ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set id.
     *
     * @param int $id
     * @return UserInterface
     */
    public function setId($id)
    {
        $this->id = (int) $id;
        return $this;
    }

    /**
     * Get username.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set username.
     *
     * @param string $username
     * @return UserInterface
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set email.
     *
     * @param string $email
     * @return UserInterface
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Get displayName.
     *
     * @return string
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * Set displayName.
     *
     * @param string $displayName
     * @return UserInterface
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
        return $this;
    }

    /**
     * Get password.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set password.
     *
     * @param string $password
     * @return UserInterface
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    public function getTodos()
    {
        return $this->todos;
    }

    public function addTodo(Todo $todo)
    {
        $todo->setUser($this);
        $this->todos[] = $todo;
    }

    /**
     * Get state.
     *
     * @return int
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set state.
     *
     * @param int $state
     *
     * @return UserInterface
     */
    public function setState($state)
    {
        $this->state = (int) $state;
        return $this;
    }

    /**
     * Roles are static
     *
     * @return array
     */
    public function getRoles()
    {
        return array(
            'user'
        );
    }

    /**
     * @param string $hash
     */
    public function setHash($hash)
    {
        $this->hash = $hash;
    }

    /**
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @return Todo[]
     */
    public function getSharedTodos()
    {
        return $this->sharedTodos;
    }

    /**
     * Shares the todo with the user
     *
     * @param Todo $todo
     */
    public function share(Todo $todo)
    {
        $this->sharedTodos[] = $todo;
    }

}
