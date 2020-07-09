<?php

namespace App\Service;

use App\Entity\Trip;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Twig\Environment;

class EmailService
{
    /**
     * @var MailerInterface
     */
    private MailerInterface $mailer;
    private $sender;
    private $templating;


    /**
     * EmailService constructor.
     * @param MailerInterface $mailer
     * @param $sender
     * @param Environment $twig
     * @param ApiService $api
     */
    public function __construct(MailerInterface $mailer, $sender, Environment $twig, ApiService $api)
    {
        $this->mailer = $mailer;
        $this->sender = $sender;
        $this->templating = $twig;
        $this->api = $api;
    }

    /**
     * @param Trip $trip
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function sendEmail(Trip $trip)
    {
        $beneficiaryId = $trip->getBeneficiary()->getMobicoopId();
        $beneficiary = $this->api->getUserById($beneficiaryId);
        $volunteerId = $trip->getVolunteer()->getMobicoopId();
        $volunteer = $this->api->getUserById($volunteerId);

        $email = (new Email())
            ->from($this->sender)
            ->to('projet.franceterredasile@gmail.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Trip accepted!')
            //->text('Sending emails is fun again!')
            ->html($this->templating->render('emails/confirmation.html.twig', [
                'username' => $beneficiary['givenName'],
                'departure' => $trip->getDeparture()->getName(),
                'arrival' => $trip->getArrival()->getName(),
                'volunteer' => $volunteer['givenName'],


            ]));

        $this->mailer->send($email);

        // ...
    }

    private function getParameter(string $string)
    {
    }
}
