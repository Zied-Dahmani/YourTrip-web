<?php

namespace App\Entity;

use App\Repository\TenteRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TenteRepository::class)
 */
class Tente
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * * @Assert\NotBlank(message="Tapez le nom!")
     * @Assert\Regex(
     *     pattern     = "/^[a-z]+$/i",
     *     htmlPattern = "[a-zA-Z]+",
     *     message="Le nom doit être alphabétique"
     * )
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * * @Assert\NotBlank(message="Tapez la description!")
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     * * @Assert\NotBlank(message="Tapez le prix!")
     * @Assert\Range(
     *      min = 40,
     *      max = 260,
     *      notInRangeMessage = "Le prix doit être compris entre {{ min }} DT et {{ max }} DT!",
     * )
     */
    private $prix;

    /**
     * @ORM\ManyToOne(targetEntity=CentreCamping::class, inversedBy="tentes")
     */
    private $centreCamping;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

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

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getCentreCamping(): ?CentreCamping
    {
        return $this->centreCamping;
    }

    public function setCentreCamping(?CentreCamping $centreCamping): self
    {
        $this->centreCamping = $centreCamping;

        return $this;
    }
}
