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
        foreach ($rows as $r){
            $province = new Province();
            foreach ($headers as $h){
                if(strtolower($h) == "libelle"){
                    $province->setLibelle($r[$h]);
                } else if(strtolower($h) == "code"){
                    $province->setCode($r[$h]);
                }
            }
            $manager->getManager()->persist($province);
            $manager->getManager()->flush();
        }
    }

    public static function ImportDepartement($file,$manager){
        $rows = SimpleExcelReader::create($file)->getRows();
        $headers =SimpleExcelReader::create($file)->getHeaders();
        foreach ($rows as $r){
            $dep = new Departement();
            foreach ($headers as $h){
                if(strtolower($h) == "province"){
                    $pro = $manager->getRepository(Province::class)->findOneBy(["code"=> $r[$h]]);
                    $dep->setProvince($pro);
                    $dep->setCode($pro->getCode()."D".$r["code"]);
                } else if(strtolower($h) == "libelle"){
                    $dep->setLibelle($r[$h]);
                }
            }
            $manager->getManager()->persist($dep);
            $manager->getManager()->flush();
        }
    }

    public static function ImportCommune($file,$manager){
        $rows = SimpleExcelReader::create($file)->getRows();
        $headers =SimpleExcelReader::create($file)->getHeaders();
        foreach ($rows as $r){
            $com = new Commune();
            foreach ($headers as $h){
                if(strtolower($h) == "departement"){
                    $dep = $manager->getRepository(Departement::class)->findOneBy(["code"=> $r[$h]]);
                    $com->setDepartement($dep);
                    $com->setCode($dep->getCode()."C".$r["code"]);
                } else if(strtolower($h) == "libelle"){
                    $com->setLibelle($r[$h]);
                }
            }
            $manager->getManager()->persist($com);
            $manager->getManager()->flush();
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