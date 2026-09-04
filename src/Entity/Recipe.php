<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use App\Validator\BadRecette;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use PHPUnit\Framework\Attributes\Group;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Vich\UploaderBundle\Mapping\Annotation\UploadableField;


#[ORM\Entity(repositoryClass: RecipeRepository::class)]
#[UniqueEntity(fields:'title')]
#[UniqueEntity(fields:'slug')]
 #[Vich\Uploadable]
class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('recipe_index')]
    private ?int $id = null;

    #[ORM\Column(length: 200)]
    #[Assert\Length(min: 3)]
    #[BadRecette()]
    #[Groups(['recipe_show', 'recipe_new', 'recipe_index'])]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Assert\Regex('/^[a-z0-9]+(?:(?:-|_)+[a-z0-9]+)*$/', " Le slug est invalide ")]
    #[Groups(['recipe_show', 'recipe_new', 'recipe_index'])]
    private ?string $slug = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['recipe_show', 'recipe_new'])]
    private ?string $content = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    // #[Assert\Positive(message:'la dure doit etre positive')]
    // #[Assert\LessThan(value:'1140', message: 'la duree maximale est 24h')]
    #[Groups(['recipe_show', 'recipe_new', 'recipe_index'])]
    private ?int $duration = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updateAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'recipes')]
    #[Groups('recipe_show')]
    private ?Category $category = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[Assert\Image()]
    #[Vich\UploadableField(mapping:'recipes', fileNameProperty: 'image')]

    private ?File $imageFile = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

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

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeImmutable
    {
        return $this->updateAt;
    }

    public function setUpdateAt(\DateTimeImmutable $updateAt): static
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get the value of imageFile
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }


    public function setImageFile(File $imageFile) : static
    {
        $this->imageFile = $imageFile;

        return $this;
    }
}
