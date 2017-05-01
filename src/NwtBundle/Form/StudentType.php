<?php

namespace NwtBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Jb\Bundle\FileUploaderBundle\Form\Type\FileAjaxType;
use Jb\Bundle\FileUploaderBundle\Form\Type\ImageAjaxType;

class StudentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array('attr' => array('class' => 'form-control',
                                                                'placeholder' => 'Full Name Ex: Saint-Cyr MAPOUKA')))
            ->add('phone', null, array('attr' => array('class' => 'form-control',
                'placeholder' => 'Phone Number Ex: 00237 652 458 032')))
            ->add('address', null, array('attr' => array('class' => 'form-control',
                'placeholder' => 'Address Ex: Camp SIC Yaounde B.P 3030 Cameroon.')))
            ->add('email', null, array('attr' => array('class' => 'form-control',
                'placeholder' => 'Email Ex: mapoukacyr@yahoo.fr')))

            ->add('programs', null, array('class' => 'NwtBundle:Program',
                                                     'multiple' => false,
                                                    'empty_value' => '-- Choose a program --',
                                                    'attr' => array('class' => 'form-control',
                                                                    )))

            ->add('schoolLevel', null, array('attr' => array('class' => 'form-control',
                'placeholder' => 'School level Ex: Bsc. in Computer Science')))
            ->add('idDocument', FileAjaxType::class, array('endpoint' => 'gallery', 'required' => true))
            ->add('diploma', FileAjaxType::class, array('endpoint' => 'gallery'))
            ->add('resume', FileAjaxType::class, array('endpoint' => 'gallery',
                                                                'attr' => array('class' => 'form-control')));



    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'NwtBundle\Entity\Student'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'nwtbundle_student';
    }


}
