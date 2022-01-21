<?php

require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
    
/**
 * Permet de generer le tableaux des environements depuis le fichier excel
 * 
 * @return $sortieHtml
 */
function GenerateAff(){
    $id = 0;
    $sortieHtml = "";
    $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
    $reader->setReadDataOnly(FALSE);
    $spreadsheet = $reader->load('/usr/share/nginx/html/sihm/referentiel_des_environnements.xlsx');
    $worksheet = $spreadsheet->getActiveSheet();
    foreach ($worksheet->getRowIterator() as $row) {
        $sortieHtml.= '<tr>' . PHP_EOL;
        $cellIterator = $row->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,
                                                           //    even if a cell value is not set.
                                                           // By default, only cells that have a value
                                                           //    set will be iterated.
        foreach ($cellIterator as $cell) {      
            $valueCell='';
                // On teste si un lien est présent
            if ($cell->getHyperlink()->getUrl()!='') {
                // Un lien est présent, on renvoie un lien hyper texte
                $valueCell = "<a href='".$cell->getHyperlink()->getUrl()."' target='_blank' id='".$id++."'>".$cell->getValue()."</a>";
            } else {
                // pas de lien on renvoie juste la value
                $id++;
                $valueCell = $cell->getValue();
            }
            $sortieHtml.= "<td>" . $valueCell.'</td>' . PHP_EOL;
        }
        $sortieHtml.= '</tr>' . PHP_EOL;
    }
    return $sortieHtml;
}

/**
 * Implementation du tableaux des environements dans une page html static
 */
Function main(){

    $contenuDyna = GenerateAff();
    if(!$contenuDyna){
        // On test la sortie de la generation si false -> sortie du script
        // Evite d'avoir un tableau avec des erreurs PHP
        echo "Probleme de generation".PHP_EOL;
        return false;
    }

    $today = date("d/m/y");
    $corpHtml ="<!DOCTYPE html>
    <html lang=\"fr\">
    <head>
        <meta charset=\"UTF-8\">
        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
        <meta http-equiv=\"X-UA-Compatible\" content=\"ie=edge\">
        <link rel=\"stylesheet\" href=\"base.css\">
        <link rel=\"stylesheet\" href=\"referentielsEnvironement.css\">
        <title>Référentiel des Environnements SYMPHONIE</title>
    </head>
    <body>
    <header>
        <img class=\"logo\" src=\"img/logo.png\">
        <h1 class=\"Ref_tittle\">Référentiel des Environnements SYMPHONIE</h1>
        <p class=\"small\">Page optimisée pour firefox et chrome</p>
    </header>
    
    <section class=\"section-main\">
        <p>Dernière mise a jour: $today<br/>
        </p>
    </section>
    <section class=\"section-table\">
    
        <table>
            <tbody>
            $contenuDyna
            </tbody>
        </table>
        <p class=\"info\">* APIX_DEV est connecté au couloir de DEV. Il n'apparaît pas dans le tableau.</p>
        </section>
        <section class=\"liste\">
            <ul>
                <li >
                    <a href=\"Référentiel_des_versions_applicatives_ODS.html\" target=\"_blank\">Référentiels des versions des applications</a>
                    <!-- a href=\"file://nas37hpd2/CTO0R00_SYMPHONIE/ODS_Transverses/Réfrentiel des versions applicatives ODS.html\" target=\"_blank\">Référentiels des versions des applications
                </a -->
                </li>
            </ul>
        </section>
    </body>
    </html>";

    // On ouvre et créer le fichier 
    $fp = fopen('/usr/share/nginx/html/sihm/public/index.html','w');
    //verification de l'ouverture ou de la creation du fichier
    if($fp == true){
        // On écrit dedans 
        //Verifie si ca as bien ecrit dans le fichier static
        if(fwrite($fp,$corpHtml) == true){
            echo"le fichier html a été rempli".PHP_EOL;
        }else{
            echo"l'ecriture a echoué".PHP_EOL;
        }; 
        // On ferme le fichier
        fclose($fp);  
        echo "Creation ok".PHP_EOL;
    } 
    else{
        echo "le fichier html static na pas été créer".PHP_EOL;
    }
}

main();



?>
