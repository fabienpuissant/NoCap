<?php

namespace App\Entity;

use \App\Entity\User;


class Message
{

    private $phoneNumber;

    private $name;

    private $firstName;

    private $lien;
    
    private $message;

    public function __construct(String $phoneNumber, User $user, String $lien)
    {
        $this->phoneNumber = $phoneNumber;
        $this->name = $user->getNom();
        $this->firstname = $user->getPrenom();
        $this->lien = $lien;
        $this->message = $message;
    }

    public function sendSMS()
    {
        
    }



}