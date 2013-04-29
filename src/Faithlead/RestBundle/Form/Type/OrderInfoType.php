<?php

namespace Faithlead\RestBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Faithlead\RestBundle\Form\DataTransformer\UserToIdTransformer;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OrderInfoType extends AbstractType{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('customerName', 'text');
        $builder->add('customerEmail', 'text');
        $builder->add('orderId', 'text');

        $builder->add('user');
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Faithlead\RestBundle\Document\OrderInfo',
            'csrf_protection'   => false,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'orderInfo';
    }
}