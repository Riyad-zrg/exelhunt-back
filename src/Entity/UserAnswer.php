<?php

namespace App\Entity;

use App\Repository\UserAnswerRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserAnswerRepository::class)]
class UserAnswer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::JSON)]
    private array $contentAnswerJSON = [];

    #[ORM\Column]
    private ?bool $isCorrect = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $sendAt = null;

    #[ORM\ManyToOne(inversedBy: 'userAnswers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $player = null;

    #[ORM\ManyToOne(inversedBy: 'userAnswers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Puzzle $puzzle = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getContentAnswerJSON(): array
    {
        return $this->contentAnswerJSON;
    }

    public function setContentAnswerJSON(array $contentAnswerJSON): static
    {
        $this->contentAnswerJSON = $contentAnswerJSON;

        return $this;
    }

    public function getPlayer(): ?User
    {
        return $this->player;
    }

    public function setPlayer(?User $player): static
    {
        $this->player = $player;

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
