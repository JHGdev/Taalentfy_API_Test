<?php

namespace App\Entity;

use App\Repository\UserAnswersTestBRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserAnswersTestBRepository::class)
 */
class UserAnswersTestB
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="userAnswersTestB", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user_id;

    /**
     * @ORM\Column(type="smallint")
     */
    private $percent_answer_a;

    /**
     * @ORM\Column(type="smallint")
     */
    private $percent_answer_b;

    /**
     * @ORM\Column(type="smallint")
     */
    private $percent_answer_c;

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

    public function getPercentAnswerA(): ?int
    {
        return $this->percent_answer_a;
    }

    public function setPercentAnswerA(int $percent_answer_a): self
    {
        $this->percent_answer_a = $percent_answer_a;

        return $this;
    }

    public function getPercentAnswerB(): ?int
    {
        return $this->percent_answer_b;
    }

    public function setPercentAnswerB(int $percent_answer_b): self
    {
        $this->percent_answer_b = $percent_answer_b;

        return $this;
    }

    public function getPercentAnswerC(): ?int
    {
        return $this->percent_answer_c;
    }

    public function setPercentAnswerC(int $percent_answer_c): self
    {
        $this->percent_answer_c = $percent_answer_c;

        return $this;
    }
}
