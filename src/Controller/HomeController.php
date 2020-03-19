<?php

namespace App\Controller;

use App\Service\EtablissementPublicApi;
use App\Service\GeoApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        if($_POST && isset($_POST)){
            if(!empty($_POST['city']) && !empty($_POST['postal'])){
                $commune = (new GeoApi())->getCommuneBy([
                    ['nom', $_POST['city']],
                    ['codePostal', $_POST['postal']]
                ]);

                if(sizeof($commune) !== 0){
                    $etablissements = (new EtablissementPublicApi())->getEtablissementBy($commune['code'], [
                        'mairie',
                        'pole_emploi',
                        'gendarmerie',
                        'caf',
                        'urssaf',
                        'commissariat_police',
                        'crous'
                    ]);
                }else{
                    $this->addFlash('danger', 'Aucune ville ne correspond à votre recherche');
                }
            }else{
                $this->addFlash('danger', 'Veuillez remplir tous les champs !');
            }
        }

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'etablissements' => $etablissements ?? 'none',
            'commune' => $commune ?? 'none',
        ]);
    }
}
