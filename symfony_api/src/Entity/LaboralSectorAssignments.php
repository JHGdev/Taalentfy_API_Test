<?php

namespace App\Entity;

use App\Repository\LaboralSectorAssignmentsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LaboralSectorAssignmentsRepository::class)
 */
class LaboralSectorAssignments
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=User::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user_id;

    /**
     * @ORM\ManyToOne(targetEntity=LaboralSector::class, inversedBy="laboralSectorAssignments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $laboral_sector_id;


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
