<?php
class statC{
    // te5ou liste mta les utilisateurs w les criteres, traja3 ken eli yelzmou
    public function filtrer($liste, $role = '', $statut = '', $periode = ''){
        //lundi hata lel dimanche mta el semaine eli ahna fiha
        $debutS = (new DateTimeImmutable('monday this week'))->setTime(0, 0, 0);
        $finS   = (new DateTimeImmutable('sunday this week'))->setTime(23, 59, 59);

        $resultat = [];
        foreach($liste as $u){
            //ken el role matlob mouch kif el role mta user, n3adiw lel user eli ba3dou
            if($role !== '' && $u['role'] !== $role){
                continue;
            }
            if($statut !== '' && $u['statut'] !== $statut){
                continue;
            }
            if($periode === 'semaine'){
                //nconvertiw date cration mta el compte lel format eli hachetna bih
                $date = new DateTimeImmutable($u['date_creation']);
                if($date < $debutS || $date > $finS){
                    continue;
                }
            }
            //ken el user hedha fih les 3 critere nzidoh fel res 
            $resultat[] = $u;
        }
        return $resultat;
    }
}
?>