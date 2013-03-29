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

class UserController extends FosRestController{


    /**
     * Get the list of users
     *
     * @return array data
     *
     * @View()
     * @ApiDoc()
     */
    public function allAction()
    {
        $users = array('bim', 'bam', 'bingo');

        return array('users' => $users);
    }

    /**
     * Create a new user
     *
     * @param Request $request
     * @return View view instance
     *
     * @View()
     * @ApiDoc(
     *      input="Faithlead\RestBundle\Form\Type\UserType"
     * )
     */

    public function postAction(Request $request){

        $dm = $this->get('doctrine.odm.mongodb.document_manager');

        $user = new User();

        //$content = json_decode($request->getParameter('json'));

        $form = $this->getForm($user);

//        $data = array(
//            'firstName' => isset($_POST['firstName']),
//            'lastName' => $_POST['lastName'],
//            'email'=> $_POST['email'],
//            'password' => $_POST['password']
//        );

        //$request = $this->get('request');
        if ('POST' == $request->getMethod()) {
            $form->bind(array(
                "firstName" => $this->getRequest()->request->get('firstName'),
                "lastName" => $this->getRequest()->request->get('lastName'),
                "email" => $this->getRequest()->request->get('email'),
                "password" => $this->getRequest()->request->get('password'),
                )
            );
            if ($form->isValid()) {
                return array('users' => $form->getData());
            }else{
                return array($form);
            }
        }
    }


//
//        $user->setFirstName('saeed');
//        $user->setLastName('Ahmed');
//        $user->setPassword('123456');
//        $user->setEmail('saeed.sas@gmail.com');
//        $user->setAccountConfirmed(true);
//
//        $dm = $this->get('doctrine.odm.mongodb.document_manager');
//
//        $dm->persist($user);
//        $dm->flush();

    protected function getForm($user = null)
    {
        return $this->createForm(new UserType(), $user);
    }

}

