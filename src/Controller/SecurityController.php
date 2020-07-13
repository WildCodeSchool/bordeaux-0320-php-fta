<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ConnectionType;
use App\Form\MobicoopForm;
use App\Service\ApiService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/register", name="user_new", methods={"GET","POST"})
     * @param Request $request
     * @param ApiService $api
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
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
                ->setRoles(['ROLE_USER_UNVALIDATE']);
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'You are now connected');

            return $this->redirectToRoute('login');
        }
        return $this->render('security/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param ApiService $api
     * @param SessionInterface $session
     * @param EventDispatcherInterface $eventDispatcher
     * @return RedirectResponse|Response
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws Exception
     * @Route("/login", name="login")
     */
    public function connection(
        Request $request,
        ApiService $api,
        SessionInterface $session,
        EventDispatcherInterface $eventDispatcher
    ) {
        $form = $this->createForm(ConnectionType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $api->getToken();
            try {
                $mobicoopUser = $api->getUser($form);
            } catch (Exception $e) {
                throw new Exception('Error server' . $e, 500);
            }
            $passwordSaved = $mobicoopUser['hydra:member'][0]['password'];
            $password = $form->getData()['password'];
            if (ApiService::passwordVerify($passwordSaved, $password)) {
                $userObject = $api->makeUser($mobicoopUser);
                $session->set('user', $userObject);
                $user = $this->getDoctrine()
                    ->getRepository(User::class)
                    ->findOneBy(['mobicoopId' => $userObject->getMobicoopId()]);

                $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
                $this->get('security.token_storage')->setToken($token);
                $this->get('session')->set('_security_main', serialize($token));
                $event = new InteractiveLoginEvent($request, $token);
                $eventDispatcher->dispatch("security.interactive_login", $event);

                if ($user->getStatus() === 'volunteer') {
                    return $this->redirectToRoute('trip_matching');
                } elseif ($user->getStatus() === 'beneficiary') {
                    return $this->redirectToRoute('trip_beneficiary');
                } else {
                    return $this->redirectToRoute('admin_index');
                }
            }
        }
        $this->addFlash('success', 'You are now connected');

        return $this->render('security/login.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/logout", name="logout", methods={"GET"})
     */
    public function logout(): void
    {
        $this->addFlash(
            'succes',
            'Your are disconnected !'
        );
    }
}
