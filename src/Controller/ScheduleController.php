<?php


namespace App\Controller;

use App\Entity\ScheduleVolunteer;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        $table = [];
        $users = $user->getScheduleVolunteers();
        $i = 0;
        foreach ($users as $user) {
            $table[$i]['date'] = $user->getDate()->format('d-m-Y');
            $table[$i]['isMorning'] = $user->getIsMorning();
            $table[$i]['isAfternoon'] = $user->getIsAfternoon();
            $i++;
        }
        dump($table);
        return new JsonResponse($table);
    }

}
