<?php

namespace App\Services\Lockers\Sameday;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Lockers;
use App\Entity\LockersBoxes as LockersBoxesEntity;
use App\Entity\LockersBoxesTypes;

class LockersBoxes
{
    /**
     * @var Lockers
     */
    protected $locker;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var object
     */
    protected $providerLocker;

    public function __construct(
        Lockers $locker,
        EntityManagerInterface $entityManager,
        $providerLocker)
    {
        $this->locker = $locker;
        $this->entityManager = $entityManager;
        $this->providerLocker = $providerLocker;
    }

    /**
     * Store boxes
     * 
     * @return void
     */
    public function storeBoxes() :void
    {
        foreach (LockersBoxesTypes::getTypes() as $typeName => $typeId) {
            $repository = $this->entityManager->getRepository(LockersBoxesEntity::class);
            $boxesTypes = $repository->findBy([
                'locker_id' => $this->locker->id,
                'box_type' => $typeId
            ]);

            // If provider locker has no current type name try to delete all locker boxes
            if (!property_exists($this->providerLocker, $typeName)) {
                $this->deleteAllLockerBoxes($repository, $boxesTypes);
                continue;
            }

            foreach ($boxesTypes as $boxType) {
                $find = 0;

                // Update existing locker box with provided data
                foreach ($this->providerLocker->$typeName as $providerBoxKey => $providerBox) {
                    if (strtolower($boxType->getSize()) === strtolower($providerBox->size)) {
                        $find = 1;
                        $boxType = $this->updateLockerBox($boxType, $providerBox);

                        // Remove current box from provided boxes list
                        unset($this->providerLocker->{$typeName}[$providerBoxKey]);
                    }
                }
                
                // If the searched box does not exist in the received list, we delete the box
                if ($find === 0) {
                    $repository->remove($boxType);
                }
            }

            // If provided list has new boxes, we save them
            if (count($this->providerLocker->$typeName) == 0) {
                foreach ($this->providerLocker->$typeName as $providerBox) {
                    $lockerBox = $this->createLockerBox($this->locker->id, $typeId, $providerBox);
                    $repository->persist($lockerBox);
                }    
            }

            try {
                $repository->flush();
            } catch (\Throwable $th) {
                //throw $th; TODO
            }
        }
    }

    /**
     * Create locker box
     * 
     * @param int $lockerId
     * @param int $boxType
     * @param object $providerBox
     * @return LockersBoxesEntity
     */
    private function createLockerBox(int $lockerId, int $boxType, object $providerBox) :LockersBoxesEntity
    {
        $lockerBox = new LockersBoxesEntity();
        $lockerBox->setLockerId($lockerId);
        $lockerBox->setSize($providerBox->size);
        $lockerBox->setNumber($providerBox->number);
        $lockerBox->setLockerBoxesTypeId($boxType);

        return $lockerBox;
    }

    /**
     * Update locker box
     * 
     * @param LockersBoxesEntity $boxType
     * @param object $providerBox
     * @return LockersBoxesEntity
     */
    private function updateLockerBox(LockersBoxesEntity $boxType, $providerBox) :LockersBoxesEntity
    {
        if ($boxType->getNumber() != $providerBox->number) {
            $boxType->setNumber($providerBox->number);
        }

        return $boxType;
    }

    /**
     * Delete all locker boxes
     * 
     * @param object $repository
     * @param array $boxesTypes
     * @return void
     */
    private function deleteAllLockerBoxes(&$repository, $boxesTypes)
    {
        foreach ($boxesTypes as $boxType) {
            $repository->remove($boxType);
        }
        $repository->flush();
    }
}