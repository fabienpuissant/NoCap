<?php

namespace App\Controller;


use \App\Repository\PhotoRepository;
use \App\Entity\Photo;
use \App\Repository\UserRepository;
use \App\Entity\User;
use \App\Repository\PhotoLikeRepository; 
use \App\Entity\PhotoLike;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ClassementController extends AbstractController
{

    private $photorepository;
    private $userrepository;
    private $session;
    private $user;
    private $apikey;
    private $photodisplayed = 0;

    public function __construct(PhotoRepository $photorepository, SessionInterface $session, UserRepository $userrepository) 
    {
        $this->session = $session;
        $this->photorepository = $photorepository;
        $this->userrepository = $userrepository;
        $this->apikey = $this->session->get('user');
        $this->user = $this->userrepository->findUserByApiKey($this->apikey);
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
     * @Route("/classement", name = "classement" , methods = "GET|POST") 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function display(Request $request)
    {

        if(!isset($this->apikey)) {
            return $this->render("notConnected.html.twig");
        }

        $allPhotosArraynNotSorted = $this->getPhotosByCategory('Mixte', 1);

        $allPhotosArray = $this->photorepository->getPhotoByLikes($allPhotosArraynNotSorted);
        
        return $this->render("classement.html.twig",
                            ['allPhotos' => $allPhotosArray,
                            'FirstCategory' => 'Mixte',
                            'SecondCategory' => 'Femme',
                            'ThirdCategory' => 'Homme']);
    }

    

    /**
     * Select a category of photos
     * @Route("/classement/{categorie}", name = "select_category")
     * @return Response
     */
    public function selectCategory($categorie){
        {
            $allPhotosArray = $this->getPhotosByCategory($categorie, 1);
            $allPhotosArray = $this->photorepository->getPhotoByLikes($allPhotosArraynNotSorted);

            if($categorie == 'Mixte'){
                return $this->render("classement.html.twig",
                            ['allPhotos' => $allPhotosArray,
                            'FirstCategory' => 'Mixte',
                            'SecondCategory' => 'Femme',
                            'ThirdCategory' => 'Homme']);   
            }
            else if($categorie == 'Homme'){
                return $this->render("classement.html.twig",
                            ['allPhotos' => $allPhotosArray,
                            'FirstCategory' => 'Homme',
                            'SecondCategory' => 'Femme',
                            'ThirdCategory' => 'Mixte']);   
            }
            else {
                return $this->render("classement.html.twig",
                            ['allPhotos' => $allPhotosArray,
                            'FirstCategory' => 'Femme',
                            'SecondCategory' => 'Mixte',
                            'ThirdCategory' => 'Homme']);   
            }
        }
    }


    /**
     * Display more photos
     * @Route("/flux/{category}/{nbPhotoDisplayed}", name = "diplay_more")
     * @param string The category we want to display
     * @param integer number of photo already displayed
     * @return Response
     */
    public function scroll(string $category, string $nbPhotoDisplayed){
        $allPhotosArray = $this->getPhotosByCategory($category, intval($nbPhotoDisplayed));
        return $this->json([
            'code' => 200,
            'photos' => $allPhotosArray
        ], 200);
    }



    /**
     * Select photos corresponding to the category
     * @param string category chosen
     * @param int Numberof photo already displayed
     * @return array array representing all the photos with keys : 'Likes' => Number of likes
     *                                                             'Title' => Title of the photo
     *                                                             'Description' => Description of the photo
     *                                                             'Author' => Author of the photo
     *                                                             'FileName' => The filename to get the path of the file
     *                                                             'id' => The id of the photo 
     */
    private function getPhotosByCategory(string $category, int $nbPhotoDisplayed)
    {
        $allPhotosArray = [];
        //The array with all photos sorted by decreasing order
        $allPhotosObjects = $this->photorepository->getPhotoByCategory($category);


        //Get the photos we need
        //p is the number of photos we display at the same time
        $p = 2;
        $photoToDisplay = [];
        
        for($i = ($nbPhotoDisplayed - 1)*$p; $i<$nbPhotoDisplayed*$p; $i++){
            if(isset($allPhotosObjects[$i])){
                $photoToDisplay[] = $allPhotosObjects[$i];
            }
        }

        foreach($photoToDisplay as $photoObject)
        {
            $intarray = [];
            if($photoObject->isLikedByUser($this->user)){
                $intarray["Like"] = "coeurliked.png";
            } else {
                $intarray["Like"] = "coeur.png";
            }            
            $intarray["Likes"] = count($photoObject->getLikes());
            $intarray["Title"] = $photoObject->getTitle();
            $intarray["Description"] = $photoObject->getDescription();
            $intarray["Author"] = $photoObject->getAuthor();
            $intarray["FileName"] = $photoObject->getFileName();
            $intarray["id"] = $photoObject->getId();
            $allPhotosArray[] = $intarray;
        }
        return $allPhotosArray;

    }

}

?>