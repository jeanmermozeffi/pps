<?php

namespace PS\GestionBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Osms\Osms;
use PS\GestionBundle\Entity\DestinataireMessage;
use PS\GestionBundle\Entity\Message;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SmsManager implements SmsManagerInterface
{
    /**
     * Message d'erreur
     * @var array
     */
    private $errors = [];

    /**
     * Message de succès
     * @var array
     */
    private $messages = [];

    /**
     * Instance de oSMS
     * @var null
     */
    private $sms;

    /**
     * Jeton d'accès
     * @var string
     */
    private $accessToken;

    /**
     * @var mixed
     */
    private $em;

    /**
     * @var mixed
     */
    private $token;

    /**
     * {@constructor}
     * @param Osms $osms
     */
    public function __construct(Osms $sms, TokenStorageInterface $token, EntityManagerInterface $em)
    {
        $this->sms   = $sms;
        $this->token = $token;
        $this->em    = $em;
    }

    /**
     * Retourne le numéro du destinataire
     * @param  string $receiverAddress
     * @return string
     */
    public function getReceiverAddress($receiverAddress)
    {
        $receiverAddress = preg_replace('/[^0-9+]/', '', $receiverAddress);
        $receiverAddress = trim($receiverAddress);

        if (preg_match('#^((0{2}|\+)(225))(\d{10})$#', $receiverAddress)) {
            if (strlen($receiverAddress) == 15) {
                $receiverAddress = substr_replace($receiverAddress, '+', 0, 2);
            }

            $receiverAddress = sprintf('tel:%s', $receiverAddress);
        } elseif (strlen($receiverAddress) == 10) {
            $receiverAddress = sprintf('tel:+225%s', $receiverAddress);
        } else {
            $receiverAddress = '';
        }

        return $receiverAddress;
    }

    /**
     * retourne le jeton d'accès
     * @return string
     */
    public function getAccessToken()
    {
        return array_get($this->sms->getTokenFromConsumerKey(), 'access_oken');
    }

    /**
     * Normalize phone numbers
     * @param  mixed $numbers
     * @return array
     */
    public function normalizeNumbers($numbers)
    {
        $numbers = (array) $numbers;

        $numbers = array_map('trim', $numbers);

        return array_unique($numbers);
    }

    /**
     * Envoi de SMS à $numbers
     * @param  string $message
     * @param  mixed $numbers
     * @return mixed
     */
    public function send($message, $numbers, $user = null)
    {
        $this->sms->setToken(
            array_get($this->sms->getTokenFromConsumerKey(), 'access_token')
        );

        $numbers = $this->normalizeNumbers($numbers);

        $senderAddress = 'tel:+22500000000';
        $senderName    = '';

        $success = 0;
        $failed  = 0;

         if (!$user && !is_object($this->token->getToken()->getUser())) {
            $user = null;
        }

        $_message = new Message();
        $_message->setUtilisateur($user);
        $_message->setContenu($message);
        $_message->setDate(new \DateTime());

        foreach ($numbers as $receiverAddress) {
            $receiverAddress = $this->getReceiverAddress($receiverAddress);

            if ($receiverAddress) {
                $destinataire = new DestinataireMessage();
                $response     = $this->sms->sendSMS($senderAddress, $receiverAddress, $message, $senderName);

                $destinataire->setContact($receiverAddress);

                if (!empty($response['error'])) {
                    $destinataire->setStatut(false);

                    $this->errors[] = $response['error'];
                    $failed += 1;
                } else {
                    $destinataire->setStatut(true);

                    $this->messages[] = sprintf('SMS envoyé avec succès à %s', $receiverAddress);
                    $success += 1;
                }

                $_message->addDestinataire($destinataire);
            } else {
                $failed += 1;
            }
        }

        if ($success > 0) {
            $_message->setStatut(true);
            $_message->setDateEnvoi(new \DateTime());
            $result = ['success' => $success, 'failed' => $failed];
        } else {
            $_message->setStatut(false);
            $result = false;
        }

        $this->em->persist($_message);
        $this->em->flush();

        return $result;
    }

    public function addToken()
    {
        $this->sms->setToken($this->getAccessToken());
    }

    /**
     * Nombre de SMS restants
     * @return int
     */
    public function availableUnits()
    {
        $contracts = $this->getContracts();

        return array_get($contracts, 'serviceContracts.0.availableUnits', 0);
    }

    /**
     * @return mixed
     */
    public function getContracts()
    {
        $this->sms->setToken(
            array_get($this->sms->getTokenFromConsumerKey(), 'access_token')
        );

        $contracts = array_get($this->sms->getAdminContracts('CIV'), 'partnerContracts.contracts.0', []);

        return $contracts;
    }

    /**
     * Date d'expiration
     * @return DateTime
     */
    public function getExpireDate()
    {
        $contracts = $this->getContracts();

        return array_get($contracts, 'serviceContracts.0.expires');
    }

    /**
     * Retourne les messages d'erreurs
     * @return array
     */
    public function errors()
    {
        return $this->errors;
    }

    /**
     * Retourne les messages d'erreurs
     * @return array
     */
    public function messages()
    {
        return $this->messages;
    }

    private function demo()
    {
        $ch = curl_init('https://api.bizao.com/omoneypay/v1');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            "currency"   => "XOF",
            "order_id"   => "MCM_10",
            "amount"     => 100,
            "return_url" => "http://santemousso.net/test-paiement",
            "cancel_url" => "http://santemousso.net/test-paiement?mode=cancelled",
            "notif_url"  => "http://santemousso.net/validation-paiement",
            "lang"       => "fr",
            "reference"  => "MCM",
        ]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer b6226d4d-7455-3610-9402-5b1ff3da40d9',
            'Accept: application/json',
            'Content-Type: application/json',
            'country-code: CI',
            'mno-name: orange',
        ]);

        $apiResponse = curl_exec($ch);

        var_dump($apiResponse);exit;

    }

    public function mtn_send()
    {
        $data = [
            "sender"     => "PASS MOUSSO",
            "content"    => "text message",
            "encoding"   => "gsm",
            "dlrUrl"     => "santemousso.net",
            "recipients" => ["22565986832"],

        ];
        $ch = curl_init('https://api.smscloud.ci/v1/campains');

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ViVV9YYkj9D0jSM45zHrRuQNTOPyibuy4C8',
            'Accept: application/json',
            'Content-Type: application/json',
            "cache-control" => "no-cache",
        ]);

        $apiResponse = curl_exec($ch);

        var_dump($apiResponse);exit;

    }

}
