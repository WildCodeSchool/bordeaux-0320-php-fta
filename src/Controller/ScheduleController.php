<?php


namespace App\Controller;

use App\Entity\ScheduleVolunteer;
use App\Entity\User;
use App\Service\CalendarService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;

/**
 * Class ScheduleController
 * @package App\Controller
 */
class ScheduleController extends AbstractController
{
    /**
     * @Route("/calendar", name="calendar_schedule")
     */
    public function show()
    {
        $calendar = $this->getDoctrine()
            ->getRepository(ScheduleVolunteer::class)
            ->findAll();

        return $this->render('schedule/calendar.html.twig', [
            'calendars' => $calendar

        ]);
    }

    /**
     * @Route("/ajax/schedule", name="ajax_schedule")
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function ajaxAddSchedule(Request $request): JsonResponse
    {
        $entityManager = $this->getDoctrine()
            ->getManager();
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find(1); //TODO: change when log is ready
        $schedule = new ScheduleVolunteer();
        $date = new DateTime($request->request->get('datePicker'));
        $schedule->setDate($date);
        $schedule->setUser($user);
        $schedule->setIsAfternoon($request->request->has('afternoon') ? true : false);
        $schedule->setIsMorning($request->request->has('morning') ? true : false);
        $entityManager->persist($schedule);
        $entityManager->flush();
        $availabilityUsers = $user->getScheduleVolunteers();
        $table = CalendarService::transformToJson($availabilityUsers);
        return new JsonResponse($table);
    }
}
