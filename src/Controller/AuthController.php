<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{
    /**
     * @Route("/auth", name="auth", methods={"GET", "HEAD"})
     */
    public function index()
    {
        return $this->render('auth/index.html.twig', [
            'controller_name' => 'AuthController',
        ]);
    }

    /**
     * @Route("/auth/register", name="register", methods={"GET", "HEAD"})
     */
    public function register()
    {
        $user = new User;

        $form = $this->createForm(UserType::class, $user);

        return $this->render('auth/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @route("/auth", name="login", methods={"POST", "HEAD"})
     */
    public function login()
    {
        //
    }

    /**
     * @route("/auth/register", name="newuser", methods={"POST", "HEAD"})
     */
    public function new()
    {
        return $this->render('auth/register.html.twig', [
            'responses' => ['Successfully created a new user, you can now log in with the details provided.'],
        ]);
    }
}
