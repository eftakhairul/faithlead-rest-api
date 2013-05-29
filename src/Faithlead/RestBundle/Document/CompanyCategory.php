<?php

/**
 * Company Category
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
 * @MongoDB\Document(collection="company_categories",
 *                   repositoryClass="Faithlead\RestBundle\Repository\CompanyCategoryRepository"
 * )
 */
class CompanyCategory
{
    /**
     * Primary Id of Company Category
     *
     * @MongoDB\Id
     * @Expose
     * @Type("integer")
     */
    protected $id;

    /**
     * Name
     *
     * @MongoDB\Field(type="string")
     * @Expose
     * @Type("string")
     */
    protected $name;

    /**
     * @MongoDB\EmbedMany(targetDocument="Subcategory")
     */
    protected $subcategory;

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
        $this->subcategory = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     * @return \Faithlead\RestBundle\Document\CompanyCategory
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $subcategory
     * @return \Faithlead\RestBundle\Document\CompanyCategory
     */
    public function setOneSubcategory($subcategory)
    {
        $this->subcategory[] = $subcategory;
        return $this;
    }

    /**
     * @param $subcategory
     * @return \Faithlead\RestBundle\Document\CompanyCategory
     */
    public function setSubcategory(ArrayCollection $subcategory)
    {
        $this->subcategory = $subcategory;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSubcategory()
    {
        return $this->subcategory;
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
    
    /**
     * Add tag
     *
     * @param \Faithlead\RestBundle\Document\Subcategory $subcategory
     */
    public function addSubcategory(\Faithlead\RestBundle\Document\Subcategory $subcategory)
    {
        $this->subcategory[] = $subcategory;
    }

    /**
    * Remove Subcategory
    *
    * @param \Faithlead\RestBundle\Document\Subcategory $subcategory
    */
    public function removeSubcategory(\Faithlead\RestBundle\Document\Subcategory $subcategory)
    {
        $this->subcategory->removeElement($subcategory);
    }
}
