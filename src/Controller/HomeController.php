<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(): Response
    {
        return $this->render('home.html.twig');
    }

    /**
     * @Route("/change-locale/{locale}", name="change_locale")
     * @param string $locale
     * @param Request $request
     * @return RedirectResponse
     */
    public function changeLocale(string $locale, Request $request)
    {
        $request->getSession()->set('_locale', $locale);
        return $this->redirectToRoute('homepage');
    }
}
