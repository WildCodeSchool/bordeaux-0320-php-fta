<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $mobicoopId;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $status;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $role;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity=ScheduleVolunteer::class, mappedBy="userId", orphanRemoval=true)
     */
    private $scheduleVolunteers;

    /**
     * @ORM\ManyToOne(targetEntity=Shelter::class, inversedBy="userId")
     */
    private $shelter;

    /**
     * @ORM\ManyToMany(targetEntity=Trip::class, mappedBy="userId")
     */
    private $trips;

    public function __construct()
    {
        $this->scheduleVolunteers = new ArrayCollection();
        $this->trips = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMobicoopId(): ?int
    {
        return $this->mobicoopId;
    }

    public function setMobicoopId(int $mobicoopId): self
    {
        $this->mobicoopId = $mobicoopId;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

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

    /**
     * @return Collection|ScheduleVolunteer[]
     */
    public function getScheduleVolunteers(): Collection
    {
        return $this->scheduleVolunteers;
    }

    public function addScheduleVolunteer(ScheduleVolunteer $scheduleVolunteer): self
    {
        if (!$this->scheduleVolunteers->contains($scheduleVolunteer)) {
            $this->scheduleVolunteers[] = $scheduleVolunteer;
            $scheduleVolunteer->setUserId($this);
        }

        return $this;
    }

    public function removeScheduleVolunteer(ScheduleVolunteer $scheduleVolunteer): self
    {
        if ($this->scheduleVolunteers->contains($scheduleVolunteer)) {
            $this->scheduleVolunteers->removeElement($scheduleVolunteer);
            // set the owning side to null (unless already changed)
            if ($scheduleVolunteer->getUserId() === $this) {
                $scheduleVolunteer->setUserId(null);
            }
        }

        return $this;
    }

    public function getShelter(): ?Shelter
    {
        return $this->shelter;
    }

    public function setShelter(?Shelter $shelter): self
    {
        $this->shelter = $shelter;

        return $this;
    }

    /**
     * @return Collection|Trip[]
     */
    public function getTrips(): Collection
    {
        return $this->trips;
    }

    public function addTrip(Trip $trip): self
    {
        if (!$this->trips->contains($trip)) {
            $this->trips[] = $trip;
            $trip->addUserId($this);
        }

        return $this;
    }

    public function removeTrip(Trip $trip): self
    {
        if ($this->trips->contains($trip)) {
            $this->trips->removeElement($trip);
            $trip->removeUserId($this);
        }

        return $this;
    }
}
