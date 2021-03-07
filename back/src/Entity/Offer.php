<?php

namespace App\Entity;

use App\Repository\OfferRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OfferRepository::class)
 */
class Offer
{
    const TYPE_SALE = 'sale';
    const TYPE_PURCHASE = 'purchase';
    const TYPE_SERVICE  = 'service';
    const TYPE_SEARCH = 'search';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string", columnDefinition="ENUM('sale', 'purchase', 'service', 'search')", length=255)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="offers")
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity=OfferComment::class, mappedBy="offer", orphanRemoval=true)
     */
    private $offerComments;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->offerComments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
       if(!in_array($type, array(self::TYPE_PURCHASE, self::TYPE_SALE, self::TYPE_SERVICE, self::TYPE_SEARCH))) {
            throw new \InvalidArgumentException("Type Invalide");
        }
        $this->type = $type;
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
     * @return Collection|OfferComment[]
     */
    public function getOfferComments(): Collection
    {
        return $this->offerComments;
    }

    public function addOfferComment(OfferComment $offerComment): self
    {
        if (!$this->offerComments->contains($offerComment)) {
            $this->offerComments[] = $offerComment;
            $offerComment->setOffer($this);
        }

        return $this;
    }

    public function removeOfferComment(OfferComment $offerComment): self
    {
        if ($this->offerComments->removeElement($offerComment)) {
            // set the owning side to null (unless already changed)
            if ($offerComment->getOffer() === $this) {
                $offerComment->setOffer(null);
            }
        }

        return $this;
    }
}
