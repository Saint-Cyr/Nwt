<?php

namespace UserBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class UserAdmin extends AbstractAdmin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('username')

        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('username')
            ->add('email')
            ->add('enabled')
            ->add('lastLogin')
            ->add('roles')
            ->add('firstName')
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
        $edition = (preg_match('/_edit$/', $this->getRequest()->get('_route'))) ? false : true;
        $typeContext = array();

        $typeContext['administrator'] = 'administrator';

        $formMapper
            ->with('Connexion Information', array('class' => 'col-md-4'))
            ->add('username')
            ->add('email')
            ->add('plainPassword', 'repeated', array(
                'type' => 'password',
                'invalid_message' => 'The password fields must match.',
                'required' => $edition,
                'first_options'  => array('label' => 'Password'),
                'second_options' => array('label' => 'Repeat Password'),
            ))

            ->end()

            ->with('Personal information', array('class' => 'col-md-4'))

            ->add('firstName', null, array('label' => 'Name (length must be more than 5)'))
        ;

        $formMapper->end()
            ->with('Security', array('class' => 'col-md-4'))
            ->add('type', 'choice', array('choices' => $typeContext,
                'expanded' => true))
            ->end();

    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('username')
            ->add('email')
            ->add('enabled')
            ->add('lastLogin')
            ->add('roles')
            ->add('firstName')
        ;
    }

    public function preValidate($object) {
        parent::preValidate($object);
        $object->setEnabled(true);
        switch ($object->getType()){
            case 'super-admin':
                $object->setRoles(array('ROLE_SUPER_ADMIN'));
                break;
            case 'administrator':

                $object->setRoles(array('ROLE_ADMIN'));
                break;

        }
    }
}
