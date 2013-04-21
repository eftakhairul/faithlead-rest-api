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
     * @param int $userId
     * @return array data
     *
     * @View()
     * @ApiDoc(statusCodes={ 200="array of all email template (id, body, period, subject, user_id)",
     *                       404="Returned when user id found"},
     *         description="Get the list of Email Templates by User Id"
     * )
     */
    public function getUserAction($userId)
    {
        if (empty($userId)) return new Response('user not found', 404);

        $data = array();
        $dm                      = $this->get('doctrine.odm.mongodb.document_manager');
        $emailTemplateRepository = $dm->getRepository('FaithleadRestBundle:EmailTemplate');
        $emailTemplateEntities   = $emailTemplateRepository->findBy(array('user' => $userId));

        $cnt = 0;
        foreach($emailTemplateEntities as $emailTemplateEntity)
        {
            $result = array(
            'id'      => $emailTemplateEntity->getId(),
            'body'    => $emailTemplateEntity->getBody(),
            'period'  => $emailTemplateEntity->getPeriod(),
            'subject' => $emailTemplateEntity->getSubject(),
            'user_id' => $emailTemplateEntity->getUser()->getId()
            );

            $data[$cnt++] = $result;
        }

        return $data;
    }

    /**
     * Get total of  Email Templates by User Id
     *
     * @param int $userId
     * @return array data
     *
     * @View()
     * @ApiDoc(statusCodes={ 200="count",
     *                       404="Returned when user id found"},
     *         description="Get total of  Email Templates by User Id"
     * )
     */
    public function getCountAction($userId)
    {
        if (empty($userId)) return new Response('user not found', 404);

        $dm                      = $this->get('doctrine.odm.mongodb.document_manager');
        $emailTemplateRepository = $dm->getRepository('FaithleadRestBundle:EmailTemplate');

        return array('count' => $emailTemplateRepository->countByUserId($userId));
    }

    /**
     * Get the details of Email Template by Id
     *
     * @param int $id
     * @return array data
     *
     * @View()
     * @ApiDoc(statusCodes={ 200="array (id, body, period, subject, user_id)",
     *                       404="Returned when user id found"},
     *         description="Get the details of Email Template by Id"
     * )
     */
    public function getAction($id)
    {
        $dm                      = $this->get('doctrine.odm.mongodb.document_manager');
        $emailTemplateRepository = $dm->getRepository('FaithleadRestBundle:EmailTemplate');
        $emailTemplateEntity     = $emailTemplateRepository->findOneById($id);

        if (empty($emailTemplateEntity)) return new Response('Id not found', 404);

        return array(
            'id'      => $emailTemplateEntity->getId(),
            'body'    => $emailTemplateEntity->getBody(),
            'period'  => $emailTemplateEntity->getPeriod(),
            'subject' => $emailTemplateEntity->getSubject(),
            'user_id' => $emailTemplateEntity->getUser()->getId()
        );
    }

    /**
     * Create a new Email Template by User Id
     *
     * @param int $userId
     * @return View view instance
     *
     * @View()
     * @ApiDoc(statusCodes={ 200="Returned when successful",
     *                       404="Returned when user id found"},
     *         description="Create a new Email Template by User Id",
     *         output="Faithlead\RestBundle\Document\EmailTemplate",
     *         input="Faithlead\RestBundle\Form\Type\EmailTemplateType"
     * )
     */
    public function postAction($userId)
    {
        if (empty($userId)) return new Response('user not found.', 404);

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
                $userEntity     = $userRepository->findOneById($userId);

                if (empty($userEntity)) return new Response('user not found.', 404);

                $emailTemplateEntity->setUser($userEntity);
                $dm->persist($emailTemplateEntity);
                $dm->flush();

                return array('id' => $emailTemplateEntity->getId());
            } else {
                return array($form);
            }
        }
    }

    /**
     * Delete the specific Email Template by Id
     *
     * @param int $id
     * @return View view instance
     *
     * @View()
     * @ApiDoc(statusCodes={ 200="Returned when successful",
     *                       404="Returned when Id not found"},
     *         description="Delete the specific Email Template by Id"
     * )
     */
    public function deleteAction($id)
    {
        $dm                      = $this->get('doctrine.odm.mongodb.document_manager');
        $emailTemplateRepository = $dm->getRepository('FaithleadRestBundle:EmailTemplate');
        $emailTemplateEntity     = $emailTemplateRepository->findOneById($id);

        if (empty($emailTemplateEntity)) return new Response('Id not found.', 404);

        $dm->remove($emailTemplateEntity);
        $dm->flush();

        return new Response('success', 200);
    }

    /**
     * Update an Email Template by Id
     *
     * @param int $id
     * @return View view instance
     *
     * @View()
     * @ApiDoc(statusCodes={ 200="Returned when successful",
     *                       404="Returned when Id not found"},
     *         description="Update an Email Template by Id"
     * )
     */
    public function putAction($id)
    {

        $dm                      = $this->get('doctrine.odm.mongodb.document_manager');
        $emailTemplateRepository = $dm->getRepository('FaithleadRestBundle:EmailTemplate');
        $emailTemplateEntity     = $emailTemplateRepository->findOneById($id);

        if (empty($emailTemplateEntity)) return new Response('id not found.', 404);

        $emailTemplateEntity->setBody($this->getRequest()->request->get('body'));
        $emailTemplateEntity->setPeriod($this->getRequest()->request->get('period'));
        $emailTemplateEntity->setSubject($this->getRequest()->request->get('subject'));

        $dm->persist($emailTemplateEntity);
        $dm->flush();

        return new Response('success', 200);
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