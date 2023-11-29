<?php
namespace App\Controller;

use App\Entity\Resultat;

class Utils{

    public static function comparerResultat(Resultat $resultat1, Resultat $resultat2):bool
    {
        if($resultat1->getVotant() == $resultat2->getVotant() 
            && $resultat1->getSuffrageExprime()==$resultat2->getSuffrageExprime() 
            && $resultat1->getSuffrageNul()==$resultat2->getSuffrageNul() 
            && $resultat1->getVoteOui()==$resultat2->getVoteOui() 
            && $resultat1->getVoteNon()==$resultat2->getVoteNon() 
            )
        {
            return true;
        } else {
            return false;
        }
    }
}
?>