<?php

namespace App\Services\Lockers\Sameday;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Lockers;
use App\Entity\LockersSchedule as LockersScheduleEntity;

class LockersSchedule
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
     * Store schedules
     * 
     * @return bool
     */
    public function storeSchedules() :bool
    {
        // If provider locker has no current type name try to delete all locker boxes
        if (!property_exists($this->providerLocker, 'schedule')) {
            $this->deleteAllSchedules($this->locker->id);
            return false;
        }

        $repository = $this->entityManager->getRepository(LockersScheduleEntity::class);
        $lockerSchedules = $repository->findBy([
            'lockerId' => $this->locker->id
        ]);

        if (!$lockerSchedules) {
            return false;
        }

        foreach ($lockerSchedules as $lockerSchedule) {
            $find = 0;

            // Update existing locker box with provided data
            foreach ($this->providerLocker->schedule as $providerScheduleKey => $providerSchedule) {
                if (strtolower($lockerSchedule->getDay()) === strtolower($providerSchedule->size)) {
                    $find = 1;
                    $lockerSchedule = $this->updateLockerSchedule($lockerSchedule, $providerSchedule);

                    // Remove current box from provided boxes list
                    unset($this->providerLocker->schedule[$providerScheduleKey]);
                }
            }
            
            // If the searched box does not exist in the received list, we delete the box
            if ($find === 0) {
                $repository->remove($lockerSchedule);
            }
        }

        // If provided list has new boxes, we save them
        if (count($this->providerLocker->schedule) == 0) {
            foreach ($this->providerLocker->schedule as $providerSchedule) {
                $this->createLockerSchedule($this->locker->id, $providerSchedule);
            }    
        }

        try {
            $repository->flush();
        } catch (\Throwable $th) {
            //throw $th; TODO
        }
    }

    /**
     * Delete all locker schedules
     * 
     * @param int @lockerId
     * @return void
     */
    private function deleteAllSchedules($lockerId) :void
    {
        $repository = $this->entityManager->getRepository(LockersScheduleEntity::class);
        $schedules = $repository->findBy([
            'lockerId' => $lockerId
        ]);

        if ($schedules) {
            foreach ($schedules as $schedule) {
                $repository->remove($schedule);
            }
        }

        $repository->flush();
    }

    /**
     * Create locker schedule
     * 
     * @param int $lockerId
     * @param object $providerSchedule
     * @return LockersScheduleEntity
     */
    private function createLockerSchedule(int $lockerId) :LockersScheduleEntity
    {
        $lockerSchedule = new LockersScheduleEntity();
        $lockerSchedule->setLockerId($lockerId);
        $lockerSchedule->setDay($this->providerSchedule->day);
        $lockerSchedule->setOpeningHour($this->providerSchedule->openingHour);
        $lockerSchedule->setClosingHour($this->providerSchedule->closingHour);

        return $lockerSchedule;
    }

    /**
     * Update locker schedules
     * 
     * @param LockersScheduleEntity $lockerSchedule
     * @param object $providerSchedule
     * @return LockersScheduleEntity
     */
    private function updateLockerSchedule(LockersScheduleEntity $lockerSchedule, $providerSchedule) :LockersScheduleEntity
    {
        if ($lockerSchedule->getOpeningHour() != $providerSchedule->openingHour) {
            $lockerSchedule->setOpeningHour($providerSchedule->openingHour);
        }

        if ($lockerSchedule->getClosingHour() != $providerSchedule->closingHour) {
            $lockerSchedule->setClosingHour($providerSchedule->closingHour);
        }

        return $lockerSchedule;
    }
}