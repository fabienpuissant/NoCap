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
        if ($this->user == null) {
            return $this->render("notConnected.html.twig");
        }


        if (!isset($this->apikey)) {
            return $this->render("notConnected.html.twig");
        }

        $allPhotosArray = $this->getArrayFromObjectsSorted("Mixte");


        return $this->render(
            "classement.html.twig",
            [
                'allPhotos' => $allPhotosArray,
                'FirstCategory' => 'Mixte',
                'SecondCategory' => 'Femme',
                'ThirdCategory' => 'Homme'
            ]
        );
    }



    /**
     * Select a category of photos
     * @Route("/classement/{categorie}", name = "select_category_classement")
     * @return Response
     */
    public function selectCategory($categorie)
    {
        if ($this->user == null) {
            return $this->render("notConnected.html.twig");
        }

        $allPhotosArray = $this->getArrayFromObjectsSorted($categorie);

        if ($categorie == 'Mixte') {
            return $this->render(
                "classement.html.twig",
                [
                    'allPhotos' => $allPhotosArray,
                    'FirstCategory' => 'Mixte',
                    'SecondCategory' => 'Femme',
                    'ThirdCategory' => 'Homme'
                ]
            );
        } else if ($categorie == 'Homme') {
            return $this->render(
                "classement.html.twig",
                [
                    'allPhotos' => $allPhotosArray,
                    'FirstCategory' => 'Homme',
                    'SecondCategory' => 'Femme',
                    'ThirdCategory' => 'Mixte'
                ]
            );
        } else {
            return $this->render(
                "classement.html.twig",
                [
                    'allPhotos' => $allPhotosArray,
                    'FirstCategory' => 'Femme',
                    'SecondCategory' => 'Mixte',
                    'ThirdCategory' => 'Homme'
                ]
            );
        }
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
    private function getArrayFromObjectsSorted(string $category)
    {
        $allPhotosArray = [];
        //The array with all photos sorted by decreasing order

        $allPhotosObjects = $this->photorepository->getPhotoByCategory($category);

        usort($allPhotosObjects, function ($a, $b) {
            if (count($a->getLikes()) == count($b->getLikes())) {
                return 0;
            }
            return (count($a->getLikes()) < count($b->getLikes())) ? -1 : 1;
        });

        $allPhotosObjects = array_reverse($allPhotosObjects);

        //Get the photos we need
        //p is the number of photos we display at the same time
        $p = 2;
        $photoToDisplay = [];
        $nbPhotoDisplayed = 10;
        for ($i = 0; $i < $nbPhotoDisplayed; $i++) {
            if (isset($allPhotosObjects[$i])) {
                $photoToDisplay[] = $allPhotosObjects[$i];
            }
        }

        foreach ($photoToDisplay as $photoObject) {
            $intarray = [];
            if ($photoObject->isLikedByUser($this->user)) {
                $intarray["Like"] = "http://nocap.ddns.net:8000/img/coeurliked.png";
            } else {
                $intarray["Like"] = "http://nocap.ddns.net:8000/img/coeur.png";
            }
            $intarray["Likes"] = count($photoObject->getLikes());
            $intarray["Title"] = $photoObject->getTitle();
            $intarray["Description"] = $photoObject->getDescription();
            $intarray["Author"] = $photoObject->getAuthor();
            $intarray["FileName"] = "http://nocap.ddns.net:8000/photos/" . $photoObject->getImageName();
            $intarray["id"] = $photoObject->getId();
            $allPhotosArray[] = $intarray;
        }
        return $allPhotosArray;
    }
}
