<?php

namespace App\Entity;

use App\Repository\TripRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TripRepository::class)
 */
class Trip
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Departure::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $departureId;

    /**
     * @ORM\ManyToOne(targetEntity=Arrival::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $arrivalId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getDepartureId(): ?Departure
    {
        return $this->departureId;
    }

    public function setDepartureId(?Departure $departureId): self
    {
        $this->departureId = $departureId;

        return $this;
    }

    public function getArrivalId(): ?Arrival
    {
        return $this->arrivalId;
    }

    public function setArrivalId(?Arrival $arrivalId): self
    {
        $this->arrivalId = $arrivalId;

        return $this;
    }
}
