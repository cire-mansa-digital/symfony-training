<?php

namespace App\Controller\Public;

use App\Entity\User;
use App\Entity\Recipe;
use App\Form\RecipeType;
use Doctrine\ORM\EntityManager;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


#[Route("/recipe", name:"recipe.")]
final class RecetteController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(Request $request, RecipeRepository $repository): Response
    {
        $page = $request->query->getInt('page', 1);
        $recipes = $repository->paginatePublicRecipes($page, 9);

        return $this->render('Public/recette/index.html.twig', [
            'recipes' => $recipes
        ]);
    }


    #[Route(path: '/{slug}-{id}', name: 'show', requirements: ['id' => '\d+', 'slug' => '[a-z0-9-]+'])]
    public function show(Request $request, int $id, string $slug, RecipeRepository $repository): Response
    {

        $recipe = $repository->find($id);
        if ($recipe->getSlug() != $slug ) {
            return $this->redirectToRoute('recipe.show', ['id' => $id, 'slug' => $recipe->getSlug()]);
        }

        return $this->render('Public/recette/show.html.twig', [
            'recipe' => $recipe
        ]);
    }




}
