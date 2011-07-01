<?php

    ini_set("memory_limit", "3000M");
    set_time_limit(0);

    include 'functionsDAT.php';

    $filename = "C:\\Users\\William\\Desktop\\SFSU\\SVM\\Uniprot\\42.7\\uniprot_sprot5.dat";
    $svmfilename = "C:\\Users\\William\\Desktop\\SFSU\\SVM\\Uniprot\\42.7\\svm";
    
    $protein = getProtein($filename);       
    
    $SVMdata = array();
    $SVMdataNoBonds = array();
    $NoBonds = "";
    $totalNoBonds = 0;
    
    $count = count($protein);
    //form the 13 window: 6AAs+C+6AAs
    $windowsize = 13;        
    //Disulfide Bonds
    $class = "+1";
    for($i=0;$i<$count;$i++){
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
    $basediv = 10;
    $div = $basediv;
    for($i=0;$i<$count;$i++){
        $tmp = array();
        $tmp = getFeaturesNoBonds($class,$windowsize,$protein[$i]['FASTA'],$protein[$i]['BONDS']);
        $countbonds = count($tmp);
        for($j=0;$j<$countbonds;$j++){
            $SVMdataNoBonds[] = $tmp[$j];
        }
        unset($tmp);
        if(($i-$div) == 0 || $countbonds >= 100){
            file_put_contents($svmfilename.((string)($div/$basediv)), $SVMdataNoBonds);
            $NoBonds .= "i:".$i." ".$countbonds."<br/>";
            $totalNoBonds += $countbonds;
            unset($SVMdataNoBonds);
            $SVMdataNoBonds = array();
            $div += $basediv;            
        }        
    }
    
    if(count($SVMdataNoBonds) > 0){
        $countbonds = count($SVMdataNoBonds);
        file_put_contents($svmfilename.((string)($div/$basediv)), $SVMdataNoBonds);
        $NoBonds .= "i:".$i." ".$countbonds."<br/>";
        $totalNoBonds += $countbonds;
        unset($SVMdataNoBonds);
    }
    
    echo "Non-disulfide Bonds: <br/>".$NoBonds."<br/><br/>".$totalNoBonds."<br/>";
    
    unset($protein);
    
    echo "<br/>FINISHED SUCCESSFULLY!";
    

?>
