<?php

namespace Impetus\AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Impetus\AppBundle\Entity\Message;
use Impetus\AppBundle\Entity\User;


class MessageRepository extends EntityRepository {
    public function getMessageListByRecipient(User $user) {
        $em = $this->getEntityManager();

        $query = $em->createQuery("SELECT m1.id,
                                          m1.subject,
                                          CONCAT(s.firstName, CONCAT(' ', s.lastName)) AS sender,
                                          s.id AS user_id,
                                          m1.sentAt,
                                          (
                                              SELECT COUNT(r2.messageRead)
                                              FROM ImpetusAppBundle:Message m2
                                              INNER JOIN m2.recipients r2
                                              WHERE ( m2.parent = m1 OR ( m2.parent IS NULL AND m2 = m1 ) )
                                                  AND r2.user = :user
                                                  AND r2.messageRead = FALSE
                                              GROUP BY r2.messageRead
                                          ) AS messageRead
                                   FROM ImpetusAppBundle:Message m1
                                   INNER JOIN m1.sender s
                                   INNER JOIN m1.recipients r
                                       INNER JOIN r.user u
                                   WHERE (u = :user OR m1.sender = :user)
                                       AND r.messageDeleted = FALSE
                                       AND m1.parent IS NULL
                                   GROUP BY m1.id
                                   ORDER BY messageRead DESC,
                                            m1.sentAt DESC
                                   ")->setParameter('user', $user);

        return $query->getResult();
    }

    public function getParentByIdAndUser($id, User $user) {
        $em = $this->getEntityManager();

        $query = $em->createQuery("SELECT mr
                                   FROM ImpetusAppBundle:MessageRecipient mr
                                   INNER JOIN mr.message m
                                   INNER JOIN mr.user u
                                   WHERE m.id = :id
                                       AND u = :user
                                       AND mr.messageDeleted = FALSE
                                       AND m.parent IS NULL
                                   ")->setParameters(array('id' => $id,
                                                           'user' => $user));

        try {
            $result = $query->getSingleResult();
        }
        catch (\Doctrine\ORM\NoResultException $e) {
            $result = null;
        }

        return $result;
    }

    public function getRepliesByParentAndUser(Message $message, User $user) {
        $em = $this->getEntityManager();

        $query = $em->createQuery("SELECT mr
                                   FROM ImpetusAppBundle:MessageRecipient mr
                                   INNER JOIN mr.message m
                                   INNER JOIN mr.user u
                                   WHERE m.parent = :message
                                       AND u = :user
                                   ORDER BY m.sentAt ASC
                                   ")->setParameters(array('message' => $message,
                                                           'user' => $user));

        return $query->getResult();
    }
}