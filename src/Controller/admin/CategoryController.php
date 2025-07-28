<?php

namespace App\Controller\admin;

use App\Entity\Category;
use App\Form\CategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CategoryController extends AbstractController
{
    #[Route('/admin/category', name: 'app_category', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $manager): Response
    {

        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $form->getData()->getSlug();
            $category->setSlug(strtolower(str_replace(' ', '-', $category->getName())));

            $manager->persist($category);
            $manager->flush();

            $this->addFlash('success', 'Catégorie ajoutée !');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('admin/category/index.html.twig', [
            'formAdminCategory' => $form->createView()
        ]);
    }
}
