<?php

namespace App\Entity;

use App\Repository\EmpruntRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EmpruntRepository::class)
 */
class Emprunt
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $Reserve;

    /**
     * @ORM\Column(type="datetime")
     */
    private $Datestart;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $Dateend;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $Emptrunte;

    /**
     * @ORM\ManyToOne(targetEntity=Livre::class, inversedBy="emprunts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Livre;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="emprunts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $User;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReserve(): ?bool
    {
        return $this->Reserve;
    }

    public function setReserve(bool $Reserve): self
    {
        $this->Reserve = $Reserve;

        return $this;
    }

    public function getDatestart(): ?\DateTimeInterface
    {
        return $this->Datestart;
    }

    public function setDatestart(\DateTimeInterface $Datestart): self
    {
        $this->Datestart = $Datestart;

        return $this;
    }

    public function getDateend(): ?\DateTimeInterface
    {
        return $this->Dateend;
    }

    public function setDateend(?\DateTimeInterface $Dateend): self
    {
        $this->Dateend = $Dateend;

        return $this;
    }

    public function getEmptrunte(): ?bool
    {
        return $this->Emptrunte;
    }

    public function setEmptrunte(?bool $Emptrunte): self
    {
        $this->Emptrunte = $Emptrunte;

        return $this;
    }

    public function getLivre(): ?Livre
    {
        return $this->Livre;
    }

    public function setLivre(?Livre $Livre): self
    {
        $this->Livre = $Livre;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }
}
