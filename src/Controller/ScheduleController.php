<?php


namespace App\Controller;

use App\Entity\ScheduleVolunteer;
use App\Entity\User;
use phpDocumentor\Reflection\Types\This;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

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
            'calendar' => $calendar

        ]);
    }

    /**
     * @Route("/ajax/schedule", name="ajax_schedule")
     * @param Request $request
     * @return JsonResponse
     */
    public function ajaxAddSchedule(Request $request): JsonResponse
    {
        $schedule = new ScheduleVolunteer();
        $date = new \DateTime($request->request->get('date'));
        $entityManager = $this->getDoctrine()->getManager();

        $user = $this->getDoctrine()->getRepository(User::class)->find(1);

        $schedule->setDate($date);
        $schedule->setUserId($user); //TODO: change when log is ready

        $schedule->setIsAfternoon($request->request->has('afternoon') ? true : false);
        $schedule->setIsMorning($request->request->has('morning') ? true : false);
        $entityManager->persist($schedule);
        $entityManager->flush();

        json_encode($user->getScheduleVolunteers());

    }

}
