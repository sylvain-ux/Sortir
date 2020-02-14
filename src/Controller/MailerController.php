<?php

// src/Controller/MailerController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class MailerController extends AbstractController
{
    /**
     * @Route("/email/send")
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function sendEmail(MailerInterface $mailer)
    {
        $email = (new Email())
            ->from('contact@guillaume-bex.fr')
            ->to('guillaume.bex44@gmail.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('youhou')
            ->text('mail envoyé')
            ->html('<p>trop <strong>cool</strong></p>');

        $sentEmail = $mailer->send($email);
        return $this->render('main/index.html.twig');
        // $messageId = $sentEmail->getMessageId();


/*        $to      = 'guillaume.bex44@gmail.com';
        $subject = 'le sujet';
        $message = 'Bonjour !';
        $headers = 'From: contact@guillaume-bex.fr' . "\r\n" .
                   'Reply-To: contact@guillaume-bex.fr' . "\r\n" .
                   'X-Mailer: PHP/' . phpversion();

        mail($to, $subject, $message, $headers);*/

        return $this->render('mailer/index.html.twig');

    }
}
