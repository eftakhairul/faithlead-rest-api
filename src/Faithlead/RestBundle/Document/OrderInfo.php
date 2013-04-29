<?php
namespace Faithlead\RestBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;

/**
 * @MongoDB\Document(collection="order_info")
 *
 * @ExclusionPolicy("all")
 */

class OrderInfo
{

    /**
     * @MongoDB\id
     * @Expose
     * @Type("string")
     */
    protected $id;

    /**
     * @MongoDB\ReferenceOne(targetDocument="User", simple=true)
     */
    private $user;

    /**
     * @MongoDB\String
     * @Assert\NotBlank()
     * @Expose
     * @Type("string")
     */
    protected $customerName;

    /**
     * @MongoDB\String
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.",
     *     checkMX = true
     * )
     * @Assert\NotBlank()
     * @Expose
     * @Type("string")
     */
    protected $customerEmail;


    /**
     * @MongoDB\String
     * @MongoDB\Index(unique=true)
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
     * @MongoDB\Boolean
     */
    protected $status = true;


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
     * Set user
     *
     * @param Faithlead\RestBundle\Document\User $user
     * @return \OrderInfo
     */
    public function setUser(\Faithlead\RestBundle\Document\User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get user
     *
     * @return Faithlead\RestBundle\Document\User $user
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set customerName
     *
     * @param string $customerName
     * @return \OrderInfo
     */
    public function setCustomerName($customerName)
    {
        $this->customerName = $customerName;
        return $this;
    }

    /**
     * Get customerName
     *
     * @return string $customerName
     */
    public function getCustomerName()
    {
        return $this->customerName;
    }

    /**
     * Set customerEmail
     *
     * @param string $customerEmail
     * @return \OrderInfo
     */
    public function setCustomerEmail($customerEmail)
    {
        $this->customerEmail = $customerEmail;
        return $this;
    }

    /**
     * Get customerEmail
     *
     * @return string $customerEmail
     */
    public function getCustomerEmail()
    {
        return $this->customerEmail;
    }

    /**
     * Set orderId
     *
     * @param string $orderId
     * @return \OrderInfo
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;
        return $this;
    }

    /**
     * Get orderId
     *
     * @return string $orderId
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * Set createdAt
     *
     * @param date $createdAt
     * @return \OrderInfo
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
     * @return \OrderInfo
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
     * @return \OrderInfo
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
}
