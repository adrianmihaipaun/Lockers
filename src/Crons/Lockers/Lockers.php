<?php

namespace App\Crons\Lockers;

use App\Crons\Lockers\Interfaces\LockersInterface;

use App\Crons\Lockers\LockerCredentials;

class Lockers implements LockersInterface
{
    /**
     * @var LockerCredentials
     */
    protected $username;

    /**
     * @var LockerCredentials
     */
    protected $password;

    /**
     * @var array
     */
    protected $response;

    /**
     * @var integer
     */
    protected $sourceId;

    public function __construct(LockerCredentials $lockerCredentials)
    {
        $this->username = $lockerCredentials->getUsername();
        $this->password = $lockerCredentials->getPassword();
    }

    /**
     * Set response
     * 
     * @param array $response
     * @return void
     */
    public function setResponse($response)
    {
        $this->response = $response;
    }

    /**
     * Get response
     * 
     * @return array
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Get source id
     * 
     * @return int
     */
    public function getSourceId()
    {
        return $this->sourceId;
    }

    public function getLockers() {}
    public function storeLockers() {}
}