<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use DateTime;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class User implements UserInterface
{
    const ARRAY_ROLES = ['ROLE_USER_BENEFICIARY', 'ROLE_ADMIN', 'ROLE_USER_VOLUNTEER'];
    const STATUS_VOLUNTEER = 'volunteer';
    const SATUS_BENEFICIARY = 'beneficiary';
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
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity=ScheduleVolunteer::class, mappedBy="user")
     */
    private $scheduleVolunteers;

    /**
     * @ORM\OneToMany(targetEntity=Trip::class, mappedBy="volunteer")
     */
    private $tripsVolunteer;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\OneToMany(targetEntity=Trip::class, mappedBy="beneficiary", orphanRemoval=true)
     */
    private $trips;

    private $givenName;

    private $familyName;

    public function __construct()
    {
        $this->trips = new ArrayCollection();
        $this->scheduleVolunteers = new ArrayCollection();
        $this->tripsVolunteer = new ArrayCollection();
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @ORM\PrePersist
     * @return $this
     */
    public function setCreatedAt(): self
    {
        $this->createdAt = new DateTime();

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @ORM\PreUpdate()
     * @return $this
     */
    public function setUpdatedAt(): self
    {
        $this->updatedAt = new DateTime();

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
            $scheduleVolunteer->setUser($this);
        }

        return $this;
    }

    public function removeScheduleVolunteer(ScheduleVolunteer $scheduleVolunteer): self
    {
        if ($this->scheduleVolunteers->contains($scheduleVolunteer)) {
            $this->scheduleVolunteers->removeElement($scheduleVolunteer);
            // set the owning side to null (unless already changed)
            if ($scheduleVolunteer->getUser() === $this) {
                $scheduleVolunteer->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Trip[]
     */
    public function getTripsVolunteer(): Collection
    {
        return $this->tripsVolunteer;
    }

    public function addTripsVolunteer(Trip $tripsVolunteer): self
    {
        if (!$this->tripsVolunteer->contains($tripsVolunteer)) {
            $this->tripsVolunteer[] = $tripsVolunteer;
            $tripsVolunteer->setVolunteer($this);
        }

        return $this;
    }

    public function removeTripsVolunteer(Trip $tripsVolunteer): self
    {
        if ($this->tripsVolunteer->contains($tripsVolunteer)) {
            $this->tripsVolunteer->removeElement($tripsVolunteer);
            // set the owning side to null (unless already changed)
            if ($tripsVolunteer->getVolunteer() === $this) {
                $tripsVolunteer->setVolunteer(null);
            }
        }

        return $this;
    }

    public function getRoles()
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER_VOLUNTEER';

        return array_unique($roles);
    }

    public function setRoles(array $roles)
    {
        $this->roles = $roles;
    }

    public function getPassword()
    {
        // TODO: Implement getPassword() method.
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function getUsername()
    {
        return $this->mobicoopId;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
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
            $trip->setBeneficiary($this);
        }

        return $this;
    }

    public function removeTrip(Trip $trip): self
    {
        if ($this->trips->contains($trip)) {
            $this->trips->removeElement($trip);
            // set the owning side to null (unless already changed)
            if ($trip->getBeneficiary() === $this) {
                $trip->setBeneficiary(null);
            }
        }

        return $this;
    }

    public function getGivenName()
    {
        return $this->givenName;
    }

    public function setGivenName($givenName)
    {
        $this->givenName = $givenName;

        return $this;
    }

    public function getFamilyName()
    {
        return $this->familyName;
    }

    public function setFamilyName($familyName)
    {
        $this->familyName = $familyName;

        return $this;
    }
}
