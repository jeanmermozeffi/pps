<?php

// LogoutListener.php - Change the namespace according to the location of this class in your bundle
namespace PS\UtilisateurBundle\EventListener;

use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Logout\LogoutHandlerInterface;

class LogoutListener implements LogoutHandlerInterface
{
    /**
     * @var mixed
     */
    protected $userManager;

    /**
     * @param UserManagerInterface $userManager
     */
    public function __construct(UserManagerInterface $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param TokenInterface $token
     */
    public function logout(Request $request, Response $response, TokenInterface $token)
    {
        $user = $token->getUser();
        if (is_object($user)) {
             $user->setSmsCode(null);
        $user->setSmsCodeExpireAt(null);
        $this->userManager->updateUser($user);
        }
       
    }
}
