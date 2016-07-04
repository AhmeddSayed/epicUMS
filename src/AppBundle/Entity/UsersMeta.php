<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UsersMeta
 */
class UsersMeta
{
    /**
     * @var integer
     */
    private $roleId;

    /**
     * @var integer
     */
    private $userId;


    /**
     * Set roleId
     *
     * @param integer $roleId
     * @return UsersMeta
     */
    public function setRoleId($roleId)
    {
        $this->roleId = $roleId;

        return $this;
    }

    /**
     * Get roleId
     *
     * @return integer 
     */
    public function getRoleId()
    {
        return $this->roleId;
    }

    /**
     * Get userId
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->userId;
    }
}
