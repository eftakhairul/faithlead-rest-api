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

        $form = $this->getForm($user);

        if ('POST' == $request->getMethod()) {
            $form->bind(array(
                "firstName" => $this->getRequest()->request->get('firstName'),
                "lastName" => $this->getRequest()->request->get('lastName'),
                "email" => $this->getRequest()->request->get('email'),
                "password" => $this->getRequest()->request->get('password'),
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

