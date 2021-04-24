<?php

namespace App\Entity;

use App\Repository\LockersBoxesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LockersBoxesRepository::class)
 */
class LockersBoxes
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
     * @ORM\Column(type="string", length=20)
     */
    private $size;

    /**
     * @ORM\Column(type="integer")
     */
    private $number;

    /**
     * @ORM\Column(type="integer")
     */
    private $boxType;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

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

    public function getSize(): ?string
    {
        return $this->size;
    }

    public function setSize(string $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getBoxType(): ?int
    {
        return $this->boxType;
    }

    public function setBoxType(int $boxType): self
    {
        $this->boxType = $boxType;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }
}
