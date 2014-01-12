<?php

namespace Admin\PageBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class StaticPageType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pageName', 'text', array(
                'required' => true,
                'error_bubbling' => true,
                ))
            ->add('pageBody', 'textarea')
            ->add('pageSeo', 'text')
            ->add('isPublished', 'checkbox')
            ->add('locale', 'entity', array(
                'required'  => true,
                'class'     => 'Admin\PageBundle\Entity\Locale',
                'empty_value' => '',
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Admin\PageBundle\Entity\StaticPage'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'admin_pagebundle_staticpage';
    }
}
