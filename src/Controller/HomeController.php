<?php

namespace App\Controller;

use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route(path: "/", name: "Home")]
    public function index(RecipeRepository $recipeRepository): Response
    {
        $recipes = $recipeRepository->findBy(
            [],
            ["createdAt" => "DESC", "id" => "DESC"],
            3
        );

        return $this->render("home/index.html.twig", [
            "latestRecipes" => $recipes
        ]);
    }
}
