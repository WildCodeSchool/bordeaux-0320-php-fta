<?php

namespace App\Controller;

use App\Entity\Trip;
use App\Entity\User;
use App\Form\TripType;
use App\Repository\TripRepository;
use App\Repository\UserRepository;
use App\Service\ApiService;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class TripController extends AbstractController
{
    /**
     * @Route("/beneficiary/trip", name="trip_index", methods={"GET"})
     * @param TripRepository $tripRepository
     * @return Response
     */
    public function index(TripRepository $tripRepository): Response
    {
        return $this->render('trip/index.html.twig', [
            'trips' => $tripRepository->findAll(),
        ]);
    }

    /**
     * @Route("/beneficiary/{id}", name="trip_byId", methods={"GET"})
     * @param UserRepository $userRepository
     * @param int $id
     * @return Response
     */
    public function tripByUserId(UserRepository $userRepository, int $id): Response
    {
        $user  = $userRepository->findOneBy(['id' => $id]);
        $trips = $user->getTrips();

        return $this->render('trip/index.html.twig', [
            'trips' => $trips,
        ]);
    }

    /**
     * @param SessionInterface $session
     * @Route("/volunteer/trip", name="trip_volunteer")
     * @return Response
     */
    public function myTrip(SessionInterface $session): Response
    {
        $id = $session->get('user')->getMobicoopId();
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(['mobicoopId' => $id]);
        $trips = $user->getTripsVolunteer();
        return $this->render('trip/index.html.twig', [
            'trips' => $trips,
        ]);
    }

    /**
     * @Route("/volunteer/matching", name="trip_all")
     * @param SessionInterface $session
     * @return Response
     */
    public function allTrip(SessionInterface $session): Response
    {
        //$id = $session->get('user')->getId();
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(['mobicoopId' => 11]);

        $trips = [];
        $i = 0;
        foreach ($user->getScheduleVolunteers() as $scheduleVolunteer) {
            $trips[$i] = $this->getDoctrine()->getRepository(Trip::class)
                ->matchingAvailability(
                    $scheduleVolunteer->getIsMorning(),
                    $scheduleVolunteer->getIsAfternoon(),
                    $scheduleVolunteer->getDate()->format('Y-m-d')
                );
            $i++;
        }

        return $this->render('_components/_allTrip.html.twig', [
            'trips' => $trips,
        ]);
    }

    /**
     * @Route("/beneficiary/trip/new", name="trip_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function new(Request $request): Response
    {
        $trip = new Trip();
        $form = $this->createForm(TripType::class, $trip);
        $trip->getUser();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $date = $request->request->get('datePicker');
            $time = $request->request->get('timePicker');
            $dateTime = $date . $time;
            $entityManager = $this->getDoctrine()->getManager();
            $trip->setDate(new DateTime($dateTime));
            $entityManager->persist($trip);
            $entityManager->flush();

            return $this->redirectToRoute('trip_index');
        }

        return $this->render('trip/new.html.twig', [
            'trip' => $trip,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/beneficiary/trip/{id}", name="trip_show", methods={"GET"})
     * @param ApiService $api
     * @param Trip $trip
     * @return Response
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function show(ApiService $api, Trip $trip): Response
    {
        $volunteer = null;
        $user = $trip->getUser()->getValues()[0];

        if ($trip->getVolunteer() != null) {
            $api->getToken();
            $volunteerId = $trip->getVolunteer()->getMobicoopId();
            $volunteer = $api->getUserById($volunteerId)['hydra:member'][0];
        }

        return $this->render('trip/show.html.twig', [
            'trip'      => $trip,
            'volunteer' => $volunteer,
            'user'      => $user
        ]);
    }

    /**
     * @Route("/beneficiary/trip/{id}/edit", name="trip_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Trip $trip
     * @return Response
     * @throws \Exception
     */
    public function edit(Request $request, Trip $trip): Response
    {
        $form = $this->createForm(TripType::class, $trip);
        $form->handleRequest($request);
        //$oldDate = $trip->getDate()->format('Y-m-d');
        //$oldTime = $trip->getDate()->format('h:i');

        if ($form->isSubmitted() && $form->isValid()) {
            $date = $request->request->get('datePicker');
            $time = $request->request->get('timePicker');
            $dateTime = $date . $time;
            $trip->setDate(new DateTime($dateTime));
            $trip->setUpdatedAt(new DateTime('now'));
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('trip_index');
        }

        return $this->render('trip/edit.html.twig', [
            'trip' => $trip,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/beneficiary/{id}", name="trip_delete", methods={"DELETE"})
     * @param Request $request
     * @param Trip $trip
     * @return Response
     */
    public function delete(Request $request, Trip $trip): Response
    {
        if ($this->isCsrfTokenValid('delete' . $trip->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($trip);
            $entityManager->flush();
        }

        return $this->redirectToRoute('trip_index');
    }
}
