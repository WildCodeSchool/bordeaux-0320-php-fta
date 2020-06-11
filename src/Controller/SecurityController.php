<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ConnectionType;
use App\Form\MobicoopForm;
use App\Service\ApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    /**
     * @Route("/register", name="user_new", methods={"GET","POST"})
     * @param Request $request
     * @param ApiService $api
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function new(Request $request, ApiService $api, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MobicoopForm::class);
        $form->handleRequest($request);
        $api->getToken();

        if ($form->isSubmitted() && $form->isValid()) {
            $client = $api->baseUri();
            $fullForm = $api::addPhoneDisplay($form->getData());
            $response = $client->request('POST', '/users', [
                'json' => $fullForm,
            ]);
            $response->getContent();
            $decodeUser = ApiService::decodeJson($response->getContent());
            $user = new User();
            $user->setMobicoopId($decodeUser['id'])
                ->setIsActive(true)
                ->setStatus('volunteer')
                ->setCreatedAt(new \DateTime());
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('login');
        }
        return $this->render('user/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param ApiService $api
     * @param SessionInterface $session
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/login", name="login")
     */
    public function connection(Request $request, ApiService $api, SessionInterface $session)
    {
        $form = $this->createForm(ConnectionType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $api->getToken();
            $user = $api->getUser($form);
            $password = $form->getData()['password'];
            if (ApiService::passwordVerify($user, $password)) {
                $userObject = $api->makeUser($user);
                dump($userObject);
                $session->set('user', $userObject);
                dd();
                return $this->redirectToRoute('/'); // TODO change the redirect route
            }
        }
        return $this->render('user/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
