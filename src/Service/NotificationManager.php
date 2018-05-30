<?php

namespace  App\Services;

use App\Entity\Center;
use App\Entity\Notification;
use Doctrine\ORM\EntityManager;

class NotificationManager
{
    protected $em;

    /**
     * NotificationManager constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * function created notification
     * @param Center $center
     * @param $subject
     * @param $link
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function newNotification(Center $center, $subject, $link)
    {
        $notification = new Notification();

        $notification->setSubject($subject);
        $notification->setMessage(null);
        $notification->setLink($link);
        $notification->setCenter($center);

        $this->em->persist($notification);
        $this->em->flush();
    }

    /**
     * function created notification for supper_admin
     * @param $subject
     * @param $link
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function newNotificationMessage($subject, $link)
    {
        $notification = new Notification();

        $notification->setSubject($subject);
        $notification->setMessage(null);
        $notification->setLink($link);
        $notification->setCenter(null);

        $this->em->persist($notification);
        $this->em->flush();
    }

}