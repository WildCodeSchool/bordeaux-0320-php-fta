<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserPhoto;
use App\Form\MobicoopForm;
use App\Form\PictureType;
use App\Repository\UserPhotoRepository;
use App\Service\ApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class UserController extends AbstractController
{
    /**
     * Route to access user profile page
     * @Route("common/user", name="user_show", methods={"GET", "POST"})
     * @param ApiService $api
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param UserPhotoRepository $userPhoto
     * @return Response
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function show(ApiService $api, Request $request, EntityManagerInterface $entityManager, UserPhotoRepository $userPhoto): Response
    {
        $user = $api->getUserById($this->getUser()->getMobicoopId());
        $pictureUser = $this->getUser()->getProfilePicture();

        $profilePicture = new UserPhoto();

        $form = $this->createForm(PictureType::class, $profilePicture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($profilePicture);
            $new = $this->getUser()->setProfilePicture($profilePicture);
            $entityManager->persist($new);
            $entityManager->flush();
            $this->redirect($request->headers->get('referer'));
        }

        return $this->render('user/show.html.twig', [
            'user'    => $user,
            'picture' => $pictureUser,
            'form'    => $form->createView(),
        ]);
    }

    /**
     * @Route("/common/user/{id}/edit", name="user_edit", methods={"GET","PUT","POST"})
     * @param int $id
     * @param Request $request
     * @param ApiService $apiService
     * @return Response
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function edit(
        int $id,
        Request $request,
        ApiService $apiService
    ): Response {
        $user = $apiService->getUserById($id);
        $userLocal = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(['mobicoopId' => $id]);

        $form = $this->createForm(MobicoopForm::class, null, [
            'gender' => $user['gender'],
            'status' => $user['status'],
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $client = $apiService->baseUri();
            $client->request('PUT', '/users/' . $id, [
                'json' => $form->getData(),
            ]);

            return $this->redirectToRoute('user_show', ['id' => $userLocal->getId()]);
        }
        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    /**
     * route ajax to activate or deactivate users
     * @Route("/ajax/activate/{id}")
     * @param int $id
     * @return JsonResponse
     */
    public function activateUser(int $id)
    {
        $entityManager   = $this->getDoctrine()->getManager();
        $user            = $this->getDoctrine()
                                ->getRepository(User::class)
                                ->findOneById($id);

        $user->setIsActive(!$user->getIsActive());

        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse('Votre modification a bien été prise en compte');
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
