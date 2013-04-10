<?php

/**
 * Email History
 *
 * @author Eftakahirul Islam  <eftakhairul@gmail.com>
 * Copyright @ Faithlead
 */
namespace Faithlead\RestBundle\Controller;

use Faithlead\RestBundle\Form\Type\EmailTemplateType;
use Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\Response,
    Symfony\Bundle\FrameworkBundle\Controller\Controller;

use FOS\RestBundle\Controller\Annotations\View,
    FOS\RestBundle\Controller\FOSRestController,
    FOS\RestBundle\Controller\Annotations\QueryParam,
    FOS\RestBundle\Controller\Annotations\RouteResource;

use Faithlead\RestBundle\Document\User,
    Faithlead\RestBundle\Document\EmailTemplate;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Class UserController
 * @package Faithlead\Bundle\RestBundle\Controller
 * @RouteResource("Emailhistory")
 */
class EmailHistoryController extends FosRestController
{
    /**
     * Get the list of Email by User Id
     *
     * @param int $userId
     * @return array data
     *
     * @View()
     * @ApiDoc()
     */
    public function getUserAction($userId)
    {
        if (empty($userId)) return array('status' => false);

        $data = array();
        $dm                     = $this->get('doctrine.odm.mongodb.document_manager');
        $emailHistoryRepository = $dm->getRepository('FaithleadRestBundle:EmailHistory');
        $emailHistoryEntities   = $emailHistoryRepository->findBy(array('user' => $userId));

        $cnt = 0;
        foreach($emailHistoryEntities as $emailHistoryEntity)
        {
            $result = array(
            'id'      => $emailHistoryEntity->getId(),
            'subject' => $emailHistoryEntity->getSubject(),
            'user_id' => $emailHistoryEntity->getUser()->getId(),
            'status'  => $emailHistoryEntity->getStatus(),
            'success' => true
            );

            $data[$cnt++] = $result;
        }

        return $data;
    }

    /**
     * Get total of  Email History by User Id
     *
     * @param int $userId
     * @return array data
     *
     * @View()
     * @ApiDoc()
     */
    public function getCountAction($userId)
    {
        if (empty($userId)) return array('status' => false);

        $dm                      = $this->get('doctrine.odm.mongodb.document_manager');
        $emailHistoryRepository = $dm->getRepository('FaithleadRestBundle:EmailHistory');


        return array('count' => $emailHistoryRepository->countByUserId($userId));
    }

    /**
     * Get the details of Email Template by Id
     *
     * @param int $id
     * @return array data
     *
     * @View()
     * @ApiDoc()
     */
    public function getAction($id)
    {
        $dm                      = $this->get('doctrine.odm.mongodb.document_manager');
        $emailHistoryRepository  = $dm->getRepository('FaithleadRestBundle:EmailHistory');
        $emailHistoryEntity     = $emailHistoryRepository->findOneById($id);

        if (empty($emailHistoryEntity)) return array('status' => false);

        return array(
            'id'      => $emailHistoryEntity->getId(),
            'period'  => $emailHistoryEntity->getPeriod(),
            'subject' => $emailHistoryEntity->getSubject(),
            'user_id' => $emailHistoryEntity->getUser()->getId(),
            'status'  => $emailHistoryEntity->getStatus(),
            'success' => true,
        );
    }



    /**
     * Delete the specific Email Template by Id
     *
     * @param int $id
     * @return array data
     *
     * @View()
     * @ApiDoc()
     */
    public function deleteAction($id)
    {
        $dm                      = $this->get('doctrine.odm.mongodb.document_manager');
        $emailTemplateRepository = $dm->getRepository('FaithleadRestBundle:EmailTemplate');
        $emailTemplateEntity     = $emailTemplateRepository->findOneById($id);

        if (empty($emailTemplateEntity)) return array('status' => false);

        $dm->remove($emailTemplateEntity);
        $dm->flush();

        return array('status' => 'success');
    }

    /**
     * Update an Email Template
     *
     * @param int $id
     * @return View view instance
     *
     * @View()
     * @ApiDoc()
     */
    public function putAction($id)
    {

        $dm                      = $this->get('doctrine.odm.mongodb.document_manager');
        $emailTemplateRepository = $dm->getRepository('FaithleadRestBundle:EmailTemplate');
        $emailTemplateEntity     = $emailTemplateRepository->findOneById($id);

        if (empty($emailTemplateEntity)) array('status' => false);

        $emailTemplateEntity->setBody($this->getRequest()->request->get('body'));
        $emailTemplateEntity->setPeriod($this->getRequest()->request->get('period'));
        $emailTemplateEntity->setSubject($this->getRequest()->request->get('subject'));

        $dm->persist($emailTemplateEntity);
        $dm->flush();

        return array('status' => 'success');
    }

    /**
     * Return a form
     *
     * @param null $emailTemplateEntity
     * @return \Symfony\Component\Form\Form
     */
    protected function getForm($emailTemplateEntity = null)
    {
        return $this->createForm(new EmailTemplateType(), $emailTemplateEntity);
    }
}