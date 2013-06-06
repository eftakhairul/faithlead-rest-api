<?php

namespace Faithlead\RestBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use FOS\RestBundle\Controller\Annotations\View,
    FOS\RestBundle\Controller\FOSRestController,
    FOS\RestBundle\Controller\Annotations\RouteResource;

use Faithlead\RestBundle\Document\User,
    Faithlead\RestBundle\Form\Type\UserType,
    Faithlead\RestBundle\Form\Type\FbFunPageUrlType;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Class UserController
 * @package Faithlead\Bundle\RestBundle\Controller
 * @RouteResource("User")
 */

class UserController extends FosRestController
{

    /**
     * Get the list of all User
     *
     * @return array data
     *
     * @View()
     * @ApiDoc(
     *      description="Get user details",
     *      output="Faithlead\RestBundle\Document\User",
     *      statusCodes={
     *         200="OK - Returned when successful and return created User Id",
     *         404="ERROR message"
     *     }
     *
     * )
     */
    public function allAction()
    {
        $data = array();
        $dm                      = $this->get('doctrine.odm.mongodb.document_manager');
        $userRepository          = $dm->getRepository('FaithleadRestBundle:User');
        $userEntities            = $userRepository->findAll();

        $cnt = 0;
        foreach($userEntities as $userEntity)
        {
            $result = array(
            'id'            => $userEntity->getId(),
            'first_name'    => $userEntity->getFirstName(),
            'last_name'     => $userEntity->getLastName(),
            'email'         => $userEntity->getEmail(),
            'success'       => true
            );

            $data[$cnt++] = $result;
        }

        return $data;
    }



    /**
     * Get the list of users
     *
     * @param int $id
     * @return array data
     *
     * @View()
     * @ApiDoc(
     *      description="Get user details by user id",
     *      output="Faithlead\RestBundle\Document\User",
     *      statusCodes={
     *         200="OK - Returned when successful and return created User Id",
     *         404="ERROR message"
     *     }
     *
     * )
     */
    public function getAction($id)
    {

        $repository = $this->get('doctrine.odm.mongodb.document_manager')
                           ->getRepository('FaithleadRestBundle:User');
        $user = $repository->findOneById($id);

        return array('users' => array(
            'id'            => $user->getId(),
            'firstName'     => $user->getFirstName(),
            'lastName'      => $user->getLastName(),
            'email'         => $user->getEmail(),
            'eompany'       => $user->getCompany(),
            'websiteUrl'    => $user->getWebsite(),
            'phone'         => $user->getPhone()
        ));
    }

    /**
     * @View()
     * @ApiDoc(
     *      description="Register new user account.",
     *      input="Faithlead\RestBundle\Form\Type\UserType",
     *      output="Faithlead\RestBundle\Document\User",
     *      statusCodes={
     *         200="OK - Returned when successful and return created User Id",
     *         404="ERROR message"
     *     }
     *
     * )
     */
    public function postAction(Request $request)
    {

        $dm = $this->get('doctrine.odm.mongodb.document_manager');

        $user = new User();

        $form = $this->getForm($user);

        if ('POST' == $request->getMethod()) {
            $form->bind(array(
                "firstName" => $this->getRequest()->request->get('firstName'),
                "lastName" => $this->getRequest()->request->get('lastName'),
                "email" => $this->getRequest()->request->get('email'),
                "password" => $this->getRequest()->request->get('password'),
                "company" => $this->getRequest()->request->get('company'),
                "website" => $this->getRequest()->request->get('website'),
                "phone" => $this->getRequest()->request->get('phone'),
                )
            );
            if ($form->isValid()) {
                $dm->persist($user);
                $dm->flush();
                return array('users' => $user->getId());
            }else{
                return array($form);
            }
        }
    }

    /**
     * Add FB Fun Page Url  by user id
     *
     * @param int $id
     * @return View view instance
     *
     * @View()
     * @ApiDoc(input="Faithlead\RestBundle\Form\Type\FbFunPageUrlType",
     *         statusCodes={200="Returned success when it successful",
     *                      505="Server Error",
     *                      404="Returned when id not found"}
     * )
     */
    public function postFbfubpagerlAction($id)
    {
        if (empty($id)) return new Response('Id not found.', 404);

        $dm             = $this->get('doctrine.odm.mongodb.document_manager');
        $userRepository = $dm->getRepository('FaithleadRestBundle:User');
        $userEntity     = $userRepository->findOneById($id);

        if (empty($userEntity)) return new Response('Id not found.', 404);

        $dm       = $this->get('doctrine.odm.mongodb.document_manager');
        $form     = $this->getFbFunPageUrlForm($userEntity);
        $request  = $this->getRequest();

        if ('POST' == $request->getMethod()) {

            $form->bind(array(
                "fbFanPageUrl" => $request->request->get('fbFanPageUrl')
                )
            );

            if ($form->isValid()) {

                $userEntity->setFbFanPageUrl($request->request->get('fbFanPageUrl'));
                $dm->persist($userEntity);
                $dm->flush();

                return array('users' => 'success');
            } else {
                return array($form);
            }
        }
    }

    protected function getForm($user = null)
    {
        return $this->createForm(new UserType(), $user);
    }

    /**
     * Return a FbFunPageUrl form
     *
     * @param null $userEntity
     * @return \Faithlead\RestBundle\Form\Type\FbFunPageUrlType
     */
    protected function getFbFunPageUrlForm($userEntity = null)
    {
        return $this->createForm(new FbFunPageUrlType(), $userEntity);
    }
}

