<?php

namespace PS\GestionBundle\Service\Paiement;

use Exception;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PaiementProApi
{
    const BASE_URL = "https://www.paiementpro.net/webservice/onlinepayment/init/curl-init.php";

    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function initTransact($data)
    {
        $url = self::BASE_URL;

        $paiementData = $this->getPaiementData($data);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $paiementData);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);

        return json_decode($response, true);
        curl_close($ch);
    }

    /**
     * 
     */
    public function setTransact($data)
    {
        $responses = [];
        try {
            $response = $this->initTransact($data);
            if ($response['success'] == true) {
                $responses = $response['url'];
                return $responses;
            }
        } catch (Exception $e) {
            $message = sprintf("Message d'erreur : %s", $e->getMessage());
            $responses['error'] = true;
            $responses['message'] = $message;
            return $responses;
        }
    }

    /**
     * @param array
     */
    public function getPaiementData($data)
    {
        $_route = $this->getReturnRoute();

        $data = [
            'merchantId' => 'PP-F204',
            'countryCurrencyCode' => '225',
            'amount' => $data['amount'],
            'channel' => $data['channel'],
            'customerEmail' => $data['customerEmail'],
            'customerFirstName' => $data['customerFirstName'],
            'customerLastname' => $data['customerLastname'],
            'customerPhoneNumber' => $data['customerPhoneNumber'],
            'referenceNumber' => $data['referenceNumber'],
            'notificationURL' => $_route['notificationURL'],
            'returnURL' => $_route['returnURL'],
            'description' => $data['description'],
            'returnContext' => 'test=2&ok=1&oui=2',
        ];
        $data = json_encode($data);

        return $data;
    }

    /**
     * 
     */
    public function getReturnRoute(): array
    {
        $_route = [];

        $notificationURL = $this->urlGenerator->generate('app_payement_return_csm', [], UrlGeneratorInterface::ABSOLUTE_URL);

        $returnURL = $this->urlGenerator->generate('app_payement_redirect', [], UrlGeneratorInterface::ABSOLUTE_URL);

        $_route['notificationURL'] = $notificationURL;
        $_route['returnURL'] = $returnURL;

        return $_route;
    }
}