<?php

namespace PS\GestionBundle\Service;

use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Doctrine\ORM\EntityManagerInterface;
use PS\GestionBundle\Entity\Message;
use PS\GestionBundle\Entity\DestinataireMessage;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class MtargetApiService
{
    const BASE_URL = 'https://api-public-2.mtarget.fr/';

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

    public function __construct(
        TokenStorageInterface $token, 
        EntityManagerInterface $em
    )
    {
        $this->token = $token;
        $this->em    = $em;
    }

    public function getUrl(string $type){
        $url = self::BASE_URL . $type;
        return $url;
    }

    public function sendSMS($receiverAddress, $message, $senderName)
    {

        $user = null;
        $numbers = $this->normalizeNumbers($receiverAddress);

        if (empty($senderName)) {
            $senderName    = 'CN SANTE CI';
        }

        $success = 0;
        $failed  = 0;
        $result = [];

        if (!$user && !is_object($this->token->getToken()->getUser())) {
            $user = null;
        }

        $_message = new Message();
        $_message->setUtilisateur($user);
        $_message->setContenu($message);
        $_message->setDate(new \DateTime());

        foreach ($numbers as $receiverAddres) {
            $receiverAddresValited = $this->getReceiverAddress($receiverAddres);

            if ($receiverAddresValited) {
                $destinataire = new DestinataireMessage();
                $response     = $this->toSend($receiverAddresValited, $message, $senderName);
                
                $destinataire->setContact($receiverAddresValited);
                
                if (!empty($response['error'])) {
                    $destinataire->setStatut(false);
                    $this->errors[] = $response['error'];
                    $failed += 1;
                } else {
                    $destinataire->setStatut(true);
                    $this->messages[] = sprintf('SMS envoyé avec succès à %s', $receiverAddresValited);
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
        }


        $this->em->persist($_message);
        $this->em->flush();

        return $result;
    }

    
    public function toSend($receiverAddress, $message, $senderName)
    {
        $curl = curl_init();
       
        $request = [
            'username' => 'mauricecom',
            'password' => 'eXziMBhO6sYy',
            'msisdn' => $receiverAddress,
            'msg' => $message,
            'sender' => $senderName,
            'allowunicode' => true,
            'encoding' => 'UTF-8'

        ];

        $curlOptions = [
            CURLOPT_URL => "https://api-public-2.mtarget.fr/messages",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HEADER => false,
            CURLOPT_HTTPHEADER => [
                'Accept' => 'application/json',
                "cache-control" => "no-cache",
            ],
            CURLOPT_POSTFIELDS => http_build_query($request),
        ];

        curl_setopt_array($curl, $curlOptions);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        $error =  "cURL Error #:" . $err;
        $result = [
            'error' => $error,
        ];
        } else {
        $result = [
            'response' => $response
        ];
        }

        return $result;
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

            $receiverAddress = sprintf('%s', $receiverAddress);
        } elseif (strlen($receiverAddress) == 10) {
            $receiverAddress = sprintf('+225%s', $receiverAddress);
        }

        return $receiverAddress;
    }

    public function getSuffixeMobile($numbers)
    {
        // Permet de verifier si les anciens numeros sont a 10
        $numbersValids = [];
        $suffixe_moov = ['01', '02', '03', '40', '41', '42', '43', '50', '51', '52', '53', '70', '71', '72', '73'];
        $suffixe_mtn = ['04', '05', '06', '44', '45', '46', '54', '55', '56', '64', '65', '66', '74', '75', '76', '84', '85', '86', '94', '95', '96'];
        $suffixe_orange = ['07', '08', '09', '47', '48', '49', '57', '58', '59', '67', '68', '69', '77', '78', '79', '87', '88', '89', '97', '98'];


        foreach ($numbers as $key => $value) {
            $prefixe = substr($value, 0, 2);

            if (!(strlen($value) == 10)) {

                if ($prefixe == '00') {
                    // unset($value);
                }

                if ($prefixe && in_array($prefixe, $suffixe_orange)) {
                    $contact = sprintf('07%s', $value);
                    $numbersValids[] = $contact;
                }
                if ($prefixe && in_array($prefixe, $suffixe_mtn)) {
                    $contact = sprintf('05%s', $value);
                    $numbersValids[] = $contact;
                }
                if ($prefixe && in_array($prefixe, $suffixe_moov)) {
                    $contact = sprintf('01%s', $value);
                    $numbersValids[] = $contact;
                }
            } else {
                $numbersValids[] = $value;
            }
        }

        $numbersValids = array_unique($numbersValids);

        $numbersValids = implode(',', $numbersValids);
    }
}
