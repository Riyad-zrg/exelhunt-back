<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\PuzzleAnswerRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PuzzleAnswerRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(security: "is_granted('ROLE_USER')"),
        new Put(security: "is_granted('ROLE_USER')"),
        new Patch(security: "is_granted('ROLE_USER')"),
        new Delete(security: "is_granted('ROLE_ADMIN')"),
    ],
    normalizationContext: ['groups' => ['puzzle_answer:read']],
    denormalizationContext: ['groups' => ['puzzle_answer:write']]
)]
#[ApiFilter(SearchFilter::class, properties: [
    'type' => 'partial',
    'puzzle.id' => 'exact',
])]
#[ApiFilter(OrderFilter::class, properties: ['id', 'type'], arguments: ['orderParameterName' => 'order'])]
class PuzzleAnswer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['puzzle_answer:read', 'puzzle:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 15)]
    #[Groups(['puzzle_answer:read', 'puzzle_answer:write', 'puzzle:read', 'puzzle:write'])]
    private ?string $type = null;

    #[ORM\Column]
    #[Groups(['puzzle_answer:read', 'puzzle_answer:write', 'puzzle:read', 'puzzle:write'])]
    private array $content = [];
    #[ORM\ManyToOne(inversedBy: 'answers')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['puzzle_answer:read', 'puzzle_answer:write'])]
    private ?Puzzle $puzzle = null;
    #[ORM\OneToMany(targetEntity: UserAnswer::class, mappedBy: 'puzzleAnswer', cascade: ['persist', 'remove'])]
    #[Groups(['puzzle_answer:read'])]
    private Collection $userAnswers;

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

    public function getContent(): array
    {
        return $this->content;
    }

    public function setContent(array $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getPuzzle(): ?Puzzle
    {
        return $this->puzzle;
    }

    public function setPuzzle(?Puzzle $puzzle): static
    {
        $this->puzzle = $puzzle;

        return $this;
    }

    public function addUserAnswer(UserAnswer $answer): static
    {
        if (!$this->userAnswers->contains($answer)) {
            $this->userAnswers->add($answer);
            $answer->setPuzzleAnswer($this);
        }

        return $this;
    }
}
