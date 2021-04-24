<?php

namespace App\Crons\Lockers\Interfaces;

interface LockersInterface
{
    public function setResponse($response);
    public function getResponse();
    public function getLockers();
    public function storeLockers();
}