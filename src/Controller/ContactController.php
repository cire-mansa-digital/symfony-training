<?php

namespace App\Controller;

use App\DTO\ContactDTO;
use App\Form\ContactType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

final class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $contact = new ContactDTO();
        $contact->email =  'cire@gmail.com';
        $contact->name = 'Diallo';
        $contact->message = 'Lorem lorem lorememe ';
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        // dd($form->getData());

        if ($form->isSubmitted() && $form->isValid()) {
            // dd($form);
            $mail = new TemplatedEmail()
                ->from($contact->service)
                ->to($contact->email)
                ->subject("Demande de contact")
                ->context(['data' => $contact])
                ->htmlTemplate('emails/contact.html.twig')

                ;
            try {

                $mailer->send($mail);
                $this->addFlash("success", "Email envoyer avec success");
                return $this->redirectToRoute("contact");
            } catch (\Throwable $th) {
                $this->addFlash("error", $th->getMessage());
            }
        }
        return $this->render('contact/contact.html.twig', [
            'form' => $form
        ]);
    }
}
