<?php

    function getCSP($bond, $msbonds){
        
        $CSP = array();
        $cysteines = array();
        
        $cys1 = substr($bond, 0, strpos($bond, "-"));
        $cys2 = substr($bond, strpos($bond, "-")+1);
        $cysteines[] = $cys1;
        $cysteines[] = $cys2;
        
        for($i=0;$i<count($msbonds);$i++){
            $msbond = $msbonds[$i];
            $cys1 = substr($msbond, 0, strpos($msbond, "-"));
            $cys2 = substr($msbond, strpos($msbond, "-")+1);
            if(!in_array($cys1, $cysteines)){
                $cysteines[] = $cys1;
            }
            if(!in_array($cys2, $cysteines)){
                $cysteines[] = $cys2;
            }
        }
        
        for($i=1;$i<count($cysteines);$i++){
            $CSP[] = abs($cysteines[$i]-$cysteines[$i-1]);
        }
        
        return $CSP;
    }
    
    //used for Swiss-Prot DB where disulfide connectivity is known
    function getCSPKnownConnectivity($protein){
        
        $CSP = array();
        $cysteines = array();
        
        for($i=0;$i<count($protein['BONDS']);$i++){
            $bond = $protein['BONDS'][$i]['BOND'];
            $cys1 = substr($bond, 0, strpos($bond, "-"));
            $cys2 = substr($bond, strpos($bond, "-")+1);
            if(!in_array($cys1, $cysteines)){
                $cysteines[] = $cys1;
            }
            if(!in_array($cys2, $cysteines)){
                $cysteines[] = $cys2;
            }
        }
        
        for($i=1;$i<count($cysteines);$i++){
            $CSP[] = abs($cysteines[$i]-$cysteines[$i-1]);
        }
        
        return $CSP;
    }
    
    function getCSPData(&$protein, &$proteinDB){
        
        $CSPmatches = array();
        
        $count = count($protein['BONDS']);
        $countDB = count($proteinDB);
        
        for($i=0;$i<$count;$i++){
            $CSPmin = 100000;
            for($j=0;$j<$countDB;$j++){
                if(count($protein['BONDS'][$i]['CSP']) == count($proteinDB[$j]['CSP'])){
                    $CSPd = getCSPDivergence($protein['BONDS'][$i]['CSP'],$proteinDB[$j]['CSP'],$CSPmin);
                    if($CSPd < $CSPmin){
                        $CSPmin = $CSPd;
                        $CSPmatches[$i]['match'] = $j;
                        $CSPmatches[$i]['CSP'] = $CSPd;
                    }
                }            
            }
        }
        
        return $CSPmatches;
    }
    
    function getCSPDivergence($CSP1, $CSP2, $CSPmin){
        
        $CSPd = 0;
        $count = count($CSP1);
        
        //check if it is a valid disulfide bond. The distance between cysteines
        //need to be greater than 0.
        if($CSP1[0] == 0){
            return $CSPmin+1;
        }
        
        for($i=0;$i<$count;$i++){
            $d = $CSP1[$i]-$CSP2[$i];
            if($d < 0){
                $d = 0-$d;
            }
            $CSPd += $d;
        }
        
        return $CSPd;
    }
    
    function calculateSimilarity($distance){
        
        return pow(1+log10(1+($distance/10)), -1);
        
    }
    
    function getMaxProteinLength(&$set){
        
        $length = 0;
        $tmp = 0;
        
        $count = count($set);
        for($i=0;$i<$count;$i++){
            $tmp = strlen($set[$i]['FASTA']);
            if($tmp > $length){
                $length = $tmp;
            }
        }
        unset($tmp);
        
        return $length;
    }
    
    function getCSPMatches(&$CSPs,&$proteinDB){
        
        $CSPmatches = array();
        
        for($k=2;$k<=count($CSPs);$k++){

            $count = count($CSPs[$k]);
            $countDB = count($proteinDB);

            for($i=0;$i<$count;$i++){
                $CSPmin = 100000;
                for($j=0;$j<$countDB;$j++){
                    if(count($CSPs[$k][$i]['CSP']) == count($proteinDB[$j]['CSP'])){
                        $CSPd = getCSPDivergence($CSPs[$k][$i]['CSP'],$proteinDB[$j]['CSP'],$CSPmin);
                        if($CSPd < $CSPmin){
                            $CSPmin = $CSPd;
                            $CSPmatches[$k][$i] = array("match" => $j, "CSPdelta" => $CSPd, "BONDS" => $CSPs[$k][$i], "DB" => $proteinDB[$j]);
                        }
                    }            
                }
            }
        }
        
        $CSPmatches = filterCSPMatches($CSPmatches);
        
        return $CSPmatches;
    }
    
    function filterCSPMatches($CSPmatches){
        
        $matches = array();
        
        for($k=2;$k<=count($CSPmatches)+1;$k++){
            $count = count($CSPmatches[$k]);
            $index = -1;
            $CSPmin = 100000;
            for($i=0;$i<$count;$i++){
                if($CSPmatches[$k][$i]['CSPdelta'] < $CSPmin){
                    $CSPmin = $CSPmatches[$k][$i]['CSPdelta'];
                    $index = $i;
                }
            }
            $matches[$k][] = $CSPmatches[$k][$index];
        }
        
        return $matches;
        
    }
    
    
    
?>
