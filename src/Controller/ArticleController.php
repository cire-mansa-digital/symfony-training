<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ArticleController extends AbstractController
{





    #[Route(path:"/article", name:"article.index")]
    public function index (): Response
    {
        return  new Response("Articles de Manga");
    }



    #[Route('/article/{slug}-{id}', name: 'article.show', requirements:[ 'id' => '\d+', 'slug'=> '[a-z0-9-]+'])]
    public function show(Request $request, string $slug, int $id): Response
    {
        //    return new Response("Post: ". $request->attributes->get("slug") . "--" . $request->attributes->get("id"));

        // return $this->json([
        //     $slug,
        //     $id
        // ]);


        return  new JsonResponse([
            "Slug" =>  $slug,
            "ID" => $id
        ]);
    }
}
