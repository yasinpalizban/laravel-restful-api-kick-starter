<?php namespace Modules\Auth\Entities;


use Modules\Shared\Libraries\Entity;

class  AuthEntity extends Entity
{
    public $id;
    public $phone;
    public $username;
    public $image;
    public $email;
    public $password;
    public $login;
    public $loginType;
    public $remember;
    public $ipAddress;
    public $userAgent;
    public $role;
    public $resetExpires;
    public $resetAt;
    public $resetToken;
    public $createdAt;
    public $updatedAt;
    public $activeToken;
    public $active;
    public $firstName;
    public $lastName;
    public $bio;
    public $title;
    public $gender;
    public $status;
    public $statusMessage;

    public function __construct($attributes)
    {
        $this->fill($this, $attributes);



    }

    protected $datamap = [
         'statusMessage'=>'status_message',
         'firstName'=>'first_name',
         'lastName'=>'last_name',
         'resetAt'=>'reset_at',
         'resetToken'=>'reset_token',
         'resetExpires'=>'reset_expires',
         'activeToken'=>'active_token',
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at',
        'deletedAt' => 'deleted_at',
        'ipAddress' => 'ip_address',
        'userAgent' => 'user_agent',

    ];


    public function generatePassword(): AuthEntity
    {
        $this->password = bcrypt($this->password);
        return $this;
    }


    /**
     * Generates a secure hash to use for password reset purposes,
     * saves it to the instance.
     *
     * @return $this
     * @throws \Exception
     */
    public function generateResetToken(): AuthEntity
    {
        $resetTime = 3600;
        $this->resetToken = bin2hex(random_bytes(16));
        $this->resetExpires = date('Y-m-d H:i:s', time() + $resetTime);

        return $this;
    }

        public function activateExpiration(): AuthEntity
        {
            $resetTime = 3600;
            $this->resetExpires = date('Y-m-d H:i:s', time() + $resetTime);

            return $this;
        }
    /**
     * Generates a secure random hash to use for account activation.
     *
     * @return $this
     * @throws \Exception
     */
    public function generateActiveteToken(): AuthEntity
    {
        $this->activeToken = bin2hex(random_bytes(16));

        return $this;
    }

    /**
     * Activate user.
     *
     * @return $this
     */
    public function activate(): AuthEntity
    {
        $this->active = 1;
        $this->activeToken = null;

        return $this;
    }

    /**
     * Unactivate user.
     *
     * @return $this
     */
    public function deactivate(): AuthEntity
    {
        $this->active = 0;

        return $this;
    }

    /**
     * Checks to see if a user is active.
     *
     * @return bool
     */
    public function isActivated(): bool
    {
        return isset($this->active) && $this->active == true;
    }


    public function logInMode($flag = true): AuthEntity
    {
// Determine credential type

        if ($flag == false) {

            if (filter_var($this->login, FILTER_VALIDATE_EMAIL)) {
                $this->loginType = 'email';

            } else if (is_numeric($this->login)) {
                $this->loginType = 'phone';

            } else {
                $this->loginType = 'username';

            }

        } else {


            if (filter_var($this->login, FILTER_VALIDATE_EMAIL)) {
                $this->loginType = 'email';
                $this->email = $this->login;
            } else if (is_numeric($this->login)) {
                $this->loginType = 'phone';
                $this->phone = $this->login;

            } else {
                $this->loginType = 'username';
                $this->username = $this->login;
            }
        }
        return $this;
    }

    public function ipAddress(string $ip): AuthEntity
    {
        $this->ipAddress = $ip;
        return $this;
    }



    public function createdAt(): AuthEntity
    {
        $this->createdAt = date('Y-m-d H:i:s', time());
        return $this;
    }

    public function setRole(string $role): AuthEntity
    {
        $this->role = $role;
        return $this;
    }

    public function resetPassword(): AuthEntity
    {
        $this->resetHash = null;
        $this->resetExpires = null;
        $this->resetAt = date('Y-m-d H:i:s');


        return $this;
    }

    public function userAgent(string $agent): AuthEntity
    {
        $this->userAgent = $agent;
        return $this;
    }


    public function enableStatus(): AuthEntity
    {
        $this->status = 1;


        return $this;
    }

    /**
     * Unactivate user.
     *
     * @return $this
     */
    public function disableStatus(): AuthEntity
    {
        $this->status = 0;

        return $this;
    }
}
