<?php

namespace App\Entity;

use App\Repository\OfferRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OfferRepository::class)
 */
class Offer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="smallint")
     */
    private $status;

    /**
     * @ORM\Column(type="bigint")
     */
    private $incorporation_date;

    /**
     * @ORM\OneToOne(targetEntity=LaboralSectorOfferAssignments::class, mappedBy="offer_id", cascade={"persist", "remove"})
     */
    private $laboralSectorOfferAssignments;

    /**
     * @ORM\OneToMany(targetEntity=KnowledgeOfferAssignments::class, mappedBy="offer_id")
     */
    private $knowledgeOfferAssignments;

    /**
     * @ORM\OneToOne(targetEntity=OfferCriteriaTestA::class, mappedBy="offer_id", cascade={"persist", "remove"})
     */
    private $offerCriteriaTestA;

    /**
     * @ORM\OneToOne(targetEntity=OfferCriteriaTestB::class, mappedBy="offer_id", cascade={"persist", "remove"})
     */
    private $offerCriteriaTestB;

    public function __construct()
    {
        $this->knowledgeOfferAssignments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getIncorporationDate(): ?string
    {
        return $this->incorporation_date;
    }

    public function setIncorporationDate(string $incorporation_date): self
    {
        $this->incorporation_date = $incorporation_date;

        return $this;
    }

    public function getLaboralSectorOfferAssignments(): ?LaboralSectorOfferAssignments
    {
        return $this->laboralSectorOfferAssignments;
    }

    public function setLaboralSectorOfferAssignments(LaboralSectorOfferAssignments $laboralSectorOfferAssignments): self
    {
        // set the owning side of the relation if necessary
        if ($laboralSectorOfferAssignments->getOfferId() !== $this) {
            $laboralSectorOfferAssignments->setOfferId($this);
        }

        $this->laboralSectorOfferAssignments = $laboralSectorOfferAssignments;

        return $this;
    }

    /**
     * @return Collection|KnowledgeOfferAssignments[]
     */
    public function getKnowledgeOfferAssignments(): Collection
    {
        return $this->knowledgeOfferAssignments;
    }

    public function addKnowledgeOfferAssignment(KnowledgeOfferAssignments $knowledgeOfferAssignment): self
    {
        if (!$this->knowledgeOfferAssignments->contains($knowledgeOfferAssignment)) {
            $this->knowledgeOfferAssignments[] = $knowledgeOfferAssignment;
            $knowledgeOfferAssignment->setOfferId($this);
        }

        return $this;
    }

    public function removeKnowledgeOfferAssignment(KnowledgeOfferAssignments $knowledgeOfferAssignment): self
    {
        if ($this->knowledgeOfferAssignments->removeElement($knowledgeOfferAssignment)) {
            // set the owning side to null (unless already changed)
            if ($knowledgeOfferAssignment->getOfferId() === $this) {
                $knowledgeOfferAssignment->setOfferId(null);
            }
        }

        return $this;
    }

    public function getOfferCriteriaTestA(): ?OfferCriteriaTestA
    {
        return $this->offerCriteriaTestA;
    }

    public function setOfferCriteriaTestA(OfferCriteriaTestA $offerCriteriaTestA): self
    {
        // set the owning side of the relation if necessary
        if ($offerCriteriaTestA->getOfferId() !== $this) {
            $offerCriteriaTestA->setOfferId($this);
        }

        $this->offerCriteriaTestA = $offerCriteriaTestA;

        return $this;
    }

    public function getOfferCriteriaTestB(): ?OfferCriteriaTestB
    {
        return $this->offerCriteriaTestB;
    }

    public function setOfferCriteriaTestB(OfferCriteriaTestB $offerCriteriaTestB): self
    {
        // set the owning side of the relation if necessary
        if ($offerCriteriaTestB->getOfferId() !== $this) {
            $offerCriteriaTestB->setOfferId($this);
        }

        $this->offerCriteriaTestB = $offerCriteriaTestB;

        return $this;
    }


}
