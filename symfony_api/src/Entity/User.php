<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email."
     * )
     * @Assert\NotNull
     * @Assert\NotBlank(
     *     message = "The email cannot be blank."
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull
     * @Assert\NotBlank(
     *     message = "The fisrtname cannot be blank."
     * )
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull
     * @Assert\NotBlank(
     *     message = "The firstname cannot be blank."
     * )
     */
    private $lastname;

    /**
     * @ORM\Column(type="bigint", columnDefinition="BIGINT")
     * @Assert\NotNull
     * @Assert\NotBlank(
     *     message = "The birth date cannot be blank."
     * )
     */
    private $birth_date;

    /**
     * @ORM\Column(type="bigint", columnDefinition="BIGINT")
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

    /**
     * @ORM\OneToOne(targetEntity=UserAnswersTestA::class, mappedBy="user_id", cascade={"persist", "remove"})
     */
    private $userAnswersTestA;

    /**
     * @ORM\OneToOne(targetEntity=UserAnswersTestB::class, mappedBy="user_id", cascade={"persist", "remove"})
     */
    private $userAnswersTestB;

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

    public function getBirthDate(): ?int
    {
        return $this->birth_date;
    }

    public function setBirthDate(int $birth_date): self
    {
        $this->birth_date = $birth_date;

        return $this;
    }

    public function getCreated(): ?int
    {
        return $this->created;
    }

    public function setCreated(int $created): self
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

    public function getUserAnswersTestA(): ?UserAnswersTestA
    {
        return $this->userAnswersTestA;
    }

    public function setUserAnswersTestA(UserAnswersTestA $userAnswersTestA): self
    {
        // set the owning side of the relation if necessary
        if ($userAnswersTestA->getUserId() !== $this) {
            $userAnswersTestA->setUserId($this);
        }

        $this->userAnswersTestA = $userAnswersTestA;

        return $this;
    }

    public function getUserAnswersTestB(): ?UserAnswersTestB
    {
        return $this->userAnswersTestB;
    }

    public function setUserAnswersTestB(UserAnswersTestB $userAnswersTestB): self
    {
        // set the owning side of the relation if necessary
        if ($userAnswersTestB->getUserId() !== $this) {
            $userAnswersTestB->setUserId($this);
        }

        $this->userAnswersTestB = $userAnswersTestB;

        return $this;
    }
}
