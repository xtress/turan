<?php

namespace Helpers;

use Symfony\Component\Security\Http\Logout\LogoutHandlerInterface;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * Handler for clearing invalidating the current session.
 *
 * @author uncle_empty
 */
class LogoutHandler implements LogoutHandlerInterface
{
    /**
     * Invalidate the current session and destroy it
     *
     * @param Request        $request
     * @param Response       $response
     * @param TokenInterface $token
     *
     * @return void
     */
    public function logout(Request $request, Response $response, TokenInterface $token)
    {
        $request->getSession()->invalidate();
        session_destroy();
    }
}

?>
