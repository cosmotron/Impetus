<?php

namespace Impetus\AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Impetus\AppBundle\Entity\User;


class MessageRepository extends EntityRepository {
    public function getMessageListByRecipient(User $user) {
        $em = $this->getEntityManager();

        $query = $em->createQuery("SELECT m.id,
                                          m.subject,
                                          CONCAT(s.firstName, CONCAT(' ', s.lastName)) as sender,
                                          s.id as user_id,
                                          m.sentAt,
                                          r.messageRead
                                   FROM ImpetusAppBundle:Message m
                                   INNER JOIN m.sender s
                                   INNER JOIN m.recipients r
                                       INNER JOIN r.user u
                                   WHERE u = :user
                                       AND r.messageDeleted = FALSE
                                       AND m.parent IS NULL
                                   ORDER BY r.messageRead ASC,
                                            m.sentAt DESC
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

    public function getRepliesByParent($message) {
        $em = $this->getEntityManager();

        $query = $em->createQuery("SELECT m
                                   FROM ImpetusAppBundle:Message m
                                   WHERE m.parent = :message
                                   ORDER BY m.sentAt ASC
                                   ")->setParameter('message', $message);

        return $query->getResult();
    }
}