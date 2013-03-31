<?php

/**
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
 * @RouteResource("Emailtemplate")
 */
class EmailTemplateController extends FosRestController
{
    /**
     * Get the list of Email Templates by User Id
     *
     * @param int $id
     * @return array data
     *
     * @View()
     * @ApiDoc()
     */
    public function getUserAction($id)
    {
        if (empty($id)) return array('status' => false);

        $data = array();
        $dm                      = $this->get('doctrine.odm.mongodb.document_manager');
        $emailTemplateRepository = $dm->getRepository('FaithleadRestBundle:EmailTemplate');
        $emailTemplateEntities   = $emailTemplateRepository->findBy(array('user' => $id));

        $cnt = 0;
        foreach($emailTemplateEntities as $emailTemplateEntity)
        {
            $result = array(
            'id'      => $emailTemplateEntity->getId(),
            'body'    => $emailTemplateEntity->getBody(),
            'period'  => $emailTemplateEntity->getPeriod(),
            'subject' => $emailTemplateEntity->getSubject(),
            'user_id' => $emailTemplateEntity->getUser()->getId(),
            'success' => true
            );

            $data[$cnt++] = $result;
        }

        return $data;
    }

    /**
     * Get total of  Email Templates by User Id
     *
     * @param int $id
     * @return array data
     *
     * @View()
     * @ApiDoc()
     */
    public function getCountAction($id)
    {
        if (empty($id)) return array('status' => false);

        $dm                      = $this->get('doctrine.odm.mongodb.document_manager');
        $emailTemplateRepository = $dm->getRepository('FaithleadRestBundle:EmailTemplate');

        return array('count' => $emailTemplateRepository->countByUserId($id));
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
        $emailTemplateRepository = $dm->getRepository('FaithleadRestBundle:EmailTemplate');
        $emailTemplateEntity     = $emailTemplateRepository->findOneById($id);

        if (empty($emailTemplateEntity)) return array('status' => false);

        return array(
            'id'      => $emailTemplateEntity->getId(),
            'body'    => $emailTemplateEntity->getBody(),
            'period'  => $emailTemplateEntity->getPeriod(),
            'subject' => $emailTemplateEntity->getSubject(),
            'user_id' => $emailTemplateEntity->getUser()->getId(),
            'success' => true,
        );
    }

    /**
     * Create a new Email Template
     *
     * @param int $id
     * @return View view instance
     *
     * @View()
     * @ApiDoc(
     *      input="Faithlead\RestBundle\Form\Type\EmailTemplateType"
     * )
     */
    public function postAction($id)
    {
        if (empty($id)) return array('status' => false);

        $dm                  = $this->get('doctrine.odm.mongodb.document_manager');
        $emailTemplateEntity = new EmailTemplate();
        $form                = $this->getForm($emailTemplateEntity);
        $request             = $this->getRequest();

        if ('POST' == $request->getMethod()) {

            $form->bind(array(
                "body"      => $request->request->get('body'),
                "period"    => $request->request->get('period'),
                "subject"   => $request->request->get('subject'),
                )
            );

            if ($form->isValid()) {
                $userRepository = $dm->getRepository('FaithleadRestBundle:User');
                $userEntity     = $userRepository->findOneById($id);

                $emailTemplateEntity->setUser($userEntity);
                $dm->persist($emailTemplateEntity);
                $dm->flush();

                return array('id' => $emailTemplateEntity->getId(), 'status' => 'success');
            } else {
                return array($form);
            }
        }
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

