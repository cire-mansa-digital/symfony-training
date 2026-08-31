<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route(path: "admin/category", name: "admin.category.")]
final class CategoryController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(EntityManagerInterface $em): Response
    {
        $category = $em->getRepository(Category::class)->findAll();

        return $this->render('admin/category/index.html.twig', [
            'category' => $category,
        ]);
    }

    #[Route(path: '/new', name: 'new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $category = new Category();
        $form =  $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($category);
            $em->flush();
            $this->addFlash('success', 'Catégorie Ajouter avec succès');
            return $this->redirectToRoute('admin.category.index');
        }


        return $this->render(
            'Admin/category/create.html.twig',
            [
                'category' => $category,
                'form' => $form
            ]
        );
    }

    #[Route(path: '/show/{slug}-{id}', name: 'show', requirements: ['slug' => Requirement::ASCII_SLUG])]
    public function show(Category $category, string $slug): Response
    {
        if ($category->getSlug() != $slug) {
            return $this->redirectToRoute('admin.category.show', ['id' => $category->getId(), 'slug' => $category->getSlug()]);
        }

        return $this->render(
            'Admin/category/show.html.twig',
            [
                'category' => $category
            ]
        );
    }

    #[Route(path: '/edit/{slug}-{id}', name: 'edit', requirements: ['slug' => Requirement::ASCII_SLUG, 'id' => Requirement::POSITIVE_INT])]
    public function edit(Category $category, string $slug, Request $request, EntityManagerInterface $em): Response
    {

        $form =  $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($category);
            $em->flush();
            $this->addFlash('success', 'Catégorie Modifier avec succès');
            return $this->redirectToRoute('admin.category.index');
        }


        return $this->render(
            'Admin/category/create.html.twig',
            [
                'category' => $category,
                'form' => $form
            ]
        );
    }
    #[Route(path: '/delete/{slug}-{id}', name: 'delete', requirements: ['slug' => Requirement::ASCII_SLUG, 'id' => Requirement::POSITIVE_INT])]
    public function delete(Category $category, string $slug, Request $request, EntityManagerInterface $em)
    {

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

            $em->remove($category);
            $em->flush();
            $this->addFlash('success', 'Catégorie supprimé avec succes');
            return $this->redirectToRoute('admin.category.index');
    }
}
