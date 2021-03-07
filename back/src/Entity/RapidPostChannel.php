<?php

namespace App\Entity;

use App\Repository\RapidPostChannelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RapidPostChannelRepository::class)
 */
class RapidPostChannel
{
    const TYPE_MANUAL = 'manual';
    const TYPE_AUTO = 'auto';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $logo;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string", columnDefinition="ENUM('manual', 'auto')", length=255)
     */
    private $type;

    /**
     * @ORM\ManyToMany(targetEntity=RapidPost::class, mappedBy="channels")
     */
    private $rapidPosts;

    public function __construct()
    {
        $this->rapidPosts = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->type = 'auto';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;

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
        if(!in_array($type, array(self::TYPE_AUTO, self::TYPE_MANUAL))) {
            throw new \InvalidArgumentException("Type Invalide");
        }
        $this->type = $type;
        return $this;
    }

    /**
     * @return Collection|RapidPost[]
     */
    public function getRapidPosts(): Collection
    {
        return $this->rapidPosts;
    }

    public function addRapidPost(RapidPost $rapidPost): self
    {
        if (!$this->rapidPosts->contains($rapidPost)) {
            $this->rapidPosts[] = $rapidPost;
            $rapidPost->addChannel($this);
        }

        return $this;
    }

    public function removeRapidPost(RapidPost $rapidPost): self
    {
        if ($this->rapidPosts->removeElement($rapidPost)) {
            $rapidPost->removeChannel($this);
        }

        return $this;
    }
}
