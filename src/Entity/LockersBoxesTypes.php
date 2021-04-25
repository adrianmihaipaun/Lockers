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
     * Lockers boxes type available boxes
     * - represents the entity id
     * 
     * @var integer
     */
    public static $type_availableBoxes = 1;

    /**
     * Lockers boxes type reserved boxes
     * - represents the entity id
     * 
     * @var integer
     */
    public static $type_reservedBoxes = 2;

    /**
     * Lockers boxes type occupied boxes
     * - represents the entity id
     * 
     * @var integer
     */
    public static $type_occupiedBoxes = 3;

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

    /**
     * Get lockers boxes types
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
                $types[str_replace('type_', '', $propertyName)] = $value;
            }
        }

        return $types;
    }
}
