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
            ->setFrom('no-reply@faithlead.net')
            ->setTo('saeed.sas@gmail.com')
            ->setBody('hello email parser')
            )
        ;
        $this->get('mailer')->send($message);

        return new Response('I\'m email parser');
    }
}
