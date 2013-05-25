<?php

namespace Faithlead\RestBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * Class UserController
 * @package Faithlead\Bundle\RestBundle\Controller
 */
class EmailParserController
{
    public function indexAction()
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Hello Email')
            ->setFrom('No-Reply@faithlead.net')
            ->setTo('saeed.sas@gmail.com')
            ->setBody(
                $this->renderView(
                    'FaithleadRestBundle:index:email.txt.twig',
                    array('name' => 'Saeed')
                )
            )
        ;
        $this->get('mailer')->send($message);

        return new Response('I\'m email parser');
    }
}
