<?php

namespace Faithlead\RestBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


/**
 * Class EmailParserController
 * @package Faithlead\Bundle\RestBundle\Controller
 */
class EmailParserController extends Controller
{
    public function indexAction()
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Hello Email')
            ->setFrom('No-Reply@faithlead.net')
            ->setTo('saeed.sas@gmail.com')
            ->setBody(
                   'email parser'
            )
        ;
        $this->get('mailer')->send($message);

        return new Response('I\'m email parser');
    }
}
