<?php

namespace Admin\GalleryBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GalleryType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('galleryType', 'entity', array(
                'class' => 'Admin\GalleryBundle\Entity\GalleryType',
                'property' => 'alias',
            ))
            ->add('locale', 'entity', array(
                'class' => 'Admin\GalleryBundle\Entity\Locale',
                'property' => 'locale',
            ))
            ->add('isPublished', 'checkbox', array(
                'attr' => array(
                    'disabled' => true,
                ),
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Admin\GalleryBundle\Entity\Gallery'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'admin_gallerybundle_gallery_create';
    }
}
