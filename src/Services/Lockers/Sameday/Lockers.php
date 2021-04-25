<?php

namespace App\Services\Lockers\Sameday;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Lockers as LockersEntity;
use App\Repository\LockersRepository;

class Lockers
{
    /**
     * @var int
     */
    protected $sourceId;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var LockersRepository
     */
    protected $lockersRepository;
    /**
     * @var object
     */
    protected $providerLocker;

    public function __construct(
        EntityManagerInterface $entityManager,
        LockersRepository $lockersRepository,
        $providerLocker,
        $sourceId
    ) {
        $this->entityManager = $entityManager;
        $this->lockersRepository = $lockersRepository;
        $this->providerLocker = $providerLocker;
        $this->sourceId = $sourceId;
    }

    /**
     * Get locker
     * 
     * @return LockersEntity
     */
    public function getLocker() :LockersEntity
    {
        $locker = $this->lockersRepository->findOneBy([
            'source_id' => $this->sourceId,
            'external_id' => $this->providerLocker->lockerId
        ]);

        if (!$locker) {
            $locker = $this->createLocker();
        } else {
            $locker = $this->updateLocker($locker);
        }

        return $locker;
    }

    /**
     * Create new locker
     * 
     * @return LockersEntity
     */
    public function createLocker() :LockersEntity
    {
        $locker = new LockersEntity();
        $locker->setName($this->providerLocker->name);
        $locker->setCounty($this->providerLocker->county);
        $locker->setCountyId($this->providerLocker->countyId);
        $locker->setCity($this->providerLocker->city);
        $locker->setCityId($this->providerLocker->cityId);
        $locker->setAddress($this->providerLocker->address);
        $locker->setPostalCode($this->providerLocker->postalCode);
        $locker->setLat($this->providerLocker->lat);
        $locker->setLng($this->providerLocker->lng);
        $locker->setEmail($this->providerLocker->email);
        $locker->setPhone($this->providerLocker->phone);
        $locker->setStatus(($this->providerLocker->clientVisible === 1) ? 1 : 0);
        $locker->setSourceId($this->sourceId);
        $locker->setExternalId($this->providerLocker->lockerId);
        $locker->setSupportedPayment($this->providerLocker->supportedPayment);
        $this->entityManager->persist($locker);

        return $locker;
    }

    /**
     * Update locker
     * 
     * @param LockersEntity $locker
     * @return LockersEntity
     */
    protected function updateLocker(LockersEntity $locker) :LockersEntity
    {
        if ($locker->getName() != $this->providerLocker->name) {
            $locker->setName($this->providerLocker->name);
        }
        if ($locker->getCounty() != $this->providerLocker->name) {
            $locker->setCounty($this->providerLocker->county);
        }
        if ($locker->getCountyId() != $this->providerLocker->name) {
            $locker->setCountyId($this->providerLocker->countyId);
        }
        if ($locker->getCity() != $this->providerLocker->name) {
            $locker->setCity($this->providerLocker->city);
        }
        if ($locker->getCityId() != $this->providerLocker->name) {
            $locker->setCityId($this->providerLocker->cityId);
        }
        if ($locker->getAddress() != $this->providerLocker->name) {
            $locker->setAddress($this->providerLocker->address);
        }
        if ($locker->getPostalCode() != $this->providerLocker->postalCode) {
            $locker->setPostalCode($this->providerLocker->postalCode);
        }
        if ($locker->getLat() != $this->providerLocker->lat) {
            $locker->setLat($this->providerLocker->lat);
        }
        if ($locker->getLng() != $this->providerLocker->lng) {
            $locker->setLng($this->providerLocker->lng);
        }
        if ($locker->getEmail() != $this->providerLocker->email) {
            $locker->setEmail($this->providerLocker->email);
        }
        if ($locker->getPhone() != $this->providerLocker->phone) {
            $locker->setPhone($this->providerLocker->phone);
        }
        if ($locker->getStatus() != $this->providerLocker->clientVisible) {
            $locker->setStatus($this->providerLocker->clientVisible);
        }
        if ($locker->getSupportedPayment() != $this->providerLocker->supportedPayment) {
            $locker->setSupportedPayment($this->providerLocker->supportedPayment);
        }

        return $locker;
    }
}