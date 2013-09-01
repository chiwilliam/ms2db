<?php

    function getProtein($filename){
        
        $filearray = array();
    
        $filestr = file_get_contents($filename);
        $filearray = explode("//", $filestr);

        unset($filestr);

        $protein = array();

        $count = count($filearray);
        for($i=0;$i<$count;$i++){

            //read Protein into array, separating S-S bonds, length and sequence
            if(strlen(trim($filearray[$i])) > 0){
                $protein[] = readProtein($filearray[$i]);
            }
        }
        
        unset($filearray);
        
        return $protein;
    }

    function readProtein($sequence){
        
        $protein = array();
        
        $fasta = substr($sequence, stripos($sequence, ";")+1);
        $fasta = str_replace(" ", "", $fasta);
        $fasta = str_replace("\n", "", $fasta);
        
        $protein['FASTA'] = trim($fasta);
        
        $start = strpos($sequence,"SEQUENCE");
        $end = strpos($sequence,"AA;");
        
        $AAs = substr($sequence,$start+9,($end-$start-10));
        $AAs = str_replace(" ", "", $AAs);
        
        $protein['AAs'] = ((int)(trim($AAs)));
        
        $end = strpos($sequence,"--");
        
        $bonds = substr($sequence, 0, $end-1);
        $abonds = array();
        $truebonds = array();
        $abonds = explode("**", $bonds);
        $count = count($abonds);
        for($j=0;$j<$count;$j++){
            $abonds[$j] = str_replace("FT", "", $abonds[$j]);
            $abonds[$j] = str_replace("DISULFID", "", $abonds[$j]);
            $abonds[$j] = trim($abonds[$j]);
            $pos = stripos($abonds[$j], " ");
            $c1 = trim(substr($abonds[$j],0,$pos));
            $c2 = trim(substr($abonds[$j],$pos));
            $truebonds[$j]['BOND'] = trim($c1."-".$c2);
            $truebonds[$j]['DOC'] = ((int)($c2))-((int)($c1));
        }
        
        $protein['BONDS'] = $truebonds;
        
        unset($bonds);
        unset($abonds);
        unset($truebonds);
        unset($start);
        unset($end);
        unset($fasta);
        unset($AAs);
        unset($sequence);
        
        return $protein;        
    }
    
    function getWindows($size,$sequence,$bond){
        
        //discount cysteine at center
        $size = $size-1;
        
        $cys = array();
        $windows = array();
        
        $cys = explode("-", $bond);
        for($i=0;$i<count($cys);$i++){
            $cys[$i] = ((int)(trim($cys[$i])));
        }
        
        $c1 = "";
        $start = $cys[0]-($size/2)-1;
        $length = $size+1;
        if($start < 0){
            for($i=0;$i<(0-$start);$i++){
                $c1 .= "X";
            }
            $length = $length+$start;
            $start = 0;            
        }
        $c1 .= substr($sequence, $start, $length);
        while(strlen($c1) < 13){
            $c1 .= "X";
        }
        
        $c2 = "";
        $start = $cys[1]-($size/2)-1;
        $length = $size+1;
        if($start < 0){
            for($i=0;$i<(0-$start);$i++){
                $c2 .= "X";
            }
            $length = $length+$start;
            $start = 0;            
        }
        $c2 .= substr($sequence, $start, $length);
        while(strlen($c2) < 13){
            $c2 .= "X";
        }
        
        $windows['C1'] = $c1;
        $windows['C2'] = $c2;        
        
        return $windows;
    }
    
    function getFeatures($class, $c1window, $c2window, $DOC){
        
        $str = "";
        
        $window = $c1window.$c2window;
        
        $str = convertAAstoVectorinString($class, $window, $DOC);
        
        return $str;
    }
    
    function convertAAstoVectorinString($class, $AAs, $DOC){
        
        $array = array();
        $string = $class."\t";
        
        $count = strlen($AAs);
        for($i=0;$i<$count;$i++){
            $position = 0;
            if($AAs[$i] != "X"){
                $position = getPosition($AAs[$i]);
            }            
            $array = convertPositiontoArray($position);
            $string .= convertArraytoString($i, $count, $array, $DOC);       
        }
        
        unset($array);
        
        return $string;
        
    }
    
    function convertArraytoString($location, $totalAAs, &$array, $DOC){
        
        $str = "";
        
        $normDOC = 1;
        $DOC = $DOC/100;
        if($DOC < 1){
            $normDOC = $DOC;
        }
        
        $total = count($array);
        
        for($i=1;$i<=$total;$i++){
            if($i == $total && $location == ($totalAAs-1)){
                $str .= ((string)($i+($location*20))).":".$array[$i]."\t";
                $str .= ((string)($i+($location*20)+1)).":".((string)($normDOC))."\n";
            }
            else{
                $str .= ((string)($i+($location*20))).":".$array[$i]."\t";
            }            
        }
        
        return $str;
    }
    
    function convertPositiontoArray($position){
        
        $array = array();
        
        for($i=1;$i<=20;$i++){
            if($i==$position){
                $array[$i] = 1;
            }
            else{
                $array[$i] = 0;
            }
        }
        
        return $array;
    }
    
    function getPosition($char){
        
        switch($char){
            case "A":
                return 1;
                break;
            case "R":
                return 2;
                break;
            case "N":
                return 3;
                break;
            case "D":
                return 4;
                break;
            case "C":
                return 5;
                break;
            case "E":
                return 6;
                break;
            case "Q":
                return 7;
                break;
            case "G":
                return 8;
                break;
            case "H":
                return 9;
                break;
            case "I":
                return 10;
                break;
            case "L":
                return 11;
                break;
            case "K":
                return 12;
                break;
            case "M":
                return 13;
                break;
            case "F":
                return 14;
                break;
            case "P":
                return 15;
                break;
            case "S":
                return 16;
                break;
            case "T":
                return 17;
                break;
            case "W":
                return 18;
                break;
            case "Y":
                return 19;
                break;
            case "V":
                return 20;
                break;            
        }
    }
    
    function getFeaturesNoBonds($class, $windowsize, $sequence, $bonds){
        
        $aFeatures = array();
        
        $truebonds = array();
        for($i=0;$i<count($bonds);$i++){
            if($bonds[$i]['BOND'] != 'X-X'){
                $truebonds[] = $bonds[$i]['BOND'];
            }
        }
        
        //find all non-bonded cysteine pairs
        //B(2B-1) cysteine pairs, where B is the number of disulfide bonds
        $pairs = array();
        $pairs = getNotBondedPairs($sequence,$truebonds);
        
        $count = count($pairs);
        for($i=0;$i<$count;$i++){            
            $windows = getWindows($windowsize, $sequence, $pairs[$i]);
            $DOC = getDOC($pairs[$i]);
            $aFeatures[$i] = getFeatures($class, $windows['C1'], $windows['C2'],$DOC);
        }
               
        return $aFeatures;        
    }
    
    function getDOC($bond){
        
        $cys = array();
        $cys = explode("-", $bond);
        $DOC = ((int)($cys[1]) - (int)($cys[0]));
        
        return $DOC;
    }
    
    function getNotBondedPairs($sequence, $truebonds){
        
        $falsebonds = array();
        $finalfalsebonds = array();
        
        //Discarding all NotBondedPairs with known bonded cysteines
        $bondedcys = array();
        for($i=0;$i<count($truebonds);$i++){
            $abond = explode("-", $truebonds[$i]);
            for($j=0;$j<count($abond);$j++){
                $bondedcys[] = $abond[$j];
            }
        }
        //End
            
        $length = strlen($sequence);
        $start = 0;
        $cysteines = array();
        while($start <= $length){
            $pos = strpos($sequence, "C", $start);
            if($pos === false){
                break;
            }
            else{
                //Discarding all NotBondedPairs with known bonded cysteines
                //otherwise, just remove IF condition
                if(!in_array($pos+1, $bondedcys)){
                    $cysteines[] = $pos+1;
                }
                $start = $pos+1;
            }
        }
        
        if(count($cysteines) > 1){
            for($i=0;$i<count($cysteines);$i++){
                for($j=$i+1;$j<count($cysteines);$j++){
                    $falsebonds[] = $cysteines[$i]."-".$cysteines[$j];
                }
            }
        }
        
        $count = count($falsebonds);
        for($i=0;$i<$count;$i++){
            if(!in_array($falsebonds[$i], $truebonds)){
                $finalfalsebonds[] = $falsebonds[$i];
            }
        }
        
        unset($falsebonds);
        unset($truebonds);
        unset($bondedcys);
        
        return $finalfalsebonds;        
    }

?>
