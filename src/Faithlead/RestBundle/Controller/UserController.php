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

    public function createAction(Request $request){

        $dm = $this->get('doctrine.odm.mongodb.document_manager');

        $user = new User();

        $form = $this->get('form.factory')->create(new UserType());

//        $data = array(
//            'firstName' => $_POST['firstName'],
//            'lastName' => $_POST['lastName'],
//            'email'=> $_POST['email'],
//            'password' => $_POST['password']
//        );

        $request = $this->get('request');
        if ('POST' == $request->getMethod()) {
            $form->bindRequest($request);
            if ($form->isValid()) {
                return array('users' => $form->getData());
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

