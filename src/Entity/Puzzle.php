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

    #[ORM\Column(length: 1000)]
    private ?string $content = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $hint = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTime $timeLimit = null;

    #[ORM\Column]
    private ?int $index = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTime $malus = null;

    #[ORM\ManyToOne(inversedBy: 'puzzles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Hunt $hunt = null;

    /**
     * @var Collection<int, HasStarted>
     */
    #[ORM\OneToMany(targetEntity: HasStarted::class, mappedBy: 'puzzle')]
    private Collection $hasStarteds;

    /**
     * @var Collection<int, PuzzleAnswer>
     */
    #[ORM\OneToMany(targetEntity: PuzzleAnswer::class, mappedBy: 'puzzle', cascade: ['persist', 'remove'])]
    private Collection $answers;

    public function __construct()
    {
        $this->hasStarteds = new ArrayCollection();
        $this->answers = new ArrayCollection();
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

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
     * @return Collection<int, HasStarted>
     */
    public function getHasStarteds(): Collection
    {
        return $this->hasStarteds;
    }

    public function addHasStarted(HasStarted $hasStarted): static
    {
        if (!$this->hasStarteds->contains($hasStarted)) {
            $this->hasStarteds->add($hasStarted);
            $hasStarted->setPuzzle($this);
        }

        return $this;
    }

    public function removeHasStarted(HasStarted $hasStarted): static
    {
        if ($this->hasStarteds->removeElement($hasStarted)) {
            // set the owning side to null (unless already changed)
            if ($hasStarted->getPuzzle() === $this) {
                $hasStarted->setPuzzle(null);
            }
        }

        return $this;
    }

    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(PuzzleAnswer $answer): static
    {
        if (!$this->answers->contains($answer)) {
            $this->answers->add($answer);
            $answer->setPuzzle($this);
        }
        return $this;
    }

    public function removeAnswer(PuzzleAnswer $answer): static
    {
        if ($this->answers->removeElement($answer)) {
            if ($answer->getPuzzle() === $this) {
                $answer->setPuzzle(null);
            }
        }
        return $this;
    }
}
