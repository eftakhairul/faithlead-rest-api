<?php

/**
 * Category
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

use Faithlead\RestBundle\Document\Category,
    Faithlead\RestBundle\Document\Subcategory,
    Faithlead\RestBundle\Form\Type\CategoryType,
    Faithlead\RestBundle\Form\Type\SubcategoryType;


use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Class Category
 * @package Faithlead\Bundle\RestBundle\Controller
 * @RouteResource("Category")
 */
class CategoryController extends FosRestController
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
        $categoryRepository = $dm->getRepository('FaithleadRestBundle:Category');


        return array('count' => $categoryRepository->findCount());
    }

    /**
     * Get the details of category by Id
     *
     * @param int $id
     * @return array data
     *
     * @View()
     * @ApiDoc(statusCodes={200="Returned when successful",
     *                      404="Returned when id found"},
     *         output="Faithlead\RestBundle\Document\Category"
     * )
     */
    public function getAction($id)
    {
        if (empty($id)) return new Response('Id not found.', 404);

        $dm                        = $this->get('doctrine.odm.mongodb.document_manager');
        $categoryRepository        = $dm->getRepository('FaithleadRestBundle:Category');
        $categoryEntity            = $categoryRepository->findOneById($id);

        if (empty($categoryEntity)) return new Response('Id not found.', 404);

        return array('id'            => $categoryEntity->getId(),
                     'name'          => $categoryEntity->getName(),
                     'subcategories' => $categoryEntity->getSubcategory()->getValues(),
                     'create_date'   => $categoryEntity->getCreatedAt()
               );
    }

    /**
     * Create a new Category
     *
     * @return View view instance
     *
     * @View()
     * @ApiDoc(input="Faithlead\RestBundle\Form\Type\CategoryType",
     *         statusCodes={200="Returned Id when successful",
     *                      505="Server Error"}
     * )
     */
    public function postAction()
    {
        $dm                      = $this->get('doctrine.odm.mongodb.document_manager');
        $CategoryEntity   = new Category();
        $form                    = $this->getForm($CategoryEntity);
        $request                 = $this->getRequest();

        if ('POST' == $request->getMethod()) {

            $form->bind(array(
                "name"         => $request->request->get('subject')
                )
            );

            if ($form->isValid()) {

                $subcategories = explode(',', $request->request->get('subcategories', array()));
                foreach($subcategories as $key => $subcategory) $CategoryEntity->setOneSubcategory(new Subcategory($subcategory));
                $dm->persist($CategoryEntity);
                $dm->flush();

                return array('id' => $CategoryEntity->getId());
            } else {
                return array($form);
            }
        }
    }

    /**
     * Add subcategory by category id
     *
     ** @param int $id
     * @return View view instance
     *
     * @View()
     * @ApiDoc(input="Faithlead\RestBundle\Form\Type\SubCategoryType",
     *         statusCodes={200="Returned subcategory name when successful",
     *                      505="Server Error",
     *                      404="Returned when id not found"}
     * )
     */
    public function postSubCategoryAction($id)
    {
        if (empty($id)) return new Response('Id not found.', 404);

        $dm                 = $this->get('doctrine.odm.mongodb.document_manager');
        $categoryRepository = $dm->getRepository('FaithleadRestBundle:Category');
        $categoryEntity     = $categoryRepository->findOneById($id);

        if (empty($categoryEntity)) return new Response('Id not found.', 404);

        $dm                      = $this->get('doctrine.odm.mongodb.document_manager');
        $SubcategoryEntity       = new Subcategory();
        $form                    = $this->getSubcategoryForm($SubcategoryEntity);
        $request                 = $this->getRequest();

        if ('POST' == $request->getMethod()) {

            $form->bind(array(
                "subcategory" => $request->request->get('subcategory')
                )
            );

            if ($form->isValid()) {

                $categoryEntity->setOneSubcategory($SubcategoryEntity);
                $dm->persist($categoryEntity);
                $dm->flush();

                return array('subcategory' => $SubcategoryEntity->getSubcategory());
            } else {
                return array($form);
            }
        }
    }

    /**
     * Delete the specific category by Id
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

        $dm                 = $this->get('doctrine.odm.mongodb.document_manager');
        $categoryRepository = $dm->getRepository('FaithleadRestBundle:Category');
        $categoryEntity     = $categoryRepository->findOneById($id);

        if (empty($categoryEntity)) return new Response('Id not found.', 404);

        $dm->remove($categoryEntity);
        $dm->flush();

        return new Response('success', 200);
    }

    /**
     * Update an Category by Id
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

        $dm                 = $this->get('doctrine.odm.mongodb.document_manager');
        $categoryRepository = $dm->getRepository('FaithleadRestBundle:Category');
        $categoryEntity     = $categoryRepository->findOneById($id);

        if (empty($categoryEntity)) return new Response('Id not found.', 404);

        $categoryEntity->setName($this->getRequest()->request->get('name'));

        $subcategories = explode(',', $this->getRequest()->request->get('subcategories', array()));
        foreach($subcategories as $key => $subcategory) $categoryEntity->setOneSubcategory(new Subcategory($subcategory));

        $dm->persist($categoryEntity);
        $dm->flush();

        return new Response('success', 200);
    }

    /**
     * Return a category form
     *
     * @param null $CategoryEntity
     * @return \Symfony\Component\Form\Form
     */
    protected function getForm($CategoryEntity = null)
    {
        return $this->createForm(new CategoryType(), $CategoryEntity);
    }

    /**
     * Return a subcategory form
     *
     * @param null $SubcategoryEntity
     * @return \Symfony\Component\Form\Form
     */
    protected function getSubcategoryForm($SubcategoryEntity = null)
    {
        return $this->createForm(new SubcategoryType(), $SubcategoryEntity);
    }
}