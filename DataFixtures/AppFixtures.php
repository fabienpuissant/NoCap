<?php

namespace App\DataFixtures;

use App\Entity\Photo;
use App\Entity\User;
use App\Entity\PhotoLike;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;


class AppFixtures extends Fixture
{

    public function load()
    {
        $manager = $this->getDoctrine()->getManager();
        $users = [];
        for($i = 0; $i < 10; $i++){
            $user = new User();
            $user->setApiKey(md5(microtime().rand()));
            $manager->persist($user);
            $users[] = $user;
        }
        

        for ($i = 0; $i < 20; $i++) {
            $post = new Photo();
            $post->setTitle('testTitre')
                ->setAuthor('testAuthor')
                ->setDescription('testDescription')
                ->setFileName('ygcu.png');
            $manager->persist($post);

            for($j = 0; $j<mt_rand(0, 9); $j++){
                $like = new PhotoLike();
                $like->setPhoto($post)
                    ->setUser($users[mt_rand(0, 9)]);
                $manager->persist($like);
            }
        }

        $manager->flush();
    }
}