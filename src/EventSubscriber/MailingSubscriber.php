<?php

namespace App\EventSubscriber;

use App\Entity\User;
use Symfony\Component\Mime\Email;
use App\Event\ContactRequestEvent;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class MailingSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly MailerInterface $mailer) {

    }
    public function onContactRequestEvent(ContactRequestEvent $event): void
    {
         $contact = $event->data;
          $mail = new TemplatedEmail()
                ->from($contact->service)
                ->to($contact->email)
                ->subject("Demande de contact")
                ->context(['data' => $contact])
                ->htmlTemplate('emails/contact.html.twig');

                 $this->mailer->send($mail);

    }

    public function onLogin (InteractiveLoginEvent $event){


           /**
            *  @var  user User
            */
           $user = $event->getAuthenticationToken()->getUser();

           $mail = (new  Email())
                ->from('support@recipe.app')
                ->to($user->getEmail())
                ->subject("Conexion")
                ->text('Vous vous êtes connecter ');

                 $this->mailer->send($mail);

    }

    public static function getSubscribedEvents(): array
    {
        return [
            ContactRequestEvent::class => 'onContactRequestEvent',
            InteractiveLoginEvent::class => 'onLogin'
        ];
    }
}
