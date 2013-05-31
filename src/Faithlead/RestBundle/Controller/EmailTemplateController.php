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
     * Get total of  Email Templates
     *
     * @return array data
     *
     * @View()
     * @ApiDoc(statusCodes={200="count"},
     *         description="Get total of  Email Templates"
     * )
     */
    public function getCountAction()
    {
        $dm                      = $this->get('doctrine.odm.mongodb.document_manager');
        $emailTemplateRepository = $dm->getRepository('FaithleadRestBundle:EmailTemplate');

        return array('count' => $emailTemplateRepository->findCount());
    }

    /**
     * Get the details of Email Template by Id
     *
     * @param int $id
     * @return array data
     *
     * @View()
     * @ApiDoc(statusCodes={ 200="Returned when successful",
     *                       404="Returned when user id found"},
     *         description="Get the details of Email Template by Id",
     *         output="Faithlead\RestBundle\Document\EmailTemplate"
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
            'name'    => $emailTemplateEntity->getName()
        );
    }

    /**
     * Create a new Email Template
     *
     * @return View view instance
     *
     * @View()
     * @ApiDoc(statusCodes={ 200="Returned ID when successful",
     *                       500="Server error"},
     *         description="Create a new Email Template",
     *         input="Faithlead\RestBundle\Form\Type\EmailTemplateType"
     * )
     */
    public function postAction()
    {
        $dm                  = $this->get('doctrine.odm.mongodb.document_manager');
        $emailTemplateEntity = new EmailTemplate();
        $form                = $this->getForm($emailTemplateEntity);
        $request             = $this->getRequest();

        if ('POST' == $request->getMethod()) {

            $form->bind(array(
                "body"      => $request->request->get('body'),
                "name"      => $request->request->get('name')
                )
            );

            if ($form->isValid()) {

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
        $emailTemplateEntity->setName($this->getRequest()->request->get('name'));

        $dm->persist($emailTemplateEntity);
        $dm->flush();

        return new Response('success', 200);
    }

    /**
     * Return a email template form
     *
     * @param null $emailTemplateEntity
     * @return \Symfony\Component\Form\Form
     */
    protected function getForm($emailTemplateEntity = null)
    {
        return $this->createForm(new EmailTemplateType(), $emailTemplateEntity);
    }
}