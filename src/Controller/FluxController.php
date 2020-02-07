<?php

namespace App\Controller;



use \App\Repository\PhotoRepository;
use \App\Entity\Photo;
use \App\Entity\Vote;
use \App\Repository\VoteRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FluxController extends AbstractController
{

    private $photorepository;
    private $voterepository;

    public function __construct(PhotoRepository $photorepository, VoteRepository $voterepository ) 
    {
        $this->photorepository = $photorepository;
        $this->voterepository = $voterepository;
    }

    /**
     * @Route("/connexion", name = "connexion")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function connexion()
    {
        return $this->render("user_connexion.html.twig");
    }


    /**
     * @Route("/flux", name = "flux" , methods = "GET|POST") 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function display(Request $request)
    {
        //Check if the user is connected
        /*if(empty($_SESSION["user"]))
        {
            return $this->redirectToRoute('connexion');
        } 
        */

        $allPhotosArray = [];
        $allPhotosObjects = $this->photorepository->getAllPhotos();

        foreach($allPhotosObjects as $photoObject)
        {
            $intarray = [];

            //Récupération des données relatives aux likes de la personne
            //$like = $this->voterepository->isLiked($photoObject, $_SESSION["user"]);
            $like = $this->voterepository->isLiked($photoObject->getFileName(), "fabien");

            if($like){
                $intarray["Like"] = "coeurliked.png";
            } else {
                $intarray["Like"] = "coeur.png ";  
            }

            $like_counter = $photoObject->getLikeCounter();

            $intarray["Title"] = $photoObject->getTitle();
            $intarray["Description"] = $photoObject->getDescription();
            $intarray["Author"] = $photoObject->getAuthor();
            $intarray["FileName"] = $photoObject->getFileName();
            $intarray["id"] = $photoObject->getId();
            $allPhotosArray[] = $intarray;

        }

        return $this->render("flux.html.twig",
                            ['allPhotos' => $allPhotosArray]);
    }
}


?>