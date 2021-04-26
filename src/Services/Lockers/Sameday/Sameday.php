<?php

namespace App\Services\Lockers\Sameday;

use Symfony\Component\Config\FileLocator;
use Doctrine\ORM\EntityManagerInterface;

use Sameday\Requests\SamedayGetLockersRequest;
use Sameday\Responses\SamedayGetLockersResponse;
use Sameday\SamedayClient;
use Sameday\Sameday as SamedaySDK;
use stdClass;

use App\Services\Lockers\Lockers as BaseLockers;
use App\Services\Lockers\LockerCredentials;
use App\Services\Lockers\Exceptions\LockerErrorException;
use App\Services\Lockers\Sameday\Lockers;
use App\Services\Lockers\Sameday\LockersBoxes;
use App\Services\Lockers\Sameday\LockersSchedule;

use App\Entity\Lockers as LockerEntity;
use App\Entity\LockersSource;
use App\Repository\LockersRepository;
use Exception;

class Sameday extends BaseLockers
{
    protected $types = [
        "availableBoxes" => "available",
        "reservedBoxes" => "reserved",
        "occupiedBoxes" => "occupied"
    ];
    /**
     * @var SamedaySDK
     */
    protected $provider;

    /**
     * @var SamedayClient
     */
    protected $providerClient;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    protected $lockersRepository;

    public function __construct(
        LockerCredentials $lockerCredentials,
        EntityManagerInterface $entityManager
    ) {
        parent::__construct($lockerCredentials);

        $this->sourceId = LockersSource::$source_Sameday;
        $this->entityManager = $entityManager;
        $this->lockersRepository = $this->entityManager->getRepository(LockerEntity::class);
        $this->makeProviderConnection();
    }

    /**
     * Make samedy provider connection
     * 
     * @return void
     */
    protected function makeProviderConnection() :void
    {
        $this->providerClient =  new SamedayClient(
            $this->username,
            $this->password
        );

        $this->provider = new SamedaySDK(
            $this->providerClient
        );
    }

    /**
     * Get lockers
     * 
     * @return void
     */
    public function getLockers() :void
    {
        try {
            $response = $this->provider->getLockers(
                new SamedayGetLockersRequest()
            );

        } catch (\Exception $e) {
            throw new LockerErrorException($e->getMessage());
        }
            

        if (!$response instanceof SamedayGetLockersResponse) {
            throw new LockerErrorException($e->getMessage());
        }

        $response = [];

        $this->response = $response;
    }

    public function getLockerFromLocalResource()
    {
        $configDirectories = [__DIR__.'/../../../../public'];
        $fileLocator = new FileLocator($configDirectories);
        $json = $fileLocator->locate('lockers.json', null, false);

        $this->response = json_decode(
            file_get_contents($json[0])
        );
    }

    /**
     * Store lockers
     * 
     * @return void
     */
    public function storeLockers() :void
    {
        $this->getLockerFromLocalResource();

        foreach ($this->getResponse() as $locker) {
            try {
                $this->prepareLocker($locker);
            } catch (\Exception $e) {
                dd($e->getMessage()); // TODO throw exception
            }
        }
    }

    /**
     * Prepare lockers
     * 
     * @param object $providerLocker
     * @return void
     */
    protected function prepareLocker(stdClass $providerLocker) :void
    {
        $repository = $this->lockersRepository;
        $locker = $repository->findOneBy([
            'sourceId' => $this->getSourceId(),
            'externalId' => $providerLocker->lockerId
        ]);

        $lockersService = new Lockers(
            $this->entityManager,
            $this->lockersRepository,
            $providerLocker,
            $this->getSourceId()
        );

        try {
            $locker = $lockersService->getLocker();
        } catch (\Exception $e) {
            dd($e->getMessage()); // TODO throw exception
        }

        $lockerBoxes = new LockersBoxes(
            $locker,
            $this->entityManager,
            $providerLocker
        );

        try {
            $lockerBoxes->storeBoxes();
        } catch (\Exception $e) {
            dd($e->getMessage()); // TODO throw exception
        }

        $lockerBoxes = new LockersSchedule(
            $locker,
            $this->entityManager,
            $providerLocker
        );

        try {
            $lockerBoxes->storeSchedules();
        } catch (\Exception $e) {
            dd($e->getMessage()); // TODO throw exception
        }
    }
}