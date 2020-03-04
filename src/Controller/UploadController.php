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
     * @Route("photographe/upload", name = "photographe_upload", methods="GET|POST")
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

                    //Saving the file in the database
                    $em = $this->getDoctrine()->getManager();
                    $photo->setImageFile($imageFile);
                    $photo->setTitle(htmlentities($data->getTitle()));
                    $photo->setAuthor(htmlentities($data->getAuthor()));
                    $photo->setDescription(htmlentities($data->getDescription()));
                    $photo->setCategorie(htmlentities($data->getCategorie()));
                    $em->persist($data);
                    $em->flush();

                    //$this->correctImageOrientation("http://90.14.135.33:8000/photos/" . $photo->getImageName());



                    return $this->redirection($form, 1);
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
    private function redirection($form, $err)
    {
        return $this->render('upload.html.twig', [
            'form' => $form->createView(),
            'err' => $err
        ]);
    }

    private function correctImageOrientation($filename)
    {
        if (function_exists('exif_read_data')) {
            $exif = exif_read_data($filename, null, true);
            if ($exif && isset($exif['Orientation'])) {
                $orientation = $exif['Orientation'];
                if ($orientation != 1) {
                    $img = imagecreatefromjpeg($filename);
                    $deg = 0;
                    switch ($orientation) {
                        case 3:
                            $deg = 180;
                            break;
                        case 6:
                            $deg = 270;
                            break;
                        case 8:
                            $deg = 90;
                            break;
                    }
                    if ($deg) {
                        $img = imagerotate($img, $deg, 0);
                    }
                    // then rewrite the rotated image back to the disk as $filename 
                    imagejpeg($img, $filename, 95);
                } // if there is some rotation necessary
            } // if have the exif orientation info
        } // if function exists      
    }
}
