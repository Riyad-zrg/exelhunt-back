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
use App\Repository\UserAnswerRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserAnswerRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(security: "is_granted('ROLE_USER')"),
        new Put(security: "is_granted('ROLE_USER')"),
        new Patch(security: "is_granted('ROLE_USER')"),
        new Delete(security: "is_granted('ROLE_ADMIN')"),
    ],
    normalizationContext: ['groups' => ['user_answer:read']],
    denormalizationContext: ['groups' => ['user_answer:write']]
)]
#[ApiFilter(SearchFilter::class, properties: [
    'puzzleAnswer.id' => 'exact',
    'isCorrect' => 'exact',
])]
#[ApiFilter(OrderFilter::class, properties: ['sendAt', 'id'], arguments: ['orderParameterName' => 'order'])]
class UserAnswer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['user_answer:read'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::JSON)]
    #[Groups(['user_answer:read', 'user_answer:write'])]
    private array $answerContent = [];

    #[ORM\Column]
    #[Groups(['user_answer:read', 'user_answer:write'])]
    private ?bool $isCorrect = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Gedmo\Timestampable]
    #[Groups(['user_answer:read', 'user_answer:write'])]
    private ?\DateTimeImmutable $sendAt = null;

    #[ORM\ManyToOne(inversedBy: 'userAnswers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $player = null;

    #[ORM\ManyToOne(inversedBy: 'userAnswers')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['user_answer:read', 'user_answer:write'])]
    #[ApiProperty(readable: true)]
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

    public function getAnswerContent(): array
    {
        return $this->answerContent;
    }

    public function setAnswerContent(array $answerContent): static
    {
        $this->answerContent = $answerContent;

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
