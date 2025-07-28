<?php

namespace App\Controller\admin;

use App\Entity\Game;
use App\Form\GameType;
use App\Repository\GameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

final class AdminGameController extends AbstractController
{
    #[Route('/admin/game', name: 'app_admin_game', methods: ['GET'])]
    public function index(GameRepository $gameRepository, PaginatorInterface $paginator, Request $request): Response
    {
        // $game = $gameRepository->findAll();

        // dump($game);

        $game = $paginator->paginate(
            $gameRepository->getAll(),
            $request->query->getInt('page', 1), /* page number */
            10 /* limit per page */
        );


        return $this->render('admin/game/index.html.twig', [
            'games' => $game,
        ]);
    }

    #[Route('/admin/game/add', name: 'app_admin_game_add', methods: ['GET', 'POST'])]
    public function add(Request $request, EntityManagerInterface $manager, SluggerInterface $slugger): Response
    {
        // pas de constructeur ajout date + le slug
        $game = new Game();
        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $game->setPublishedAt(new \DateTimeImmutable());
            $game->setSlug(strtolower(str_replace(' ', '-', $game->getName())));

            // $thumbnailCover = $form->get('thumbnailCover')->getData();

            // // this condition is needed because the 'brochure' field is not required
            // // so the PDF file must be processed only when a file is uploaded
            // if ($thumbnailCover) {
            //     $originalFilename = pathinfo($thumbnailCover->getClientOriginalName(), PATHINFO_FILENAME);
            //     // this is needed to safely include the file name as part of the URL
            //     $safeFilename = $slugger->slug($originalFilename);
            //     $newFilename = $safeFilename . '-' . uniqid() . '.' . $thumbnailCover->guessExtension();

            //     // Move the file to the directory where brochures are stored
            //     try {
            //         $thumbnailCover->move(
            //             $this->getParameter('image_directory'),
            //             $newFilename
            //         );
            //     } catch (FileException $e) {
            //         // ... handle exception if something happens during file upload
            //     }

            //     // updates the 'brochureFilename' property to store the PDF file name
            //     // instead of its contents
            //     $game->setThumbnailCover($newFilename);

            // }

            $manager->persist($game);
            $manager->flush();

            $this->addFlash('success', 'Game created successfully!');
            return $this->redirectToRoute('app_admin_game');
        }

        return $this->render('admin/game/add.html.twig', [
            'formAdminAddGame' => $form,
        ]);
    }

    #[Route('/admin/game/edit/{id}', name: 'app_admin_game_edit', methods: ['GET', 'POST'])]
    public function edit(int $id, GameRepository $gameRepository, Request $request, EntityManagerInterface $manager): Response
    {
        $game = $gameRepository->findOneBy(['id' => $id]);

        dump($game);

        if (!$game) {
            throw $this->createNotFoundException(
                'No game found for id ' . $id
            );
        }

        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $game->setPublishedAt(new \DateTimeImmutable());
            $game->setSlug(strtolower(str_replace(' ', '-', $game->getName())));

            $manager->persist($game);
            $manager->flush();

            $this->addFlash('success', 'Game created successfully!');
            return $this->redirectToRoute('app_show_game', [
                'slug' => $game->getSlug()
            ]);
        }

        return $this->render('admin/game/edit.html.twig', [
            'game' => $game,
            'formAdminEditGame' => $form,
        ]);
    }

    #[Route('/admin/game/{id}', name: 'app_admin_game_show', methods: ['GET'])]
    public function show(int $id, GameRepository $gameRepository): Response
    {
        $game = $gameRepository->findOneBy(['id' => $id]);

        dump($game);

        if (!$game) {
            throw $this->createNotFoundException(
                'No game found for id ' . $id
            );
        }

        return $this->render('admin/game/show.html.twig', [
            'games' => $game,
        ]);
    }

    #[Route('/admin/game/delete/{id}', name: 'app_admin_game_delete', methods: ['GET'])]
    public function delete(int $id, GameRepository $gameRepository, EntityManagerInterface $manager): Response
    {
        $game = $gameRepository->findOneBy(['id' => $id]);
        if (!$game) {
            throw $this->createNotFoundException(
                'No game found for id ' . $id
            );
        }
        $manager->remove($game);
        $manager->flush();
        $this->addFlash('success', 'Game deleted successfully!');
        return $this->redirectToRoute('app_admin_game');

    }

}
