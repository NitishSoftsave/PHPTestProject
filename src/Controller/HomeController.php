<?php

namespace App\Controller;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index(Request $request): Response
    {

        $form = $this->createFormBuilder(['attr' => ['class' => 'myClass']])
            ->add('date',TextType::class,array('attr' => array('class' => 'input'),'label_attr' => array('class' => 'label')))
            ->add('timezone',TextType::class,array('attr' => array('class' => 'input'),'label_attr' => array('class' => 'label')))
            ->add('save',SubmitType::class,array('label' => 'Submit' , 'attr' => array('class' => 'button'),))
            ->getForm();
            $form->handleRequest($request);
        if($form->isSubmitted()){
            $formData =  $form->getData();
            $data['timezone'] =  $formData['timezone'];
            $current   = timezone_open($data['timezone']);
            $utcTime  = new \DateTime($formData['date'], new \DateTimeZone('UTC'));
            $data['offset'] =  timezone_offset_get( $current, $utcTime)/60;
            $data['givenMonth'] = date_format(date_create($formData['date']),"F");
            $data['currentMonthDays'] = cal_days_in_month(CAL_GREGORIAN,date_format(date_create($formData['date']),"m"),date_format(date_create($formData['date']),"Y"));
            $data['febDays'] = cal_days_in_month(CAL_GREGORIAN,2,date_format(date_create($formData['date']),"Y"));

            return $this->render('home/success.html.twig', [
                'controller_name' => 'HomeController','data'=>$data
            ]);

        }
        else{
            return $this->render('home/index.html.twig', [
                'controller_name' => 'HomeController','form_themes' => [
                    'bootstrap_4_layout.html.twig',
                ],'form'=>$form->createView()
            ]);
        }

    }
}
