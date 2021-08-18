<?php

namespace App\Entity;

use App\Repository\SortiesRepository;



use DateTime;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Array_;


/**
 * @ORM\Entity(repositoryClass=SortiesRepository::class)
 */
class Sorties
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @var int
     */
    private $idSortie;


    /**
     * @var string|null
     * @ORM\Column(type="string", length=255)
     */
    private ?string $nom;

    /**
     * @ORM\Column(type="datetime")
     * @var DateTime
     */
    private ?DateTime $dateHeureDebut;

    /**
     * @ORM\Column(type="datetime")
     * @var DateTime
     */
    private ?DateTime $dateLimiteInscription;

    /**
     * @ORM\Column(type="integer")
     * @var int|null
     */
    private int $duree;

    /**
     * @ORM\Column(type="integer")
     * @var int|null
     */
    private ?int $nbIncriptionsMax;

    /**
     * @ORM\Column(type="text", nullable=true, length=500)
     * @var string|null
     */
    private ?string $infosSortie;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string|null
     */
    private ?string $etat;

    /**
     * @ORM\Column(type="integer")
     * @var int | null
     */
    private int $idOrganisateur;

    /**
     * @var array|null
     * @ORM\Column(type="array")
     */
    private ?array $inscrit;


    /**
     * @return int
     */
    public function getIdSortie(): int
    {
        return $this->idSortie;
    }

    /**
     * @return string|null
     */
    public function getNom(): ?string
    {
        return $this->nom;
    }

    /**
     * @param string|null $nom
     */
    public function setNom(?string $nom): void
    {
        $this->nom = $nom;
    }

    /**
     * @return DateTime
     */
    public function getDateHeureDebut(): ?DateTime
    {
        return $this->dateHeureDebut;
    }

    /**
     * @param DateTime $dateHeureDebut
     */
    public function setDateHeureDebut(?DateTime $dateHeureDebut): void
    {
        $this->dateHeureDebut = $dateHeureDebut;
    }

    /**
     * @return DateTime
     */
    public function getDateLimiteInscription(): ?DateTime
    {
        return $this->dateLimiteInscription;
    }

    /**
     * @param DateTime $dateLimiteInscription
     */
    public function setDateLimiteInscription(?DateTime $dateLimiteInscription): void
    {
        $this->dateLimiteInscription = $dateLimiteInscription;
    }

    /**
     * @return int|null
     */
    public function getDuree()
    {
        return $this->duree;
    }

    /**
     * @param int|null $duree
     *
     */
    public function setDuree(?int $duree): void
    {
        $this->duree = $duree;
    }

    /**
     * @return int|null
     */
    public function getNbIncriptionsMax(): ?int
    {
        return $this->nbIncriptionsMax;
    }

    /**
     * @param int|null $nbIncriptionsMax
     */
    public function setNbIncriptionsMax(?int $nbIncriptionsMax): void
    {
        $this->nbIncriptionsMax = $nbIncriptionsMax;
    }

    /**
     * @return string|null
     */
    public function getInfosSortie(): ?string
    {
        return $this->infosSortie;
    }

    /**
     * @param string|null $infosSortie
     */
    public function setInfosSortie(?string $infosSortie): void
    {
        $this->infosSortie = $infosSortie;
    }

    /**
     * @return string|null
     */
    public function getEtat(): ?string
    {
        return $this->etat;
    }

    /**
     * @param string|null $etat
     */
    public function setEtat(?string $etat): void
    {
        $this->etat = $etat;
    }

    /**
     * @return int|null
     */
    public function getIdOrganisateur(): ?int
    {
        return $this->idOrganisateur;
    }

    /**
     * @param int|null $idOrganisateur
     */
    public function setIdOrganisateur(?int $idOrganisateur): void
    {
        $this->idOrganisateur = $idOrganisateur;
    }

    /**
     * @return array|null
     */
    public function getInscrit(): ?array
    {
        return $this->inscrit;
    }

    /**
     * @param array|null $inscrit
     */
    public function setInscrit(?array $inscrit): void
    {
        $this->inscrit = $inscrit;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
}


