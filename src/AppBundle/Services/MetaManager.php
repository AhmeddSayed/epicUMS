<?php

namespace AppBundle\Services;

use Doctrine\ORM\EntityManager;

class MetaManager {

    private $em;

    public function __construct(EntityManager $entityManager) {
        $this->em = $entityManager;
    }

    public function getMeta($userId) {
        $metaRepo = $this->em->getRepository('AppBundle:UsersMeta');
        $userMetaIds = array();
        $userMetaIds = $metaRepo->createQueryBuilder('i')
                ->from('AppBundle:UsersMeta', 'u')
                ->select('u.roleId')
                ->where('u.userId = :userId')
                ->setParameters(array('userId' => $userId))
                ->getQuery()
                ->getResult();
        ;
        $roles = array();

        $rolesRepo = $this->em->getRepository('AppBundle:Roles');

        foreach ($userMetaIds as $metaId) {
            $aRole = $rolesRepo->findOneBy(array('id' => $metaId));
            if ($aRole) {
                array_push($roles, $aRole);
            }
        }
        return $roles;
    }

}
