<?php

/**
 * FbFanPageUrl Form
 *
 * @author Eftakahirul Islam  <eftakhairul@gmail.com>
 * Copyright @ Faithlead
 */
namespace Faithlead\RestBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FbFunPageUrlType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('fbFanPageUrl', 'text');
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'        => 'Faithlead\RestBundle\Document\User',
            'csrf_protection'   => false,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'fbFanPageUrl';
    }
}