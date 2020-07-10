<?php


namespace App\Controller;

use App\Entity\ScheduleVolunteer;
use App\Service\CalendarService;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;

/**
 * Class ScheduleController
 * @package App\Controller
 */
class ScheduleController extends AbstractController
{
    /**
     * Route for see availability for volunteer (only ROLE_USER_VOLUNTEER)
     * @Route("/volunteer/calendar", name="calendar_schedule")
     * @return Response
     */
    public function show()
    {
        return $this->render('schedule/calendar.html.twig', [
            'calendars' => $this->getUser()->getScheduleVolunteers(),
        ]);
    }

    /**
     * Route ajax to add and refresh schedule list
     * @Route("/ajax/schedule", name="ajax_schedule")
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function ajaxAddSchedule(Request $request): JsonResponse
    {
        $entityManager = $this->getDoctrine()
            ->getManager();
        $user = $this->getUser();
        $schedule = new ScheduleVolunteer();
        $date = new DateTime($request->request->get('datePicker'));
        $schedule->setDate($date);
        $schedule->setUser($user);
        $schedule->setIsAfternoon($request->request->has('afternoon'));
        $schedule->setIsMorning($request->request->has('morning'));
        $entityManager->persist($schedule);
        $entityManager->flush();
        $availabilityUsers = $user->getScheduleVolunteers();
        $table = CalendarService::transformToJson($availabilityUsers);
        return new JsonResponse($table);
    }
}
