<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\TeamPlayerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TeamPlayerRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(security: "is_granted('ROLE_USER')"),
        new Put(security: "is_granted('ROLE_USER')"),
        new Patch(security: "is_granted('ROLE_USER')"),
        new Delete(security: "is_granted('ROLE_ADMIN')"),
    ],
    normalizationContext: ['groups' => ['teamPlayer:read']],
    denormalizationContext: ['groups' => ['teamPlayer:write']]
)]
#[ApiFilter(SearchFilter::class, properties: ['code' => 'exact', 'teamPlayer.id' => 'exact'])]
#[ApiFilter(OrderFilter::class, properties: ['createdAt', 'expireAt', 'code'], arguments: ['orderParameterName' => 'order'])]
class TeamPlayer extends Team
{
    #[ORM\Column]
    #[Groups('teamPlayer:read', 'team:write')]
    private ?int $nbPlayers = null;

    #[ORM\Column]
    #[Groups('teamPlayer:read', 'team:write')]
    private ?bool $isPublic = null;

    #[ORM\OneToOne(mappedBy: 'teamPlayer', cascade: ['persist', 'remove'])]
    #[Groups('teamPlayer:read')]
    #[ApiProperty(readable: true)]
    private ?Code $code = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    #[Groups('teamPlayer:read', 'teamPlayer:write')]
    private ?\DateTime $teamGlobalTime = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    #[Groups('teamPlayer:read', 'teamPlayer:write')]
    private ?\DateTime $averageGlobalTime = null;

    /**
     * @var Collection<int, Participation>
     */
    #[ORM\OneToMany(targetEntity: Participation::class, mappedBy: 'teamPlayer')]
    #[Groups('teamPlayer:read')]
    #[ApiProperty(readable: true)]
    private Collection $participations;

    #[ORM\ManyToOne(inversedBy: 'teamPlayers')]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups('teamPlayer:read')]
    #[ApiProperty(readable: true)]
    private ?Hunt $hunt = null;

    public function __construct()
    {
        parent::__construct();
        $this->participations = new ArrayCollection();
    }

    public function getNbPlayers(): ?int
    {
        return $this->nbPlayers;
    }

    public function setNbPlayers(int $nbPlayers): static
    {
        $this->nbPlayers = $nbPlayers;

        return $this;
    }

    public function isPublic(): ?bool
    {
        return $this->isPublic;
    }

    public function setIsPublic(bool $isPublic): static
    {
        $this->isPublic = $isPublic;

        return $this;
    }

    public function getCode(): ?Code
    {
        return $this->code;
    }

    public function setCode(?Code $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getTeamGlobalTime(): ?\DateTime
    {
        return $this->teamGlobalTime;
    }

    public function setTeamGlobalTime(?\DateTime $teamGlobalTime): static
    {
        $this->teamGlobalTime = $teamGlobalTime;

        return $this;
    }

    public function getAverageGlobalTime(): ?\DateTime
    {
        return $this->averageGlobalTime;
    }

    public function setAverageGlobalTime(?\DateTime $averageGlobalTime): static
    {
        $this->averageGlobalTime = $averageGlobalTime;

        return $this;
    }

    /**
     * @return Collection<int, Participation>
     */
    public function getParticipations(): Collection
    {
        return $this->participations;
    }

    public function addParticipation(Participation $participation): static
    {
        if (!$this->participations->contains($participation)) {
            $this->participations->add($participation);
            $participation->setTeamPlayer($this);
        }

        return $this;
    }

    public function removeParticipation(Participation $participation): static
    {
        if ($this->participations->removeElement($participation)) {
            // set the owning side to null (unless already changed)
            if ($participation->getTeamPlayer() === $this) {
                $participation->setTeamPlayer(null);
            }
        }

        return $this;
    }

    public function getHunt(): ?Hunt
    {
        return $this->hunt;
    }

    public function setHunt(?Hunt $hunt): static
    {
        $this->hunt = $hunt;

        return $this;
    }
}
