<?php

namespace App\Crons\Lockers\Sameday;

use App\Crons\Lockers\Lockers as BaseLockers;
use Sameday\Requests\SamedayGetLockersRequest;
use Sameday\Responses\SamedayGetLockersResponse;
use Sameday\SamedayClient;
use Sameday\Sameday as SamedaySDK;
use App\Crons\Lockers\LockerCredentials;
use App\Crons\Lockers\Exceptions\LockerErrorException;
use Symfony\Component\Config\FileLocator;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Lockers;
use App\Entity\LockersBoxes;
use App\Entity\LockersBoxesTypes;
use App\Entity\LockersSource;
use App\Repository\LockersRepository;
use App\Repository\LockersBoxesRepository;
use App\Repository\LockersScheduleRepository;
use App\Repository\LockersBoxesTypesRepository;
use stdClass;

class Sameday extends BaseLockers
{
    protected $types = [
        "availableBoxes",
        "reservedBoxes",
        "occupiedBoxes"
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
        $this->lockersRepository = $this->entityManager->getRepository(LockersRepository::class);

        $this->makeProviderConnection();
    }

    /**
     * Make samedy provider connection
     * 
     * @return void
     */
    protected function makeProviderConnection()
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
    public function getLockers()
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

    public function storeLockers()
    {
        $this->getLockerFromLocalResource();

        foreach ($this->getResponse() as $locker) {
            try {
                $this->prepareLocker($locker);
            } catch (\Throwable $th) {
                //throw $th;
            }
        }
    }

    protected function prepareLocker(stdClass $providerLocker)
    {
        $locker = $this->lockersRepository->findOneBy([
            'source_id' => $this->getSourceId(),
            'external_id' => $providerLocker->lockerId
        ]);

        if (!isset($providerLocker->lockerId) || !$locker) {
            $locker = $this->createLocker($providerLocker);
        } else {
            $locker = $this->updateLocker($locker, $providerLocker);
        }

        $this->prepareLockerBoxes($locker, $providerLocker);
    }

    /**
     * Create new locker
     * 
     * @param object $providerLocker
     * @return Lockers
     */
    public function createLocker(stdClass $providerLocker)
    {
        $locker = new Lockers();
        $locker->name = $providerLocker->name;
        $locker->county = $providerLocker->county;
        $locker->county_id = $providerLocker->countyId;
        $locker->city = $providerLocker->city;
        $locker->city_id = $providerLocker->cityId;
        $locker->address = $providerLocker->address;
        $locker->postal_code = $providerLocker->postalCode;
        $locker->lat = $providerLocker->lat;
        $locker->lng = $providerLocker->lng;
        $locker->email = $providerLocker->email;
        $locker->phone = $providerLocker->phone;
        $locker->status = ($providerLocker->clientVisible === 1) ? 1 : 0;
        $locker->source_id = $this->getSourceId();
        $locker->external_id = $providerLocker->lockerId;
        $locker->supported_payment = $providerLocker->supportedPayment;

        $this->entityManager->persist($locker);

        return $locker;
    }

    /**
     * Update locker
     * 
     * @param Lockers $locker
     * @param object $providerLocker
     * @return Lockers
     */
    protected function updateLocker(Lockers $locker, stdClass $providerLocker) :Lockers
    {
        $changes = 0;
        $this->getFieldsDifference($locker, 'name', $providerLocker->name, $changes);
        $this->getFieldsDifference($locker, 'county', $providerLocker->county, $changes);
        $this->getFieldsDifference($locker, 'county_id', $providerLocker->countyId, $changes);
        $this->getFieldsDifference($locker, 'city', $providerLocker->city, $changes);
        $this->getFieldsDifference($locker, 'city_id', $providerLocker->cityId, $changes);
        $this->getFieldsDifference($locker, 'address', $providerLocker->address, $changes);
        $this->getFieldsDifference($locker, 'postal_code', $providerLocker->postalCode, $changes);
        $this->getFieldsDifference($locker, 'lat', $providerLocker->lat, $changes);
        $this->getFieldsDifference($locker, 'lng', $providerLocker->lng, $changes);
        $this->getFieldsDifference($locker, 'email', $providerLocker->email, $changes);
        $this->getFieldsDifference($locker, 'phone', $providerLocker->phone, $changes);
        $status = ($providerLocker->clientVisible === 1) ? 1 : 0;
        $this->getFieldsDifference($locker, 'status', $status, $changes);
        $this->getFieldsDifference($locker, 'supported_payment', $providerLocker->supportedPayment, $changes);

        if ($changes > 0) {
            $this->entityManager->persist($locker);
        }

        return $locker;
    }

    /**
     * Get fields difference
     * 
     * @param Lockers $locker
     * @param string $field
     * @param object $providerField
     * @param int $changes
     * @return void
     */
    protected function getFieldsDifference(&$locker, $field, $providerField, &$changes) :void
    {
        if ($locker->$field != $providerField) {
            $locker->$field = $providerField;
            $changes++;
        }
    }

    /**
     * Prepare locker boxes
     * 
     * @param Lockers $locker
     * @param object $providerLocker
     * @return void
     */
    protected function prepareLockerBoxes($locker, $providerLocker) :void
    {
        foreach (LockersBoxesTypes::getTypes() as $type) {
            $repository = $this->entityManager->getRepository(LockersBoxes::class);
            $boxesTypes = $repository->findBy([
                'locker_id' => $locker->id,
                'box_type' => $type
            ]);

            foreach ($boxesTypes as $boxType) {
                foreach ($providerLocker->)
            }
        }
    }
}