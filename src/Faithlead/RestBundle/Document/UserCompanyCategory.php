<?php

/**
 * User Company Category
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
 * @MongoDB\Document(collection="user_company_categories",
 *                   repositoryClass="Faithlead\RestBundle\Repository\UserCompanyCategoryRepository"
 * )
 */
class UserCompanyCategory
{
    /**
     * Primary Id of User Company Category
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
    protected $user;

    /**
     * It will return associated company category id
     *
     * @MongoDB\ReferenceOne(targetDocument="CompanyCategory", simple=true)
     * @Expose
     * @Type("integer")
     */
    protected $companyCategory;

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
     * Set new user
     *
     * @param \Faithlead\RestBundle\Document\User $user
     * @return \Faithlead\RestBundle\Document\UserCompanyCategory
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
     * @param CompanyCategory $companyCategory
     * @return \Faithlead\RestBundle\Document\UserCompanyCategory
     */
    public function setCompanyCategory(\Faithlead\RestBundle\Document\CompanyCategory $companyCategory)
    {
        $this->companyCategory = $companyCategory;
        return $this;
    }

    /**
     * Get the company category
     *
     * @return \Faithlead\RestBundle\Document\CompanyCategory $companyCategory
     */
    public function getCompanyCategory()
    {
        return $this->companyCategory;
    }

    /**
     * Set Subcategory
     *
     * @param \Faithlead\RestBundle\Document\Subcategory $subcategory
     * @return \Faithlead\RestBundle\Document\UserCompanyCategory
     */
    public function setOneSubcategory(\Faithlead\RestBundle\Document\Subcategory $subcategory)
    {
        $this->subcategory[] = $subcategory;
        return $this;
    }

    /**
     * Set Subcategories
     *
     * @param ArrayCollection $subcategory
     * @return \Faithlead\RestBundle\Document\UserCompanyCategory
     */
    public function setSubcategories(ArrayCollection $subcategory)
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
     * Add Subcategory
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

    /**
     * Set createdAt
     *
     * @param date $createdAt
     * @return UserCompanyCategory
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
     * @return \Faithlead\RestBundle\Document\UserCompanyCategory
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
