<?php
    ini_set("memory_limit", "1000M");
    set_time_limit(0);

    $filename = "C:\\Users\\William\\Desktop\\SFSU\\SVM\\Uniprot\\42.7\\uniprot_sprot.dat";
    $filename2 = "C:\\Users\\William\\Desktop\\SFSU\\SVM\\Uniprot\\42.7\\uniprot_sprot2.dat";
    $filename3 = "C:\\Users\\William\\Desktop\\SFSU\\SVM\\Uniprot\\42.7\\uniprot_sprot3.dat";
    $filename4 = "C:\\Users\\William\\Desktop\\SFSU\\SVM\\Uniprot\\42.7\\uniprot_sprot4.dat";
    $filename5 = "C:\\Users\\William\\Desktop\\SFSU\\SVM\\Uniprot\\42.7\\uniprot_sprot5.dat";
    $filearray = array();
    
    $countSequences = array("1" => 0,"2" => 0,"3" => 0,"4" => 0,"5" => 0);
    
    $filestr = file_get_contents($filename);
    $filearray = explode("//", $filestr);
    
    unset($filestr);
    
    //Count size of original array
    echo 'Original array: '.count($filearray).'<br/>';
    
    $results = array();
    
    //Extract only proteins which have disulfide bonds in it.
    //copy the disulfide bonds and the FASTA sequence
    for($i=0;$i<count($filearray);$i++){
        $start = strpos($filearray[$i], "FT   DISULFID");
        $end = strpos($filearray[$i], "SQ   SEQUENCE",$start);
        if($start > 0){            
            $results[] = "// ".substr($filearray[$i],$start,($end-$start)).substr($filearray[$i],$end);
        }
    }
    
    unset($filearray);
    
    //Count Filtered array
    echo '<br/>Array containing only proteins with S-S bonds: '.count($results).'<br/>';
    
    //Write to File
    file_put_contents($filename2, $results);
    
    $filearray = array();
    
    //Extract only proteins which have HIGH QUALITY disulfide bonds in it.
    //No probable, potential, by similarity, alternate
    //copy the disulfide bonds and the FASTA sequence
    for($i=0;$i<count($results);$i++){
        $start = strpos($results[$i], "FT   DISULFID");
        $end = strpos($results[$i], "SQ   SEQUENCE",$start);
        $string = substr($results[$i],$start,($end-$start));
        $tmp = array();
        $tmp = explode("FT", $string);
        
        $range = count($tmp);
        for($j=0;$j<$range;$j++){
            if(substr(trim($tmp[$j]),0,8) == "DISULFID"){
                $tmp[$j] = "FT ".trim($tmp[$j]);
            }
            else{
                unset($tmp[$j]);
            }
        }
        ksort($tmp);
        
        $range = count($tmp);
        for($j=1;$j<=$range;$j++){
            $tmp2 = explode(" ", $tmp[$j]);
            $range2 = count($tmp2);
            for($k=0;$k<$range2;$k++){
                if(strlen(trim($tmp2[$k])) == 0){
                    unset($tmp2[$k]);
                }
            }
            ksort($tmp2);
            if(count($tmp2) != 4){
                unset($tmp[$j]);
            }
        }
        ksort($tmp);
        
        $newstr = implode(" ** ", $tmp);
        if(strlen(trim($newstr)) > 0){
            $newstr .= ' -- ';
            $newstr .= substr($results[$i], $end);
            $newstr .= ' // ';
            $filearray[] = $newstr;
        }
        
        unset($tmp);
    }
    
    unset($results);
    
    //Count Newly filtered array
    echo '<br/>Array containing only proteins with valid S-S bonds: '.count($filearray).'<br/>';    
    
    //Write to File
    file_put_contents($filename3, $filearray);    
    
    $results = array();
    
    //Extract only proteins which have no more than 4 disulfide bonds in it.
    //Also remove some missed bad bonds. i.e. similarity, interchain
    //copy the disulfide bonds and the FASTA sequence
    for($i=0;$i<count($filearray);$i++){
        if(substr_count($filearray[$i], "**") < 5 
                && strpos($filearray[$i], "SIMILARITY") == 0
                && strpos($filearray[$i], "INTERCHAIN") == 0
                && strpos($filearray[$i], "OXIDIZED") == 0
                && strpos($filearray[$i], "?") == 0){            
            $results[] = $filearray[$i];
        }
    }
    
    unset($filearray);
    
    //Count Newly Filtered array
    echo '<br/>Array containing only proteins with 4 or less valid S-S bonds: '.count($results).'<br/>';
    
    //Write to File
    file_put_contents($filename4, $results);
    
    $filearray = array();
    
    //Remove duplicated entries. Same # of S-S bonds, same sequence
    //copy the disulfide bonds and the FASTA sequence
    for($i=0;$i<count($results);$i++){
        $start = strpos($results[$i],"AA;");
        $end = strpos($results[$i],"CRC64;");
        $results[$i] = substr($results[$i],0,$start+3)." ".substr($results[$i],$end+6);
    }
    
    $filearray[] = $results[0];
    
    $range = count($results);
    for($i=1;$i<$range;$i++){
        
        $start = strpos($results[$i],"AA;");
        $str = trim(substr($results[$i],$start+3));
        $str = substr($str,0,strlen($str)-2);
        $str = str_replace(" ", "", $str);
        $unique = true;
        $percent = 0;
        for($j=$i+1;$j<$range;$j++){
            $start2 = strpos($results[$j],"AA;");
            $str2 = trim(substr($results[$j],$start2+3));
            $str2 = substr($str2,0,strlen($str2)-2);
            $str2 = str_replace(" ", "", $str2);
            $tmp = similar_text($str, $str2, $percent);
            
            if($percent > 30){
                $unique = false;
                break;
            }
        }
        if($unique == true){
            $filearray[] = $results[$i];
        }
    }
    
    unset($results);
    
    $range = count($filearray);
    for($i=0;$i<$range;$i++){
        //count sequences according to number of S-S bonds
        $sequenceBonds = substr_count($filearray[$i], "**");
        $countSequences[$sequenceBonds+1]++;
    }
    
    //Count Newly filtered array
    echo '<br/>Array containing only unique proteins with 4 or less valid S-S bonds: '.count($filearray).'<br/>';    
    
    //Write to File
    file_put_contents($filename5, $filearray);
    
    unset($filearray);    
    
?>
