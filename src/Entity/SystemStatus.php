<?php

namespace App\Entity;

use App\Repository\SystemStatusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SystemStatusRepository::class)]
class SystemStatus
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column(length: 255)]
    private ?string $responsible_person = null;

    #[ORM\ManyToOne(inversedBy: 'systemStatuses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?System $system = null;

    #[ORM\ManyToOne(inversedBy: 'systemStatuses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Status $status = null;

    

    #[ORM\OneToMany(mappedBy: 'systemStatus', targetEntity: Subscription::class)]
    private Collection $subscriptions;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $info = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $maintenanceStart = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $maintenanceEnd = null;

    #[ORM\Column]
    private ?bool $isdeactive = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getResponsible_Person(): ?string
    {
        return $this->responsible_person;
    }

    public function setResponsible_Person(string $responsible_person): self
    {
        $this->responsible_person = $responsible_person;

        return $this;
    }

    public function getSystem(): ?System
    {
        return $this->system;
    }

    public function setSystem(?System $system): self
    {
        $this->system = $system;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): self
    {
        $this->status = $status;

        return $this;
    }

    

    /**
     * @return Collection<int, Subscription>
     */
    public function getSubscriptions(): Collection
    {
        return $this->subscriptions;
    }

   

    public function getInfo(): ?string
    {
        return $this->info;
    }

    public function setInfo(?string $info): self
    {
        $this->info = $info;

        return $this;
    }

    public function getMaintenanceStart(): ?\DateTimeInterface
    {
        return $this->maintenanceStart;
    }

    public function setMaintenanceStart(?\DateTimeInterface $maintenanceStart): self
    {
        $this->maintenanceStart = $maintenanceStart;

        return $this;
    }

    public function getMaintenanceEnd(): ?\DateTimeInterface
    {
        return $this->maintenanceEnd;
    }

    public function setMaintenanceEnd(?\DateTimeInterface $maintenanceEnd): self
    {
        $this->maintenanceEnd = $maintenanceEnd;

        return $this;
    }

    public function isIsdeactive(): ?bool
    {
        return $this->isdeactive;
    }

    public function setIsdeactive(bool $isdeactive): static
    {
        $this->isdeactive = $isdeactive;

        return $this;
    }
}
