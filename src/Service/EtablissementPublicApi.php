<?php


namespace App\Service;


class EtablissementPublicApi
{
    protected $url = 'https://etablissements-publics.api.gouv.fr/v3/';

    public function getEtablissementBy(string $code, array $etablissementsTypes) : array
    {
        $jsonResult = [];
        foreach ($etablissementsTypes as $etablissementType){
            $curl = curl_init();

            curl_setopt($curl, CURLOPT_URL, $this->url . 'communes/' . $code . '/' . $etablissementType);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');


            $headers = array();
            $headers[] = 'Accept: application/json';
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

            $result = curl_exec($curl);
            if (curl_errno($curl)) {
                return [];
            }
            curl_close($curl);

            $jsonResult = array_merge($jsonResult, (json_decode($result))->features);
        }

        return $jsonResult;
    }
}