<?php

namespace App\Controller\Admin;

use App\Entity\Departure;
use App\Form\DepartureType;
use App\Repository\DepartureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/departure")
 */
class DepartureController extends AbstractController
{
    /**
     * @Route("/", name="departure_index", methods={"GET", "POST"})
     * @param Request $request
     * @param DepartureRepository $departureRepository
     * @return Response
     */
    public function index(Request $request, DepartureRepository $departureRepository): Response
    {
        $departure = new Departure();
        $form = $this->createForm(DepartureType::class, $departure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($departure);
            $entityManager->flush();

            return $this->redirectToRoute('departure_index');
        }

        return $this->render('admin/departure/index.html.twig', [
            'departures' => $departureRepository->findAll(),
            'departure' => $departure,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="departure_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Departure $departure
     * @return Response
     */
    public function edit(Request $request, Departure $departure): Response
    {
        $form = $this->createForm(DepartureType::class, $departure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('departure_index');
        }

        return $this->render('admin/departure/edit.html.twig', [
            'departure' => $departure,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="departure_delete", methods={"DELETE"})
     * @param Request $request
     * @param Departure $departure
     * @return Response
     */
    public function delete(Request $request, Departure $departure): Response
    {
        if ($this->isCsrfTokenValid('delete'.$departure->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($departure);
            $entityManager->flush();
        }

        return $this->redirectToRoute('departure_index');
    }
}
