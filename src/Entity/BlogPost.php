<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\BlogPostRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Table(name: 'blog_post')]
#[ORM\Entity(repositoryClass: BlogPostRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(),
        new Patch(),
        new Delete(),
    ],
    normalizationContext: [
        'groups' => ['post:read']
    ],
    denormalizationContext: [
        'groups' => ['post:write']
    ]
)]
class BlogPost
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    #[Groups(['post:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['post:read', 'post:write'])]
    #[Assert\NotBlank]
    private ?string $title = null;

    #[ORM\Column(type: 'text')]
    #[Groups(['post:read', 'post:write'])]
    #[Assert\NotBlank]
    private ?string $content = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'posts')]
    #[Groups(['post:read', 'post:write'])]
    #[Assert\NotBlank]
    private ?User $author = null;

    #[ORM\Column]
    #[Groups(['post:read', 'post:write'])]
    #[Assert\NotBlank]
    private ?DateTime $publishedAt;

    #[ORM\Column(nullable: true)]
    #[Groups(['post:read', 'post:write'])]
    private ?DateTime $modifiedAt = null;

    public function __construct()
    {
        $this->publishedAt = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getPublishedAt(): ?DateTime
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(DateTime $publishedAt): static
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function getModifiedAt(): ?DateTime
    {
        return $this->modifiedAt;
    }

    public function setModifiedAt(?DateTime $modifiedAt): static
    {
        $this->modifiedAt = $modifiedAt;

        return $this;
    }

    #[ORM\PreUpdate]
    public function preUpdate(): void
    {
        $this->modifiedAt = new DateTime();
    }
}
