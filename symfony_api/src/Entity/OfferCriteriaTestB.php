<?php

namespace App\Entity;

use App\Repository\OfferCriteriaTestBRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OfferCriteriaTestBRepository::class)
 */
class OfferCriteriaTestB
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Offer::class, inversedBy="offerCriteriaTestB", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $offer_id;

    /**
     * @ORM\Column(type="smallint")
     */
    private $desired_percent_A;

    /**
     * @ORM\Column(type="smallint")
     */
    private $desired_percent_B;

    /**
     * @ORM\Column(type="smallint")
     */
    private $desired_percent_C;

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

    public function getDesiredPercentA(): ?int
    {
        return $this->desired_percent_A;
    }

    public function setDesiredPercentA(int $desired_percent_A): self
    {
        $this->desired_percent_A = $desired_percent_A;

        return $this;
    }

    public function getDesiredPercentB(): ?int
    {
        return $this->desired_percent_B;
    }

    public function setDesiredPercentB(int $desired_percent_B): self
    {
        $this->desired_percent_B = $desired_percent_B;

        return $this;
    }

    public function getDesiredPercentC(): ?int
    {
        return $this->desired_percent_C;
    }

    public function setDesiredPercentC(int $desired_percent_C): self
    {
        $this->desired_percent_C = $desired_percent_C;

        return $this;
    }
}
