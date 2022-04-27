<?php

namespace App\Entity;

use App\Repository\CentreCampingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass=CentreCampingRepository::class)
 */
class CentreCamping
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
     * @ORM\Column(type="string", length=255)
     * * @Assert\NotBlank(message="Tapez l' adresse!")
     */
    private $adresse;

    /**
     * @ORM\OneToMany(targetEntity=Tente::class, mappedBy="centreCamping")
     */
    private $tentes;

    /**
     * @ORM\Column(type="string", length=255)
     * * @Assert\NotBlank(message="Tapez l'email!")
     * * @Assert\Email(message="Tapez un email valide!")
     */
    private $email;



    public function __construct()
    {
        $this->tentes = new ArrayCollection();
    }


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

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * @return Collection<int, Tente>
     */
    public function getTentes(): Collection
    {
        return $this->tentes;
    }

    public function addTente(Tente $tente): self
    {
        if (!$this->tentes->contains($tente)) {
            $this->tentes[] = $tente;
            $tente->setCentreCamping($this);
        }

        return $this;
    }

    public function removeTente(Tente $tente): self
    {
        if ($this->tentes->removeElement($tente)) {
            // set the owning side to null (unless already changed)
            if ($tente->getCentreCamping() === $this) {
                $tente->setCentreCamping(null);
            }
        }

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }
}
