<?php

/**
 * User Document
 *
 * @author Saeed Ahmed <saeed.sas@gmail.com>
 * @author Eftakhairul Islam <eftakhairul@gmail.com>
 *
 * Copyright @ Faithlead
 */
namespace Faithlead\RestBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;

/**
 * @MongoDB\Document(collection="users")
 *
 * @ExclusionPolicy("all")
 */

class User
{
    /**
     * @MongoDB\id
     * @Expose
     * @Type("string")
     */
    protected $id;
    
    /**
     * @MongoDB\String
     * @MongoDB\Index(unique=true)
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.",
     *     checkMX = false
     * )
     * @Assert\NotBlank()
     * @Expose
     * @Type("string")
     */
    protected $email;

    /**
     * @MongoDB\String
     * @Assert\NotBlank()
     */
    protected $password;

    /**
     * @MongoDB\String
     * @Assert\NotBlank()
     * @Expose
     * @Type("string")
     */
    protected $firstName;

    /**
     * @MongoDB\String
     * @Assert\NotBlank()
     * @Expose
     * @Type("string")
     */
    protected $lastName;

    /**
     * @MongoDB\String
     */
    protected $company;

    /**
     * @MongoDB\String
     * @Assert\NotBlank()
     * @Expose
     * @Type("string")
     */
    protected $website;

    /**
     * @MongoDB\String
     * @Assert\NotBlank()
     * @Expose
     * @Type("string")
     */
    protected $phone;

    /**
     * @MongoDB\String
     * One of 'guest', 'user', 'system'
     * @Expose
     * @Type("string")
     */
    protected $role = 'user';

    /**
     * @MongoDB\Boolean
     */
    protected $accountConfirmed = true;

    /**
     * @MongoDB\String
     */
    protected $fbFanPageUrl;

    /**
     * @MongoDB\Date
     */
    protected $createdAt;

    /**
     * @MongoDB\Date
     */
    protected $updatedAt;

    /**
     * @MongoDB\Boolean
     */
    protected $status = true;

    public function _construct()
    {
    }

    /**
     * Override __toString() method to return the name of the user
     * @return mixed
     */
    public function __toString()
    {
        return $this->id;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return \Faithlead\RestBundle\Document\User
     */

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Get email
     *
     * @return string $email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return \Faithlead\RestBundle\Document\User
     */
    public function setPassword($password)
    {
        $this->password = sha1($password);
        return $this;
    }

    /**
     * Get password
     *
     * @return string $password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return \Faithlead\RestBundle\Document\User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * Get firstName
     *
     * @return string $firstName
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return \Faithlead\RestBundle\Document\User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * Get lastName
     *
     * @return string $lastName
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set role
     *
     * @param string $role
     * @return \Faithlead\RestBundle\Document\User
     */
    public function setRole($role)
    {
        $this->role = $role;
        return $this;
    }

    /**
     * Get role
     *
     * @return string $role
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set accountConfirmed
     *
     * @param boolean $accountConfirmed
     * @return \Faithlead\RestBundle\Document\User
     */
    public function setAccountConfirmed($accountConfirmed)
    {
        $this->accountConfirmed = $accountConfirmed;
        return $this;
    }

    /**
     * Get accountConfirmed
     *
     * @return boolean $accountConfirmed
     */
    public function getAccountConfirmed()
    {
        return $this->accountConfirmed;
    }

    /**
     * @param String $fbFanPageUrl
     * @return \Faithlead\RestBundle\Document\User
     */
    public function setFbFanPageUrl($fbFanPageUrl)
    {
        $this->fbFanPageUrl = $fbFanPageUrl;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFbFanPageUrl()
    {
        return $this->fbFanPageUrl;
    }

    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set createdAt
     *
     * @param date $createdAt
     * @return \Faithlead\RestBundle\Document\User
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return date $createdAt
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \date $updatedAt
     * @return \Faithlead\RestBundle\Document\User
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \date $updatedAt
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set status
     *
     * @param boolean $status
     * @return \Faithlead\RestBundle\Document\User
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Get status
     *
     * @return boolean $status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /** @MongoDB\PrePersist */
    public function prePersistSetCreatedAt()
    {
        $this->createdAt = time();
    }

    /** @MongoDB\PreUpdate */
    public function preUpdateSetUpdatedAt()
    {
        $this->updatedAt = time();
    }

    /**
     * Set company
     *
     * @param string $company
     * @return \Faithlead\RestBundle\Document\User
     */
    public function setCompany($company)
    {
        $this->company = $company;
        return $this;
    }

    /**
     * Get company
     *
     * @return string $company
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set website
     *
     * @param string $website
     * @return \Faithlead\RestBundle\Document\User
     */
    public function setWebsite($website)
    {
        $this->website = $website;
        return $this;
    }

    /**
     * Get website
     *
     * @return string $website
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return \Faithlead\RestBundle\Document\User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * Get phone
     *
     * @return string $phone
     */
    public function getPhone()
    {
        return $this->phone;
    }
}
