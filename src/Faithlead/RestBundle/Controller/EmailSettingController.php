<?php

/**
 * Controller for email setting
 *
 * @author Eftakahirul Islam  <eftakhairul@gmail.com>
 * Copyright @ Faithlead
 */
namespace Faithlead\RestBundle\Controller;

use Faithlead\RestBundle\Form\Type\EmailTemplateType;
use Faithlead\RestBundle\Form\Type\EmailSettingType;

use Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\Response,
    Symfony\Bundle\FrameworkBundle\Controller\Controller;

use FOS\RestBundle\Controller\Annotations\View,
    FOS\RestBundle\Controller\FOSRestController,
    FOS\RestBundle\Controller\Annotations\QueryParam,
    FOS\RestBundle\Controller\Annotations\RouteResource;

use Faithlead\RestBundle\Document\User,
    Faithlead\RestBundle\Document\EmailTemplate,
    Faithlead\RestBundle\Document\EmailSetting;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Class UserController
 * @package Faithlead\Bundle\RestBundle\Controller
 * @RouteResource("emailsetting")
 */
class EmailSettingController extends FosRestController
{
    /**
     * Get the list of Email Settings by User Id
     *
     * @param int $userId
     * @return array data
     *
     * @View()
     * @ApiDoc(statusCodes={ 200="Return when success",
     *                       404="Returned when user id not found"},
     *         description="Get the list (array) of Email Setting by User Id",
     *         output="Faithlead\RestBundle\Document\EmailSetting"
     * )
     */
    public function getUserAction($userId)
    {
        if (empty($userId)) return new Response('user not found', 404);

        $data = array();
        $dm                      = $this->get('doctrine.odm.mongodb.document_manager');
        $emailSettingRepository  = $dm->getRepository('FaithleadRestBundle:EmailSetting');
        $emailSettingEntities    = $emailSettingRepository->findBy(array('user' => $userId));

        $cnt = 0;
        foreach($emailSettingEntities as $emailSettingEntity)
        {
            $result = array(
            'id'            => $emailSettingEntity->getId(),
            'period'        => $emailSettingEntity->getPeriod(),
            'subject'       => $emailSettingEntity->getSubject(),
            'user_id'       => $emailSettingEntity->getUser()->getId(),
            'is_active'     => $emailSettingEntity->getIsActive(),
            'template_name' => $emailSettingEntity->getEmailTemplate()->getName(),
            'create_date'   => $emailSettingEntity->getCreatedAt
            );

            $data[$cnt++] = $result;
        }

        return $data;
    }

    /**
     * Get total of  Email Settings by User Id
     *
     * @param int $userId
     * @return array data
     *
     * @View()
     * @ApiDoc(statusCodes={ 200="count",
     *                       404="Returned when user id not found"},
     *         description="Get total of  Email Setting by User Id"
     * )
     */
    public function getCountAction($userId)
    {
        if (empty($userId)) return new Response('user not found', 404);

        $dm                      = $this->get('doctrine.odm.mongodb.document_manager');
        $emailSettingRepository  = $dm->getRepository('FaithleadRestBundle:EmailSetting');

        return array('count' => $emailSettingRepository->countByUserId($userId));
    }

    /**
     * Get the details of an Email Setting by Id
     *
     * @param int $id
     * @return array data
     *
     * @View()
     * @ApiDoc(statusCodes={ 200="array (id, body, period, subject, user_id)",
     *                       404="Returned when user id not found"},
     *         description="Get the details of Email Setting by Id",
     *         output="Faithlead\RestBundle\Document\EmailSetting"
     * )
     */
    public function getAction($id)
    {
        $dm                      = $this->get('doctrine.odm.mongodb.document_manager');
        $emailSettingRepository  = $dm->getRepository('FaithleadRestBundle:EmailSetting');
        $emailSettingEntity     = $emailSettingRepository->findOneById($id);

        if (empty($emailSettingEntity)) return new Response('Id not found', 404);

        return array(
            'id'            => $emailSettingEntity->getId(),
            'is_active'     => $emailSettingEntity->getIsActive(),
            'period'        => $emailSettingEntity->getPeriod(),
            'subject'       => $emailSettingEntity->getSubject(),
            'template_name' => $emailSettingEntity->getEmailTemplate()->getName(),
            'user_id'       => $emailSettingEntity->getUser()->getId(),
            'create_date'   => $emailSettingEntity->getCreatedAt
        );
    }

    /**
     * Create a new Email Setting by User Id and Template Id
     *
     * @return View view instance
     *
     * @View()
     * @ApiDoc(statusCodes={ 200="Returned when successful",
     *                       404="Returned when user id or template id not found"},
     *         description="Create a new Email Setting by User Id and Email Template Id",
     *         output="Faithlead\RestBundle\Document\EmailSetting",
     *         input="Faithlead\RestBundle\Form\Type\EmailSettingType"
     * )
     */
    public function postAction()
    {
        $dm                  = $this->get('doctrine.odm.mongodb.document_manager');
        $emailSettingEntity  = new EmailSetting();
        $form                = $this->getForm($emailSettingEntity);
        $request             = $this->getRequest();

        if ('POST' == $request->getMethod()) {

            $form->bind(array(
                    "period"        => $request->request->get('period'),
                    "subject"       => $request->request->get('subject'),
                    'user'          => $request->request->get('user'),
                    'emailTemplate' => $request->request->get('emailTemplate'),
                )
            );

            if ($form->isValid()) {
                /*
                 * @todo Check is user and email template exists or not
                */
                $dm->persist($emailSettingEntity);
                $dm->flush();
                return array('email_settings' => $emailSettingEntity->getId());
            }else{
                return array($form);
            }
        }
    }

    /**
     * Delete the specific Email Setting by Id
     *
     * @param int $id
     * @return View view instance
     *
     * @View()
     * @ApiDoc(statusCodes={ 200="Returned when successful",
     *                       404="Returned when Id not found"},
     *         description="Delete the specific Email Setting by Id"
     * )
     */
    public function deleteAction($id)
    {
        $dm                      = $this->get('doctrine.odm.mongodb.document_manager');
        $emailSettingRepository  = $dm->getRepository('FaithleadRestBundle:EmailSetting');
        $emailSettingEntity      = $emailSettingRepository->findOneById($id);

        if (empty($emailSettingEntity)) return new Response('Id not found.', 404);

        $dm->remove($emailSettingEntity);
        $dm->flush();

        return new Response('success', 200);
    }

    /**
     * Update an Email Setting by Id
     *
     * @param int $id
     * @return View view instance
     *
     * @View()
     * @ApiDoc(statusCodes={ 200="Returned when successful",
     *                       404="Returned when Id not found"},
     *         description="Update an Email Setting by Id"
     * )
     */
    public function putAction($id)
    {

        $dm                      = $this->get('doctrine.odm.mongodb.document_manager');
        $emailSettingRepository = $dm->getRepository('FaithleadRestBundle:EmailSetting');
        $emailSettingEntity     = $emailSettingRepository->findOneById($id);

        if (empty($emailSettingEntity)) return new Response('id not found.', 404);

        $emailSettingEntity->setPeriod($this->getRequest()->request->get('period'));
        $emailSettingEntity->setSubject($this->getRequest()->request->get('subject'));

        if ($this->getRequest()->request->get('subject')) {
            $emailSettingEntity->setIsActive(true);
        }

        $dm->persist($emailSettingEntity);
        $dm->flush();

        return new Response('success', 200);
    }

    /**
     * Return a email setting form
     *
     * @param null $emailSettingEntity
     * @return \Symfony\Component\Form\Form
     */
    protected function getForm($emailSettingEntity = null)
    {
        return $this->createForm(new EmailSettingType(), $emailSettingEntity);
    }
}