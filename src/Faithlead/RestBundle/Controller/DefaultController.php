<?php

namespace Faithlead\RestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('FaithleadRestBundle:Default:index.html.twig', array('name' => 'saeed'));
    }
}
