<?php

namespace App\Entity;

use App\Repository\CampusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CampusRepository::class)
 */
class Campus
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */

    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */

    private ?string $nomCampus;

    /**
     * @ORM\OneToMany(targetEntity=Sorties::class, mappedBy="siteOrganisateur", orphanRemoval=true)
     */
    private $sortiesRattacheesCampus;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="campusUser")
     */
    private $usersRattaches;

    public function __construct()
    {
        $this->sortiesRattacheesCampus = new ArrayCollection();
        $this->usersRattaches = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */

    public function getNomCampus(): ?string
    {
        return $this->nomCampus;
    }

    /**
     * @param string $nomCampus
     */

    public function setNomCampus(string $nomCampus): void
    {
        $this->nomCampus = $nomCampus;
    }

    /**
     * @return Collection|Sorties[]
     */
    public function getSortiesRattacheesCampus(): Collection
    {
        return $this->sortiesRattacheesCampus;
    }

    public function addSortiesRattacheeCampus(Sorties $sortiesRattacheeCampus): self
    {
        if (!$this->sortiesRattacheesCampus->contains($sortiesRattacheeCampus)) {
            $this->sortiesRattacheesCampus[] = $sortiesRattacheeCampus;
            $sortiesRattacheeCampus->setSiteOrganisateur($this);
        }

        return $this;
    }

    public function removeSortiesRattacheeCampus(Sorties $sortiesRattacheeCampus): self
    {
        if ($this->sortiesRattacheesCampus->removeElement($sortiesRattacheeCampus)) {
            // set the owning side to null (unless already changed)
            if ($sortiesRattacheeCampus->getSiteOrganisateur() === $this) {
                $sortiesRattacheeCampus->setSiteOrganisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsersRattaches(): Collection
    {
        return $this->usersRattaches;
    }

    public function addUsersRattach(User $usersRattach): self
    {
        if (!$this->usersRattaches->contains($usersRattach)) {
            $this->usersRattaches[] = $usersRattach;
            $usersRattach->setCampusUser($this);
        }

        return $this;
    }

    public function removeUsersRattach(User $usersRattach): self
    {
        if ($this->usersRattaches->removeElement($usersRattach)) {
            // set the owning side to null (unless already changed)
            if ($usersRattach->getCampusUser() === $this) {
                $usersRattach->setCampusUser(null);
            }
        }

        return $this;
    }
}
