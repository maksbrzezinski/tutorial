<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Csrf\CsrfToken;

class FileDeleteController extends AbstractController
{
    #[Route('/files/delete/{filename}', name: 'delete_file', methods: ['POST'])]
    public function deleteFile(string $filename, Request $request, CsrfTokenManagerInterface $csrfTokenManager): Response
    {
        $csrfToken = $request->request->get('_token');

        if (!$csrfTokenManager->isTokenValid(new CsrfToken('delete_file' . $filename, $csrfToken))) {
            $this->addFlash('error', 'Invalid CSRF token.');
            return $this->redirectToRoute('list_files');
        }

        $uploadDir = $this->getParameter('kernel.project_dir') . '/public/uploads';
        $filePath = $uploadDir . '/' . $filename;

        // Check if the file exists
        if (file_exists($filePath)) {
            // Attempt to delete the file
            if (unlink($filePath)) {
                $this->addFlash('success', 'File deleted successfully!');
            } else {
                $this->addFlash('error', 'Failed to delete the file.');
            }
        } else {
            $this->addFlash('error', 'File not found.');
        }

        return $this->redirectToRoute('list_files');
    }
}
