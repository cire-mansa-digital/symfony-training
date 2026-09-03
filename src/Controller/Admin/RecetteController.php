<?php

namespace App\Controller\Admin;

use App\Entity\Recipe;
use App\Entity\Category;
use App\Form\RecipeType;
use Doctrine\ORM\EntityManager;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



#[Route("/admin/recipe", name: "admin.recipe.")]
#[IsGranted('ROLE_ADMIN')]

final class RecetteController extends AbstractController
{
    #[Route('/', name: 'index')]
    // #[IsGranted('ROLE_USER')]
    public function index(RecipeRepository $repository, EntityManagerInterface $em, Request $request): Response
    {

        // $this->denyAccessUnlessGranted('ROLE_USER');

        $page = $request->query->get('page', 1);
        $limit = 2 ;
        $recipes = $repository->paginateRecipe($page, $limit);


        $Maxpage = ceil($recipes->count() / $limit);
        return $this->render('Admin/recette/index.html.twig', [
            "recipes" => $recipes,
            "maxPage" => $Maxpage,
            "page"=> $page
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


    #[Route(path: '/new', name: 'new', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $em)
    {


        $recipe =  new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // /**
            //  * @var  UploadedFile $image
            //  */
            // $image = $form->get('imageFile')->getData();

            // $imageName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME ) .''. $image->getClientOriginalExtension();
            // // dd($imageName);
            // $recipe->setImage($imageName);
            // $image->move($this->getParameter('kernel.project_dir'). '/public/images/recipe/', $imageName);

            $em->persist($recipe);
            $em->flush();
            $this->addFlash('success', 'Recette crée avec success');
            return $this->redirectToRoute('admin.recipe.index');
        }

        return $this->render(
            'Admin/recette/create.html.twig',
            [
                'form' => $form
            ]
        );
    }


    #[Route(path: '{id}/edit', name: 'edit', methods: ['POST', 'PATCH', 'GET'])]
    public function edit(Request $request, Recipe $recipe, EntityManagerInterface $em, UploaderHelper $uploaderHelper)
    {

        $form = $this->createForm(RecipeType::class, $recipe);
        $imageUrl = $uploaderHelper->asset($recipe, 'imageFile');
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // dd($form->getData());
            // $recipe->setUpdateAt(new \DateTimeImmutable());

            // /**
            //  * @var  UploadedFile $image
            //  */
            // $image = $form->get('imageFile')->getData();

            // $imageName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME ) .'.'. $image->getClientOriginalExtension();
            // // dd($imageName);
            // $recipe->setImage($imageName);
            // $image->move($this->getParameter('kernel.project_dir'). '/public/images/recipe/', $imageName);

            $em->persist($recipe);
            $em->flush();
            $this->addFlash('success', 'Recette modifié avec success');
            return $this->redirectToRoute('admin.recipe.index');
        }
        // dd($recipe);
        return $this->render(
            'Admin/recette/edit.html.twig',
            [
                'form' => $form,
                'recipe' => $recipe,
                'url' => $imageUrl
            ]
        );
    }

    #[Route(path: "{id}/delete", name: 'delete', methods: ['DELETE'])]
    public function delete(Request $request, Recipe $recipe, EntityManagerInterface $em)
    {
        $em->remove($recipe);
        $em->flush();
        $this->addFlash('success', 'Recette supprimé avec succes');
        return $this->redirectToRoute('admin.recipe.index');
    }
}
