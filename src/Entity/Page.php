<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use App\Repository\PageRepository;
use Gedmo\Mapping\Annotation\Slug;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[Entity(repositoryClass: PageRepository::class)]
class Page
{
    use TimestampableEntity;

    #[Id, GeneratedValue(strategy: 'AUTO')]
    #[Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[Column(type: Types::STRING, length: 255)]
    private ?string $title = '';

    #[Column(type: Types::STRING, length: 255, unique: true)]
    #[Slug(fields: ['title'])]
    private ?string $slug;

    #[Column(type: Types::TEXT)]
    private ?string $text = '';

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

    public function getSlug(): ?string
    {
        return $this->slug;
    }


    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }
}
