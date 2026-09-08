<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;

class ResponseSubscriber implements EventSubscriberInterface
{
    public function onControllerEvent(ControllerEvent $event): void
    {
        // ...
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ControllerEvent::class => 'onControllerEvent',
        ];
    }
}
