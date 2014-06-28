<?php

namespace Api\UserBundle\Manager;

use Admin\MainBundle\Helpers\securityHelper;
use API\UserBundle\Entity\Clients;
use Doctrine\DBAL\DBALException;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;

class ClientsManager {

    private $doctrine;
    private $encoder;

    const SESSION_TIMED_OUT = 'timed';
    const RENEW_SESSION     = 'normal';
    const INVALID_TOKEN     = 'invalid_token';

    /**
     * @param ContainerInterface $container
     */
    public function __construct(RegistryInterface $doctrine, EncoderFactory $encoder) {
        $this->doctrine = $doctrine;
        $this->encoder  = $encoder;
    }

    /**
     * @param $clientData
     */
    public function registerClient($clientData)
    {
        $em         = $this->doctrine->getManager();
        $client     = new Clients();

        $factory    = $this->encoder;
        $encoder    = $factory->getEncoder($client);
        $salt       = $this->generateSalt();
        $pass       = $encoder->encodePassword($clientData['password'], $salt);

        $client->setUsername($clientData['FIO']);
        $client->setPhone($clientData['phone']);
        $client->setEmail($clientData['email']);
        $client->setPassword($pass);
        $client->setSalt($salt);

        try {
            $em->persist($client);
            $em->flush();

            return $client;
        } catch(DBALException $e) {
            return false;
        }
    }

    private function generateSalt()
    {
        return securityHelper::SaltGenerator(50);
    }

    /**
     * Logins client from front-end into symfony2 app
     *
     * @param Clients $client
     * @param $pass
     * @return bool|string
     */
    public function login(Clients $client, $pass)
    {
        /** @var EncoderFactory $encoder_service */
        $encoder_service = $this->encoder;
        $isPasswordValid = $encoder_service->getEncoder($client)->isPasswordValid($client->getPassword(), $pass, $client->getSalt());

        if ($isPasswordValid) {

            if ($result = $this->updateClientSession($client)) {
                return $result;
            } else {
                return false;
            }

        } else {
            return false;
        }
    }

    public function getUser($username)
    {
        $em = $this->doctrine->getManager();

        return $em->getRepository('APIUserBundle:Clients')->findOneBy(array('email' => $username));
    }

    public function checkUserSession(Clients $client, $token)
    {
        $sessionToken   = $client->getSessionToken();
        $tokenLifeTime  = $client->getSessionTokenLifetime();
        $now            = new \DateTime('now', new \DateTimeZone('Europe/Minsk'));

        if ($token !== $sessionToken) {
            return self::INVALID_TOKEN;
        }

        if ($token == null || ($sessionToken != null && $now > $tokenLifeTime) ) {
            return self::SESSION_TIMED_OUT;
        } else if ($token != null && $token === $sessionToken && $now < $tokenLifeTime) {
            return self::RENEW_SESSION;
        }

    }

    public function checkToken($token)
    {
        $user = $this->getClientByToken($token);

        return ($user !== null && $user instanceof Clients && $this->checkTokenLifeTime($user) != self::SESSION_TIMED_OUT) ? self::RENEW_SESSION : self::SESSION_TIMED_OUT;
    }

    public function checkTokenLifeTime(Clients $user)
    {
        $sessionToken   = $user->getSessionToken();
        $tokenLifeTime  = $user->getSessionTokenLifetime();
        $now            = new \DateTime('now', new \DateTimeZone('Europe/Minsk'));

        if (($sessionToken != null && $now > $tokenLifeTime) ) {
            return self::SESSION_TIMED_OUT;
        } else if ($sessionToken != null && $now < $tokenLifeTime) {
            return self::RENEW_SESSION;
        }
    }

    public function updateClientSession(Clients $client)
    {
        $em             = $this->doctrine->getManager();
        $newToken       = securityHelper::SaltGenerator(50);
        $now            = new \DateTime('now', new \DateTimeZone('Europe/Minsk'));
        $tokenLifeTime  = $now->add(new \DateInterval('PT15M'));

        $client->setSessionToken($newToken);
        $client->setSessionTokenLifetime($tokenLifeTime);


        try {

            $em->persist($client);
            $em->flush();

            return $newToken;

        } catch(DBALException $e) {
            return false;
        }
    }

    public function changeClientInfo(Clients $client, $newInfo)
    {
        $em = $this->doctrine->getManager();

        if ($newInfo['FIO']) {
            $client->setUsername($newInfo['FIO']);
        }

        if ($newInfo['phone']) {
            $client->setPhone($newInfo['phone']);
        }

        try {
            $em->persist($client);
            $em->flush();

            return true;
        } catch (DBALException $e) {
            return false;
        }
    }

    public function invalidateClientSession($client)
    {
        $em = $this->doctrine->getManager();

        $client->setSessionToken(null);
        $client->setSessionTokenLifetime(null);


        try {

            $em->persist($client);
            $em->flush();

            return true;

        } catch(DBALException $e) {
            return false;
        }
    }

    public function getClientByToken($token)
    {
        return $this->doctrine->getManager()->getRepository('APIUserBundle:Clients')->findOneBy(array('sessionToken' => $token));
    }


}