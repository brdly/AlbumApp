<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Form\AlbumFormType;
use App\Form\TrackFormType;
use App\Form\ReviewFormType;
use App\Entity\Album;
use App\Entity\Track;
use App\Entity\Review;

class AlbumController extends AbstractController
{
    /**
     * @Route("/album", name="album")
     */
    public function index()
    {
        $albums = $this->getDoctrine()->getRepository(Album::class)->findAll();

        return $this->render('album/index.html.twig', [
            'albums' => $albums,
        ]);
    }

    /**
     * @Route("/album/new", name="albumnew")
    */
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');

        $album = new Album();
        $form = $this->createForm(AlbumFormType::class, $album);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $coverFile = $form['cover']->getData();

            $originalFilename = pathinfo($coverFile->getClientOriginalName(), PATHINFO_FILENAME);
            
            $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$coverFile->guessExtension();
            
            try {
                $coverFile->move(
                    $this->getParameter('covers_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                return $this->render('album/new.html.twig', [
                    'newAlbumForm' => $form->createView(),
                ]);
            }

            $album->setCover($newFilename);

            $entityManager->persist($album);
            $entityManager->flush();

            return $this->redirectToRoute('album');
        }

        return $this->render('album/new.html.twig', [
            'newAlbumForm' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/album/{id}/track/new", name="track_new")
     */
    public function newTrack($id, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');

        $track = new Track();
        $form = $this->createForm(TrackFormType::class, $track);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $track->setAlbum($this->getDoctrine()->getRepository(Album::class)->find($id));

            $entityManager->persist($track);
            $entityManager->flush();

            return $this->redirectToRoute('album_show', ['id' => $id]);
        }

        return $this->render('album/track/new.html.twig', [
            'newTrackForm' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/album/{id}/review/new", name="review_new")
     */
    public function newReview($id, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');

        $review = new Review();
        $form = $this->createForm(ReviewFormType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $review->setAlbum($this->getDoctrine()->getRepository(Album::class)->find($id));
            $review->setUser($this->getUser());
            $review->setCreatedAt(new \DateTime());

            $entityManager->persist($review);
            $entityManager->flush();

            return $this->redirectToRoute('album_show', ['id' => $id]);
        }

        return $this->render('/album/review/new.html.twig', [
            'newReviewForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/album/{id}", name="album_show")
     */
    public function show($id, Request $request): Response
    {
        $album = $this->getDoctrine()->getRepository(Album::class)->find($id);

        if (!$album) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $tracks = $album->getTracks();
        $reviews = $album->getReviews();
        // $user = $reviews->getUser();

        return $this->render('album/show.html.twig', [
            'album' => $album
        ]);
    }
}
