<?php

namespace Todo\Entity;

use Doctrine\ORM\EntityRepository;

class TagRepository extends EntityRepository implements TagFactoryInterface
{

    public function factory($name)
    {
        $tag = $this->findOneBy(array('name' => $name));
        if (!$tag) {
            $tag = new Tag();
            $tag->setName($name);
            $this->_em->persist($tag);
        }
        return $tag;
    }

}
