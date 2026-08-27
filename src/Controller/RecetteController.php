<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RecetteController extends AbstractController
{
    #[Route('/recette', name: 'recette.index')]
    public function index(RecipeRepository $repository, EntityManagerInterface $em): Response
    {
        $recipes = $repository->findAll();

        //  Ajout d'une nouvelle recette
        // $nrecip = new Recipe();
        // $nrecip->setTitle('Mafé Hakhobantara');
        // $nrecip->setContent("Créations culinaires exquises utilisant des ingrédients frais d'origine locale et des recettes authentiques. Nos chefs talentueux élaborent des plats exceptionnels qui célèbrent des saveurs diverses et des traditions culinaires. Chaque repas est un voyage à travers le goût, la texture et l'excellence de la présentation.");
        // $nrecip->setSlug("maffe-hakhobantara");
        // $nrecip->setCreatedAt(new \DateTimeImmutable());
        // $nrecip->setUpdateAt (new \DateTimeImmutable());

        // $em->persist($nrecip);
        // $em->flush();

        //  Modification d'une recette
        // $rcp = $repository->find(5);

        // dump($rcp);

        // $rcp->setDuration(5);
        // $em->flush();

        // dump($rcp);



// Suppresion

        // $recp = $repository->find(4);
        // dump($recipes);
        // // $em->remove($recp);


        // // $em->flush();


        // dump($recipes);

        // dd($repository->findByDurationShort(15 ));
        //  dd($em->getRepository(Recipe::class)->totalDuration());
        // dd($recipes);
        return $this->render('recette/index.html.twig', [
            "recipes" => $recipes
        ]);
    }


    #[Route(path: '/recette/{slug}-{id}', name: 'recette.show', requirements: ['id' => '\d+', 'slug' => '[a-z0-9-]+'])]
    public function show(Request $request, int $id, string $slug, RecipeRepository $repository): Response
    {

        $recipe = $repository->find($id);
        if ($recipe->getSlug() != $slug) {
            return $this->redirectToRoute('recette.show', ['id' => $id, 'slug' => $recipe->getSlug()]);
        }

        return $this->render('recette/show.html.twig', [
            'recipe' => $recipe
        ]);
    }


    #[Route(path:'/recette/new', name:'recette.new', methods:['GET','POST'] )]
    public function create(Request $request, EntityManagerInterface $em){

    $recipe =  new Recipe();
    $form = $this->createForm(RecipeType::class,$recipe );
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid()){
        // $recipe->setCreatedAt(new \DateTimeImmutable());
        // $recipe->setUpdateAt(new \DateTimeImmutable());
        $em->persist($recipe);
        $em->flush();
        $this->addFlash('success','Recette crée avec success');
        return $this->redirectToRoute('recette.index');
    }

      return $this->render('recette/create.html.twig',
      [
        'form'=> $form
      ]
      );
    }


    #[Route(path:'/recette/{id}/edit', name:'recette.edit', methods: ['POST','PATCH','GET'])]
    public function edit (Request $request , Recipe $recipe, EntityManagerInterface $em  ){
     $form = $this->createForm(RecipeType::class, $recipe);
     $form->handleRequest($request);
     if ($form->isSubmitted() && $form->isValid()) {
        // $recipe->setUpdateAt(new \DateTimeImmutable());
         $em->persist($recipe);
         $em->flush();
         $this->addFlash('success','Recette modifié avec success');
         return $this->redirectToRoute('recette.index');
     }
    // dd($recipe);
     return $this->render('recette/edit.html.twig',
        [
            'form'=> $form,
            'recipe' => $recipe
        ]
     );
    }

    #[Route(path:"/recette/{id}/delete", name: 'recette.delete', methods: ['DELETE'])]
    public function delete (  Request $request , Recipe $recipe, EntityManagerInterface $em ){
         $em->remove($recipe);
         $em->flush();
         $this->addFlash('success','Recette supprimé avec succes');
         return $this->redirectToRoute('recette.index');
    }
}
