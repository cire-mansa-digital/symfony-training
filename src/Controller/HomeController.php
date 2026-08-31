<?php

namespace App\Controller;

use App\Entity\Recipe;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

final class HomeController extends AbstractController
{
    #[Route(path: "/", name: "Home")]
    function index(EntityManagerInterface $em)
    {
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
