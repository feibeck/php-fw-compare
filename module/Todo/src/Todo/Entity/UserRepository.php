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
        return $query->getOneOrNullResult();
    }

    /**
     * @param string $email
     *
     * @return User
     */
    public function findByEmail($email)
    {
        $query = $this->_em->createQuery(
            'SELECT u FROM Todo\Entity\User u WHERE u.email = :email'
        );
        $query->setParameter('email', $email);
        return $query->getOneOrNullResult();
    }

}