<?php


namespace App\Service;


class GeoApi
{
    protected $url = 'https://geo.api.gouv.fr/';

    public function getCommuneBy(array $api) : array
    {
        $args = '';
        for($i = 0; $i < sizeof($api); $i++){
            if(!empty($api[$i][1])){
                if(empty($args)){
                    $args = '?' . $api[$i][0] . '=' . $api[$i][1];
                }else{
                    $args = $args . '&' . $api[$i][0] . '=' . $api[$i][1];
                }
            }
        }

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $this->url . 'communes' . $args);
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

        $result = json_decode($result);

        if(sizeof($result) === 0){
            return [];
        }

        return [
            'nom' => $result[0]->nom,
            'code' => $result[0]->code,
        ];
    }
}