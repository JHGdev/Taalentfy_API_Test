<?php

namespace App\Entity;

use App\Repository\LaboralSectorOfferAssignmentsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LaboralSectorOfferAssignmentsRepository::class)
 */
class LaboralSectorOfferAssignments
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Offer::class, inversedBy="laboralSectorOfferAssignments", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $offer_id;

    /**
     * @ORM\ManyToOne(targetEntity=LaboralSector::class, inversedBy="laboralSectorOfferAssignments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $laboral_sector_id;

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

    public function getLaboralSectorId(): ?LaboralSector
    {
        return $this->laboral_sector_id;
    }

    public function setLaboralSectorId(?LaboralSector $laboral_sector_id): self
    {
        $this->laboral_sector_id = $laboral_sector_id;

        return $this;
    }
}
