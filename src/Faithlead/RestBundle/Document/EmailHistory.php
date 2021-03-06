<?php

/**
 * Email History
 *
 * @author Eftakahirul Islam  <eftakhairul@gmail.com>
 * Copyright @ Faithlead
 */
namespace Faithlead\RestBundle\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;

/**
 * @MongoDB\Document(collection="email_histories",
 *                   repositoryClass="Faithlead\RestBundle\Repository\EmailHistoryRepository"
 * )
 */
class EmailHistory
{
    /**
     * Primary Id of Email History
     *
     * @MongoDB\Id
     * @Expose
     * @Type("integer")
     */
    protected $id;

    /**
     * It will return associated user id
     *
     * @MongoDB\ReferenceOne(targetDocument="User", simple=true)
     * @Expose
     * @Type("integer")
     */
    private $user;

    /**
     * email address
     *
     * @MongoDB\Field(type="string")
     * @Expose
     * @Type("string")
     */
    protected $emailAddress;

    /**
     * subject
     *
     * @MongoDB\Field(type="string")
     * @Expose
     * @Type("string")
     */
    protected $subject;

    /**
     * @MongoDB\Field(type="int")
     * @Expose
     * @Type("integer")
     */
    protected $status;

    /**
     * @MongoDB\EmbedMany(targetDocument="Tag")
     */
    protected $tag;

    /**
     * @MongoDB\Field(type="string")
     *
     */
    protected $orderId;

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
        $this->tag = new ArrayCollection();
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
     * @return $this
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
     * @param $emailAddress
     * @return $this
     */
    public function setEmailAddress($emailAddress)
    {
        $this->emailAddress = $emailAddress;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    /**
     * @param $tag
     * @return $this
     */
    public function setOneTag( $tag)
    {
        $this->tag[] = $tag;
        return $this;
    }

    /**
     * @param $tags
     * @return $this
     */
    public function setTag(ArrayCollection $tags)
    {
        $this->tag = $tags;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * @param $subject
     * @return $this
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
     * Set the order id
     *
     * @param $orderId
     * @return $this
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;
        return $this;
    }

    /**
     * Return the order id
     *
     * @return mixed
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * Set createdAt
     *
     * @param date $createdAt
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
     * @return date $createdAt
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param date $updatedAt
     * @return \User
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
     * @return \User
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

    public function __construct()
    {
        $this->tag = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add tag
     *
     * @param Faithlead\RestBundle\Document\Tag $tag
     */
    public function addTag(\Faithlead\RestBundle\Document\Tag $tag)
    {
        $this->tag[] = $tag;
    }

    /**
    * Remove tag
    *
    * @param <variableType$tag
    */
    public function removeTag(\Faithlead\RestBundle\Document\Tag $tag)
    {
        $this->tag->removeElement($tag);
    }
}
