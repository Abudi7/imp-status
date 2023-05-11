<?php

namespace App\Entity;

use App\Repository\SystemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SystemRepository::class)]
class System
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\OneToMany(mappedBy: 'system', targetEntity: SystemStatus::class)]
    private Collection $systemStatuses;

    public function __construct()
    {
        $this->systemStatuses = new ArrayCollection();
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection<int, SystemStatus>
     */
    public function getSystemStatuses(): Collection
    {
        return $this->systemStatuses;
    }

    public function addSystemStatus(SystemStatus $systemStatus): self
    {
        if (!$this->systemStatuses->contains($systemStatus)) {
            $this->systemStatuses->add($systemStatus);
            $systemStatus->setSystem($this);
        }

        return $this;
    }

    public function removeSystemStatus(SystemStatus $systemStatus): self
    {
        if ($this->systemStatuses->removeElement($systemStatus)) {
            // set the owning side to null (unless already changed)
            if ($systemStatus->getSystem() === $this) {
                $systemStatus->setSystem(null);
            }
        }

        return $this;
    }
   
        // ...
        
        public function __toString(): string
        {
            return $this->name; // assuming the name property is a string
        }
        
        // ...
    

}
