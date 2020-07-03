<?php

namespace App\Entity;

use App\Repository\TripRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TripRepository::class)
 * @ORM\HasLifecycleCallbacks()
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
     * @ORM\ManyToOne(targetEntity=Departure::class, inversedBy="trips")
     * @ORM\JoinColumn(nullable=false)
     */
    private $departure;

    /**
     * @ORM\ManyToOne(targetEntity=Arrival::class, inversedBy="trips")
     * @ORM\JoinColumn(nullable=false)
     */
    private $arrival;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="tripsVolunteer")
     */
    private $volunteer;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isMorning;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isAfternoon;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="trips")
     * @ORM\JoinColumn(nullable=false)
     */
    private $beneficiary;

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

    public function getDeparture(): ?Departure
    {
        return $this->departure;
    }

    public function setDeparture(?Departure $departure): self
    {
        $this->departure = $departure;

        return $this;
    }

    public function getArrival(): ?Arrival
    {
        return $this->arrival;
    }

    public function setArrival(?Arrival $arrival): self
    {
        $this->arrival = $arrival;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue()
    {
        $this->createdAt = new DateTime();
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

    public function getVolunteer(): ?User
    {
        return $this->volunteer;
    }

    public function setVolunteer(?User $volunteer): self
    {
        $this->volunteer = $volunteer;

        return $this;
    }

    public function getIsMorning(): ?bool
    {
        return $this->isMorning;
    }

    public function setIsMorning(bool $isMorning): self
    {
        $this->isMorning = $isMorning;

        return $this;
    }

    public function getIsAfternoon(): ?bool
    {
        return $this->isAfternoon;
    }

    public function setIsAfternoon(bool $isAfternoon): self
    {
        $this->isAfternoon = $isAfternoon;

        return $this;
    }

    public function getBeneficiary(): ?User
    {
        return $this->beneficiary;
    }

    public function setBeneficiary(?User $beneficiary): self
    {
        $this->beneficiary = $beneficiary;

        return $this;
    }
}
