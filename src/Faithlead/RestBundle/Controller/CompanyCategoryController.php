<?php

/**
 * User\Company Category
 *
 * @author Eftakahirul Islam  <eftakhairul@gmail.com>
 * Copyright @ Faithlead
 */
namespace Faithlead\RestBundle\Controller;

use Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\Response,
    Symfony\Bundle\FrameworkBundle\Controller\Controller;

use FOS\RestBundle\Controller\Annotations\View,
    FOS\RestBundle\Controller\FOSRestController,
    FOS\RestBundle\Controller\Annotations\QueryParam,
    FOS\RestBundle\Controller\Annotations\RouteResource;

use Faithlead\RestBundle\Document\CompanyCategory,
    Faithlead\RestBundle\Document\Subcategory,
    Faithlead\RestBundle\Form\Type\CompanyCategoryType;


use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Class Company Category
 * @package Faithlead\Bundle\RestBundle\Controller
 * @RouteResource("Companycategory")
 */
class CompanyCategoryController extends FosRestController
{
    /**
     * Get total of company category
     *
     * @return array data
     *
     * @View()
     * @ApiDoc()
     */
    public function getCountAction()
    {
        $dm                        = $this->get('doctrine.odm.mongodb.document_manager');
        $companyCategoryRepository = $dm->getRepository('FaithleadRestBundle:CompanyCategory');


        return array('count' => $companyCategoryRepository->findCount());
    }

    /**
     * Get the details of company category by Id
     *
     * @param int $id
     * @return array data
     *
     * @View()
     * @ApiDoc(statusCodes={200="Returned when successful",
     *                      404="Returned when id found"},
     *         output="Faithlead\RestBundle\Document\CompanyCategory"
     * )
     */
    public function getAction($id)
    {
        if (empty($id)) return new Response('Id not found.', 404);

        $dm                        = $this->get('doctrine.odm.mongodb.document_manager');
        $companyCategoryRepository = $dm->getRepository('FaithleadRestBundle:CompanyCategory');
        $companyCategoryEntity     = $companyCategoryRepository->findOneById($id);

        if (empty($companyCategoryEntity)) return new Response('Id not found.', 404);

        return array('id'            => $companyCategoryEntity->getId(),
                     'name'          => $companyCategoryEntity->getName(),
                     'subcategories' => $companyCategoryEntity->getSubcategory()->getValues(),
                     'create_date'   => $companyCategoryEntity->getCreatedAt()
               );
    }

    /**
     * Create a new Company Category
     *
     * @return View view instance
     *
     * @View()
     * @ApiDoc(input="Faithlead\RestBundle\Form\Type\CompanyCategoryType",
     *         statusCodes={200="Returned Id when successful",
     *                      505="Server Error"}
     * )
     */
    public function postAction()
    {
        $dm                      = $this->get('doctrine.odm.mongodb.document_manager');
        $companyCategoryEntity   = new CompanyCategory();
        $form                    = $this->getForm($companyCategoryEntity);
        $request                 = $this->getRequest();

        if ('POST' == $request->getMethod()) {

            $form->bind(array(
                "name"         => $request->request->get('subject')
                )
            );

            if ($form->isValid()) {

                $subcategories = explode(',', $request->request->get('subcategories', array()));
                foreach($subcategories as $key => $subcategory) $companyCategoryEntity->setOneSubcategory(new Subcategory($subcategory));
                $dm->persist($companyCategoryEntity);
                $dm->flush();

                return array('id' => $companyCategoryEntity->getId());
            } else {
                return array($form);
            }
        }
    }

    /**
     * Delete the specific company category by Id
     *
     * @param int $id
     * @return array data
     *
     * @View()
     * @ApiDoc(statusCodes={200="Returned when successful",
     *                      404="Returned when id not found"}
     * )
     */
    public function deleteAction($id)
    {
        if (empty($id)) return new Response('Id not found.', 404);

        $dm                        = $this->get('doctrine.odm.mongodb.document_manager');
        $companyCategoryRepository = $dm->getRepository('FaithleadRestBundle:CompanyCategory');
        $companyCategoryEntity     = $companyCategoryRepository->findOneById($id);

        if (empty($companyCategoryEntity)) return new Response('Id not found.', 404);

        $dm->remove($companyCategoryEntity);
        $dm->flush();

        return new Response('success', 200);
    }

    /**
     * Update an company category by Id
     *
     * @param int $id
     * @return View view instance
     *
     * @View()
     * @ApiDoc(statusCodes={200="Returned when successful",
     *                      404="Returned when id not found"})
     */
    public function putAction($id)
    {

        if (empty($id)) return new Response('Id not found.', 404);

        $dm                        = $this->get('doctrine.odm.mongodb.document_manager');
        $companyCategoryRepository = $dm->getRepository('FaithleadRestBundle:CompanyCategory');
        $companyCategoryEntity     = $companyCategoryRepository->findOneById($id);

        if (empty($companyCategoryEntity)) return new Response('Id not found.', 404);

        $companyCategoryEntity->setName($this->getRequest()->request->get('name'));

        $subcategories = explode(',', $this->getRequest()->request->get('subcategories', array()));
        foreach($subcategories as $key => $subcategory) $companyCategoryEntity->setOneSubcategory(new Subcategory($subcategory));

        $dm->persist($companyCategoryEntity);
        $dm->flush();

        return new Response('success', 200);
    }

    /**
     * Return a company category form
     *
     * @param null $companyCategoryEntity
     * @return \Symfony\Component\Form\Form
     */
    protected function getForm($companyCategoryEntity = null)
    {
        return $this->createForm(new CompanyCategoryType(), $companyCategoryEntity);
    }
}