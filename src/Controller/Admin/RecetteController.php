<?php



namespace App\Controller\Admin;

use App\Entity\Recipe;
use App\Entity\Category;
use App\Form\RecipeType;
use Doctrine\ORM\EntityManager;
use App\Message\RecipePDFMessage;
use Symfony\UX\Turbo\TurboBundle;
use App\Security\Voter\RecipeVoter;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route("/admin/recipe", name: "admin.recipe.")]
// #[IsGranted('ROLE_ADMIN')]


final class RecetteController extends AbstractController
{
    #[Route('/', name: 'index')]
    // #[IsGranted('ROLE_USER')]
    #[IsGranted(RecipeVoter::LIST)]
    public function index(RecipeRepository $repository, EntityManagerInterface $em, Request $request, Security $security): Response
    {

        // $this->denyAccessUnlessGranted('ROLE_USER');
        // dd($security);
        $cantAll = $security->isGranted(RecipeVoter::LIST_ALL);



        /** @var \App\Entity\User $user */
        $user = $this->getUser();



        $page = $request->query->get('page', 1);

        $recipes = $repository->paginateRecipe($page, $cantAll? null : $user->getId());


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


    #[Route(path: '/new', name: 'new', methods: ['GET', 'POST'])]
    #[IsGranted(RecipeVoter::CREATE)]
    public function create(Request $request, EntityManagerInterface $em)
    {


        $recipe =  new Recipe();
        $recipe->setRuser($this->getUser());
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


    /**
     * @throws ExceptionInterface
     */
    #[Route(path: '{id}/edit', name: 'edit', methods: ['POST', 'PATCH', 'GET'])]
    #[IsGranted(RecipeVoter::EDIT, subject: 'recipe')]
    public function edit(Request $request, Recipe $recipe, EntityManagerInterface $em, UploaderHelper $uploaderHelper, MessageBusInterface $messageBus): Response
    {

        $form = $this->createForm(RecipeType::class, $recipe);
        $imageUrl = $uploaderHelper->asset($recipe, 'imageFile');
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $messageBus->dispatch(new RecipePDFMessage($recipe->getId(), $recipe->getSlug()));
            // $em->persist($recipe);
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
        //  $id = $recipe->getId();
        //  $message = "Recette supprimé avec succes";
        $em->remove($recipe);
        $em->flush();
        //  if ($request->getPreferredFormat()== TurboBundle::STREAM_FORMAT  ) {
        //     $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
        //     return $this->render('Admin/recette/delete.html.twig',['recipe_id'=> $id, 'message'=> $message]);
        //  }
        $this->addFlash('success', 'Recette supprimé avec succes');
        return $this->redirectToRoute('admin.recipe.index');
    }
}
