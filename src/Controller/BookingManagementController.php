<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;

/**
 * BookingManagement Controller
 *
 * Class BookingManagementController
 * @Route("/booking/management")
 * @package FrontBundle\Controller
 */
class BookingManagementController extends Controller
{
    /**
     * forms login booking
     * @Route("/login", name="booking_login")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form_mail = $this->formBuilderSearchByReferenceAndMail();
        $form_name = $this->formBuilderSearchByReferenceAndName();
        $session = $request->getSession();
        if($session->has('booking')) {$session->remove('booking');}

        // Form builder search by reference and email ==================================================================
        $form_mail->handleRequest($request);
        if ($form_mail->isSubmitted() && $form_mail->isValid()) {

            $data = $request->request->all();
            $reference = isset($data['form']['reference_one']) ? $data['form']['reference_one'] : null;
            $email = isset($data['form']['email']) ? $data['form']['email'] : null;
            $token = isset($data['form']['_token']) ? $data['form']['_token'] : null;
            // get customer and reference booking
            $customer = $em->getRepository('App:Customer')->findOneBy(array('email' => $email));
            $booking= $em->getRepository('App:Booking')->findOneBy(array('reference' => $reference, 'customer' =>$customer));
            // Check customer and reference booking
            if ($booking != null && $customer != null){
                // Create session booking
                if(!$session->has('booking')) {$session->set('booking', $booking);}

                $payload=array();
                $payload['status']='ok';
                $payload['page']='success_mail';
                $payload['reference'] = $booking->getReference();
                return new Response(json_encode($payload));

            } else {

                $payload=array();
                $payload['status']='ok';
                $payload['page']='error_mail';
                return new Response(json_encode($payload));

            }

        }

        // Form builder search by reference and name ===================================================================
        $form_name->handleRequest($request);
        if ($form_name->isSubmitted() && $form_name->isValid()) {

            $data = $request->request->all();
            $reference = isset($data['form']['reference_tow']) ? $data['form']['reference_tow'] : null;
            $firstname = isset($data['form']['firstname']) ? $data['form']['firstname'] : null;
            $lastname = isset($data['form']['lastname']) ? $data['form']['lastname'] : null;
            $token = isset($data['form']['_token']) ? $data['form']['_token'] : null;
            // Get customer and reference booking
            $customer = $em->getRepository('App:Customer')->findOneBy(array('firstname' => $firstname, 'lastname' => $lastname));
            $booking= $em->getRepository('App:Booking')->findOneBy(array('reference' => $reference));
            // Check customer and reference booking
            if ($booking != null && $customer != null){
                // Create session booking
                if(!$session->has('booking')) {$session->set('booking', $booking);}

                $payload=array();
                $payload['status']='ok';
                $payload['page']='success_name';
                $payload['reference'] = $booking->getReference();
                return new Response(json_encode($payload));

            } else {
                $payload=array();
                $payload['status']='ok';
                $payload['page']='error_name';
                return new Response(json_encode($payload));

            }

        }

        return $this->render('Booking_management/login_booking.html.twig', array(
            'form_mail' => $form_mail->createView(),
            'form_name' => $form_name->createView()
        ));
    }

    /**
     * Display customer booking details
     * @Route("/{reference}", name="booking")
     * @Method("GET")
     * @param Request $request
     * @return Response
     */
    public function bookingAction(Request $request, $reference)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        if($session->has('booking')) {
            if ($session->get('booking')->getReference() == $reference){
                $booking= $em->getRepository('App:Booking')->findOneBy(array('reference' => $reference));
                return $this->render('Booking_management/booking.html.twig', array('booking' => $booking));

            } else {
                return $this->redirectToRoute('booking_login');
            }

        } else {
           return $this->redirectToRoute('booking_login');

        }
    }

    /**
     * Export receipt to pdf
     * @Route("/{id}/receipt", name="receipt_match_pdf")
     * @Method("GET")
     * @param Booking $booking
     * @return Response
     */
    public function receiptAction(Booking $booking)
    {
        $html = $this->renderView('Booking_management/receipt.html.twig', array(
            'booking' => $booking
        ));

        $filename = sprintf('REÇU-%s.pdf', $booking->getId().'_'.$booking->getReference());

        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            200,
            [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => sprintf('attachment; filename="%s"', $filename),
            ]
        );

//        return $this->render('Booking_management/receipt.html.twig', array(
//            'booking' => $booking
//        ));

    }

    /**
     * Export a pdf bill
     * @Route("/{id}/bill", name="bill_match_pdf")
     * @Method("GET")
     * @param Booking $booking
     * @return PdfResponse
     */
    public function pdfAction(Booking $booking)
    {
        $center = $booking->getBill()->getCenter();
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findOneBy(array('center' => $center));
        $number = str_pad($booking->getId(), 10, "0", STR_PAD_LEFT);
        $html =  $this->renderView('Booking_management/bill.html.twig', array(
            'booking' => $booking,
            'user' => $user
        ));

        return new PdfResponse(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            'FACTURE_'.$number.'.pdf'
        );

//        return $this->render('Booking_management/bill.html.twig', array(
//            'booking' => $booking,
//            'user' => $user
//        ));

    }

    // =================================================================================================================
    // === Form builder login ==========================================================================================
    // =================================================================================================================

    /**
     * form builder booking search by reference and mail
     * @return mixed
     */
    public function formBuilderSearchByReferenceAndMail()
    {
        $form = $this->createFormBuilder()
            ->add('reference_one', TextType::class, array('attr' => array('maxlength' => 8)))
            ->add('email', EmailType::class)
            ->getForm();

        return $form;

    }

    /**
     * form builder booking search by reference and name
     * @return mixed
     */
    public function formBuilderSearchByReferenceAndName()
    {
        $form = $this->createFormBuilder()
            ->add('reference_tow', TextType::class, array('attr' => array('maxlength' => 8)))
            ->add('firstname', TextType::class)
            ->add('lastname', TextType::class)
            ->getForm();

        return $form;

    }

}
