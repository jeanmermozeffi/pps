<?php


namespace PS\ApiBundle\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUserInterface;

final class User implements JWTUserInterface
{
    // Your own logic
    
    public function __construct($username, array $roles, $email)
    {
        $this->username = $username;
        $this->roles = $roles;
        $this->email = $email;
    }
    
    public static function createFromPayload($username, array $payload)
    {
        return new self(
            $username,
            $payload['userid'],
            $payload['personneid'],
            $payload['roles'], // Added by default
            $payload['email']  // Custom
        );
    }
}