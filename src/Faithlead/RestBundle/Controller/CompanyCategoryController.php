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

use Faithlead\RestBundle\Document\Category,
    Faithlead\RestBundle\Document\CompanyCategory,
    Faithlead\RestBundle\Document\User,
    Faithlead\RestBundle\Document\Subcategory,
    Faithlead\RestBundle\Form\Type\CompanyCategoryType;


use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Class user Company Category
 * @package Faithlead\Bundle\RestBundle\Controller
 * @RouteResource("Usercompanycategory")
 */
class UserCompanyCategoryController extends FosRestController
{

    /**
     * Get the Company Category setting by User Id
     *
     * @param int $userId
     * @return array data
     *
     * @View()
     * @ApiDoc(statusCodes={ 200="Return when success",
     *                       404="Returned when user id found",
     *                       505="Server Error"},
     *         description="Get the details of user company category setting by User Id",
     *         output="Faithlead\RestBundle\Document\UserCompanyCategory"
     * )
     */
    public function getUserAction($userId)
    {
        if (empty($userId)) return new Response('user not found', 404);

        $dm                         = $this->get('doctrine.odm.mongodb.document_manager');
        $companyCategoryRepository  = $dm->getRepository('FaithleadRestBundle:CompanyCategory');
        $companyCategoryEntities    = $companyCategoryRepository->findBy(array('user' => $userId));

        if (empty($companyCategoryEntities)) return new Response('user not found', 404);

            return array('id'            => $companyCategoryEntities->getId(),
                         'category_name' => $companyCategoryEntities->getCategory()->getName(),
                         'subcategories' => $companyCategoryEntities->getSubcategory()->getValues(),
                         'create_date'   => $companyCategoryEntities->getCreatedAt()
                    );
    }

    /**
     * Create a new Company Category Setting by User Id
     *
     * @return View view instance
     *
     * @View()
     * @ApiDoc(input="Faithlead\RestBundle\Form\Type\UserCompanyCategoryType",
     *         statusCodes={200="Returned Id when successful",
     *                      404="Returned when user id or company category id not found",
     *                      505="Server Error"}
     * )
     */
    public function postAction()
    {
        $userId            =  $this->getRequest()->request->get('user');
        $companyCategoryId =  $this->getRequest()->request->get('companyCategory');

        if (empty($userId) ) return new Response('User Id not found.', 404);
        if (empty($companyCategoryId)) return new Response('Company category Id not found.', 404);

        $dm                      = $this->get('doctrine.odm.mongodb.document_manager');
        $companyCategoryEntity   = new CompanyCategory();
        $form                    = $this->getForm($companyCategoryEntity);
        $request                 = $this->getRequest();

        $userRepository  = $dm->getRepository('FaithleadRestBundle:User');
        $userEntity      = $userRepository->findOneById($userId);

        $categoryRepository  = $dm->getRepository('FaithleadRestBundle:Category');
        $categoryEntity      = $categoryRepository->findOneById($companyCategoryId);

        if (empty($userEntity) ) return new Response('User Id not found.', 404);
        if (empty($categoryEntity)) return new Response('Company category Id not found.', 404);

        if ('POST' == $request->getMethod()) {

            $companyCategoryEntity->setUser($userEntity);
            $companyCategoryEntity->setCategory($categoryEntity);

            $subcategories = explode(',', $request->request->get('subcategories', array()));
            foreach($subcategories as $key => $subcategory) $companyCategoryEntity->setOneSubcategory(new Subcategory($subcategory));
            $dm->persist($companyCategoryEntity);
            $dm->flush();

                return array('id' => $companyCategoryEntity->getId());
        } else {
            return array($form);
        }

    }

    /**
     * Delete the specific user company category setting by Id
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

        $dm                              = $this->get('doctrine.odm.mongodb.document_manager');
        $userCompanyCategoryRepository   = $dm->getRepository('FaithleadRestBundle:UserCompanyCategory');
        $userCompanyCategoryEntity       = $userCompanyCategoryRepository->findOneById($id);

        if (empty($userCompanyCategoryEntity)) return new Response('Id not found.', 404);

        $dm->remove($userCompanyCategoryEntity);
        $dm->flush();

        return new Response('success', 200);
    }

    /**
     * Update an user company category by Id
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

        $dm                            = $this->get('doctrine.odm.mongodb.document_manager');
        $companyCategoryRepository = $dm->getRepository('FaithleadRestBundle:CompanyCategory');
        $companyCategoryEntity     = $companyCategoryRepository->findOneById($id);

        if (empty($companyCategoryEntity)) return new Response('Id not found.', 404);

        $userId            =  $this->getRequest()->request->get('user');

        if (!empty($userId)) {
            $userRepository  = $dm->getRepository('FaithleadRestBundle:User');
            $userEntity      = $userRepository->findOneById($userId);
            $companyCategoryEntity->setUser($userEntity);
        }

        $categoryId =  $this->getRequest()->request->get('companyCategory');

        if(!empty($categoryId)) {
            $categoryRepository  = $dm->getRepository('FaithleadRestBundle:Category');
            $categoryEntity             = $categoryRepository->findOneById($categoryId);
            $companyCategoryEntity->setCategory($categoryEntity);

        }

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