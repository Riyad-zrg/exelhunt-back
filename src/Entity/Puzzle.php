<?php

namespace App\Entity;

use App\Repository\PuzzleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PuzzleRepository::class)]
class Puzzle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $question = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $media = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $hint = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTime $timeLimit = null;

    #[ORM\Column]
    private ?int $index = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTime $malus = null;

    #[ORM\Column(length: 3)]
    private ?string $typeAnswer = null;

    #[ORM\Column(type: Types::JSON)]
    private array $answerContent = [];

    #[ORM\ManyToOne(inversedBy: 'puzzles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Hunt $hunt = null;

    /**
     * @var Collection<int, Start>
     */
    #[ORM\OneToMany(targetEntity: Start::class, mappedBy: 'puzzle')]
    private Collection $starts;

    /**
     * @var Collection<int, UserAnswer>
     */
    #[ORM\OneToMany(targetEntity: UserAnswer::class, mappedBy: 'puzzle', orphanRemoval: true)]
    private Collection $userAnswers;

    public function __construct()
    {
        $this->starts = new ArrayCollection();
        $this->userAnswers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getMedia(): ?string
    {
        return $this->media;
    }

    public function setMedia(?string $media): static
    {
        $this->media = $media;

        return $this;
    }

    public function getHint(): ?string
    {
        return $this->hint;
    }

    public function setHint(?string $hint): static
    {
        $this->hint = $hint;

        return $this;
    }

    public function getTimeLimit(): ?\DateTime
    {
        return $this->timeLimit;
    }

    public function setTimeLimit(?\DateTime $timeLimit): static
    {
        $this->timeLimit = $timeLimit;

        return $this;
    }

    public function getIndex(): ?int
    {
        return $this->index;
    }

    public function setIndex(int $index): static
    {
        $this->index = $index;

        return $this;
    }

    public function getMalus(): ?\DateTime
    {
        return $this->malus;
    }

    public function setMalus(?\DateTime $malus): static
    {
        $this->malus = $malus;

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

    /**
     * @return Collection<int, Start>
     */
    public function getStarts(): Collection
    {
        return $this->starts;
    }

    public function addStart(Start $start): static
    {
        if (!$this->starts->contains($start)) {
            $this->starts->add($start);
            $start->setPuzzle($this);
        }

        return $this;
    }

    public function removeStart(Start $start): static
    {
        if ($this->starts->removeElement($start)) {
            // set the owning side to null (unless already changed)
            if ($start->getPuzzle() === $this) {
                $start->setPuzzle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserAnswer>
     */
    public function getUserAnswers(): Collection
    {
        return $this->userAnswers;
    }

    public function addUserAnswer(UserAnswer $userAnswer): static
    {
        if (!$this->userAnswers->contains($userAnswer)) {
            $this->userAnswers->add($userAnswer);
            $userAnswer->setPuzzle($this);
        }

        return $this;
    }

    public function removeUserAnswer(UserAnswer $userAnswer): static
    {
        if ($this->userAnswers->removeElement($userAnswer)) {
            // set the owning side to null (unless already changed)
            if ($userAnswer->getPuzzle() === $this) {
                $userAnswer->setPuzzle(null);
            }
        }

        return $this;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): static
    {
        $this->question = $question;

        return $this;
    }

    public function getTypeAnswer(): ?string
    {
        return $this->typeAnswer;
    }

    public function setTypeAnswer(string $typeAnswer): static
    {
        $this->typeAnswer = $typeAnswer;

        return $this;
    }

    public function getAnswerContent(): array
    {
        return $this->answerContent;
    }

    public function setAnswerContent(array $answerContent): static
    {
        $this->answerContent = $answerContent;

        return $this;
    }
}
