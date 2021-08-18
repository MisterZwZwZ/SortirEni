<?php

namespace App\Entity;

use App\Repository\CampusRepository;
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
    private int $idCampus;



    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $nomCampus;


    /**
     * @return int|null
     */
    public function getIdCampus(): ?int
    {
        return $this->idCampus;
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
}
