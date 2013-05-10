<?php

/**
 * Save all setting of User
 *
 * @author Eftakahirul Islam  <eftakhairul@gmail.com>
 * Copyright @ Faithlead
 */
namespace Faithlead\RestBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(collection="email_settings",
 *                   repositoryClass="Faithlead\RestBundle\Repository\EmailSettingRepository"
 * )
 */
class EmailSetting
{
    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @MongoDB\ReferenceOne(targetDocument="User", simple=true)
     */
    private $user;

    /**
     * @MongoDB\ReferenceOne(targetDocument="EmailTemplate", simple=true)
     */
    private $emailTemplate;

    /**
     * @MongoDB\Boolean
     */
    protected $isActive;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $period;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $subject;

    /**
     * @MongoDB\Date
     */
    protected $createdAt;

    /**
     * @MongoDB\Date
     */
    protected $updatedAt;

    /**
     * initializing data
     */
    public function __contact()
    {
        $this->isActive = false;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set new user
     *
     * @param \Faithlead\RestBundle\Document\User $user
     * @return \Faithlead\RestBundle\Document\EmailSetting
     */
    public function setUser(\Faithlead\RestBundle\Document\User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Return the user
     *
     * @return \Faithlead\RestBundle\Document\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param $period
     * @return \Faithlead\RestBundle\Document\EmailSetting
     */
    public function setPeriod($period)
    {
        $this->period = $period;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPeriod()
    {
        return $this->period;
    }

    /**
     * @param $subject
     * @return \Faithlead\RestBundle\Document\EmailSetting
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param \Faithlead\RestBundle\Document\EmailTemplate $emailTemplate
     * @return \Faithlead\RestBundle\Document\EmailSetting
     */
    public function setEmailTemplate(\Faithlead\RestBundle\Document\EmailTemplate $emailTemplate)
    {
        $this->emailTemplate = $emailTemplate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmailTemplate()
    {
        return $this->emailTemplate;
    }

    /**
     * @param bool $isActive
     * @return \Faithlead\RestBundle\Document\EmailSetting
     */
    public function setIsActive($isActive = false)
    {
        $this->isActive = $isActive;
        return $this;
    }

    /**
     * @return bool
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set createdAt
     *
     * @param \date $createdAt
     * @return \User
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \date $createdAt
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param date $updatedAt
     * @return \Faithlead\RestBundle\Document\EmailSetting
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return date $updatedAt
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set status
     *
     * @param boolean $status
     * @return \Faithlead\RestBundle\Document\EmailSetting
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Get the status
     *
     * @return boolean $status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @MongoDB\PrePersist
     */
    public function prePersistSetCreatedAt()
    {
        $this->createdAt = time();
    }

    /**
     * @MongoDB\PreUpdate
     */
    public function preUpdateSetUpdatedAt()
    {
        $this->updatedAt = time();
    }
}
