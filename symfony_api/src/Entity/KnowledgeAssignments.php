<?php

namespace App\Entity;

use App\Repository\KnowledgeAssignmentsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=KnowledgeAssignmentsRepository::class)
 */
class KnowledgeAssignments
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="knowledgeAssignments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user_id;

    /**
     * @ORM\ManyToOne(targetEntity=Knowledge::class, inversedBy="knowledgeAssignments")
     */
    private $knowledge_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getKnowledgeId(): ?Knowledge
    {
        return $this->knowledge_id;
    }

    public function setKnowledgeId(?Knowledge $knowledge_id): self
    {
        $this->knowledge_id = $knowledge_id;

        return $this;
    }
}
