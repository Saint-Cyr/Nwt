<?php

namespace NwtBundle\Controller;

use NwtBundle\Entity\Student;
use NwtBundle\Form\StudentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Intl\Intl;
use Symfony\Component\Intl\Locale\Locale;
use Zend\Search\Lucene\Document\HTML;
use Symfony\Component\Form\Extension\Core\Type\LocaleType;
//use Symfony\Component\HttpFoundation\Request;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        if($request->isXmlHttpRequest()){
            //$request = $this->container->get('request_stack')->getCurrentRequest();
            //$request = $this->get('request');
            //$keyWord = $request->query->get('keyWord');
            //$keyWord = $request->request->get('keyWord');
            //$keyWord = $request->get('keyWord');
            //$keyWord = $request->request->getInt('keyWord');
            //$keyWord = $request->query->all();
            //return new Response(var_dump($keyWord));
        }

        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('NwtBundle:Product')->createQueryBuilder('p');
        $query = $queryBuilder->getQuery();
        $paginator = $this->get('knp_paginator');
        $products = $paginator->paginate(
                                        $query,
                                        $request->query->getInt('page', 1),
                                        $request->query->getInt('limit', 9));

        //Get the slide and products from the Data base
        $slides = $em->getRepository('NwtBundle:Slide')->findAll();

        //Make sure it is not null.
        if(!$slides){
            throw $this->createNotFoundException('no slide found in the DB.');
        }


        return $this->render('NwtBundle:Default:index.html.twig', array('slides' => $slides,
                                                                             'products' => $products));
    }

    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $request->query->get('query');

        if(!$query){
            return $this->redirect($this->generateUrl('nwt_homepage'));
        }

        $searchManager = $this->get('ewz_search.lucene.manager')->getIndex('indexProduct');

        $brutProducts = $searchManager->find($query);
        //Hiligh the result


        $paginator = $this->get('knp_paginator');
        $products = $paginator->paginate(
            $brutProducts,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 9));


        return $this->render('NwtBundle:Default:search.html.twig', array('products' => $products));
    }

    public function productAction($productSlug)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('NwtBundle:Product')->findOneBy(array('slug' => $productSlug));

        if(!$product){
            throw $this->createNotFoundException('Product : '.$productSlug.' not found in the DB');
        }

        return $this->render('NwtBundle:Default:product.html.twig', array('product' => $product));
    }

    public function registrationAction(Request $request)
    {
        $student = new Student();
        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($student);
            $em->flush();
            $this->addFlash('success', 'Thank you ! You have registered successfully.');
            unset($form);
            unset($student);

            $form = $this->createForm(StudentType::class, new Student());
        }

        return $this->render('NwtBundle:Default:registration.html.twig', array('form' => $form->createView()));
    }

    public function orderAction()
    {
        $em = $this->getDoctrine()->getManager();
        $object = $em->getRepository('NwtBundle:Post')->findOneBy(array('type' => 'order_product_service'));

        if(!$object){
            throw $this->createNotFoundException('No Object found.');
        }

        return $this->render('NwtBundle:Default:single.html.twig',
            array('object' => $object));
    }

    public function servicesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $object = $em->getRepository('NwtBundle:Post')->findOneBy(array('type' => 'services'));

        if(!$object){
            throw $this->createNotFoundException('No Object found.');
        }

        return $this->render('NwtBundle:Default:single.html.twig',
            array('object' => $object));
    }

    public function itProgramsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $object = $em->getRepository('NwtBundle:Post')->findOneBy(array('type' => 'it_programs'));

        if(!$object){
            throw $this->createNotFoundException('No Object found.');
        }

        return $this->render('NwtBundle:Default:single.html.twig',
            array('object' => $object));
    }

    public function otherProgramsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $object = $em->getRepository('NwtBundle:Post')->findOneBy(array('type' => 'other_programs'));

        if(!$object){
            throw $this->createNotFoundException('No Object found.');
        }

        return $this->render('NwtBundle:Default:single.html.twig',
            array('object' => $object));
    }

    public function contactAction(Request $request)
    {
        $data = array();
        $form = $this->createFormBuilder($data)
                     ->add('name', null, array('attr' => array('placeholder' => 'Ex: Saint-Cyr MAPOUKA',
                                                                          'class' => 'form-control')))
                    ->add('address', null, array('attr' => array('placeholder' => 'Ex: Yaounde, Camp SIC Nlongkak BP.___ CAMEROON',
                        'class' => 'form-control')))
                     ->add('email', 'email', array('attr' => array('placeholder' => 'Ex: mapoukacyr@yahoo.fr',
                                                                            'class' => 'form-control')))
                    ->add('phone', null, array('attr' => array('placeholder' => 'Ex: 00 237 652 458 032',
                        'class' => 'form-control')))
                    ->add('country', 'country', array('attr' => array('placeholder' => 'Ex: Central Africa Republic',
                        'class' => 'form-control'),
                        'empty_data' => Locale::getDefault()))
                    ->add('content', CKEditorType::class, array())
                    ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $origineEmail = $this->container->getParameter('mailer_user');
            $destinationEmail = $this->container->getParameter('mailer_destination');

            $countryName = Intl::getRegionBundle()->getCountryName($form->get('country')->getData());
            $name = $form->get('name')->getData();
            $address = $form->get('address')->getData();
            $email = $form->get('email')->getData();
            $phone = $form->get('phone')->getData();
            $content = $form->get('content')->getData();

            $message = \Swift_Message::newInstance()
                ->setSubject('nwt.cm contact message from: '.$countryName)
                ->setFrom(array($origineEmail => 'New World Telecom'))
                ->setTo($destinationEmail)
                ->setBody($this->renderView('NwtBundle:Default:email.html.twig', array('name' => $name,
                    'address' => $address,
                    'email' => $email,
                    'phone' => $phone,
                    'content' => $content)),
                         'text/html');

            $this->get('mailer')->send($message);

            $this->addFlash('success', 'Thanks for contacting us, we will get back to you shortly.');
            return $this->redirectToRoute('nwt_homepage');

        }

        return $this->render('NwtBundle:Default:contact.html.twig', array('form' => $form->createView()));
    }

    public function faqAction()
    {
        $em = $this->getDoctrine()->getManager();
        $object = $em->getRepository('NwtBundle:Post')->findOneBy(array('type' => 'faq'));

        if(!$object){
            throw $this->createNotFoundException('No Object found.');
        }

        return $this->render('NwtBundle:Default:single.html.twig',
            array('object' => $object));
    }

}
