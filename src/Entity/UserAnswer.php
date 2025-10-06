<?php

namespace App\Entity;

use App\Repository\UserAnswerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserAnswerRepository::class)]
class UserAnswer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private array $content = [];

    #[ORM\Column]
    private ?bool $isCorrect = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $sendAt = null;
    #[ORM\ManyToOne(inversedBy: 'userAnswers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?PuzzleAnswer $puzzleAnswer = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function isCorrect(): ?bool
    {
        return $this->isCorrect;
    }

    public function setIsCorrect(bool $isCorrect): static
    {
        $this->isCorrect = $isCorrect;

        return $this;
    }

    public function getSendAt(): ?\DateTimeImmutable
    {
        return $this->sendAt;
    }

    public function setSendAt(\DateTimeImmutable $sendAt): static
    {
        $this->sendAt = $sendAt;

        return $this;
    }
    public function getPuzzleAnswer(): ?PuzzleAnswer
    {
        return $this->puzzleAnswer;
    }

    public function setPuzzleAnswer(?PuzzleAnswer $puzzleAnswer): static
    {
        $this->puzzleAnswer = $puzzleAnswer;
        return $this;
    }
}
