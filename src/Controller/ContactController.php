<?php

namespace App\Controller;

use App\DTO\ContactDTO;
use App\Form\ContactType;
use Symfony\Component\Mime\Email;
use App\Event\ContactRequestEvent;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
// use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function index(Request $request, MailerInterface $mailer, EventDispatcherInterface $dispatcher): Response
    {
        $contact = new ContactDTO();
        $contact->email =  '';
        $contact->name = '';
        $contact->message = '';
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        // dd($form->getData());

        if ($form->isSubmitted() && $form->isValid()) {
            // dd($form);
            try {

               $dispatcher->dispatch(new ContactRequestEvent($contact));
                $this->addFlash("success", "Email envoyer avec success");
                return $this->redirectToRoute("contact");
            } catch (\Exception $th) {
                $this->addFlash("error", $th->getMessage());
            }
        }
        return $this->render('contact/contact.html.twig', [
            'form' => $form
        ]);
    }
}
