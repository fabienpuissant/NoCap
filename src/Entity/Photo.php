<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PhotoRepository")
 */
class Photo
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
    private $Title = '';

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Author = '';

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Description = '';

    /**
     * @ORM\Column(type="datetime")
     */
    private $Date;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $FileName;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PhotoLike", mappedBy="photo", orphanRemoval=true)
     */
    private $likes;


    public function __construct()
    {
        $this->Date =  \DateTime::createFromFormat("H:i:s", date("H:i:s"));
        $this->likes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->Title;
    }

    public function setTitle(string $Title): self
    {
        $this->Title = $Title;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->Author;
    }

    public function setAuthor(string $Author): self
    {
        $this->Author = $Author;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): self
    {
        $this->Description = $Description;

        return $this;
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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->Date;
    }

    public function setDate(\DateTimeInterface $Date): self
    {
        $this->Date = $Date;

        return $this;
    }

    /**
     * @return Collection|PhotoLike[]
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(PhotoLike $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes[] = $like;
            $like->setPhoto($this);
        }

        return $this;
    }

    public function removeLike(PhotoLike $like): self
    {
        if ($this->likes->contains($like)) {
            $this->likes->removeElement($like);
            // set the owning side to null (unless already changed)
            if ($like->getPhoto() === $this) {
                $like->setPhoto(null);
            }
        }

        return $this;
    }

        /**
     * Return true if the photo is liked by the user
     * @param User user to search
     * @return bool true if the user has liked the photo
     */
    public function isLikedByUser($user){
        foreach($this->likes as $like){
            if($like->getUser()->getApiKey() == $user->getApiKey()){
                return true;
            }
        }
        return false;
    }

}
