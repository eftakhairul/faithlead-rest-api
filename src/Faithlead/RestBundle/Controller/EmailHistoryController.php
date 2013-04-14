<?php

/**
 * Email History
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
    Faithlead\RestBundle\Document\EmailHistory;
    Faithlead\RestBundle\Document\Tag;


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
            $result = array('id'      => $emailHistoryEntity->getId(),
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

        return array('id'      => $emailHistoryEntity->getId(),
                    'period'  => $emailHistoryEntity->getPeriod(),
                    'subject' => $emailHistoryEntity->getSubject(),
                    'user_id' => $emailHistoryEntity->getUser()->getId(),
                    'status'  => $emailHistoryEntity->getStatus(),
                    'success' => true
                );
    }

    /**
     * Create a new Email Template by User Id
     *
     * @param int $userId
     * @return View view instance
     *
     * @View()
     * @ApiDoc(
     *      input="Faithlead\RestBundle\Form\Type\EmailTemplateType"
     * )
     */
    public function postAction($userId)
    {
        if (empty($userId)) return array('status' => false);

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

                $tags = explode(',', $request->request->get('tags'));
                foreach($tags as $key => $tag) $emailHistoryEntity->setOneTag(new Tag($tag));

                $emailHistoryEntity->setUser($userEntity);
                $dm->persist($emailHistoryEntity);
                $dm->flush();

                return array('id' => $$emailHistoryEntity->getId(), 'status' => 'success');
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
        $emailHistoryRepository  = $dm->getRepository('FaithleadRestBundle:EmailHistory');
        $emailHistoryEntity      = $emailHistoryRepository->findOneById($id);

        if (empty($emailHistoryEntity)) return array('status' => false);

        $dm->remove($emailHistoryEntity);
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
        $emailHistoryRepository  = $dm->getRepository('FaithleadRestBundle:EmailHistory');
        $emailHistoryEntity      = $emailHistoryRepository->findOneById($id);

        if (empty($emailHistoryEntity)) array('status' => false);

//        $emailHistoryEntity->setBody($this->getRequest()->request->get('subject'));
//        $emailHistoryEntity->setPeriod($this->getRequest()->request->get('ema'));
//        $emailHistoryEntity->setSubject($this->getRequest()->request->get('subject'));

        $dm->persist($emailHistoryEntity);
        $dm->flush();

        return array('status' => 'success');
    }

    /**
     * Return a form
     *
     * @param null $emailHistoryEntity
     * @return \Symfony\Component\Form\Form
     */
    protected function getForm($emailHistoryEntity = null)
    {
        return $this->createForm(new EmailHistoryType(), $emailHistoryEntity);
    }
}