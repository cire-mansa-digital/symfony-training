<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route("/admin/recipe", name:"admin.recipe.")]
final class RecetteController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(RecipeRepository $repository, EntityManagerInterface $em): Response
    {
        $recipes = $repository->findAll();

        // $category = $em->getRepository(Category::class)->findAll()[1];
        $rec = $em->getRepository(Recipe::class)->find(5);

        // $rec->setCategory($category);

        // $em->persist($rec);
        // $em->flush();

        // dd($rec->getCategory()->getName());






        return $this->render('Admin/recette/index.html.twig', [
            "recipes" => $recipes
        ]);
    }


    #[Route(path: '/{slug}-{id}', name: 'show', requirements: ['id' => '\d+', 'slug' => '[a-z0-9-]+'])]
    public function show(Request $request, int $id, string $slug, RecipeRepository $repository): Response
    {

        $recipe = $repository->find($id);
        if ($recipe->getSlug() != $slug) {
            return $this->redirectToRoute('admin.recipe.show', ['id' => $id, 'slug' => $recipe->getSlug()]);
        }

        return $this->render('Admin/recette/show.html.twig', [
            'recipe' => $recipe
        ]);
    }


    #[Route(path:'/new', name:'new', methods:['GET','POST'] )]
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
        return $this->redirectToRoute('admin.recipe.index');
    }

      return $this->render('Admin/recette/create.html.twig',
      [
        'form'=> $form
      ]
      );
    }


    #[Route(path:'{id}/edit', name:'edit', methods: ['POST','PATCH','GET'])]
    public function edit (Request $request , Recipe $recipe, EntityManagerInterface $em  ){
     $form = $this->createForm(RecipeType::class, $recipe);
     $form->handleRequest($request);
     if ($form->isSubmitted() && $form->isValid()) {
        // $recipe->setUpdateAt(new \DateTimeImmutable());
         $em->persist($recipe);
         $em->flush();
         $this->addFlash('success','Recette modifié avec success');
         return $this->redirectToRoute('admin.recipe.index');
     }
    // dd($recipe);
     return $this->render('Admin/recette/edit.html.twig',
        [
            'form'=> $form,
            'recipe' => $recipe
        ]
     );
    }

    #[Route(path:"{id}/delete", name: 'delete', methods: ['DELETE'])]
    public function delete (  Request $request , Recipe $recipe, EntityManagerInterface $em ){
         $em->remove($recipe);
         $em->flush();
         $this->addFlash('success','Recette supprimé avec succes');
         return $this->redirectToRoute('admin.recipe.index');
    }


}
