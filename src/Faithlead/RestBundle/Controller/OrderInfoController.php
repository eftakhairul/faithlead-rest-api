<?php

namespace Faithlead\RestBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Faithlead\RestBundle\Form\Type\OrderInfoType;

use FOS\RestBundle\Controller\Annotations\View,
    FOS\RestBundle\Controller\FOSRestController,
    FOS\RestBundle\Controller\Annotations\RouteResource;

use Faithlead\RestBundle\Document\OrderInfo;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Class UserController
 * @package Faithlead\Bundle\RestBundle\Controller
 * @RouteResource("OrderInfo")
 */

class OrderInfoController extends FosRestController
{
    /**
     * @View()
     * @ApiDoc(
     *      description="Product Order Id and Customer Email grab",
     *      input="Faithlead\RestBundle\Form\Type\OrderInfoType",
     *      output="Faithlead\RestBundle\Document\OrderInfo",
     *      statusCodes={
     *         200="OK - Returned when successful and return created User Id",
     *         404="ERROR message"
     *     }
     *
     * )
     */
    public function postAction(Request $request){
        $dm = $this->get('doctrine.odm.mongodb.document_manager');

        $orderInfo = new OrderInfo();

        $form = $this->getForm($orderInfo);

        if ('POST' == $request->getMethod()) {

            $form->bind(array(
                    "customerName" => $this->getRequest()->request->get('customerName'),
                    "customerEmail" => $this->getRequest()->request->get('customerEmail'),
                    "orderId" => $this->getRequest()->request->get('orderId'),
                    "user" => $this->getRequest()->request->get('user')
                )
            );
            if ($form->isValid()) {
                $dm->persist($orderInfo);
                $dm->flush();
                return array('order_info' => $orderInfo->getId());
            }else{
                return array($form);
            }
        }
    }

    protected function getForm($orderInfo = null)
    {
        return $this->createForm(new OrderInfoType(), $orderInfo);
    }
}