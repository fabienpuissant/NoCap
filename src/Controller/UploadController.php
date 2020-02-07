<?php

namespace App\Controller;

use App\Entity\Photo;
use App\Form\PhotoType;
use App\Repository\PhotoRepository;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UploadController extends AbstractController
{

    private $repository;

    public function __construct(PhotoRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("photographe/upload", name = "upload", methods="GET|POST")
     * @@return \Symfony\Component\HttpFoundation\Response
     */
    public function upload(Request $request)
    {
        $photo = new Photo();

        $form  = $this->createForm(PhotoType::class, $photo);
        $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            

            //Move uploaded file and add photo entry to database
            if ($form->isSubmitted() && $form->isValid()) {

                //Saving the image on the server
                $imageFile = $form->get('imageFile')->getData();
                


            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
                if ($imageFile) {
                    $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                    // this is needed to safely include the file name as part of the URL
                    $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
                    

                    // Move the file to the directory where brochures are stored
                    try {
                        $imageFile->move(
                            $this->getParameter('photos_directory'),
                            $newFilename
                        );

                        //Saving the file in the database
                        $em = $this->getDoctrine()->getManager();
                        $photo->setTitle(htmlentities($data->getTitle()));
                        $photo->setAuthor(htmlentities($data->getAuthor()));
                        $photo->setDescription(htmlentities($data->getDescription()));
                        $photo->setFileName($newFilename);
                        $em->persist($data);
                        $em->flush();

                        return $this->redirection($form, 1);
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                        return $this->redirection($form, 2);
                    }

                    // updates the 'brochureFilename' property to store the PDF file name
                    // instead of its contents
                    $photo->setFilename($newFilename);
                }
                    
            }
          
        }
        
        //Not submitted or not valid
      return $this->redirection($form, 3);
    }



    /**
     * @param SignInType
     * @param Integer Error to display 0 no error, 1 error
     */
    private function redirection($form, $err){
        return $this->render('upload.html.twig', [
            'form' => $form->createView(),
            'err' => $err
        ]);
    }




}
