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
use App\Repository\PuzzleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PuzzleRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(security: "is_granted('ROLE_USER')"),
        new Put(security: "is_granted('ROLE_USER')"),
        new Patch(security: "is_granted('ROLE_USER')"),
        new Delete(security: "is_granted('ROLE_ADMIN')"),
    ],
    normalizationContext: ['groups' => ['puzzle:read']],
    denormalizationContext: ['groups' => ['puzzle:write']]
)]
#[ApiFilter(SearchFilter::class, properties: [
    'title' => 'partial',
    'content' => 'partial',
    'hunt.id' => 'exact',
])]
#[ApiFilter(OrderFilter::class, properties: ['index', 'title'], arguments: ['orderParameterName' => 'order'])]
class Puzzle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['puzzle:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Groups(['puzzle:read', 'puzzle:write', 'hunt:read', 'teamCreator:read', 'user_answer:read'])]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Groups(['puzzle:read', 'puzzle:write', 'hunt:read', 'teamCreator:read'])]
    private ?string $content = null;

    #[ORM\Column(length: 500, nullable: true)]
    #[Groups(['puzzle:read', 'puzzle:write', 'hunt:read', 'teamCreator:read'])]
    private ?string $hint = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    #[Groups(['puzzle:read', 'puzzle:write', 'hunt:read', 'teamCreator:read'])]
    private ?\DateTime $timeLimit = null;
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['puzzle:read', 'puzzle:write', 'hunt:read', 'teamCreator:read'])]
    private ?string $media = null;

    #[ORM\Column]
    #[Groups(['puzzle:read', 'puzzle:write', 'hunt:read', 'teamCreator:read', 'has_started:read', 'user_answer:read'])]
    private ?int $index = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    #[Groups(['puzzle:read', 'puzzle:write', 'hunt:read', 'teamCreator:read', 'user_answer:read'])]
    private ?\DateTime $malus = null;

    #[ORM\Column(length: 3)]
    #[Groups(['puzzle:read', 'puzzle:write', 'hunt:read', 'teamCreator:read', 'user_answer:read'])]
    private ?string $typeAnswer = null;

    #[ORM\Column(type: Types::JSON)]
    #[Groups(['puzzle:read', 'puzzle:write', 'hunt:read', 'teamCreator:read', 'user_answer:read'])]
    private array $answerContent = [];

    #[ORM\ManyToOne(inversedBy: 'puzzles')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['puzzle:read', 'puzzle:write', 'user_answer:read'])]
    #[ApiProperty(readable: true)]
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
        $this->hasStarteds = new ArrayCollection();
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

    public function setContent(?string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }
}
