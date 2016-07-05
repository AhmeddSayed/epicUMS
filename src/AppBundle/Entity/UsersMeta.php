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
    private $userId;

    /**
     * @var string
     */
    private $role;

    /**
     * @var integer
     */
    private $entryId;


    /**
     * Set userId
     *
     * @param integer $userId
     * @return UsersMeta
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
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

    /**
     * Set roleId
     *
     * @param integer $roleId
     * @return UsersMeta
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get roleId
     *
     * @return integer 
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Get entryId
     *
     * @return integer 
     */
    public function getEntryId()
    {
        return $this->entryId;
    }
}
