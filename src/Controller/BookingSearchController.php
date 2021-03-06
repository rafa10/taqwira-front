<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\Center;
use App\Entity\Event;
use App\Entity\Field;
use App\Entity\Notification;
use App\Form\BookingType;
use App\Service\Mailer;
use Nzo\UrlEncryptorBundle\DependencyInjection\NzoUrlEncryptorExtension;
use Nzo\UrlEncryptorBundle\NzoUrlEncryptorBundle;
use Nzo\UrlEncryptorBundle\UrlEncryptor\UrlEncryptor;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Nzo\UrlEncryptorBundle\Annotations\ParamDecryptor;
use Nzo\UrlEncryptorBundle\Annotations\ParamEncryptor;


/**
 * Booking Search Controller
 *
 * Class BookingSearchController
 * @Route("/booking")
 * @package App\Controller
 */
class BookingSearchController extends Controller
{

    /**
     * form booking search .
     * @Route("", name="booking_page")
     */
    public function searchFirstMatchAction()
    {
        $form = $this->formBuilderSearch();

        return $this->render('Booking_search/search.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * Search match date .
     * @Route("/match-search-result", name="booking_search")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return Response
     */
    public function searchMatchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $days = $em->getRepository('App:Day')->findAll();

        $data = $request->request->get('form');
        $centerName = isset($data['center']) ? $data['center'] : null;
        $date = isset($data['date']) ? $data['date'] : date("Y-m-d");
        $date_search = new \DateTime($date);
        $now = date('Y-m-d');

        $center = $em->getRepository('App:Center')->findOneBy(array('name' => $centerName));
        $fields = $em->getRepository('App:Field')->findBy(array('center' => $center));
        $user = $em->getRepository('App:User')->findOneBy(array('center' => $center));
        $events = $em->getRepository(Event::class)->findEvent($center, $now);


        $bookingsTab = [];
        $bookings = [];
        foreach ( $fields as $field ){
            $bookingsTab[] = $em->getRepository('App:Booking')->findBy(array('field' => $field));
        }
        foreach ( $bookingsTab as $index ){
            foreach ($index as $item){
                $bookings[] = $item;
            }
        }

        $payload=array();
        $payload['status']='ok';
        $payload['page']='search';
        $payload['html'] = $this->renderView('Booking_search/content_result.html.twig', array(
            'fields' => $fields,
            'date_search' => $date_search,
            'days' => $days,
            'bookings' => $bookings,
            'user' => $user,
            'center' => $center,
            'events' => $events
        ));

        return new Response(json_encode($payload));

    }

    /**
     * Search match date .
     * @Route("/match-search-result-mobile", name="booking_search_mobile")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return Response
     */
    public function searchMatchMobileAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $days = $em->getRepository('App:Day')->findAll();

        $data = $request->request->get('form');
        $centerName = isset($data['center']) ? $data['center'] : null;
        $date = isset($data['date']) ? $data['date'] : date("Y-m-d");
        $date_search = new \DateTime($date);
        $now = date('Y-m-d');

        $center = $em->getRepository('App:Center')->findOneBy(array('name' => $centerName));
        $fields = $em->getRepository('App:Field')->findBy(array('center' => $center));
        $user = $em->getRepository('App:User')->findOneBy(array('center' => $center));
        $events = $em->getRepository(Event::class)->findEvent($center, $now);


        $bookingsTab = [];
        $bookings = [];
        foreach ( $fields as $field ){
            $bookingsTab[] = $em->getRepository('App:Booking')->findBy(array('field' => $field));
        }
        foreach ( $bookingsTab as $index ){
            foreach ($index as $item){
                $bookings[] = $item;
            }
        }

        return $this->render('Booking_search/content_result_mobile.html.twig', array(
            'fields' => $fields,
            'date_search' => $date_search,
            'days' => $days,
            'bookings' => $bookings,
            'user' => $user,
            'center' => $center,
            'events' => $events
        ));

    }

    /**
     * Creates a new booking match entity.
     * @Route("/match-details/{field}/{date}/{timeS}/{timeE}/{price}/", name="booking_details")
     * @ParamDecryptor(params={"field", "date", "timeS", "timeE", "price"})
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return Response
     */
    public function bookingMatchAction(Request $request, $field, $date, $timeS, $timeE, $price, UrlEncryptor $nzo)
    {
        $em = $this->getDoctrine()->getManager();
        $type = $em->getRepository('App:BookingType')->findOneBy(array('description' => 'Match'));
        $reference  = $this->refHash(6);
        $fiel = $em->getRepository('App:Field')->find($field);
        $dateMatch = new \DateTime($date);
        $timeStart = new \DateTime($timeS);
        $timeEnd = new \DateTime($timeE);
        $check_booking_match = $em->getRepository('App:Booking')->findBy(array('bookingType' => $type, 'date' => $dateMatch, 'timeStart' => $timeStart));
        $center = $fiel->getCenter();

        $booking = new Booking();
        $booking->setField($fiel);
        $booking->setDate($dateMatch);
        $booking->setTimeStart($timeStart);
        $booking->setTimeEnd($timeEnd);
        $booking->setPrice($price);

        $form = $this->createForm(BookingType::class, $booking, array(
            'action' => $this->generateUrl('booking_details', array(
                'field'=>$nzo->encrypt($field),
                'date' =>$nzo->encrypt($date),
                'timeS'=>$nzo->encrypt($timeS),
                'timeE'=>$nzo->encrypt($timeE),
                'price'=>$nzo->encrypt($price)
            ))
        ));

        // vérification si la réservation est toujour disponible ou non
        if (empty($check_booking_match) ){

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

                $data = $request->request->all();
                $email = isset($data['booking']['customer']['email']) ? $data['booking']['customer']['email'] : null;
                $customer = $em->getRepository('App:Customer')->findOneBy(array('email' => $email, 'center' => $center));

                // Si le client il existe en lui associer la nouvelle réservation
                if(!empty($customer)){
                    $booking->setCustomer($customer);
                }

                $booking->setReference($reference);
                $booking->setBookingType($type);

                $em->persist($booking);
                $em->flush();

                // Send mail confirmation booking to customer
                $this->container->get('app.mailer')->sendBookingMessage($booking);

                // Create notification for dashboard admin center
                $this->container->get('app.notification')->newNotification($center, $subject=Notification::BOOKING, $link=Notification::BOOKING_LINK);


                return $this->redirectToRoute('booking_confirmation', array(
                    'reference' => $nzo->encrypt($reference),
                    'field' => $nzo->encrypt($field)
                ));

            }

        } else {
            $request->getSession()
                ->getFlashBag()
                ->add('ERREUR', 'La réservation non disponible!');

            return $this->redirectToRoute('booking_page');
        }

        return $this->render('Booking_search/booking_details.html.twig', array(
            'form' => $form->createView(),
            'name' => $fiel->getName(),
            'center' => $center
        ));

    }

    /**
     * Feedback booking confirmation .
     * @Route("/booking-confirmation/{reference}/{field}", name="booking_confirmation")
     * @ParamDecryptor(params={"reference", "field"})
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param $reference
     * @param Field $field
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function bookingConfirmationAction(Request $request, $reference,  $field)
    {
        $em = $this->getDoctrine()->getManager();
        $field = $em->getRepository(Field::class)->find($field);
        $booking = $em->getRepository(Booking::class)->findOneBy(array('reference' => $reference, 'field'=> $field));

        if ($booking != null){
            $request->getSession()
                ->getFlashBag()
                ->add('SUCCÈS', 'La réservation est confirmé!');

            return $this->render('Booking_search/booking_confirmation.html.twig', array(
                'booking' => $booking,
            ));

        } else {
            $request->getSession()
                ->getFlashBag()
                ->add('ERREUR', 'La réservation non disponible!');

            return $this->redirectToRoute('booking_page');

        }

    }

    /** ============================================================================================================== */
    /** == other function ============================================================================================ */
    /** ============================================================================================================== */

    /**
     * @Route("/form/search", name="booking_form_search")
     */
    public function getFormSearchAction()
    {

        $regions = [];
        $citys = [];
        $centers = [];
        $em = $this->getDoctrine()->getManager();

        $regionAll = $em->getRepository('App:Region')->findAll();
        foreach ($regionAll as $region){
            $regions[$region->getName()] = null;
        }
        $cityAll = $em->getRepository('App:City')->findAll();
        foreach ($cityAll as $city){
            $citys[$city->getName()] = null;
        }

        $centerAll = $em->getRepository('App:Center')->findBy(array('share_program' => true));
        foreach ($centerAll as $center){
            $centers[$center->getName()] = null;
        }

        $regionCity = array_merge($regions, $citys);

        $payload=array();
        $payload['status']='ok';
        $payload['page']='show';
        $payload['region_city'] = $regionCity;
        $payload['center'] = $centers;

        return new Response(json_encode($payload));
    }

    /**
     * form builder search
     * @return mixed
     */
    public function formBuilderSearch()
    {
        $form = $this->createFormBuilder()
            ->add('ville', SearchType::class, array(
                'required' => false,
                'attr' => array(
                    'class' => 'autocomplete-region',
                )
            ))
            ->add('center', SearchType::class, array(
                'attr' => array(
                    'class' => 'autocomplete-center',
                )
            ))
            ->add('date', DateType::class, array(
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'required' => true,
                'model_timezone' => 'Europe/Paris',
                'attr' => array(
                    'class' => 'datepicker',
                    'data-provide' => 'datepicker',
                    'data-date-format' => 'dd-mm-yyyy'
                )
            ))
            ->getForm();

        return $form;

    }

    /**
     * Search center by region or city  .
     * @Route("/city_or_region/{city_region}", name="center_by_city_region")
     * @Method("GET")
     * @return Response
     */
    public function searchCenterByCityOrRegionAction($city_region)
    {
        $em = $this->getDoctrine()->getManager();

        if (!empty($city_region)){
            $region = $em->getRepository('App:Region')->findByName(array('city' => $city_region));
            $city = $em->getRepository('App:City')->findByName(array('city' => $city_region));
            if (!empty($region)){
                $centers = $em->getRepository('App:Center')->findBy(array('region' => $region, 'share_program' => true ));
            } else {
                $centers = $em->getRepository('App:Center')->findBy(array('city' => $city, 'share_program' => true ));
            }
        }

        $data_center = [];
        foreach ( $centers as $center ){
            $data_center[] = $center->getName();
        }

        $payload=array();
        $payload['status']='ok';
        $payload['page']='search';
        $payload['html'] = $this->renderView('Booking_search/center_form.html.twig', array(
            'centers' => $data_center
        ));
        return new Response(json_encode($payload));

    }

    /**
     * @param $qtd
     * @return null|string
     */
    public function refHash($qtd)
    {
        //Under the string $Caracteres you write all the characters you want to be used to randomly generate the code.
        $Caracteres = 'ABCDEFGHIJKLMOPQRSTUVXWYZ0123456789';
        $QuantidadeCaracteres = strlen($Caracteres);
        $QuantidadeCaracteres--;

        $hash = null;
        for ($x=1; $x<=$qtd; $x++){
            $postion = rand(0, $QuantidadeCaracteres);
            $hash .=substr($Caracteres, $postion,1);
        }
        return $hash;

    }

    /**
     * Auto-complete form field customer
     * @Route("form/{center}/customer", name="get_customer")
     * @Method("GET")
     */
    public function getCustomerAction(Center $center)
    {
        $customers = [];
        $em = $this->getDoctrine()->getManager();

        $customersAll = $em->getRepository('App:Customer')->findBy(array('center' => $center));
        foreach ($customersAll as $customer){
            $customers[$customer->getEmail()] = null;
        }

        $payload=array();
        $payload['status']='ok';
        $payload['page']='show';
        $payload['customers'] = $customers;

        return new Response(json_encode($payload));
    }

}
