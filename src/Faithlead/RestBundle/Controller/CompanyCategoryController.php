<?php

/**
 * Company Category
 *
 * @author Eftakahirul Islam  <eftakhairul@gmail.com>
 * Copyright @ Faithlead
 */
namespace Faithlead\RestBundle\Controller;

use Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\Response,
    Symfony\Bundle\FrameworkBundle\Controller\Controller;

use FOS\RestBundle\Controller\Annotations\View,
    FOS\RestBundle\Controller\FOSRestController,
    FOS\RestBundle\Controller\Annotations\QueryParam,
    FOS\RestBundle\Controller\Annotations\RouteResource;

use Faithlead\RestBundle\Document\User,
    Faithlead\RestBundle\Document\EmailHistory,
    Faithlead\RestBundle\Document\Tag,
    Faithlead\RestBundle\Form\Type\EmailHistoryType;


use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Class Company Category
 * @package Faithlead\Bundle\RestBundle\Controller
 * @RouteResource("Companycategory")
 */
class CompanyCategory extends FosRestController
{


    /**
     * Get total of  Email History by User Id
     *
     * @return array data
     *
     * @View()
     * @ApiDoc()
     */
    public function getCountAction()
    {
        if (empty($userId)) return new Response('user not found', 404);

        $dm                      = $this->get('doctrine.odm.mongodb.document_manager');
        $emailHistoryRepository = $dm->getRepository('FaithleadRestBundle:EmailHistory');


        return array('count' => $emailHistoryRepository->countByUserId($userId));
    }

    /**
     * Get the details of Email History by Id
     *
     * @param int $id
     * @return array data
     *
     * @View()
     * @ApiDoc(statusCodes={200="Returned when successful",
     *                      404="Returned when id found"},
     *         output="Faithlead\RestBundle\Document\EmailHistory"
     * )
     */
    public function getAction($id)
    {
        $dm                      = $this->get('doctrine.odm.mongodb.document_manager');
        $emailHistoryRepository  = $dm->getRepository('FaithleadRestBundle:EmailHistory');
        $emailHistoryEntity     = $emailHistoryRepository->findOneById($id);

        if (empty($emailHistoryEntity)) return new Response('Id not found.', 404);

        return array('id'           => $emailHistoryEntity->getId(),
                    'subject'       => $emailHistoryEntity->getSubject(),
                    'user_id'       => $emailHistoryEntity->getUser()->getId(),
                    'email_address' => $emailHistoryEntity->getEmailAddress(),
                    'status'        => $emailHistoryEntity->getStatus(),
                    'tags'          => $emailHistoryEntity->getTag()->getValues(),
                    'success'       => true
                );
    }

    /**
     * Create a new Email History by User Id
     *
     * @param int $userId
     * @return View view instance
     *
     * @View()
     * @ApiDoc(input="Faithlead\RestBundle\Form\Type\EmailHistoryType",
     *         statusCodes={200="Returned when successful",
     *                      404="Returned when user id found"},
     *         output="Faithlead\RestBundle\Document\EmailHistory"
     * )
     */
    public function postAction($userId)
    {
        if (empty($userId)) return new Response('user not found', 404);

        $dm                  = $this->get('doctrine.odm.mongodb.document_manager');
        $emailHistoryEntity = new EmailHistory();
        $form                = $this->getForm($emailHistoryEntity);
        $request             = $this->getRequest();

        if ('POST' == $request->getMethod()) {

            $form->bind(array(
                "status"          => $request->request->get('body'),
                "subject"         => $request->request->get('subject'),
                "email_address"   => $request->request->get('email_address'),
                )
            );

            if ($form->isValid()) {
                $userRepository = $dm->getRepository('FaithleadRestBundle:User');
                $userEntity     = $userRepository->findOneById($userId);

                $tags = explode(',', $request->request->get('tags', array()));
                foreach($tags as $key => $tag) $emailHistoryEntity->setOneTag(new Tag($tag));

                $emailHistoryEntity->setUser($userEntity);
                $dm->persist($emailHistoryEntity);
                $dm->flush();

                return array('id' => $emailHistoryEntity->getId());
            } else {
                return array($form);
            }
        }
    }

    /**
     * Delete the specific Email history by Id
     *
     * @param int $id
     * @return array data
     *
     * @View()
     * @ApiDoc(statusCodes={200="Returned when successful",
     *                      404="Returned when id found"}
     * )
     */
    public function deleteAction($id)
    {
        $dm                      = $this->get('doctrine.odm.mongodb.document_manager');
        $emailHistoryRepository  = $dm->getRepository('FaithleadRestBundle:EmailHistory');
        $emailHistoryEntity      = $emailHistoryRepository->findOneById($id);

        if (empty($emailHistoryEntity)) return new Response('Id not found.', 404);

        $dm->remove($emailHistoryEntity);
        $dm->flush();

        return new Response('success', 200);
    }

    /**
     * Update an Email History by Id
     *
     * @param int $id
     * @return View view instance
     *
     * @View()
     * @ApiDoc(statusCodes={200="Returned when successful",
     *                      404="Returned when id found"})
     */
    public function putAction($id)
    {

        $dm                      = $this->get('doctrine.odm.mongodb.document_manager');
        $emailHistoryRepository  = $dm->getRepository('FaithleadRestBundle:EmailHistory');
        $emailHistoryEntity      = $emailHistoryRepository->findOneById($id);

        if (empty($emailHistoryEntity)) return new Response('Id not found.', 404);

        $emailHistoryEntity->setSubject($this->getRequest()->request->get('subject'));
        $emailHistoryEntity->setStatus($this->getRequest()->request->get('status'));
        $emailHistoryEntity->setEmailAddress($this->getRequest()->request->get('email_address'));

        $tags = explode(',', $this->getRequest()->request->get('tags', array()));
        foreach($tags as $key => $tag) $emailHistoryEntity->setOneTag(new Tag($tag));

        $dm->persist($emailHistoryEntity);
        $dm->flush();

        return new Response('success', 200);
    }

    /**
     * Return a email history form
     *
     * @param null $emailHistoryEntity
     * @return \Symfony\Component\Form\Form
     */
    protected function getForm($emailHistoryEntity = null)
    {
        return $this->createForm(new EmailHistoryType(), $emailHistoryEntity);
    }
}