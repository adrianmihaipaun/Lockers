<?php

namespace App\Entity;

use App\Repository\LockersBoxesTypesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LockersBoxesTypesRepository::class)
 */
class LockersBoxesTypes
{
    /**
     * Lockers boxes type available
     * - represents the entity id
     * 
     * @var integer
     */
    public static $type_available = 1;

    /**
     * Lockers boxes type reserved
     * - represents the entity id
     * 
     * @var integer
     */
    public static $type_reserved = 2;

    /**
     * Lockers boxes type occupied
     * - represents the entity id
     * 
     * @var integer
     */
    public static $type_occupied = 1;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    /**
     * Get all entity types
     * 
     * @return array
     */
    public static function getTypes() :array
    {
        $class = new \ReflectionClass(__CLASS__);
        $staticProperties = $class->getStaticProperties();

        $types = [];
        foreach ($staticProperties as $propertyName => $value) { 
            if (substr($propertyName, 0, 5) == 'type_') {
                $types[$propertyName] = $value;
            }
        }

        return $types;
    }
}
