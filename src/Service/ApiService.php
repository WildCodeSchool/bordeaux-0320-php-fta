<?php

namespace App\Service;

use App\Entity\UserMobicoop;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ApiService
 * @package App\Service
 */
class ApiService
{
    /**
     *
     */
    const BASE_URL = 'https://api.mobicoop.io';

    /**
     * @var SessionInterface
     */
    private SessionInterface $session;
    /**
     * @var ContainerInterface
     */
    private ContainerInterface $container;

    /**
     * ApiService constructor.
     * @param SessionInterface $session
     * @param ContainerInterface $container
     */
    public function __construct(SessionInterface $session, ContainerInterface $container)
    {
        $this->session = $session;
        $this->container = $container;
    }

    /**
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function getToken(): void
    {
        $client = HttpClient::create();
        $username = $this->container->getParameter('mobicoop_user');
        $password = $this->container->getParameter('mobicoop_password');
        $response = $client->request('POST', self::BASE_URL . '/auth', [
            'json' => ['username' => $username, 'password' => $password]
        ]);
        $allToken = ApiService::decodeJson($response->getContent());
        $token = $allToken->{'token'};
        $refreshToken = $allToken->{'refreshToken'};
        $this->session->set('token', $token);
        $this->session->set('refreshToken', $refreshToken);
    }

    public function baseUri()
    {
        $client = HttpClient::createForBaseUri(self::BASE_URL, [
            // HTTP Bearer authentication (also called token authentication)
            'auth_bearer' => $this->session->get('token'),
        ]);
        return $client;
    }

    /**
     * @param FormInterface $form
     * @return array
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function getUser(FormInterface $form): array
    {
        $client = $this->baseUri();
        $response = $client->request('GET', '/users', [
            'query' => [
                'email' => $form->getData()['email']
            ]
        ]);
        return ApiService::decodeJson($response->getContent());
    }

    /**
     * @param array $array
     * @return UserMobicoop
     */
    public function makeUser(array $array): UserMobicoop
    {
        $user = new UserMobicoop();
        $user->setMobicoopId($array['hydra:member'][0]['id']);
        $user->setGivenName($array['hydra:member'][0]['givenName']);
        $user->setFamilyName($array['hydra:member'][0]['familyName']);
        $user->setGender($array['hydra:member'][0]['gender']);
        $user->setPhone($array['hydra:member'][0]['telephone']);
        $user->setAvatar($array['hydra:member'][0]['avatars'][0]);
        $user->setRole($array['hydra:member'][0]['roles'][0]);
        return $user;
    }

    /**
     * @param string $passwordSaved
     * @param string $password
     * @return bool
     */
    public static function passwordVerify(string $passwordSaved, string $password): bool
    {

        return password_verify($password, $passwordSaved);
    }

    /**
     * @param array $array
     * @return array
     */
    public static function addPhoneDisplay(array $array): array
    {
        $array['phoneDisplay'] = 1;
        return $array;
    }

    /**
     * @param string $string
     * @return array
     */
    public static function decodeJson(string $string): array
    {
        return json_decode($string, true);
    }
}
