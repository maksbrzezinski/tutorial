<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FileListController extends AbstractController
{
    #[Route('/files', name: 'list_files')]
    public function listFiles(): Response
    {
        $uploadDir = $this->getParameter('kernel.project_dir') . '/public/uploads';
        $files = array_diff(scandir($uploadDir), ['.', '..']);

        return $this->render('file/list.html.twig', [
            'files' => $files,
        ]);
    }
}
