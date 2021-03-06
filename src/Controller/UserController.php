<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user')]
#[IsGranted("ROLE_ADMIN")]
class UserController extends AbstractController
{
    #[Route('/', name: 'user_index', methods: ['GET'])]
    public function index(
        UserRepository $userRepository,
        Request $request
    ): Response {
        $template = $request->query->get('ajax')
            ? '_list.html.twig'
            : 'index.html.twig';

        return $this->render(
            'user/'.$template,
            [
                'users' => $userRepository->findBy([], ['id' => 'ASC']),
            ]
        );
    }

    #[Route('/new', name: 'user_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
    ): Response {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index');
        }

        $template = $request->query->get('ajax')
            ? '_form.html.twig'
            : 'new.html.twig';

        return $this->renderForm(
            'user/'.$template,
            [
                'user' => $user,
                'form' => $form,
            ]
        );
    }

    #[Route('/{id}', name: 'user_show', methods: ['GET'])]
    public function show(
        User $user
    ): Response {
        return $this->render(
            'user/show.html.twig',
            [
                'user' => $user,
            ]
        );
    }

    #[Route('/{id}/edit', name: 'user_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        User $user,
        EntityManagerInterface $entityManager,
    ): Response {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('user_index');
        }

        $template = $request->query->get('ajax')
            ? '_form.html.twig'
            : 'edit.html.twig';

        return $this->renderForm(
            'user/'.$template,
            [
                'user' => $user,
                'form' => $form,
            ]
        );
    }

    #[Route('/delete/{id}', name: 'user_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        User $user,
        EntityManagerInterface $entityManager,
    ): Response {
        if ($this->isCsrfTokenValid(
            'delete'.$user->getId(),
            $request->request->get('_token')
        )
        ) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }
}
