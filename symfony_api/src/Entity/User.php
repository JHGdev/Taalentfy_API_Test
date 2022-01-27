<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User
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
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastname;

    /**
     * @ORM\Column(type="datetime", columnDefinition="TIMESTAMP")
     */
    private $birth_date;

    /**
     * @ORM\Column(type="datetime", columnDefinition="TIMESTAMP DEFAULT CURRENT_TIMESTAMP")
     */
    private $created;

    /**
     * @ORM\OneToOne(targetEntity=LaboralSectorAssignments::class, mappedBy="user_id", cascade={"persist", "remove"})
     */
    private $yes;

    /**
     * @ORM\OneToMany(targetEntity=KnowledgeAssignments::class, mappedBy="user_id")
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birth_date;
    }

    public function setBirthDate(\DateTimeInterface $birth_date): self
    {
        $this->birth_date = $birth_date;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getYes(): ?LaboralSectorAssignments
    {
        return $this->yes;
    }

    public function setYes(LaboralSectorAssignments $yes): self
    {
        // set the owning side of the relation if necessary
        if ($yes->getUserId() !== $this) {
            $yes->setUserId($this);
        }

        $this->yes = $yes;

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
            $knowledgeAssignment->setUserId($this);
        }

        return $this;
    }

    public function removeKnowledgeAssignment(KnowledgeAssignments $knowledgeAssignment): self
    {
        if ($this->knowledgeAssignments->removeElement($knowledgeAssignment)) {
            // set the owning side to null (unless already changed)
            if ($knowledgeAssignment->getUserId() === $this) {
                $knowledgeAssignment->setUserId(null);
            }
        }

        return $this;
    }
}
