<?php

/**
 * User Company Category
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
    Faithlead\RestBundle\Document\UserCompanyCategory,
    Faithlead\RestBundle\Document\User,
    Faithlead\RestBundle\Document\Subcategory,
    Faithlead\RestBundle\Form\Type\UserCompanyCategoryType;


use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Class user Company Category
 * @package Faithlead\Bundle\RestBundle\Controller
 * @RouteResource("Usercompanycategory")
 */
class UserCompanyCategoryController extends FosRestController
{

    /**
     * Get the User Company Category setting by User Id
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

        $dm                             = $this->get('doctrine.odm.mongodb.document_manager');
        $userCompanyCategoryRepository  = $dm->getRepository('FaithleadRestBundle:UserCompanyCategory');
        $userCompanyCategoryEntities    = $userCompanyCategoryRepository->findBy(array('user' => $userId));

        if (empty($userCompanyCategoryEntities)) return new Response('user not found', 404);

            return array('id'            => $userCompanyCategoryEntities->getId(),
                         'category_name' => $userCompanyCategoryEntities->getCompanyCategory()->getName(),
                         'subcategories' => $userCompanyCategoryEntities->getSubcategory()->getValues(),
                         'create_date'   => $userCompanyCategoryEntities->getCreatedAt()
                    );
    }

    /**
     * Create a new User Company Category Setting by User Id
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
        $userCompanyCategoryEntity   = new UserCompanyCategory();
        $form                    = $this->getForm($userCompanyCategoryEntity);
        $request                 = $this->getRequest();

        $userRepository  = $dm->getRepository('FaithleadRestBundle:User');
        $userEntity      = $userRepository->findBy(array('user' => $userId));

        $companyCategoryRepository  = $dm->getRepository('FaithleadRestBundle:CompanyCategory');
        $companyCategoryEntity      = $companyCategoryRepository->findBy(array('companyCategory' => $companyCategoryId));

        if (empty($userEntity) ) return new Response('User Id not found.', 404);
        if (empty($companyCategoryEntity)) return new Response('Company category Id not found.', 404);

        if ('POST' == $request->getMethod()) {

            $userCompanyCategoryEntity->setUser($userEntity);
            $userCompanyCategoryEntity->setCompanyCategory($companyCategoryEntity);

            $subcategories = explode(',', $request->request->get('subcategories', array()));
            foreach($subcategories as $key => $subcategory) $userCompanyCategoryEntity->setOneSubcategory(new Subcategory($subcategory));
            $dm->persist($userCompanyCategoryEntity);
            $dm->flush();

                return array('id' => $userCompanyCategoryEntity->getId());
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

        $dm                        = $this->get('doctrine.odm.mongodb.document_manager');
        $userCompanyCategoryRepository = $dm->getRepository('FaithleadRestBundle:UserCompanyCategory');
        $serCompanyCategoryEntity     = $userCompanyCategoryRepository->findOneById($id);

        if (empty($serCompanyCategoryEntity)) return new Response('Id not found.', 404);

        $dm->remove($serCompanyCategoryEntity);
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
     * Return a user company category form
     *
     * @param null $usercompanyCategoryEntity
     * @return \Symfony\Component\Form\Form
     */
    protected function getForm($usercompanyCategoryEntity = null)
    {
        return $this->createForm(new UserCompanyCategoryType(), $usercompanyCategoryEntity);
    }
}