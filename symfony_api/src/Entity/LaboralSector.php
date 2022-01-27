<?php

namespace App\Entity;

use App\Repository\LaboralSectorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LaboralSectorRepository::class)
 */
class LaboralSector
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
     * @ORM\OneToMany(targetEntity=LaboralSectorAssignments::class, mappedBy="laboral_sector_id")
     */
    private $yes;

    public function __construct()
    {
        $this->yes = new ArrayCollection();
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
     * @return Collection|LaboralSectorAssignments[]
     */
    public function getYes(): Collection
    {
        return $this->yes;
    }

    public function addYe(LaboralSectorAssignments $ye): self
    {
        if (!$this->yes->contains($ye)) {
            $this->yes[] = $ye;
            $ye->setLaboralSectorId($this);
        }

        return $this;
    }

    public function removeYe(LaboralSectorAssignments $ye): self
    {
        if ($this->yes->removeElement($ye)) {
            // set the owning side to null (unless already changed)
            if ($ye->getLaboralSectorId() === $this) {
                $ye->setLaboralSectorId(null);
            }
        }

        return $this;
    }
}
