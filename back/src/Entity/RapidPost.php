<?php

namespace App\Entity;

use App\Repository\RapidPostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RapidPostRepository::class)
 */
class RapidPost
{
    const TYPE_INITIAL = 'initial';
    const TYPE_RESPONSE = 'response';
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
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
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="rapidPosts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\ManyToMany(targetEntity=RapidPostChannel::class, inversedBy="rapidPosts")
     */
    private $channels;

    /**
     * @ORM\Column(type="string", columnDefinition="ENUM('initial', 'response')", length=255)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=RapidPost::class, inversedBy="rapidPosts")
     */
    private $initialPost;

    /**
     * @ORM\OneToMany(targetEntity=RapidPost::class, mappedBy="initialPost")
     */
    private $rapidPosts;

    /**
     * @ORM\ManyToMany(targetEntity=Like::class, mappedBy="rapidpost")
     */
    private $likes;


    public function __construct()
    {
        $this->channels = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->rapidPosts = new ArrayCollection();
        $this->type = 'initial';
        $this->likes = new ArrayCollection();
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
     * @return Collection|RapidPostChannel[]
     */
    public function getChannels(): Collection
    {
        return $this->channels;
    }

    public function addChannel(RapidPostChannel $channel): self
    {
        if (!$this->channels->contains($channel)) {
            $this->channels[] = $channel;
        }

        return $this;
    }

    public function removeChannel(RapidPostChannel $channel): self
    {
        $this->channels->removeElement($channel);

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        if(!in_array($type, array(self::TYPE_INITIAL, self::TYPE_RESPONSE))) {
            throw new \InvalidArgumentException("Type Invalide");
        }
        $this->type = $type;
        return $this;
    }

    public function getInitialPost(): ?self
    {
        return $this->initialPost;
    }

    public function setInitialPost(?self $initialPost): self
    {
        $this->initialPost = $initialPost;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getRapidPosts(): Collection
    {
        return $this->rapidPosts;
    }

    public function addRapidPost(self $rapidPost): self
    {
        if (!$this->rapidPosts->contains($rapidPost)) {
            $this->rapidPosts[] = $rapidPost;
            $rapidPost->setInitialPost($this);
        }

        return $this;
    }

    public function removeRapidPost(self $rapidPost): self
    {
        if ($this->rapidPosts->removeElement($rapidPost)) {
            // set the owning side to null (unless already changed)
            if ($rapidPost->getInitialPost() === $this) {
                $rapidPost->setInitialPost(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Like[]
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(Like $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes[] = $like;
            $like->addRapidpost($this);
        }

        return $this;
    }

    public function removeLike(Like $like): self
    {
        if ($this->likes->removeElement($like)) {
            $like->removeRapidpost($this);
        }

        return $this;
    }
}
