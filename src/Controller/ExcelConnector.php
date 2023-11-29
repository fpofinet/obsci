<?php

namespace App\Controller;

use App\Entity\BureauVote;
use App\Entity\Commune;
use App\Entity\Province;
use App\Entity\Resultat;
use App\Entity\Departement;
use Spatie\SimpleExcel\SimpleExcelReader;

class ExcelConnector{
    public static function ImportProvince($file,$manager){
        $rows = SimpleExcelReader::create($file)->getRows();
        $headers =SimpleExcelReader::create($file)->getHeaders();
        foreach ($headers as $h){
            if(strtolower($h) == "province" || strtolower($h) == "provinces"){
                foreach ($rows as $r){
                    $province = new Province();
                    $province->setLibelle($r[$h]);
                    $manager->getManager()->persist($province);
                    $manager->getManager()->flush();
                }
            }
        }
    }

    public static function ImportDepartement($file,$manager){
        $rows = SimpleExcelReader::create($file)->getRows();
        $headers =SimpleExcelReader::create($file)->getHeaders();
        foreach ($rows as $r){
            $dep = new Departement();
            foreach ($headers as $h){
                if(strtolower($h) == "province"){
                    $dep->setProvince($manager->getRepository(Province::class)->findOneBy(["libelle"=> $r[$h]]));
                }
                if(strtolower($h) == "departement"){
                    $dep->setLibelle($r[$h]);
                }
            }
        }
    }

    public static function ImportResultat($file,$manager){
        $rows = SimpleExcelReader::create($file)->getRows();
        $headers =SimpleExcelReader::create($file)->getHeaders();

        /*if (array_key_exists("nom", $rows->toArray())) {

        }*/
        $i=1;
        $code="res".$i;
        foreach ($rows as $r){
            $res = new Resultat();
            foreach ($headers as $h){
                if(strtolower($h) == "bureauvote"){
                    $res->setBureauVote($manager->getRepository(BureauVote::class)->findOneBy(["code"=> $r[$h]]));
                }
                if(strtolower($h) == "votant"){
                    $res->setVotant($r[$h]);
                }
                if(strtolower($h) == "suffrageexprime"){
                    $res->setSuffrageExprime($r[$h]);
                }
                if(strtolower($h) == "suffragenul"){
                    $res->setSuffrageNul($r[$h]);
                }
                if(strtolower($h) == "voteoui"){
                    $res->setVoteOui($r[$h]);
                }
                if(strtolower($h) == "votenon"){
                    $res->setVoteNon($r[$h]);
                }
            }
            $i = $i +1;
            $code="res".$i;
            $res->setProcesVerbal("null");
            $res->setCode($code);
            $res->setEtat(2);
            
            $manager->getManager()->persist($res);
            $manager->getManager()->flush();
        }
    } 
}
?>