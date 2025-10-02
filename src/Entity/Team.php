<?php

namespace App\Entity;

use App\Repository\TeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TeamRepository::class)]
class Team
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 60)]
    private ?string $name = null;

    #[ORM\Column(length: 5000)]
    private ?string $avatar = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * @var Collection<int, Hunt>
     */
    #[ORM\OneToMany(targetEntity: Hunt::class, mappedBy: 'Team')]
    private Collection $hunts;

    /**
     * @var Collection<int, Membership>
     */
    #[ORM\OneToMany(targetEntity: Membership::class, mappedBy: 'Team')]
    private Collection $memberships;

    public function __construct()
    {
        $this->hunts = new ArrayCollection();
        $this->memberships = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): static
    {
        $this->avatar = $avatar;

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

    /**
     * @return Collection<int, Hunt>
     */
    public function getHunts(): Collection
    {
        return $this->hunts;
    }

    public function addHunt(Hunt $hunt): static
    {
        if (!$this->hunts->contains($hunt)) {
            $this->hunts->add($hunt);
            $hunt->setTeam($this);
        }

        return $this;
    }

    public function removeHunt(Hunt $hunt): static
    {
        if ($this->hunts->removeElement($hunt)) {
            // set the owning side to null (unless already changed)
            if ($hunt->getTeam() === $this) {
                $hunt->setTeam(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Membership>
     */
    public function getMemberships(): Collection
    {
        return $this->memberships;
    }

    public function addMembership(Membership $membership): static
    {
        if (!$this->memberships->contains($membership)) {
            $this->memberships->add($membership);
            $membership->setTeam($this);
        }

        return $this;
    }

    public function removeMembership(Membership $membership): static
    {
        if ($this->memberships->removeElement($membership)) {
            // set the owning side to null (unless already changed)
            if ($membership->getTeam() === $this) {
                $membership->setTeam(null);
            }
        }

        return $this;
    }
}
