<?php

/**
 * All About Email Template
 *
 * @author Eftakahirul Islam  <eftakhairul@gmail.com>
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
 * @MongoDB\Document(collection="email_templates",
 *                   repositoryClass="Faithlead\RestBundle\Repository\EmailTemplateRepository"
 * )
 *@ExclusionPolicy("all")
 */
class EmailTemplate
{
    /**
     * Primary Id of email template
     *
     * @MongoDB\id
     * @Expose
     * @Type("string")
     */
    protected $id;

    /**
     * Name of email template
     *
     * @MongoDB\Field(type="string")
     * @Expose
     * @Type("string")
     */
    protected $name;

    /**
     * It can be string or html
     *
     * @MongoDB\Field(type="string")
     * @Expose
     * @Type("string")
     */
    protected $body;

    /**
     * @MongoDB\Date
     * @Expose
     * @Type("string")
     */
    protected $createdAt;

    /**
     * @MongoDB\Date
     */
    protected $updatedAt;

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
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $name
     * @return \Faithlead\RestBundle\Document\EmailTemplate
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $body
     * @return \Faithlead\RestBundle\Document\EmailTemplate
     */
    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set createdAt
     *
     * @param date $createdAt
     * @return \Faithlead\RestBundle\Document\EmailTemplate
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
     * @return \Faithlead\RestBundle\Document\EmailTemplate
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
