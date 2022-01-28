<?php

namespace App\Entity;

use App\Repository\KnowledgeOfferAssignmentsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=KnowledgeOfferAssignmentsRepository::class)
 */
class KnowledgeOfferAssignments
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Offer::class, inversedBy="knowledgeOfferAssignments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $offer_id;

    /**
     * @ORM\ManyToOne(targetEntity=Knowledge::class, inversedBy="knowledgeOfferAssignments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $knowledge_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOfferId(): ?Offer
    {
        return $this->offer_id;
    }

    public function setOfferId(?Offer $offer_id): self
    {
        $this->offer_id = $offer_id;

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
