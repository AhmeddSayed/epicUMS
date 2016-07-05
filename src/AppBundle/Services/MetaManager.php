<?php

namespace AppBundle\Services;

use Doctrine\ORM\EntityManager;
use AppBundle\Entity\UsersMeta;

class MetaManager {

    private $em;

    public function __construct(EntityManager $entityManager) {
        $this->em = $entityManager;
    }

    public function getMeta($userId) {
        $metaRepo = $this->em->getRepository('AppBundle:UsersMeta');
        $userMetas = $metaRepo->createQueryBuilder('i')
                ->from('AppBundle:UsersMeta', 'u')
                ->select('u.role')
                ->where('u.userId = :userId')
                ->setParameters(array('userId' => $userId))
                ->getQuery()
                ->getResult();

        $roles = array();

        foreach ($userMetas as $aMeta) {
            if (trim($aMeta["role"])) {
                array_push($roles, $aMeta["role"]);
            }
        }
        
        return $roles;
    }

    public function setMeta($userId, $role) {
        $metaRepo = $this->em->getRepository('AppBundle:UsersMeta');

        $userMeta = new UsersMeta();
        $userMeta->setUserId($userId);
        $userMeta->setRole($role);

        $this->em->persist($userMeta);
        $this->em->flush();
    }

}
