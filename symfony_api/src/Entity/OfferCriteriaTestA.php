<?php

namespace App\Entity;

use App\Repository\OfferCriteriaTestARepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OfferCriteriaTestARepository::class)
 */
class OfferCriteriaTestA
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Offer::class, inversedBy="offerCriteriaTestA", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $offer_id;

    /**
     * @ORM\Column(type="smallint")
     */
    private $minimun_percent;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOfferId(): ?Offer
    {
        return $this->offer_id;
    }

    public function setOfferId(Offer $offer_id): self
    {
        $this->offer_id = $offer_id;

        return $this;
    }

    public function getMinimunPercent(): ?int
    {
        return $this->minimun_percent;
    }

    public function setMinimunPercent(int $minimun_percent): self
    {
        $this->minimun_percent = $minimun_percent;

        return $this;
    }
}
