<?php

namespace PS\GestionBundle\Service;



class Util
{
    /**
     * @param $len
     * @return mixed
     */
    public function random($len = 4, $options = [])
    {
        $alphabet = '0123456789';

        if (isset($options['password'])) {
            $alphabet .= '!"#$%&()*+,-./:;<=>?@[\]^_`{|}~';
        }

        if (isset($options['alphabet'])) {
            $alphabet .= implode('', array_merge(range('a', 'z'), range('A', 'Z')));
        }

        if ($len < 1) {
            throw new \InvalidArgumentException('Length must be a positive integer');
        }

        $str      =  $options['prefix'] ?? '';
        $alphamax = strlen($alphabet) - 1;
        if ($alphamax < 1) {
            throw new \InvalidArgumentException('Invalid alphabet');
        }

        for ($i = 0; $i < $len; ++$i) {
            $str .= $alphabet[random_int(0, $alphamax)];
        }

        return $str;
    }

    /**
     * @return mixed
     */
    public function sendMessage(?string $email, string $username, string $passord)
    {
        $totalsentMessages         = 0;
        $totalNumbers = 0;
        $title = "PPS | TRANSMISSION PARAMÈTRES CONNEXION !";


        $transport = (new \Swift_SmtpTransport('mail.postesante.net', 465, 'ssl'))
        ->setUsername('no-reply@postesante.net')
        ->setPassword(urldecode('Fsi8p0XUrHoD4Kly'));

        $mailer = new \Swift_Mailer($transport);

        $message = (new \Swift_Message($transport))
            ->setSubject($title)
            ->setFrom('no-reply@postesante.net')
            ->setTo($email)
            ->setBody($this->getMessage($email, $username, $passord), 'text/plain');

        return $mailer->send($message);
    }

    /**
     * @param $patient
     * @return mixed
     */
    private function getMessage($email, $username, $passord)
    {
        $message = sprintf("Vos paramètres de connexion sont: \nNom d'utilisateur: %s\nMot de passe par défaut: %s", $username, $passord);


        $message .= "\n\nPASS SANTE";
        return $message;
    }
}