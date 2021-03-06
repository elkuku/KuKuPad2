<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'default', methods: ['GET'])]
    public function index(string $projectDir): Response
    {
        if ($this->isGranted('ROLE_READER')) {
            return $this->redirectToRoute('wiki');
        }

        return $this->render(
            'default/index.html.twig',
            [
                'controller_name' => 'DefaultController',
                'php_version'     => PHP_VERSION,
                'symfony_version' => Kernel::VERSION,
                'project_dir'     => $projectDir,
            ]
        );
    }
}
