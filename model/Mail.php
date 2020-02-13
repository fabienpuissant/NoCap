<?php


namespace \App\model\Mail;

class Mail
{
    public function sendEmail(String $email, \Swift_Mailer $mailer)
    {
        $message = (new \Swift_Message('Hello Email'))
        ->setFrom('nocapwebsite@gmail.com')
        ->setTo($email)
        ->setBody(
            $this->renderView(
                // templates/emails/registration.html.twig
                'emails/registration.html.twig',
                ['name' => $name]
            ),
            'text/html'
            )

            // you can remove the following code if you don't define a text version for your emails
            ->addPart(
                $this->renderView(
                    'emails/registration.txt.twig',
                    ['name' => $name]
                ),
                'text/plain'
            )
        ;

        $mailer->send($message);
    }
}