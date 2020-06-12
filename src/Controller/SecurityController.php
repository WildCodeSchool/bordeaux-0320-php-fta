<?php

namespace App\Controller;

use App\Form\ConnectionType;
use App\Form\MobicoopForm;
use App\Service\ApiService;
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
     * @return Response
     */
    public function new(Request $request, ApiService $api): Response
    {
        $form = $this->createForm(MobicoopForm::class);
        $form->handleRequest($request);
        $api->getToken();

        if ($form->isSubmitted() && $form->isValid()) {
            $client = $api->baseUri();
            $fullForm = $api::addPhoneDisplay($form->getData());
            $client->request('POST', '/users', [
                'json' => $fullForm,
            ]);
            return $this->redirectToRoute('login');
        }
        return $this->render('security/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param ApiService $api
     * @Route("/login", name="login")
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function connection(Request $request, ApiService $api, SessionInterface $session)
    {
        $form = $this->createForm(ConnectionType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $api->getToken();
            $user = $api->getUser($form);
            $password = $form->getData()['password'];
            if ($api->passwordVerify($user, $password)) {
                $session->set('moobicoopId', $user['hydra:member'][0]['id']);
                $session->set('firstName', $user['hydra:member'][0]['givenName']);
                $session->set('familyName', $user['hydra:member'][0]['familyName']);
                return $this->redirectToRoute('trip_index'); // TODO change the redirect route
            }
        }
        return $this->render('security/login.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
