<?php

/**
 * EmbeddedDocument Subcategory
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
 * @MongoDB\EmbeddedDocument
 */
class Subcategory
{
    /**
     * @MongoDB\Field(type="string")
     */
    protected $subcategory;

    /**
     * @param String $subcategory
     */
    public function __construct($subcategory = null)
    {
        $this->subcategory = $subcategory;
    }

    /**
     * @param string $subcategory
     * @return \Faithlead\RestBundle\Document\Subcategory
     */
    public function setSubcategory($subcategory)
    {
        $this->subcategory = $subcategory;
        return $this;
    }

    /**
     * @return String
     */
    public function getSubcategory()
    {
        return $this->subcategory;
    }
}
