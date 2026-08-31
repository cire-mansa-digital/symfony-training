<?php

namespace App\Controller\Public;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route("/recipe", name:"recipe.")]
final class RecetteController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(RecipeRepository $repository, EntityManagerInterface $em): Response
    {
        $recipes = $repository->findAll();

        return $this->render('Public/recette/index.html.twig', [
            "recipes" => $recipes
        ]);
    }


    #[Route(path: '/{slug}-{id}', name: 'show', requirements: ['id' => '\d+', 'slug' => '[a-z0-9-]+'])]
    public function show(Request $request, int $id, string $slug, RecipeRepository $repository): Response
    {

        $recipe = $repository->find($id);
        if ($recipe->getSlug() != $slug) {
            return $this->redirectToRoute('recipe.show', ['id' => $id, 'slug' => $recipe->getSlug()]);
        }

        return $this->render('Public/recette/show.html.twig', [
            'recipe' => $recipe
        ]);
    }




}
