<?php

namespace Todo\Entity;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{

    /**
     * @param $hash
     *
     * @return User
     */
    public function getByHash($hash)
    {
        $query = $this->_em->createQuery(
            'SELECT u FROM Todo\Entity\User u WHERE u.hash = :hash'
        );
        $query->setParameter('hash', $hash);
        return $query->getSingleResult();
    }

}