<?php

namespace App\Entity;

use App\Repository\SubscriptionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SubscriptionRepository::class)]
class Subscription
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $isSubscribed = null;

    #[ORM\ManyToOne(inversedBy: 'subscriptions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?SystemStatus $systemStatus = null;

    #[ORM\ManyToOne(inversedBy: 'subscriptions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isIsSubscribed(): ?bool
    {
        return $this->isSubscribed;
    }

    public function setIsSubscribed(bool $isSubscribed): self
    {
        $this->isSubscribed = $isSubscribed;

        return $this;
    }

    public function getSystemStatus(): ?SystemStatus
    {
        return $this->systemStatus;
    }

    public function setSystemStatus(?SystemStatus $systemStatus): self
    {
        $this->systemStatus = $systemStatus;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
