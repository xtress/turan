<?php

namespace Admin\OrderBundle\Form;

use Helpers\ServiceBridge;
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
        $bridge = ServiceBridge::getInstance();
        $translator = $bridge->get('translator');
        $builder
            ->add('dateOrder', 'datetime', array(
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd H:mm',
                'label' => $translator->trans('dateOrder'),
                'attr' => array(
                    'disabled' => true,
                )
            ));
        if (isset($options['data']) && $options['data']->getOrdersStatus()->getId() != 4) {
            $builder
                ->add('hall', 'entity', array(
                    'class' => 'Admin\OrderBundle\Entity\RestaurantHalls',
                    'property' => 'name',
                    'label' => $translator->trans('hall'),
                ))
                ->add('seatsQuantity', 'text', array(
                    'label' => $translator->trans('seatsQuantity'),
                ));
        } else {
            $builder
                ->add('hall', 'entity', array(
                    'class' => 'Admin\OrderBundle\Entity\RestaurantHalls',
                    'property' => 'name',
                    'label' => $translator->trans('hall'),
                    'attr' => array(
                        'disabled' => true,
                    )
                ))
                ->add('seatsQuantity', 'text', array(
                    'label' => $translator->trans('seatsQuantity'),
                    'attr' => array(
                        'disabled' => true,
                    )
                ));
        }
        $builder
            ->add('contactName', 'text', array(
                'label' => $translator->trans('contactName'),
                'attr' => array(
                    'disabled' => true,
                )
            ))
            ->add('contactPhone', 'text', array(
                'label' => $translator->trans('contactPhone'),
                'attr' => array(
                    'disabled' => true,
                )
            ))
            ->add('contactEmail', 'text', array(
                'label' => $translator->trans('contactEmail'),
                'attr' => array(
                    'disabled' => true,
                )
            ));
        if (isset($options['data']) && $options['data']->getOrdersStatus()->getId() == 4) {
            $builder
                ->add('ordersStatus', 'entity', array(
                    'class' => 'Admin\OrderBundle\Entity\OrdersStatus',
                    'property' => 'alias',
                    'label' => $translator->trans('ordersStatus'),
                ));
        } else {
            $builder
                ->add('ordersStatus', null, array(
                    'label' => $translator->trans('ordersStatus'),
                ));
        }
        $builder
            ->add('orderDescription', 'textarea', array(
            'label' => $translator->trans('orderDescription'),
            'attr' => array(
                'disabled' => true,
            )
        ));
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
