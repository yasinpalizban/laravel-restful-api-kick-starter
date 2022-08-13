<?php


namespace Modules\Auth\Interfaces;


/**
 * Expected behavior of a Security.
 */
interface RequestWithUserInterface
{
    public function setUserData(object $userData);

    public function getUserVar(string $key): string;

    public function getUserData(): object;

}

