<?php

namespace NwtBundle\Admin;

use EWZ\Bundle\SearchBundle\Lucene\LuceneSearch;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Zend\Search\Lucene\Document;

class ProductAdmin extends AbstractAdmin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('name')
            ->add('unitPrice')
            ->add('image')
            ->add('description')
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
            ->add('unitPrice')
            ->add('image', null, array('template' => 'NwtBundle:Default:product_list.html.twig'))
            ->add('description')
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
            ->with('General Information', array('class' => 'col-md-8'))
            ->add('name')
            ->add('unitPrice')
            ->add('file', 'file', array('required' => false))
            ->add('description', 'textarea', array('attr' => array('class' => 'ckeditor')))
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
            ->add('unitPrice')
            ->add('image')
            ->add('description')
        ;
    }

    public function prePersist($object)
    {
        $this->manageFileUpload($object);
        //Manage Lucen search engine
        $searchManager = $this->getConfigurationPool()->getContainer()->get('ewz_search.lucene.manager');
        //Get the product index
        $productIndex = $searchManager->getIndex('indexProduct');
        $document = new Document();
        $document->addField(Document\Field::keyword('key', $object->getId()));
        $document->addField(Document\Field::text('name', $object->getName()));
        $document->addField(Document\Field::text('slug', $object->getSlug()));
        $document->addField(Document\Field::text('image', $object->getImage()));
        $document->addField(Document\Field::text('description', $object->getDescription()));
        $document->addField(Document\Field::text('unitPrice', $object->getUnitPrice()));
        //Add the new created entity as document
        $productIndex->addDocument($document);
        $productIndex->updateIndex();

    }

    public function preRemove($object){
        //Manage Lucen search engine
        $searchManager = $this->getConfigurationPool()->getContainer()->get('ewz_search.lucene.manager');
        //Get the product index
        $productIndex = $searchManager->getIndex('indexProduct');

        foreach ($productIndex->find('pk:'.$object->getId()) as $hit) {
            echo $hit->id;
        }

        exit;

        foreach ($productIndex->find('pk:'.$object->getId()) as $hit) {
            $productIndex->delete($hit->id);
        }

        $productIndex->commit();
    }

    public function preUpdate($object)
    {
        $this->manageFileUpload($object);
        $this->manageFileUpload($object);
        //Manage Lucen search engine
        $searchManager = $this->getConfigurationPool()->getContainer()->get('ewz_search.lucene.manager');
        //Get the product index
        $productIndex = $searchManager->getIndex('indexProduct');
        $document = new Document();
        $document->addField(Document\Field::keyword('key', $object->getId()));
        $document->addField(Document\Field::text('name', $object->getName()));
        $document->addField(Document\Field::text('slug', $object->getSlug()));
        $document->addField(Document\Field::text('image', $object->getImage()));
        $document->addField(Document\Field::text('description', $object->getDescription()));
        $document->addField(Document\Field::text('unitPrice', $object->getUnitPrice()));
        //Add the new created entity as document
        $productIndex->addDocument($document);
        $productIndex->updateIndex();
    }

    private function manageFileUpload($image)
    {
        if ($image->getFile()) {
            $image->refreshUpdated();
        }
    }
}
