<?php

namespace NwtBundle\Admin;

use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\Filter\ChoiceType;
use Sonata\AdminBundle\Show\ShowMapper;

class PostAdmin extends AbstractAdmin
{


    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('type')
            ->add('_action', null, array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                )
            ))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
        ->with('information', array('class' => 'col-md-8'))
            ->add('type', 'choice', array(
                'choices'  => array(
                    'Services' => 'services',
                    'FAQ' => 'faq',
                    'Order Product / Service' => 'order_product_service',
                    'IT Programs' => 'it_programs',
                    'Other Programs' => 'other_programs',
                    'order' => 'order',
                    'Software Development' => 'sofware_development',
                    'shop' => 'shop',
                    'website_design' => 'website_design',
                    'secretariat' => 'secretariat',
                    'maintenance' => 'maintenance',
                    'it_networking' => 'it_networking',
                    'remote_monitoring' => 'remote_monitoring',
                    'electricity_engineering' => 'electricity_engineering',
                    'serigraphy' => 'serigraphy',
                    'infography' => 'infography',
                    'training' => 'training',


                ),
                // *this line is important*
                'choices_as_values' => true,
            ))
            ->add('file', 'file', array('required' => false))
            ->add('content', CKEditorType::class, array())
            ->end()
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('type')
            ->add('content')
        ;
    }

    public function prePersist($object)
    {
        $this->manageFileUpload($object);
    }


    public function preUpdate($object)
    {
        $this->manageFileUpload($object);
        $this->manageFileUpload($object);

    }

    private function manageFileUpload($image)
    {
        if ($image->getFile()) {
            $image->refreshUpdated();
        }
    }
}
