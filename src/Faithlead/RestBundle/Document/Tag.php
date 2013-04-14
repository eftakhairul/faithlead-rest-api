<?php

/**
 * EmbeddedDocument Tag
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
class Tag
{
    /**
     * @MongoDB\Field(type="string")
     */
    protected $tag;

    /**
     * @param String $tag
     */
    public function __construct($tag)
    {
        $this->tag = $tag;
    }

    /**
     * @param $tag
     */
    public function setTag($tag)
    {
        $this->tag = $tag;
    }

    /**
     * @return String
     */
    public function getTag()
    {
        return $this->tag;
    }
}
