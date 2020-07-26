<?php

namespace App\Service;

use App\Entity\Trip;
use Symfony\Component\DependencyInjection\ContainerInterface;
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
    private $templating;
    private ContainerInterface $container;


    /**
     * EmailService constructor.
     * @param MailerInterface $mailer
     * @param Environment $twig
     * @param ApiService $api
     * @param ContainerInterface $container
     */
    public function __construct(
        MailerInterface $mailer,
        Environment $twig,
        ApiService $api,
        ContainerInterface $container
    ) {
        $this->mailer = $mailer;
        $this->templating = $twig;
        $this->api = $api;
        $this->container = $container;
    }

    const TYPE_CREATED = 'created';
    const TYPE_ACCEPTED = 'confirmation';
    const TYPE_CANCELED = 'canceled';
    const TYPE_ACCOUNT_IS_ACTIVE = 'isActiveAccount';
    const TYPE_ACCOUNT_CREATED = 'createdAccount';

    const SUBJECT_CREATED = 'Un accompagnement a été créé';
    const SUBJECT_ACCEPTED = 'Un accompagnement a été accepté';
    const SUBJECT_CANCELED = 'Un accompagnement a été annulé';
    const SUBJECT_ACCOUNT_ACTIVE = 'Votre compte a été activé';
    const SUBJECT_ACCOUNT_INACTIVE = 'Votre compte a été désactivé';
    const SUBJECT_ACCOUNT_CREATED = 'Votre compte a bien été créé';

    public function newTrip(Trip $trip): void
    {
        $rendering = [];
        $beneficiary = $this->api->getUserById($trip->getBeneficiary()->getMobicoopId());
        $rendering['beneficiary'] = $beneficiary;
        $rendering['trip'] = $trip;
        $email = $this->createBaseEmail(self::SUBJECT_CREATED, self::TYPE_CREATED, $rendering, $beneficiary['email']);

        $this->mailer->send($email);
    }

    /**
     * Send email to Beneficiary and Volunteer for matched confirmation
     * @param Trip $trip
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function acceptedTrip(Trip $trip)
    {
        $rendering = [];
        $beneficiary = $this->api->getUserById($trip->getBeneficiary()->getMobicoopId());
        $volunteer = $this->api->getUserById($trip->getVolunteer()->getMobicoopId());

        $rendering['beneficiary'] = $beneficiary;
        $rendering['volunteer'] = $volunteer;
        $rendering['trip'] = $trip;

        $email = $this->createBaseEmail(
            self::SUBJECT_ACCEPTED,
            self::TYPE_ACCEPTED,
            $rendering,
            $beneficiary['email'],
            $volunteer['email'],
        );

        $this->mailer->send($email);
    }

    /**
     * Send email to Beneficiary and Volunteer for trip canceled
     * @param Trip $trip
     * @param $volunteer
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function canceledTrip(Trip $trip, $volunteer = null)
    {
        $rendering = [];
        $beneficiary = $this->api->getUserById($trip->getBeneficiary()->getMobicoopId());
        $rendering['beneficiary'] = $beneficiary;
        if ($trip->getVolunteer() || $volunteer) {
            $volunteer = $this->api->getUserById(
                $trip->getVolunteer() ? $trip->getVolunteer()->getMobicoopId() : $volunteer->getMobicoopId()
            );
            $rendering['volunteer'] = $volunteer;
        }
        $rendering['trip'] = $trip;

        $email = $this->createBaseEmail(
            self::SUBJECT_CANCELED,
            self::TYPE_CANCELED,
            $rendering,
            $beneficiary['email'],
            $volunteer = $volunteer['email'] ?? ''
        );

        $this->mailer->send($email);
    }

    public function activateAccountMail(bool $isActive, int $mobicoopId)
    {
        $rendering = [];
        $subject = $isActive ? self::SUBJECT_ACCOUNT_ACTIVE : self::SUBJECT_ACCOUNT_INACTIVE;
        $user = $this->api->getUserById($mobicoopId);
        $rendering['user'] = $user;
        $rendering['isActive'] = $isActive;

        $email = $this->createBaseEmail($subject, self::TYPE_ACCOUNT_IS_ACTIVE, $rendering, $user['email']);

        $this->mailer->send($email);
    }

    public function createdAccountMail($user)
    {
        $rendering = [];
        $rendering['user'] = $user;

        $email = $this->createBaseEmail(
            self::SUBJECT_ACCOUNT_CREATED,
            self::TYPE_ACCOUNT_CREATED,
            $rendering,
            $user['email']
        );

        $this->mailer->send($email);
    }

    private function createBaseEmail(
        string $subject,
        string $type,
        array $rendering,
        string $beneficiaryEmail = '',
        string $volunteerEmail = ''
    ): Email {
        $email = (new Email())
            ->from($this->container->getParameter('mailer_from'))
            ->to($beneficiaryEmail);
        if ($volunteerEmail) {
            $email->addTo($volunteerEmail);
        }
        $email->addTo($this->container->getParameter('email_admin'))
        ->subject($subject)
        ->html($this->templating->render('emails/'. $type .'.html.twig', [
            'beneficiary' => $rendering['beneficiary'] ?? '',
            'trip' => $rendering['trip'] ?? '',
            'volunteer' => $rendering['volunteer'] ?? '',
            'user' => $rendering['user'] ?? '',
            'isActive' => $rendering['isActive'] ?? '',
        ]));

        return $email;
    }
}
