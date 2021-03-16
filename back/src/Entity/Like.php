<?php

namespace App\Entity;

use App\Repository\LikeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LikeRepository::class)
 * @ORM\Table(name="`like`")
 */
class Like
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $likedAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="likes")
     */
    private $author;

    /**
     * @ORM\ManyToMany(targetEntity=BlogPost::class, inversedBy="likes")
     */
    private $blogpost;


    /**
     * @ORM\ManyToMany(targetEntity=RapidPost::class, inversedBy="likes")
     */
    private $rapidpost;

    public function __construct()
    {
        $this->likedAt = new \DateTime();
        $this->blogpost = new ArrayCollection();
        $this->rapidpost = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLikedAt(): ?\DateTimeInterface
    {
        return $this->likedAt;
    }

    public function setLikedAt(\DateTimeInterface $likedAt): self
    {
        $this->likedAt = $likedAt;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection|BlogPost[]
     */
    public function getBlogpost(): Collection
    {
        return $this->blogpost;
    }

    public function addBlogpost(BlogPost $blogpost): self
    {
        if (!$this->blogpost->contains($blogpost)) {
            $this->blogpost[] = $blogpost;
        }

        return $this;
    }

    public function removeBlogpost(BlogPost $blogpost): self
    {
        $this->blogpost->removeElement($blogpost);

        return $this;
    }


    /**
     * @return Collection|RapidPost[]
     */
    public function getRapidpost(): Collection
    {
        return $this->rapidpost;
    }

    public function addRapidpost(RapidPost $rapidpost): self
    {
        if (!$this->rapidpost->contains($rapidpost)) {
            $this->rapidpost[] = $rapidpost;
        }

        return $this;
    }

    public function removeRapidpost(RapidPost $rapidpost): self
    {
        $this->rapidpost->removeElement($rapidpost);

        return $this;
    }
}
