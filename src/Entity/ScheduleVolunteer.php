<?php

namespace App\Entity;

use App\Repository\ScheduleVolunteerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ScheduleVolunteerRepository::class)
 */
class ScheduleVolunteer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isMorning;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isAfternoon;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }
}
