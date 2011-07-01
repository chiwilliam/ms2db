<?php

    ini_set("memory_limit", "3000M");
    set_time_limit(0);

    include 'functionsDAT.php';
    include 'functionsCSP.php';

    $filename = "C:\\Users\\William\\Desktop\\SFSU\\SVM\\Uniprot\\bonds.txt";
    $filenameDB = "C:\\Users\\William\\Desktop\\SFSU\\SVM\\Uniprot\\42.7\\uniprot_sprot5.dat";
    
    $protein = getProtein($filename);
    $proteinDB = getProtein($filenameDB);
    
    $maxProteinLength = getMaxProteinLength(&$protein);
    $maxProteinLengthDB = getMaxProteinLength(&$proteinDB);
    if($maxProteinLengthDB > $maxProteinLength){
        $maxProteinLength = $maxProteinLengthDB;
    }
    unset($maxProteinLengthDB);
    
    $count = count($protein);
    for($i=0;$i<$count;$i++){
        $protein[$i]['CSP'] = getCSP($protein[$i]);
        $protein[$i]['relativelength'] = strlen($protein[$i]['FASTA'])/$maxProteinLength;
    }
    
    $countDB = count($proteinDB);
    for($i=0;$i<$countDB;$i++){
        $proteinDB[$i]['CSP'] = getCSP($proteinDB[$i]);
    }
    
    $CSPmatches = array();
    $CSPmatches = getCSPData(&$protein,&$proteinDB);
    
    for($i=0;$i<count($CSPmatches);$i++){
        $CSPmatches[$i]['similarity'] = calculateSimilarity($CSPmatches[$i]['CSP']);
    }    
    
    $a=1;

?>
