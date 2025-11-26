<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\TeamCreatorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TeamCreatorRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['teamCreator:read']],
    denormalizationContext: ['groups' => ['teamCreator:write']]
)]
class TeamCreator extends Team
{
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Gedmo\Timestampable]
    #[Groups(['teamCreator:read', 'hunt:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * @var Collection<int, Hunt>
     */
    #[ORM\OneToMany(targetEntity: Hunt::class, mappedBy: 'createdBy')]
    #[Groups(['teamCreator:read'])]
    #[ApiProperty(readable: true)]
    private Collection $hunts;

    public function __construct()
    {
        parent::__construct();
        $this->hunts = new ArrayCollection();
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
            $hunt->setCreatedBy($this);
        }

        return $this;
    }

    public function removeHunt(Hunt $hunt): static
    {
        if ($this->hunts->removeElement($hunt)) {
            if ($hunt->getCreatedBy() === $this) {
                $hunt->setCreatedBy(null);
            }
        }

        return $this;
    }
}
