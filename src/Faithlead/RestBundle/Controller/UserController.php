<?php

namespace Faithlead\RestBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Faithlead\RestBundle\Form\Type\UserType;

use FOS\RestBundle\Controller\Annotations\View,
    FOS\RestBundle\Controller\FOSRestController,
    FOS\RestBundle\Controller\Annotations\RouteResource;

use Faithlead\RestBundle\Document\User;

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
            'id' => $user->getId(),
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName(),
            'email' => $user->getEmail(),
            'eompany' => $user->getCompany(),
            'websiteUrl' => $user->getWebsite(),
            'phone' => $user->getPhone()
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

    public function postAction(Request $request){

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

    protected function getForm($user = null)
    {
        return $this->createForm(new UserType(), $user);
    }
}

