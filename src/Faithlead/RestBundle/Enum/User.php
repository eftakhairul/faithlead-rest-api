<?php
/**
 * Author: Saeed Ahmed
 * Email: saeed.sas@gmail.com
 * Date: 3/27/13
 */

namespace Faithlead\RestBundle\Enum;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;

/**
 * @MongoDB\Document(collection="users")
 * @ExclusionPolicy("all")
 */

class User{

    /**
     * @MongoDB\id
     * @Expose
     * @Type("string")
     */
    protected $id;

    protected $email;

    protected $password;

    protected $firstName;

    protected $lastName;

    protected $role = 'user';

    protected $accountConfirmed = true;

    protected $createdAt;

    protected $status = true;

}