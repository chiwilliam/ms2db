<?php

    ini_set("memory_limit", "3000M");
    set_time_limit(0);

    include 'functionsDAT.php';

    $filename = "C:\\Users\\William\\Desktop\\SFSU\\SVM\\Uniprot\\bonds.txt";
    $svmfilename = "C:\\Users\\William\\Desktop\\SFSU\\SVM\\Uniprot\\SFSU\\bonds";
    
    $protein = getProtein($filename);
    
    $SVMdata = array();
    $SVMdataNoBonds = array();
    $NoBonds = 0;
    
    $count = count($protein);
    //form the 13 window: 6AAs+C+6AAs
    $windowsize = 13;        
    //Disulfide Bonds
    $class = "+1";
    for($i=0;$i<($count-2);$i++){
        for($j=0;$j<count($protein[$i]['BONDS']);$j++){
            $protein[$i]['BONDS'][$j]['WINDOWS'] = getWindows($windowsize,$protein[$i]['FASTA'],$protein[$i]['BONDS'][$j]['BOND']);
            $SVMdata[] = getFeatures($class,$protein[$i]['BONDS'][$j]['WINDOWS']['C1'],$protein[$i]['BONDS'][$j]['WINDOWS']['C2'],$protein[$i]['BONDS'][$j]['DOC']);
        }
    }
    
    file_put_contents($svmfilename, $SVMdata);
    
    echo "Disulfide Bonds: ".count($SVMdata)."<br/>";
    
    unset($SVMdata);
    
    //No Disulfide Bonds
    $class = "-1";
    $div = 100;
    for($i=0;$i<$count;$i++){
        $tmp = array();
        $tmp = getFeaturesNoBonds($class,$windowsize,$protein[$i]['FASTA'],$protein[$i]['BONDS']);
        for($j=0;$j<count($tmp);$j++){
            $SVMdataNoBonds[] = $tmp[$j];
        }
        unset($tmp);
        if(($i-$div) == 0){
            file_put_contents($svmfilename.((string)($div/100)), $SVMdataNoBonds);
            $NoBonds += count($SVMdataNoBonds);
            unset($SVMdataNoBonds);
            $SVMdataNoBonds = array();
            $div += 100;            
        }        
    }
    
    if(count($SVMdataNoBonds) > 0){
        file_put_contents($svmfilename.((string)($div/100)), $SVMdataNoBonds);
        $NoBonds += count($SVMdataNoBonds);
        unset($SVMdataNoBonds);
    }
    
    echo "Non-disulfide Bonds: ".$NoBonds."<br/>";
    
    unset($protein);    
    
    echo "<br/>FINISHED SUCCESSFULLY!";    

?>
