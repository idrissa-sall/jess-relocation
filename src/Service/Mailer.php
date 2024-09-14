<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;

class Mailer
{
    public function __construct(private MailerInterface $mailerInterface) {}

    // send email from contact form
    public function sendContactMail($name, $mail, $subject, $message): void
    {
        $email = (new TemplatedEmail())
            ->from('contact@jess-relocation.com')
            ->replyTo($mail)
            ->to(new Address('contact@jess-relocation.com'))
            ->subject($subject)
            ->htmlTemplate('email/contact.html.twig')
            ->context([
                'message'   => $message,
                'name'      => $name,
                'mail'     => $mail,
            ]);

        $this->mailerInterface->send($email);
    }


    // send reservation mail
    public function sendReservationEmail($name): void
    {
        $email = (new TemplatedEmail())
            ->from('contact@jess-relocation.com')
            ->to('contact@jess-relocation.com')
            ->subject('Nouvelle demande de rendez-vous')
            ->htmlTemplate('email/appointment.html.twig')
            ->context([
                'name'  => $name,
            ])
        ;

        $this->mailerInterface->send($email);
    }
}
