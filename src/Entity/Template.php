<?php

namespace App\Entity;

use App\Repository\TemplateRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TemplateRepository::class)]
class Template
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $maintenance = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $incident = null;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMaintenance(): ?string
    {
        return $this->maintenance;
    }

    public function setMaintenance(?string $maintenance): self
    {
        $this->maintenance = $maintenance;

        return $this;
    }

    public function getIncident(): ?string
    {
        return $this->incident;
    }

    public function setIncident(?string $incident): self
    {
        $this->incident = $incident;

        return $this;
    }

}
