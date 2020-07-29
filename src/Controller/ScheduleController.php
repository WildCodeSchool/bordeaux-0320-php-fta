<?php


namespace App\Controller;

use App\Entity\ScheduleVolunteer;
use App\Repository\ScheduleVolunteerRepository;
use App\Service\CalendarService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
     * @param ScheduleVolunteerRepository $scheduleRepository
     * @return JsonResponse|RedirectResponse
     * @throws Exception
     */
    public function ajaxAddSchedule(
        Request $request,
        ScheduleVolunteerRepository $scheduleRepository
    ): JsonResponse {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $schedule = new ScheduleVolunteer();
        $date = new DateTime($request->request->get('datePicker'));
        $scheduleCheck = $scheduleRepository->findOneBy([
            'id' => $user->getId(),
            'date' => $date,
        ]);
        if ($scheduleCheck) {
            $scheduleCheck->setIsAfternoon($request->request->has('afternoon'))
                ->setIsMorning($request->request->has('morning'));
            $entityManager->persist($scheduleCheck);
        } else {
            $schedule->setUser($user)
                ->setDate($date)
                ->setIsAfternoon($request->request->has('afternoon'))
                ->setIsMorning($request->request->has('morning'));
            $entityManager->persist($schedule);
        }

        $entityManager->flush();
        $availabilityUsers = $user->getScheduleVolunteers();
        $table = CalendarService::transformToJson($availabilityUsers);
        return new JsonResponse($table);
    }

    /**
     * Route to delete a schedule
     * @Route("/schedule/delete/{id}", name="delete_schedule")
     * @param ScheduleVolunteer $schedule
     * @param EntityManagerInterface $entityManager
     * @return RedirectResponse
     */
    public function delete(ScheduleVolunteer $schedule, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($schedule);
        $entityManager->flush();

        return $this->redirectToRoute('calendar_schedule');
    }
}
