<?php

namespace App\Controller;

use App\Entity\Recipe;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class HomeController extends AbstractController
{
    #[Route(path: "/", name: "Home")]
    function index(EntityManagerInterface $em, TranslatorInterface $translator)
    {
        // dd($translator->trans("Cooking"));
        $recipes= $em->getRepository(Recipe::class)->findBy(
            [],
            ["id"=>"ASC"],
            3
        );

      return $this->render("/home/index.html.twig",
         [
            "latestRecipes"=> $recipes
         ]
      );

    }


}
