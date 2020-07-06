<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\MobicoopAdminForm;
use App\Repository\TripRepository;
use App\Repository\UserRepository;
use App\Service\ApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * Class AdminController
 * @Route("/admin", name="admin_")
 * @package App\Controller\Admin
 */
class AdminController extends AbstractController
{
    const LIMIT = 5;

    /**
     * Home for admin
     * @Route("/", name="index")
     * @param UserRepository $userRepository
     * @param TripRepository $tripRepository
     * @param ApiService $apiService
     * @return Response
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function index(
        UserRepository $userRepository,
        TripRepository $tripRepository,
        ApiService $apiService
    ): Response {
        $usersVolunteer = $userRepository->findBy(
            ['status' => User::STATUS_VOLUNTEER],
            ['id' => 'DESC'],
            self::LIMIT
        );
        $usersBeneficiary = $userRepository->findBy(
            ['status' => User::SATUS_BENEFICIARY],
            ['id' => 'DESC'],
            self::LIMIT
        );

        $apiService->getToken();
        $usersMobicoop = $apiService->getAllUsers();

        $usersVolunteer = $apiService->setFullName($usersMobicoop, $usersVolunteer);
        $usersBeneficiary = $apiService->setFullName($usersMobicoop, $usersBeneficiary);

        return $this->render('admin/index.html.twig', [
            'usersVolunteer' => $usersVolunteer,
            'usersBeneficiary' => $usersBeneficiary,
            'trips' => $tripRepository->findBy([], ['id' => 'DESC'], self::LIMIT),
        ]);
    }

    /**
     * Show volunteer and can add new volunteer
     * @param string $status
     * @param UserRepository $userRepository
     * @param ApiService $apiService
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @Route("/common/{status}", name="common", requirements={"status"="beneficiary|volunteer"})
     */
    public function usersAccordingToStatus(
        string $status,
        UserRepository $userRepository,
        ApiService $apiService,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $users = $userRepository->findBy(['status' => $status]);
        $apiService->getToken();
        $form = $this->createForm(MobicoopAdminForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $client = $apiService->baseUri();
            $fullForm = $apiService::addPhoneDisplay($form->getData());
            $response = $client->request('POST', '/users', [
                'json' => $fullForm,
            ]);
            $response->getContent();
            $decodeUser = ApiService::decodeJson($response->getContent());
            $user = new User();
            $user->setMobicoopId($decodeUser['id'])
                ->setIsActive(true)
                ->setStatus($status)
                ->setRoles(['ROLE_USER_UNVALIDATE']);
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Le ' . $status . ' est bien inscrit');
        }

        $userMobicoop = $apiService->getAllUsers();

        $users = $apiService->setFullName($userMobicoop, $users);

        return $this->render('admin/user/common.html.twig', [
            'users' => $users,
            'status' => $status,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param int $id
     * @param string $status
     * @param Request $request
     * @param ApiService $apiService
     * @return Response
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @Route("/common/edit/{id}/{status}",
     *     name="edit_user",
     *     requirements=
     *      {"id"="[0-9]+",
     *     "status"="beneficiary|volunteer"}
     *     )
     */
    public function editUser(int $id, string $status, Request $request, ApiService $apiService)
    {
        $apiService->getToken();
        $user = $apiService->getUserById($id)['hydra:member'][0];

        $form = $this->createForm(MobicoopAdminForm::class, null, [
            'gender' => $user['gender'],
            'status' => $user['status']
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $client = $apiService->baseUri();
            $response = $client->request('PUT', '/users/' . $id, [
                'json' => $form->getData(),
            ]);
            dump($response->getContent());

            return $this->redirectToRoute('admin_common', [
                'status' => $status,
            ]);
        }
        return $this->render('admin/user/edit.html.twig', [
            'form'   => $form->createView(),
            'user'   => $user,
            'status' => $status,
        ]);
    }
}
