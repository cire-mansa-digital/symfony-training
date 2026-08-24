<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{

    #[Route(path: "/home", name: "Home")]
    function index(Request $request)
    {

        // dd($request);
        return new Response("Bonjour ". $request->query->get("nom"," à toi !!"));
    }
}
