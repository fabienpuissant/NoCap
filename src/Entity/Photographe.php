<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Serializable;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class Photographe implements Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Confirmation_token;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_Confirmed = false;


    public function __construct()
    {
        $this->Confirmation_token = $this->initConfirmationToken();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return htmlentities($this->Email);
    }

    public function setEmail(string $Email): self
    {
        $this->Email = htmlentities($Email);

        return $this;
    }

    public function getPassword(): ?string
    {
        return htmlentities($this->Password);
    }

    public function setPassword(string $Password): self
    {
        $this->Password = htmlentities($Password);

        return $this;
    }

    public function getConfirmationToken(): ?string
    {
        return $this->Confirmation_token;
    }

    public function setConfirmationToken(string $Confirmation_token): self
    {
        $this->Confirmation_token = $Confirmation_token;

        return $this;
    }

    public function getIsConfirmed(): ?bool
    {
        return $this->is_Confirmed;
    }

    public function setIsConfirmed(bool $is_Confirmed): self
    {
        $this->is_Confirmed = $is_Confirmed;

        return $this;
    }

    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {
        
    }

    public function serialize()
    {
        return serialize([
            $this->id,
            $this->Email,
            $this->Password
        ]);
    }

    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->Email,
            $this->Password) = unserialize($serialized, ["allowed_classes" => false]);
    }

    private function initConfirmationToken() {
            $length = 16;
            $alphabet = "0123456789azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN";
            return substr(str_shuffle(str_repeat($alphabet, $length)), 0 , $length);
    }

}
