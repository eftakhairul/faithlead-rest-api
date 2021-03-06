<?php

/**
 * Subcategory
 *
 * @author Eftakahirul Islam  <eftakhairul@gmail.com>
 * Copyright @ Faithlead
 */
namespace Faithlead\RestBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SubcategoryType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('subcategory', 'text');
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'        => 'Faithlead\RestBundle\Document\Subcategory',
            'csrf_protection'   => false,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'subcategory';
    }
}