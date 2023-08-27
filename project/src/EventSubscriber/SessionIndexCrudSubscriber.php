<?php

namespace App\EventSubscriber;

use App\Entity\Session;
use App\Repository\SessionRepository;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeCrudActionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SessionIndexCrudSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly SessionRepository $sessionRepository)
    {
    }

    public function onBeforeShowIndexPage(BeforeCrudActionEvent $event): void
    {

        if ($event->getAdminContext()->getCrud()->getEntityFqcn() !== Session::class) {
            return;
        }

        array_map(function (Session $session) {
            if (!$session->getData() || !$session->getCinema() || !$session->getHall()) {
                $this->sessionRepository->remove($session, true);
            }
        }, $this->sessionRepository->findAll());

    }

    public static function getSubscribedEvents(): array
    {
        return [
            BeforeCrudActionEvent::class => 'onBeforeShowIndexPage',
        ];
    }

}