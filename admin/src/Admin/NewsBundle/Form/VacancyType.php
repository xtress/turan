<?php

namespace Admin\NewsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Doctrine\ORM\EntityRepository;

class VacancyType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $session = \Helpers\ServiceBridge::getInstance()->get('session');
        $locale = $session->get('_locale');
        
        $builder
//            ->add('newsCategories', 'entity', array(
//                'class' => 'Admin\NewsBundle\Entity\NewsCategories',
//                'property' => 'name',
//                'query_builder' => function(EntityRepository $er) use ($locale) {
//                    return $er->createQueryBuilder('c')
//                        ->orderBy('c.name', 'ASC')
//                        ->where("c.locale = '$locale'");
//                }
//            ))
            ->add('title', 'text', array(
                'required' => true,
                'error_bubbling' => true,
                ))
            ->add('body', 'textarea')
            ->add('isPublished', 'checkbox', array(
                'required' => false,
                'error_bubbling' => true,
            ))
            ->add('locale', 'entity', array(
                'required'  => true,
                'class'     => 'Admin\NewsBundle\Entity\Locale',
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
            'data_class' => 'Admin\NewsBundle\Entity\News'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'admin_newsbundle_news';
    }
}
