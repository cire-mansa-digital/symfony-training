<?php

namespace App\Controller\API;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[Route(path:'/api/user', name: 'user.' )]
final class UserController extends AbstractController
{
    #[Route('/me', name: 'me', methods: ['GET'])]
    #[IsGranted("ROLE_USER")]
    public function me(): Response
    {
          return $this->json([
              $this->getUser()
          ]);
    }
}
