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

    #[ORM\OneToMany(mappedBy: 'system', targetEntity: Subscription::class)]
    private Collection $subscriptions;

    #[ORM\Column]
    private ?bool $active = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $info = null;

    #[ORM\ManyToOne(inversedBy: 'systems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $creator = null;

    #[ORM\OneToMany(mappedBy: 'system', targetEntity: Events::class)]
    private Collection $events;



    public function __construct()
    {

        $this->subscriptions = new ArrayCollection();
        $this->events = new ArrayCollection();


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

        /**
         * @return Collection<int, Subscription>
         */
        public function getSubscriptions(): Collection
        {
            return $this->subscriptions;
        }

        public function addSubscription(Subscription $subscription): static
        {
            if (!$this->subscriptions->contains($subscription)) {
                $this->subscriptions->add($subscription);
                $subscription->setSystem($this);
            }

            return $this;
        }

        public function removeSubscription(Subscription $subscription): static
        {
            if ($this->subscriptions->removeElement($subscription)) {
                // set the owning side to null (unless already changed)
                if ($subscription->getSystem() === $this) {
                    $subscription->setSystem(null);
                }
            }

            return $this;
        }

        public function isActive(): ?bool
        {
            return $this->active;
        }

        public function setActive(bool $active): static
        {
            $this->active = $active;

            return $this;
        }

        public function getInfo(): ?string
        {
            return $this->info;
        }

        public function setInfo(?string $info): static
        {
            $this->info = $info;

            return $this;
        }

        public function getCreator(): ?User
        {
            return $this->creator;
        }

        public function setCreator(?User $creator): static
        {
            $this->creator = $creator;

            return $this;
        }

        /**
         * @return Collection<int, Events>
         */
        public function getEvents(): Collection
        {
            return $this->events;
        }

        public function addEvent(Events $event): static
        {
            if (!$this->events->contains($event)) {
                $this->events->add($event);
                $event->setSystem($this);
            }

            return $this;
        }

        public function removeEvent(Events $event): static
        {
            if ($this->events->removeElement($event)) {
                // set the owning side to null (unless already changed)
                if ($event->getSystem() === $this) {
                    $event->setSystem(null);
                }
            }

            return $this;
        }

       

       
    

}
