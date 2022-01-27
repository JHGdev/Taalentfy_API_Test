<?php

namespace App\Entity;

use App\Repository\KnowledgeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=KnowledgeRepository::class)
 */
class Knowledge
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=KnowledgeAssignments::class, mappedBy="knowledge_id")
     */
    private $knowledgeAssignments;

    public function __construct()
    {
        $this->knowledgeAssignments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|KnowledgeAssignments[]
     */
    public function getKnowledgeAssignments(): Collection
    {
        return $this->knowledgeAssignments;
    }

    public function addKnowledgeAssignment(KnowledgeAssignments $knowledgeAssignment): self
    {
        if (!$this->knowledgeAssignments->contains($knowledgeAssignment)) {
            $this->knowledgeAssignments[] = $knowledgeAssignment;
            $knowledgeAssignment->setKnowledgeId($this);
        }

        return $this;
    }

    public function removeKnowledgeAssignment(KnowledgeAssignments $knowledgeAssignment): self
    {
        if ($this->knowledgeAssignments->removeElement($knowledgeAssignment)) {
            // set the owning side to null (unless already changed)
            if ($knowledgeAssignment->getKnowledgeId() === $this) {
                $knowledgeAssignment->setKnowledgeId(null);
            }
        }

        return $this;
    }
}
