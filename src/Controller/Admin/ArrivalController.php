<?php

namespace App\Controller\Admin;

use App\Entity\Arrival;
use App\Form\ArrivalType;
use App\Repository\ArrivalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/arrival")
 */
class ArrivalController extends AbstractController
{
    /**
     * @Route("/", name="arrival_index", methods={"GET", "POST"})
     * @param Request $request
     * @param ArrivalRepository $arrivalRepository
     * @return Response
     */
    public function index(Request $request, ArrivalRepository $arrivalRepository): Response
    {
        $arrival = new Arrival();
        $form = $this->createForm(ArrivalType::class, $arrival);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($arrival);
            $entityManager->flush();

            return $this->redirectToRoute('arrival_index');
        }

        return $this->render('admin/arrival/index.html.twig', [
            'arrivals' => $arrivalRepository->findAll(),
            'arrival' => $arrival,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="arrival_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Arrival $arrival
     * @return Response
     */
    public function edit(Request $request, Arrival $arrival): Response
    {
        $form = $this->createForm(ArrivalType::class, $arrival);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('arrival_index');
        }

        return $this->render('admin/arrival/edit.html.twig', [
            'arrival' => $arrival,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="arrival_delete", methods={"DELETE"})
     * @param Request $request
     * @param Arrival $arrival
     * @return Response
     */
    public function delete(Request $request, Arrival $arrival): Response
    {
        if ($this->isCsrfTokenValid('delete'.$arrival->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($arrival);
            $entityManager->flush();
        }

        return $this->redirectToRoute('arrival_index');
    }
}
