<?php

namespace NwtBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class StudentAdmin extends AbstractAdmin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('name')
            ->add('schoolLevel')
            ->add('programs')
            ->add('idDocument', null, array('template' => 'NwtBundle:Default:id_document.html.twig'))
            ->add('resume', null, array('template' => 'NwtBundle:Default:resume.html.twig'))
            ->add('diploma', null, array('template' => 'NwtBundle:Default:diploma.html.twig'))
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
            ->with('General Information', array('class' => 'col-md-5'))
            ->add('name')
            ->add('schoolLevel')
            ->add('idDocument')
            ->add('resume')
            ->add('diploma')
            ->add('programs', 'entity', array('class' => 'NwtBundle:Program'))
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
            ->add('name')
            ->add('schoolLevel')
            ->add('idDocument')
            ->add('resume')
            ->add('diploma')
        ;
    }
}
