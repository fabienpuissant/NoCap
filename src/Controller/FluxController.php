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

class FluxController extends AbstractController
{

    private $photorepository;
    private $userrepository;
    private $session;

    public function __construct(PhotoRepository $photorepository, SessionInterface $session, UserRepository $userrepository) 
    {
        $this->session = $session;
        $this->photorepository = $photorepository;
        $this->userrepository = $userrepository;
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

        $apikey = $this->session->get('user');

        $user = $this->userrepository->findUserByApiKey($apikey);

    
        $allPhotosArray = [];
        $allPhotosObjects = $this->photorepository->getAllPhotos();

        foreach($allPhotosObjects as $photoObject)
        {
            $intarray = [];
            if($photoObject->isLikedByUser($user)){
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

        return $this->render("flux.html.twig",
                            ['allPhotos' => $allPhotosArray]);
    }

    /**
     * Like or unlike a photo
     * @Route("/flux/{id}/like", name = "post_like")
     * @param integer id of photo
     * @param PostLikeRepository
     * @return Response
     */
    public function like($id, PhotoLikeRepository $repository){
        $apikey = $this->session->get('user');
        $user = $this->userrepository->findUserByApiKey($apikey);
        $entityManager = $this->getDoctrine()->getManager();
        $photo = $this->photorepository->find($id);
        

        //Premier cas, l'utilisateur n'est pas connecté
        if(!$user) return $this->json([
            'code' => 403,
            'message' => 'Unauthorized'
        ], 403);

        //var_dump($photo->isLikedByUser($user));   

        
        //Deuxieme cas, l'utilisateur supprime son like
        if($photo->isLikedByUser($user)) {
            $like = $repository->findOneBy([
                'photo' => $photo,
                'user' => $user
            ]);
            $photo->removeLike($like);
            $entityManager->remove($like);
            
            $entityManager->flush();

            return $this->json([
                'code' => 200,
                'message' => 'like bien supprimé',
                'likes' => $repository->count(['photo' => $photo])
            ], 200);
        }

        //troisieme cas, l'utilisateur ajoute un like
        $like = new PhotoLike();
        $like->setPhoto($photo)->setUser($user);
        $entityManager->persist($like);
        $photo->addLike($like);
        $entityManager->flush();

        return $this->json([
            'code' => 200,
            'message' => 'like bien ajouté',
            'likes' => $repository->count(['photo' => $photo])
        ], 200);




    }


}


?>