<?php

namespace App\Controller\admin;

use App\Entity\Publisher;
use App\Form\PublisherType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PublisherController extends AbstractController
{
    #[Route('admin/publisher', name: 'app_publisher', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $manager): Response
    {
        $publisher = new Publisher();
        $form = $this->createForm(PublisherType::class, $publisher);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $publisher->setCreatedAt(new \DateTimeImmutable());
            $publisher->setSlug(strtolower(str_replace(' ', '-', $publisher->getName())));

            //        dd($form);

            $manager->persist($publisher);
            $manager->flush();

            $this->addFlash('success', 'Publisher created successfully!');
            return $this->redirectToRoute('app_publisher');
        }

        return $this->render('admin/publisher/index.html.twig', [
            'formPublisher' => $form,
        ]);
    }
}
