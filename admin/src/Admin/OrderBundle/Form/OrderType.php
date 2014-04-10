<?php

namespace Admin\OrderBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OrderType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateOrder', 'datetime', array(
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'attr' => array(
                    'disabled' => true,
                )
            ))
            ->add('hall', 'entity', array(
                'class' => 'Admin\OrderBundle\Entity\RestaurantHalls',
                'property' => 'name',
                'attr' => array(
                    'disabled' => true,
                )
            ))
            ->add('seatsQuantity', 'text', array(
                'attr' => array(
                    'disabled' => true,
                )
            ))
            ->add('contactName', 'text', array(
                'attr' => array(
                    'disabled' => true,
                )
            ))
            ->add('contactPhone', 'text', array(
                'attr' => array(
                    'disabled' => true,
                )
            ))
            ->add('contactEmail', 'text', array(
                'attr' => array(
                    'disabled' => true,
                )
            ));
            if (isset($options['data']) && $options['data']->getOrdersStatus()->getId() == 4) {
                $builder
                    ->add('ordersStatus', 'entity', array(
                        'class' => 'Admin\OrderBundle\Entity\OrdersStatus',
                        'property' => 'name',
                        'attr' => array(
                            'disabled' => true,
                        )
                    ));
            } else {
                $builder
                    ->add('ordersStatus');
            }
            $builder
                ->add('orderDescription', 'text', array(
                'attr' => array(
                    'disabled' => true,
                )
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Admin\OrderBundle\Entity\Orders'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'admin_mainbundle_admin';
    }
}
