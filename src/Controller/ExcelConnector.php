<?php

namespace App\Controller;
use App\Entity\User;
use App\Entity\Commune;
use App\Entity\Province;
use App\Entity\Departement;
use Spatie\SimpleExcel\SimpleExcelReader;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ExcelConnector{
    public static function ImportGeoData($file,$manager){
        $rows = SimpleExcelReader::create($file)->getRows();
        $headers =SimpleExcelReader::create($file)->getHeaders();
        $provinces=array();
        $depts=array();
        $coms=array();
       // dd($rows[0]);
        foreach ($rows as $r){
            if(strtolower($r["type"])=="province"){
                $provinces[]=$r;
            } else if(strtolower($r["type"])=="departement"){
                $depts[]=$r;
            } else if(strtolower($r["type"])=="commune"){
                $coms[]=$r;
            }
        }
        foreach($provinces as $p){
            $prov= new Province();
            $prov->setCode($p["code"]);
            $prov->setLibelle($p["libelle"]);
            $manager->getManager()->persist($prov);
            $manager->getManager()->flush();
        }
        foreach($depts as $d){
            $dep= new Departement();
            $dep->setCode($d["code"]);
            $dep->setLibelle($d["libelle"]);
            $dep->setProvince($manager->getRepository(Province::class)->findOneBy(["code" => $d["code_parent"]]));
            $manager->getManager()->persist($dep);
            $manager->getManager()->flush();
        }
        foreach($coms as $c){
            $com= new Commune();
            $com->setCode($c["code"]);
            $com->setLibelle($c["libelle"]);
            $com->setDepartement($manager->getRepository(Departement::class)->findOneBy(["code" => $c["code_parent"]]));
            $manager->getManager()->persist($com);
            $manager->getManager()->flush();
        }
        //dd($depts[0]["code_parent"]);
    }

    

    public static function ImportUser($file,$manager,UserPasswordHasherInterface $encoder){
        $rows = SimpleExcelReader::create($file)->getRows();
        $rows2 =SimpleExcelReader::create($file)->getRows();
        foreach ($rows as $r){
            if($r["role"]=="ROLE_SUPERVISOR"){
                $user= new User();
                $user->setUsername($r["login"]);
                $user->setNom($r["nom"]);
                $user->setPrenom($r["prenom"]);
                $user->setEmail($r["email"]);
                $user->setRoles([$r["role"]]);
                $user->setTelephone($r["telephone"]);
                $user->setSexe($r["sexe"]);
                $user->setStatus(0);
                $hash= $encoder->hashPassword($user,"123ocel");
                $user->setPassword($hash);
                $manager->getManager()->persist($user);
                $manager->getManager()->flush();
            }
        }
        foreach ($rows2 as $r){
            if($r["role"]!="ROLE_SUPERVISOR"){
                $user = new User();
                $user->setUsername($r["login"]);
                $user->setNom($r["nom"]);
                $user->setPrenom($r["prenom"]);
                $user->setEmail($r["email"]);
                $user->setRoles([$r["role"]]);
                $user->setTelephone($r["telephone"]);
                $user->setSexe($r["sexe"]);
                $user->setStatus(0);
                if ($r["role"] == "ROLE_MANAGER") {
                    $user->setValidateur($manager->getRepository(User::class)->findOneBy(["username" => $r["validateur"]])->getId());
                }
                $hash = $encoder->hashPassword($user, "123ocel");
                $user->setPassword($hash);
                $manager->getManager()->persist($user);
                $manager->getManager()->flush();
            }
        }
    }

   /* public static function ImportResultat($file,$manager){
        $rows = SimpleExcelReader::create($file)->getRows();
        $headers =SimpleExcelReader::create($file)->getHeaders();

      
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
    }*/
}
?>