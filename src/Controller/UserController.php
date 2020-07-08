<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\MobicoopForm;
use App\Repository\UserRepository;
use App\Service\ApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Vich\UploaderBundle\Form\Type\VichFileType;






class UserController extends AbstractController
{

    /**
     * @Route("/user", name="user_index", methods={"GET"})
     * @param UserRepository $userRepository
     * @return Response
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * Route to access user profile page
     * @Route("common/user/{id}", name="user_show", methods={"GET"})
     * @param ApiService $api
     * @param int $id
     * @return Response
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function show(ApiService $api, int $id): Response
    {
        $userLocal = $this->getDoctrine()->getRepository(User::class)->findOneById($id);
        $user = $api->getUserById($userLocal->getMobicoopId())['hydra:member'][0];
        return $this->render('user/show.html.twig', [
            'user' => $user,

        ]);

    }

    /**
     * @Route("/common/user/{id}/edit", name="user_edit", methods={"GET","PUT","POST"})
     * @param Request $request
     * @param ApiService $api
     * @param int $id
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function edit(Request $request, ApiService $api, int $id, EntityManagerInterface $entityManager): Response
    {
        $user = $api->getUserById($id)['hydra:member'][0];
        $userLocal = $this->getDoctrine()
                            ->getRepository(User::class)
                            ->findOneBy(['mobicoopId' => $id]);

        $userLocalId = $userLocal->getId();


        $form = $this->createForm(MobicoopForm::class, $userLocal, [
            'gender' => $user['gender'],
            'status' => $user['status'],
            'edit' => true,

    ]);
        $form->handleRequest($request);
        $api->getToken();

        if ($form->isSubmitted() && $form->isValid()) {

        $entityManager->persist($userLocal);
        $entityManager->flush();

            return $this->redirectToRoute('user_show', ['id' => $userLocalId]);
        }
        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    /**
     * @Route("/user/{id}", name="user_delete", methods={"DELETE"})
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }
}
