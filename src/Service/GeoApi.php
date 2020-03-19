<?php


namespace App\Service;


class GeoApi
{
    protected $url = 'https://geo.api.gouv.fr/';

    public function getCommuneBy(array $params) : array
    {
        $datas = '';
        for($i = 0; $i < sizeof($params); $i++){
            if(!empty($params[$i][1])){
                if(empty($datas)){
                    $datas = '?' . $params[$i][0] . '=' . $params[$i][1];
                }else{
                    $datas = $datas . '&' . $params[$i][0] . '=' . $params[$i][1];
                }
            }
        }

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $this->url . 'communes' . $datas);
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
            'codeDepartement' => $result[0]->codeDepartement,
            'codeRegion' => $result[0]->codeRegion,
            'codesPostaux' => $result[0]->codesPostaux,
            'population' => $result[0]->population,
        ];
    }
}