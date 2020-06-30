<?php

namespace App\Controller;

use App\Entity\Trip;
use App\Form\TripType;
use App\Service\ApiService;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TripController extends AbstractController
{
    /**
     * Route for beneficiary trip (only ROLE_USER_BENEFICIARY)
     * @Route("/beneficiary/trip", name="trip_beneficiary", methods={"GET"})
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('trip/index.html.twig', [
            'trips' => $this->getUser()->getTrips(),
        ]);
    }

    /**
     * Route for volunteer trip (only ROLE_USER_VOLUNTEER)
     * @Route("/volunteer/trip", name="trip_volunteer")
     * @return Response
     */
    public function myTrip(): Response
    {
        return $this->render('trip/index.html.twig', [
            'trips' => $this->getUser()->getTripsVolunteer(),
        ]);
    }

    /**
     * Route for see matching trips with availability (only ROLE_USER_VOLUNTEER)
     * @Route("/volunteer/matching", name="trip_matching")
     * @return Response
     */
    public function allTrip(): Response
    {
        $user = $this->getUser()->getScheduleVolunteers();
        $trips = null;
        $i = 0;
        foreach ($user as $scheduleVolunteer) {
            $trips[$i] = $this->getDoctrine()->getRepository(Trip::class)
                ->matchingAvailability(
                    $scheduleVolunteer->getIsMorning(),
                    $scheduleVolunteer->getIsAfternoon(),
                    $scheduleVolunteer->getDate()->format('Y-m-d')
                );
            $i++;
        }

        if ($trips[0] === null) {
            $trips = 'error';
        }



        return $this->render('trip/index.html.twig', [
            'trips' => $trips[0],
        ]);
    }

    /**
     * Create new trip (only ROLE_USER_BENEFICIARY)
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
            if (substr($time, -2) === 'AM') {
                $trip->setIsMorning(true);
                $trip->setIsAfternoon(false);
            } else {
                $trip->setIsMorning(false);
                $trip->setIsAfternoon(true);
            }
            $dateTime = $date . $time;
            $trip->setDate(new DateTime($dateTime));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($trip);
            $entityManager->flush();

            $this->addFlash('success', 'New trip added ');

            return $this->redirectToRoute('trip_beneficiary');
        }

        return $this->render('trip/new.html.twig', [
            'trip' => $trip,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Route for show more of one trip (ROLE_USER_VOLUNTEER && ROLE_USER_BENEFICIARY)
     * @Route("/common/trip/{id}", name="trip_show", methods={"GET"})
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
        $userID = $trip->getUser()->getValues()[0]->getMobicoopId();
        $api->getToken();
        $user = $api->getUserById($userID)['hydra:member'][0];

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
     * Route for edit a trip (only ROLE_USER_BENEFICIARY)
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

            return $this->redirectToRoute('trip_beneficiary');
        }

        return $this->render('trip/edit.html.twig', [
            'trip' => $trip,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Route for delete one trip (only ROLE_USER_BENEFICIARY)
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

        return $this->redirectToRoute('trip_beneficiary');
    }
}
