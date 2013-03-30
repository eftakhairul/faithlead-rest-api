<?php

namespace Faithlead\RestBundle\Controller;

use Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\Response,
    Symfony\Bundle\FrameworkBundle\Controller\Controller;

use FOS\RestBundle\Controller\Annotations\View,
    FOS\RestBundle\Controller\FOSRestController,
    FOS\RestBundle\Controller\Annotations\QueryParam,
    FOS\RestBundle\Controller\Annotations\RouteResource;

use Faithlead\Bundle\RestBundle\Document\User,
    Faithlead\Bundle\RestBundle\Document\EmailTempalte;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Class UserController
 * @package Faithlead\Bundle\RestBundle\Controller
 * @RouteResource("Emailtemplate")
 */
class EmailTemplateController extends FosRestController
{
    /**
     * Get the list of Email Templates
     *
     * @return array data
     *
     * @View()
     * @ApiDoc()
     */
    public function allAction()
    {
        $data = array();
        $dm                      = $this->get('doctrine.odm.mongodb.document_manager');
        $emailTemplateRepository = $dm->getRepository('FaithleadRestBundle:EmailTemplate');
        $emailTemplateEntities     = $emailTemplateRepository->findAll();

        if(count($emailTemplateEntities) > 0) return array('status' => 'success');

        $cnt = 0;
        foreach($emailTemplateEntities as $emailTemplateEntity)
        {
            $result = array(
            'id'      => $emailTemplateEntity->getId(),
            'body'    => $emailTemplateEntity->getBody(),
            'period'  => $emailTemplateEntity->getPeriod(),
            'subject' => $emailTemplateEntity->getSubject(),
            'success' => true
            );

            $data[$cnt++] = $result;
        }

        return $data;
    }


    /**
     * Get the details of Email Template by Id
     *
     * @param int $id
     * @return array data
     *
     * @View()
     * @QueryParam(name="id", requirements="\d+", default="1", description="id of email template")
     * @ApiDoc()
     */
    public function getAction($id)
    {
        $dm                      = $this->get('doctrine.odm.mongodb.document_manager');
        $emailTemplateRepository = $dm->getRepository('FaithleadRestBundle:EmailTemplate');
        $emailTemplateEntity     = $emailTemplateRepository->findOneById($id);

        if (empty($emailTemplateEntity)) return array('status' => false);

         $result = array(
            'id'      => $emailTemplateEntity->getId(),
            'body'    => $emailTemplateEntity->getBody(),
            'period'  => $emailTemplateEntity->getPeriod(),
            'subject' => $emailTemplateEntity->getSubject(),
            'success' => true,
        );

        return $result;
    }

    /**
     * Delete the specific Email Template by Id
     *
     * @param int $id
     * @return array data
     *
     * @View()
     * @QueryParam(name="id", requirements="\d+", default="1", description="id of email template")
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
     * Create a new Email Template
     *
     * @return View view instance
     *
     * @View()
     * @ApiDoc()
     */
    public function editAction()
    {

        $emialTempalteId         = $this->getRequest()->request->get('id');
        if (empty($emailTemplateEntity)) return array('status' => false);

        $dm                      = $this->get('doctrine.odm.mongodb.document_manager');
        $emailTemplateRepository = $dm->getRepository('FaithleadRestBundle:EmailTemplate');
        $emailTemplateEntity     = $emailTemplateRepository->findOneById($emialTempalteId);

        if (empty($emailTemplateEntity)) array('status' => false);

        $emailTemplateEntity->setBody($this->getRequest()->request->get('body'));
        $emailTemplateEntity->setPeriod($this->getRequest()->request->get('period'));
        $emailTemplateEntity->setSubject($this->getRequest()->request->get('subject'));

        $dm->persist($emailTemplateEntity);
        $dm->flush();

        return array('status' => 'success');
    }
}

