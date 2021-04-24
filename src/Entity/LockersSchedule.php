<?php

namespace App\Entity;

use App\Repository\LockersScheduleRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LockersScheduleRepository::class)
 */
class LockersSchedule
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $lockerId;

    /**
     * @ORM\Column(type="integer")
     */
    private $day;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $openingHour;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $closingHour;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLockerId(): ?int
    {
        return $this->lockerId;
    }

    public function setLockerId(int $lockerId): self
    {
        $this->lockerId = $lockerId;

        return $this;
    }

    public function getDay(): ?int
    {
        return $this->day;
    }

    public function setDay(int $day): self
    {
        $this->day = $day;

        return $this;
    }

    public function getOpeningHour(): ?string
    {
        return $this->openingHour;
    }

    public function setOpeningHour(string $openingHour): self
    {
        $this->openingHour = $openingHour;

        return $this;
    }

    public function getClosingHour(): ?string
    {
        return $this->closingHour;
    }

    public function setClosingHour(string $closingHour): self
    {
        $this->closingHour = $closingHour;

        return $this;
    }
}
