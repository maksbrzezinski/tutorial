<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadController extends AbstractController
{
    #[Route('/upload', name: 'upload_file')]
    public function upload(Request $request): Response
    {
        // Handle file upload
        if ($request->isMethod('POST')) {
            /** @var UploadedFile $file */
            $file = $request->files->get('file');
            if ($file && $file->isValid()) {
                $allowedFileTypes = ['jpg', 'jpeg', 'png', 'pdf', 'mov', 'mp4', 'avi'];
                
                if (!in_array($file->getClientOriginalExtension(), $allowedFileTypes)) {
                    return new Response('Nieodpowiednie rozszerzenie pliku. Możliwe rozszerzenia: jpg, jpeg, png, pdf, mov, mp4, avi.');
                }
                
                if (!$file->isValid()) {
                    $errorMessages = [
                        UPLOAD_ERR_INI_SIZE => 'The uploaded file exceeds the maximum allowed size.',
                        UPLOAD_ERR_FORM_SIZE => 'The uploaded file exceeds the maximum allowed size specified in the form.',
                        UPLOAD_ERR_PARTIAL => 'The file was only partially uploaded.',
                        UPLOAD_ERR_NO_FILE => 'No file was uploaded.',
                        UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder.',
                        UPLOAD_ERR_CANT_WRITE => 'Failed to write the file to disk.',
                        UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload.',
                    ];
                    $errorCode = $file->getError();
                    $errorMessage = isset($errorMessages[$errorCode]) ? $errorMessages[$errorCode] : 'Unknown error during file upload.';
                    return new Response($errorMessage, 400);
                }

                $uploadDir = $this->getParameter('kernel.project_dir') . '/public/uploads';
                $fileName = uniqid() . '.' . $file->guessExtension();
                $file->move($uploadDir, $fileName);

                $this->addFlash('success', 'Plik został pomyślnie dodany!');
            }
        }

        return $this->render('file/upload.html.twig');
    }
}
