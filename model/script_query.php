<?php

use \App\Entity\Photo;

use \App\Repository\PhotoRepository;

$categorie = htmlentities($_POST["categorie"]);

$repository = new PhotoRepository();

$array = $repository->getPhotoByCategory($categorie);


die(json_encode($array));

