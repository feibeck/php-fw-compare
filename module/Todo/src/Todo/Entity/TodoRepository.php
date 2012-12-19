<?php

namespace Todo\Entity;

use Doctrine\ORM\EntityRepository;

class TodoRepository extends EntityRepository
{

    /**
     * Returns all reminders which are set during the hour of the given date
     *
     * @param \DateTime $date
     *
     * @return Todo[]
     */
    public function getReminders(\DateTime $date)
    {
        $start = clone $date;
        $end = $date;

        $start->setTime($start->format("H"), 0, 0);
        $end->setTime($end->format('H'), 59, 59);

        $query = $this->_em->createQuery(
            'SELECT t FROM Todo\Entity\Todo t WHERE t.reminderDate >= :start AND t.reminderDate <= :end'
        );

        $query->setParameters(
            array(
                 'start' => $start->format('Y-m-d H:i:s'),
                 'end' => $end->format('Y-m-d H:i:s'),
            )
        );

        return $query->getResult();
    }

    public function getTodosForUser(User $user)
    {
        $query = $this->_em->createQuery(
            'SELECT t FROM Todo\Entity\Todo t WHERE t.user = :user AND t.done = 0 ORDER BY t.priority DESC'
        );
        $query->setParameter('user', $user);
        return $query->getResult();
    }

    public function getDone(User $user)
    {
        $query = $this->_em->createQuery(
            'SELECT t FROM Todo\Entity\Todo t WHERE t.user = :user AND t.done = 1'
        );
        $query->setParameter('user', $user);
        return $query->getResult();
    }

    public function getShared(User $user)
    {
        $query = $this->_em->createQuery(
            'SELECT t FROM Todo\Entity\Todo t JOIN t.sharedBy u WHERE u = :user AND t.done = 0 ORDER BY t.priority DESC'
        );
        $query->setParameter('user', $user);
        return $query->getResult();
    }

}
