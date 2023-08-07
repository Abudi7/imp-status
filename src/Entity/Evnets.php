<?php

namespace App\Entity;

use App\Repository\EvnetsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EvnetsRepository::class)]
class Evnets
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $startevent = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $endevent = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getStartevent(): ?\DateTimeInterface
    {
        return $this->startevent;
    }

    public function setStartevent(\DateTimeInterface $startevent): static
    {
        $this->startevent = $startevent;

        return $this;
    }

    public function getEndevent(): ?\DateTimeInterface
    {
        return $this->endevent;
    }

    public function setEndevent(?\DateTimeInterface $endevent): static
    {
        $this->endevent = $endevent;

        return $this;
    }
}
