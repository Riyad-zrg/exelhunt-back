<?php

namespace App\Entity;

use App\Repository\PuzzleAnswerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PuzzleAnswerRepository::class)]
class PuzzleAnswer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 15)]
    private ?string $type = null;

    #[ORM\Column]
    private array $content = [];
    #[ORM\ManyToOne(inversedBy: 'answers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Puzzle $puzzle = null;

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
}
