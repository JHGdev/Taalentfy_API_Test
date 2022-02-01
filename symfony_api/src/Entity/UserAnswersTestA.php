<?php

namespace App\Entity;

use App\Repository\UserAnswersTestARepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserAnswersTestARepository::class)
 */
class UserAnswersTestA
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="userAnswersTestA", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user_id;

    /**
     * @ORM\Column(type="smallint")
     */
    private $total_percent;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(User $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getTotalPercent(): ?int
    {
        return $this->total_percent;
    }

    public function setTotalPercent(int $total_percent): self
    {
        $this->total_percent = $total_percent;

        return $this;
    }
}
