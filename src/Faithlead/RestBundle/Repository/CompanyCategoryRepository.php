<?php

/**
 * @author Eftakahirul Islam  <eftakhairul@gmail.com>
 * Copyright @ Faithlead
 */
namespace Faithlead\RestBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;

/**
 * CompanyCategoryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CompanyCategoryRepository extends DocumentRepository
{
    /**
     * Return count of emails by user id
     *
     * @return bool|int
     */
    public function findCount()
    {
        $total = $this->createQueryBuilder('e')
                      ->getQuery()
                      ->execute()
                      ->count();

        return empty($total)? 0 : $total;
    }
}