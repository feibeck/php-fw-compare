<?php

namespace Todo\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Todo\Entity\TagRepository")
 */
class Tag
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
     * @ORM\Column(type="string",unique=true)
     */
    protected $name;

    /**
     * @var Todo[]
     *
     * @ORM\ManyToMany(targetEntity="\Todo\Entity\Todo", inversedBy="tags")
     * @ORM\JoinTable(name="todo_tags")
     **/
    protected $todos;


    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function addTodo(Todo $todo)
    {
        $this->todos[] = $todo;
    }

}
