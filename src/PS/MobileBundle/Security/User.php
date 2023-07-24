<?php


namespace PS\MobileBundle\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUserInterface;
use Symfony\Component\Security\Core\Encoder\EncoderAwareInterface;

final class User implements JWTUserInterface
{
    // Your own logic
    
    public function __construct($username, array $roles = [], $email = null, $idPersonne = null)
    {
        
        $this->username = $username;
        $this->roles = $roles;
        $this->email = $email;
        $this->idPersonne = $idPersonne;
       
    }
    
    public static function createFromPayload($username, array $payload)
    {
        if (isset($payload['roles'], $payload['email'], $payload['id_personne'])) {
            return new self(
            $username,
            

           
            $payload['roles'], // Added by default

            $payload['email'],
            $payload['id_personne']

        );
        }

        return new self($username);

        
    }


    /** {@inheritdoc}
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
    }


}