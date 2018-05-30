<?php

namespace  App\Services;

use App\Entity\Booking;
use App\Entity\Customer;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;


class Mailer
{
    protected $mailer;
    protected $templating;
    private   $from ="support@taqwira.com";
    private   $reply ="noreplay@taqwira.com";
    private   $name ="taqwira.com";

    /**
     * Mailer constructor.
     * @param $mailer
     * @param EngineInterface $templating
     */
    public function __construct($mailer, EngineInterface $templating)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    /**
     * function send mail standard
     * @param $to
     * @param $subject
     * @param $body
     */
    public function sendMessage($to, $subject, $body)
    {
        $message = \Swift_Message::newInstance()
            ->setFrom($this->from)
            ->setTo($to)
            ->setSubject($subject)
            ->setBody($body)
            ->setReplyTo($this->reply)
            ->setContentType('text/html');

        $this->mailer->send($message);
    }

    /**
     * function send mail confirmation message
     * @param User $user
     */
    public function sendConfirmationAccountMessage(User $user)
    {
        $subject = "Votre compte stadier a été crée";
        $template = "mail/confirmation.html.twig";
        $to = $user->getEmail();
        $body = $this->templating->render($template, array('user' => $user));
        $this->sendMessage($to, $subject, $body);
    }

    /**
     * function send mail success message
     * @param Booking $booking
     */
    public function sendBookingMessage(Booking $booking)
    {
        $subject = "Confirmation de votre réservation match";
        $template = "Mail/booking_mail.html.twig";
        $to = $booking->getCustomer()->getEmail();
        $body = $this->templating->render($template, array('booking' => $booking));
        $this->sendMessage($to, $subject, $body);
    }

    /**
     * function send mail success booking subscription message
     * @param $basket
     * @param Customer $customer
     */
    public function sendBookingSubscriptionMessage($basket, Customer $customer)
    {

        $subject = "Confirmation de votre réservation abonnement";
        $template = "Mail/booking_subscription_mail.html.twig";
        $to = $customer->getEmail();
        $body = $this->templating->render($template, array('basket' => $basket, 'customer' => $customer));
        $this->sendMessage($to, $subject, $body);
    }

}