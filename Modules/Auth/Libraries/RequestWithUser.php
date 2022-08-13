<?php

namespace Modules\Auth\Libraries;

use Modules\Auth\Interfaces\RequestWithUserInterface;
use \Illuminate\Http\Request;


class  RequestWithUser extends Request implements RequestWithUserInterface
{
    protected  $userData;



    public function __construct(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
    }

    public function setUserData(object $userData)
    {
        $this->userData = $userData;
    }

    /**
     * @return mixed
     */
    public function getUserData(): object
    {
        return $this->userData;
    }

    public function getUserVar(string $key): string
    {
        if (isset($this->userData->$key)) {
            return $this->userData->$key;
        }

        return '';

    }


}
