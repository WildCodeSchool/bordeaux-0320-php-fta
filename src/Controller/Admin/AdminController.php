<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\MobicoopAdminForm;
use App\Repository\ScheduleVolunteerRepository;
use App\Repository\TripRepository;
use App\Repository\UserRepository;
use App\Service\ApiService;
use App\Service\TripService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
            ['status' => User::STATUS_BENEFICIARY],
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
     * @Route("/trips", name = "trips")
     * @param ApiService $apiService
     * @param TripRepository $tripRepository
     * @param UserRepository $userRepository
     * @return Response
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function seeAllTrips(
        ApiService $apiService,
        TripRepository $tripRepository,
        UserRepository $userRepository
    ): Response {
        $usersLocal = $userRepository->findAll();
        $apiService->getToken();
        $usersMobicoop = $apiService->getAllUsers();

        $users = $apiService->setFullName($usersMobicoop, $usersLocal);

        return $this->render('admin/trips/trips.html.twig', [
            'users' => $users,
            'trips' => $tripRepository->findAll(),
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
        $users = $userRepository->findBy(['status' => $status], ['id' => 'ASC'], self::LIMIT);
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
     * @param UserRepository $userRepository
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
    public function editUser(
        int $id,
        string $status,
        Request $request,
        ApiService $apiService,
        UserRepository $userRepository
    ): Response {
        $mobicoopId = $userRepository->findOneById($id)->getMobicoopId();
        $apiService->getToken();
        $user = $apiService->getUserById($mobicoopId);

        $form = $this->createForm(MobicoopAdminForm::class, null, [
            'gender' => $user['gender'],
            'status' => $user['status']
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $client = $apiService->baseUri();
            $client->request('PUT', '/users/' . $mobicoopId, [
                'json' => $form->getData(),
            ]);

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

    /**
     * Route ajax for search with givenName
     * @Route("/ajax/search/users", name="ajax_search_users")
     * @param UserRepository $userRepository
     * @param ApiService $apiService
     * @param Request $request
     * @return JsonResponse
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function ajaxSearchUsers(
        UserRepository $userRepository,
        ApiService $apiService,
        Request $request
    ): JsonResponse {
        $apiService->getToken();
        $type = $request->query->get('type');
        $name = $request->query->get('name');

        $usersMobicoop = $apiService->getUserByGivenName($name);
        $usersBeneficiary = $userRepository->findBy(
            ['status' => $type],
        );

        $usersMobicoop = $apiService::createAjaxUserArray($usersMobicoop, $usersBeneficiary);

        return new JsonResponse($usersMobicoop);
    }

    /**
     * @Route("/volunteer/schedule/{id}", name="volunteer_schedule")
     * @param int $id
     * @param ScheduleVolunteerRepository $scheduleRepository
     * @param ApiService $apiService
     * @param UserRepository $userRepository
     * @return Response
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function volunteerAvailabilities(
        int $id,
        ScheduleVolunteerRepository $scheduleRepository,
        ApiService $apiService,
        UserRepository $userRepository
    ): Response {
        $userLocal = $userRepository->findOneById($id);
        $apiService->getToken();
        $user = $apiService->getUserById($userLocal->getMobicoopId());

        $schedules = $scheduleRepository->findBy(['user' => $id]);

        return $this->render('admin/user/volunteer_schedules.html.twig', [
            'user' => $user,
            'schedules' => $schedules
        ]);
    }

   /**
     * @Route("/ajax/page/users")
     * @param Request $request
     * @param UserRepository $userRepository
     * @param ApiService $apiService
     * @return JsonResponse
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function ajaxPageUsers(
        Request $request,
        UserRepository $userRepository,
        ApiService $apiService
    ): JsonResponse {
        $apiService->getToken();
        $limit = $request->query->get('limit');
        $type = $request->query->get('type');

        $usersMobicoop = $apiService->getAllUsers();
        $users = $userRepository->findBy(['status' => $type], ['id' => 'ASC'], 5, $limit);

        return new JsonResponse($apiService::createAjaxUserArray($usersMobicoop, $users));
    }

   /**
     * @Route("/beneficiary/trips/{id}", name="beneficiary_trips")
     * @param int $id
     * @param TripRepository $tripRepository
     * @param ApiService $apiService
     * @param UserRepository $userRepository
     * @return Response
     */
    public function beneficiaryTrips(
        int $id,
        TripRepository $tripRepository,
        ApiService $apiService,
        UserRepository $userRepository
    ): Response {
        $userLocal = $userRepository->findOneById($id);
        $apiService->getToken();
        $user = $apiService->getUserById($userLocal->getMobicoopId());

        $usersLocal = $userRepository->findAll();
        $apiService->getToken();
        $usersMobicoop = $apiService->getAllUsers();

        $users = $apiService->setFullName($usersMobicoop, $usersLocal);

        $trips = $tripRepository->findBy(['beneficiary' => $id]);

        return $this->render('admin/user/beneficiary_trips.html.twig', [
            'user'  => $user,
            'users' => $users,
            'trips' => $trips
        ]);
    }


    /**
     * @Route("/ajax/page/trips")
     * @param Request $request
     * @param TripRepository $tripRepository
     * @param ApiService $apiService
     * @param TripService $tripService
     * @return JsonResponse
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function ajaxPageTrips(
        Request $request,
        TripRepository $tripRepository,
        ApiService $apiService,
        TripService $tripService
    ): JsonResponse {
        $apiService->getToken();
        $limit = $request->query->get('limit');

        $usersMobicoop = $apiService->getAllUsers();
        $trips = $tripRepository->findBy([], ['id' => 'ASC'], 5, $limit);

        return new JsonResponse($tripService::createAjaxTripsArray($usersMobicoop, $trips));
    }
}
