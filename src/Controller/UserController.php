<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function index(UserRepository $userRepository, Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) 
        {
            $user->setPassword($userRepository->hashPassword($user->getPassword(), $user));
            $user->setRoles(['ROLE_USER']);
            $userRepository->add($user, true);
        }


        return $this->renderForm('user/index.html.twig', [
            'controller_name' => 'UserController',
            'form' => $form
        ]);
    }
}
