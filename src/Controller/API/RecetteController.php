<?php

namespace App\Controller\API;

use App\DTO\PaginationDTO;
use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route(path: 'api/recette', name: 'api.recipe.')]
class RecetteController extends AbstractController
{

    #[Route(path: '/', name: 'index', methods: ['GET'])]
    public function index(Request $request, RecipeRepository $repo, SerializerInterface $serializer, #[MapQueryString]  PaginationDTO $paginationDTO): JsonResponse
    {
        $recipe = $repo->paginateRecipe($request->query->getInt('page', 1));
        //        dd($serializer->serialize($recipe,'json', [
        //            'groups' => 'recipe_index'
        //        ] ));

        return $this->json($recipe, 200, [], [
            'groups' => ['recipe_index']
        ]);
    }

    #[Route(path: '/show/{id}', name: 'show', methods: ['GET'])]
    public function show(Recipe $recipe)
    {
        return $this->json($recipe, 200, [], [
            'groups' => ['recipe_index', 'recipe_show']
        ]);
    }

    #[Route(path: '/new', name: 'new', methods: ['POST'])]
    public function create(Request $request , EntityManagerInterface $em , #[MapRequestPayload(serializationContext: ['groups' => 'recipe_new'])] Recipe $recipe)
    {

        $recipe->setCreatedAt(new \DateTimeImmutable());
        $recipe->setUpdateAt(new \DateTimeImmutable());

//        dd($recipe);

        $em->persist($recipe);
        $em->flush();
        return $this->json($recipe, 200, [], [
            'groups' => ['recipe_new', 'recipe_show']
        ]);
    }
}
