<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VoteRepository")
 */
class Vote
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
    private $FileName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $UserVote;

    /**
     * @ORM\Column(type="datetime")
     */
    private $Date;

    public function __construct()
    {
        $this->Date =  \DateTime::createFromFormat("H:i:s", date("H:i:s"));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFileName(): ?string
    {
        return $this->FileName;
    }

    public function setFileName(string $FileName): self
    {
        $this->FileName = $FileName;

        return $this;
    }

    public function getUserVote(): ?string
    {
        return $this->UserVote;
    }

    public function setUserVote(string $UserVote): self
    {
        $this->UserVote = $UserVote;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->Date;
    }

    public function setDate(\DateTimeInterface $Date): self
    {
        $this->Date = $Date;

        return $this;
    }
}
