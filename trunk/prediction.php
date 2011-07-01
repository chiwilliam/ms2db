<?php
    //build root path (i.e.: C:\xampp\htdocs\)
    $root = $_SERVER['DOCUMENT_ROOT'];
    //fix for tintin root path
    if(trim($root) == "/var/www/html/bioinformatics" || trim($root) == "/var/www"){
        //for tintin
        $root = "/home/whemurad/public_html/disulfidebond";
        //for haddock2
        //$root = "/home/ms2db/public_html/disulfidebond";
        $istintin = "yes";
    }
    else{
        $root .= "/disulfidebond";
    }
    
    
    include $root."/prediction/functionsDAT.php";
    include $root."/prediction/functionsCSP.php";
    
    function getBondsByPredictiveTechniques($bonds,$FASTA,$root,&$time, $transmembranefrom, $transmembraneto){
        
        //$bonds is the array holding all bonds found by the MS/MS method
        //the structure is
        /*
         * $bond[0] = "C1-C2"
         * $bond[1] = "C3-C4"
         * $bond[2] = "C5-C6"
         */
        //$FASTA is the protein's FASTA sequence
        
        //$pbonds is the array holding all bonds found by the predictive method (SVMs)
        //and follows the same format mentioned above
        $pbonds = array();
        $protein = array();
        $CSPmatch = array();
        
        $protein = formatProtein($FASTA, $transmembranefrom, $transmembraneto);
        
        //This is the function that changes according to the SVMs used!
        //Level-1 SVM
        $start = microtime(true);
        $result = runSVM($protein,$root);
        $time["SVM"] = microtime(true) - $start;
        
        //Filtering process using disulfide connectivity from MS-based framework
        //if $bonds is replaced by array(), the filtering does not occur
        //$pbonds = getTruebonds($protein,$result,$bonds);
        $pbonds = getTruebonds($protein,$result,array());
        
        //Level-2 Predictive
        $start = microtime(true);        
        //Level-2 Predictive based on the results from Level-1 SVM and MS/MS
        //$CSPmatch = confirmBondsViaSVM2(&$protein, &$pbonds, $bonds, $root);
        //Level-2 CSPmatchPredictive alone! No filtering nor dependency on others
        $p2bonds = runCSP(&$protein, $pbonds, $root);        
        $time["CSP"] = microtime(true) - $start;
        
        //Back to Level 1 here
        //Remove the bonds with lower scores
        $pbonds = filterSVMBonds($pbonds);
        
        unset($bonds);
        unset($protein);
        unset($result);
        
        return $pbonds;
    }
    
    function filterSVMBonds($pbonds){
        $bonds = array();
        
        $keys = array_keys($pbonds);
        $count = count($keys);
        for($i=0;$i<$count;$i++){
            $bond = $keys[$i];
            $bondWithMaxScore = $i;
            $cys1 = substr($bond, 0, strpos($bond, "-"));
            for($j=$i+1;$j<$count;$j++){
                $bond2 = $keys[$j];
                $cys2 = substr($bond2, 0, strpos($bond2, "-"));
                if($cys1 == $cys2){
                    if($pbonds[$keys[$j]]['score'] > $pbonds[$keys[$bondWithMaxScore]]['score']){
                        $bondWithMaxScore = $j;
                    }                        
                }
                else{
                    $i = $j-1;
                    break;
                }
            }
            $bonds[$keys[$bondWithMaxScore]] = $pbonds[$keys[$bondWithMaxScore]];
        }
        
        return $bonds;
    }
    
    function formatProtein($FASTA, $transmembranefrom, $transmembraneto){
        
        $prot = array();
        
        $prot['FASTA'] = $FASTA;
        $prot['AAs'] = (int)(trim(strlen($FASTA)));
        
        $tmp = getCysteines($FASTA);
        
        $cys = array();
        
        if(strlen($transmembranefrom) > 0 && strlen($transmembraneto) > 0){
            for($i=0;$i<count($tmp);$i++){
                if(($tmp[$i] < (int)$transmembranefrom) || ($tmp[$i] > (int)$transmembraneto)){
                    $cys[] = $tmp[$i];
                }                
            }
        }
        else{
            $cys = $tmp;
        }
        
        $bonds = getBonds($cys);
        
        $prot['BONDS'] = $bonds;
        
        unset($cys);
        unset($bonds);
        
        return $prot;
    }
    
    function runSVM($protein,$root){
        
        $result = array();
        $SVMdata = array();
        
        $SVMdata = getSVMdata($protein);
        
        $filename = generateRandomString();
        
        $folder = "SVM";
        $svmfilename = getFileName($folder,$filename,$root);
        $svmfilenamepredict = $svmfilename.".predict";
        $save = file_put_contents($svmfilename, $SVMdata);
        
        $model = getFileName("prediction", "allsvm.model",$root);
        $command = getFileName("prediction", "svm-predict.exe",$root);
        if(substr($command,0,2) != "C:"){
            $command = substr($command, 0, strlen($command)-4);
        }
        $command .= " -b 1 ".$svmfilename." ".$model." ".$svmfilenamepredict;
        exec($command);
        
        $tmp = file_get_contents($svmfilenamepredict);
        $result = explode("\n", $tmp);
        unset($tmp);
        array_pop(&$result);
        array_shift(&$result);
        
        return $result;
    }
    
    function getCysteines($FASTA){
        
        $cys = array();
        $AAs = array();
        $AAs = str_split($FASTA,1);
        $length = count($AAs);
        
        for($i=0;$i<$length;$i++){
            if($AAs[$i] == 'C'){
                $cys[] = $i+1;
            }
        }
        
        return $cys;
    }
    
    function getBonds($cys){
        
        $bonds = array();
        
        $count = count($cys);
        $bondcount = 0;
        for($i=0;$i<$count-1;$i++){
            for($j=$i+1;$j<$count;$j++){
                $bonds[$bondcount]['BOND'] = $cys[$i].'-'.$cys[$j];
                $bonds[$bondcount]['DOC'] = $cys[$j]-$cys[$i];
                $bondcount++;
            }
        }
        
        return $bonds;
    }
    
    function getSVMdata($protein){
        
        $SVMdata = array();
        
        //form the 13 window: 6AAs+C+6AAs
        $windowsize = 13;        
        //Disulfide Bonds
        $class = "0";
        $count = count($protein['BONDS']);
        for($j=0;$j<$count;$j++){
            $protein['BONDS'][$j]['WINDOWS'] = getWindows($windowsize,$protein['FASTA'],$protein['BONDS'][$j]['BOND']);
            $SVMdata[] = getFeatures($class,$protein['BONDS'][$j]['WINDOWS']['C1'],$protein['BONDS'][$j]['WINDOWS']['C2'],$protein['BONDS'][$j]['DOC']);
        }
        
        return $SVMdata;
    }
    
    function getFileName($folder,$filename,$root){
        
        $path = "";
        $path = $root."/".$folder."/".$filename;
        
        if($_ENV['OS'] == "Windows_NT"){
            $command = str_replace("/", "\\", $command);
        }
        
        return $path;
    }
    
    function generateRandomString() {
        
        $length = 10;
        $characters = "abcdefghijklmnopqrstuvwxyz";
        $string = "";    

        for($i=0;$i<$length;$i++){
            $string .= $characters[mt_rand(0, strlen($characters))];
        }

        return $string;
    }
    
    function getTruebonds($protein,$result, $bonds){
        
        $pbonds = array();        
        $tmpbonds = array();
        $counterbonds = 0;
        
        $count = count($result);
        for($i=0;$i<$count;$i++){
            $data = explode(" ", $result[$i]);
            //If clause used to capture only bonds labeled as +1 (true S-S)
            //If the clause is removed, all bonds are scored, independent of the label
            //if($data[0] == "1"){
                $bond = $protein['BONDS'][$i]['BOND'];
                $score = $data[1];
                $tmpbonds[$counterbonds]['BOND'] = $bond;
                $tmpbonds[$counterbonds]['SCORE'] = $score;
                $tmpbonds[$counterbonds]['INDEX'] = $i;
                $counterbonds++;                
            //}
        }
        
        //$pbonds = filterBonds($tmpbonds,array());
        $pbonds = filterBonds($tmpbonds,$bonds);
        
        unset($tmpbonds);
        return $pbonds;        
    }
    
    function filterBonds($tmpbonds,$bonds){
        
        $pbonds = array();
        $tmpcys = array();
        
        for($j=0;$j<count($bonds);$j++){
            $cys = explode("-", $bonds[$j]);
            $tmpcys[] = $cys[0];
            $tmpcys[] = $cys[1];
        }                
            
        $count = count($tmpbonds);
        for($i=0;$i<$count;$i++){
            //remove bonds already found by MS/MS
            if(!in_array($tmpbonds[$i]['BOND'], $bonds)){
                $cys = explode("-", $tmpbonds[$i]['BOND']);
                $cys1 = $cys[0];
                $cys2 = $cys[1];                
                //remove bonds that are impossible due to bonds found by MS/MS
                if(!(in_array($cys1, $tmpcys) || in_array($cys2, $tmpcys))){
                    $pbonds[$tmpbonds[$i]['BOND']]['bond'] = $tmpbonds[$i]['BOND'];
                    $pbonds[$tmpbonds[$i]['BOND']]['index'] = $tmpbonds[$i]['INDEX'];
                    $pbonds[$tmpbonds[$i]['BOND']]['cys1'] = $cys1;
                    $pbonds[$tmpbonds[$i]['BOND']]['cys2'] = $cys2;
                    $pbonds[$tmpbonds[$i]['BOND']]['score'] = round($tmpbonds[$i]['SCORE'],3);
                    //use exp() function two times to give more importance to higher scores. 
                    //See exponential function graph online!
                    $pbonds[$tmpbonds[$i]['BOND']]['scoreexp'] = round(exp(exp($pbonds[$tmpbonds[$i]['BOND']]['score'])),3);
                }                
            }
        }
        
        return $pbonds;
    }
    
    function confirmBondsViaSVM2(&$protein, &$pbonds, $msbonds, $root){
        
        $filenameDB = getFileName("prediction", "uniprotDB.dat",$root);
        $proteinDB = getProtein($filenameDB);
        $maxProteinLengthDB = getMaxProteinLength(&$proteinDB);
        
        //remove non-bonds from all possible combinations
        $protein['BONDS'] = updateToValidBonds($pbonds, $msbonds);
        
        //remove Bonds that received higher score, but not as high as other bonds
        //clean the graph to speed up Gabow and avoid false positives
        $pbonds = getBondsByMaxScoreExpFromSVM($pbonds);
        $protein['BONDS'] = removeBondsByMaxScoreExpFromSVM($protein['BONDS'], array_keys($pbonds));
        
        $bonds = array_keys($pbonds);        
        $count = count($bonds);
        for($i=0;$i<$count;$i++){
            $pbonds[$bonds[$i]]['csp'] = getCSP($bonds[$i], $msbonds);
            $pbonds[$bonds[$i]]['relativelength'] = round(strlen($protein['FASTA'])/$maxProteinLengthDB,3);
            $protein['BONDS'][$i]['CSP'] = $pbonds[$bonds[$i]]['csp'];
            $protein['BONDS'][$i]['relativelength'] = round($pbonds[$bonds[$i]]['relativelength'],3);
        }

        $countDB = count($proteinDB);
        for($i=0;$i<$countDB;$i++){
            $proteinDB[$i]['CSP'] = getCSPKnownConnectivity($proteinDB[$i]);
        }

        $CSPmatches = array();
        $CSPmatches = getCSPData(&$protein,&$proteinDB);
        
        unset($proteinDB);

        for($i=0;$i<count($CSPmatches);$i++){
            $CSPmatches[$i]['similarity'] = calculateSimilarity($CSPmatches[$i]['CSP']);
        }
        
        for($i=0;$i<$count;$i++){
            $pbonds[$bonds[$i]]['cspd'] = $CSPmatches[$i]['CSP'];
            $pbonds[$bonds[$i]]['proteinDBmatch'] = $CSPmatches[$i]['match'];
            $pbonds[$bonds[$i]]['similarity'] = round($CSPmatches[$i]['similarity'],3);
            $pbonds[$bonds[$i]]['similarityexp'] = exp(exp(round($CSPmatches[$i]['similarity'],3)));
            $protein['BONDS'][$i]['CSPd'] = $CSPmatches[$i]['CSP'];
            $protein['BONDS'][$i]['proteinDBmatch'] = $CSPmatches[$i]['match'];
            $protein['BONDS'][$i]['similarity'] = round($CSPmatches[$i]['similarity'],3);
            $protein['BONDS'][$i]['similarityexp'] = exp(exp(round($CSPmatches[$i]['similarity'],3)));
        }
        
        return $CSPmatches;
    }
    
    function updateToValidBonds($pbonds, $msbonds){
        
        $bonds = array();
        
        for($i=0;$i<count($msbonds);$i++){
            $cys1 = substr($msbonds[$i], 0, strpos($msbonds[$i], "-"));
            $cys2 = substr($msbonds[$i], strpos($msbonds[$i], "-")+1);
            $DOC = $cys2-$cys1;
            $bonds[] = array("BOND" => $msbonds[$i], "DOC" => $DOC);
        }
        
        $keys = array_keys($pbonds);
        for($i=0;$i<count($keys);$i++){
            $cys1 = substr($keys[$i], 0, strpos($keys[$i], "-"));
            $cys2 = substr($keys[$i], strpos($keys[$i], "-")+1);
            $DOC = $cys2-$cys1;
            $bonds[] = array("BOND" => $keys[$i], "DOC" => $DOC);
        }
        
        return $bonds;
    }
    
    function getBondsByMaxScoreExpFromSVM($pbonds){

        $bonds = array();
        $cys = array();
        $scoreexp = 0;
        $maxscorebond = 0;
        $threshold = 2.0;
        
        $keys = array_keys($pbonds);
        $count = count($keys);

        for($i=0;$i<$count;$i++){
            $icys1 = substr($keys[$i], 0, strpos($keys[$i], "-"));
            $scoreexp = $pbonds[$keys[$i]]['scoreexp'];
            $maxscorebond = $i;
            for($j=0;$j<$count;$j++){
                if($i != $j){
                    $cys = explode("-", $keys[$j]);
                    if($icys1 == $cys[0]){
                        if($pbonds[$keys[$j]]['scoreexp'] > $scoreexp){
                            $scoreexp = $pbonds[$keys[$j]]['scoreexp'];
                            $maxscorebond = $j;
                        }
                    }
                }
            }
            if(!in_array($keys[$maxscorebond], $bonds)){
                $bonds[$keys[$maxscorebond]] = $pbonds[$keys[$maxscorebond]];
            }
        }

        return $bonds;
    }
    
    function removeBondsByMaxScoreExpFromSVM($bonds, $pbonds){
        
        $newbonds = array();
        
        for($i=0;$i<count($bonds);$i++){
            if(in_array($bonds[$i]['BOND'], $pbonds)){
                $newbonds[] = $bonds[$i];
            }
        }
        
        return $newbonds;        
    }
    
    function runCSP(&$protein, $pbonds, $root){
        
        $filenameDB = getFileName("prediction", "uniprotDB.dat",$root);
        $proteinDB = getProtein($filenameDB);
        $maxProteinLengthDB = getMaxProteinLength(&$proteinDB);
        
        $countDB = count($proteinDB);
        for($i=0;$i<$countDB;$i++){
            $proteinDB[$i]['CSP'] = getCSPKnownConnectivity($proteinDB[$i]);
        }
        
        $CSPs = array();
        $CSPs = getCSPsFromAllBonds($pbonds);

        $CSPmatches = array();
        $CSPmatches = getCSPMatches(&$CSPs,&$proteinDB);
        
        unset($proteinDB);

        for($k=2;$k<=count($CSPmatches)+1;$k++){
            $count = count($CSPmatches[$k]);
            for($i=0;$i<$count;$i++){
                $CSPmatches[$k][$i]['similarity'] = calculateSimilarity($CSPmatches[$k][$i]['CSPdelta']);
            }
        }
        
        return $CSPmatches;
    }
    
    function getCSPsFromAllBonds($pbonds){
        
        $CSPs = array();
        
        //get number of cysteines and their location
        $bonds = array_keys($pbonds);
        $count = count($bonds);
        $cysInfo = getCysteinesInfo($bonds);
        $cystotal = count($cysInfo);
        
        for($i=0;$i<$count;$i++){
            $CSPs[1][] = array($bonds[$i] => $bonds[$i]);
        }
        
        $totalbonds = 2;
        
        while($totalbonds < ($cystotal/2)){
            
            if($totalbonds == 2){
                $cysteines = array();            
                for($i=0;$i<$count-1;$i++){
                    $bond = $bonds[$i];
                    $cysteines[] = substr($bond, 0, strpos($bond, "-"));
                    $cysteines[] = substr($bond, strpos($bond, "-")+1);
                    for($j=$i+1;$j<$count;$j++){
                        $bond2 = $bonds[$j];
                        $cys1 = substr($bond2, 0, strpos($bond2, "-"));
                        $cys2 = substr($bond2, strpos($bond2, "-")+1);
                        if(!in_array($cys1, $cysteines) && !in_array($cys2, $cysteines)){
                            $CSPs[$totalbonds][] = array($bond => $bond, $bond2 => $bond2);                        
                        }                    
                    }
                    unset($cysteines);            
                }
            }
            
            if($totalbonds == 3){
                $cysteines = array();
                $countCSP2 = count($CSPs[2]);
                $countCSP1 = count($CSPs[1]);
                for($i=0;$i<$countCSP2;$i++){
                    $bondsCSP2 = array_keys($CSPs[2][$i]);
                    for($k=0;$k<count($bondsCSP2);$k++){
                        $bond = $bondsCSP2[$k];
                        $cysteines[] = substr($bond, 0, strpos($bond, "-"));
                        $cysteines[] = substr($bond, strpos($bond, "-")+1);                        
                    }
                    for($j=0;$j<$countCSP1;$j++){
                        $bondCSP1 = key(&$CSPs[1][$j]);
                        $cys1 = substr($bondCSP1, 0, strpos($bondCSP1, "-"));
                        $cys2 = substr($bondCSP1, strpos($bondCSP1, "-")+1);
                        if(!in_array($cys1, $cysteines) && !in_array($cys2, $cysteines)){
                            $CSPs[$totalbonds][] = array($bondCSP1 => $bondCSP1, $bondsCSP2[0] => $bondsCSP2[0], $bondsCSP2[1] => $bondsCSP2[1]);
                        }
                    }
                    unset($cysteines);
                }                
            }
            if($totalbonds == 4){
                $cysteines = array();
                $countCSP2 = count($CSPs[2]);
                for($i=0;$i<$countCSP2-1;$i++){
                    $bondsCSP2 = array_keys($CSPs[2][$i]);
                    for($k=0;$k<count($bondsCSP2);$k++){
                        $bond = $bondsCSP2[$k];
                        $cysteines[] = substr($bond, 0, strpos($bond, "-"));
                        $cysteines[] = substr($bond, strpos($bond, "-")+1);                        
                    }
                    for($j=$i+1;$j<$countCSP2;$j++){
                        $bondsSecondCSP2 = array_keys($CSPs[2][$j]);                            
                        $bond = $bondsSecondCSP2[0];
                        $cys1 = substr($bond, 0, strpos($bond, "-"));
                        $cys2 = substr($bond, strpos($bond, "-")+1);
                        $bond = $bondsSecondCSP2[1];
                        $cys3 = substr($bond, 0, strpos($bond, "-"));
                        $cys4 = substr($bond, strpos($bond, "-")+1);
                        if(!in_array($cys1, $cysteines) && !in_array($cys2, $cysteines) && !in_array($cys3, $cysteines) && !in_array($cys4, $cysteines)){
                            $CSPs[$totalbonds][] = array($bondsCSP2[0] => $bondsCSP2[0], $bondsCSP2[1] => $bondsCSP2[1], $bondsSecondCSP2[0] => $bondsSecondCSP2[0], $bondsSecondCSP2[1] => $bondsSecondCSP2[1]);
                        }
                    }
                    unset($cysteines);
                }
            }
            //I will comment this due to perfomance issues
            /*
            if($totalbonds == 5){
                $cysteines = array();
                $countCSP3 = count($CSPs[3]);
                $countCSP2 = count($CSPs[2]);
                for($i=0;$i<$countCSP3;$i++){
                    $bondsCSP3 = array_keys($CSPs[3][$i]);
                    for($k=0;$k<count($bondsCSP3);$k++){
                        $bond = $bondsCSP3[$k];
                        $cysteines[] = substr($bond, 0, strpos($bond, "-"));
                        $cysteines[] = substr($bond, strpos($bond, "-")+1);                        
                    }
                    for($j=0;$j<$countCSP2;$j++){
                        $bondsCSP2 = array_keys($CSPs[2][$j]);
                        $bond = $bondsCSP2[0];
                        $cys1 = substr($bond, 0, strpos($bond, "-"));
                        $cys2 = substr($bond, strpos($bond, "-")+1);
                        $bond = $bondsCSP2[1];
                        $cys3 = substr($bond, 0, strpos($bond, "-"));
                        $cys4 = substr($bond, strpos($bond, "-")+1);
                        if(!in_array($cys1, $cysteines) && !in_array($cys2, $cysteines) && !in_array($cys3, $cysteines) && !in_array($cys4, $cysteines)){
                            $CSPs[$totalbonds][] = array($bondsCSP3[0] => $bondsCSP3[0], $bondsCSP3[1] => $bondsCSP3[1], $bondsCSP3[2] => $bondsCSP3[2], $bondsCSP2[0] => $bondsCSP2[0], $bondsCSP2[1] => $bondsCSP2[1]);
                        }
                    }
                    unset($cysteines);
                }
            }
            */
            $totalbonds++;
        }
        
        setCSPs(&$CSPs);
        
        return $CSPs;        
    }
    
    function getCysteinesInfo($bonds){
        
        $cys = array();
        
        $count = count($bonds);
        for($i=0;$i<$count;$i++){
            $bond = $bonds[$i];
            $cys1 = substr($bond, 0, strpos($bond, "-"));
            $cys2 = substr($bond, strpos($bond, "-")+1);
            if(!in_array($cys1, $cys)){
                $cys[] = $cys1;
            }
            if(!in_array($cys2, $cys)){
                $cys[] = $cys2;
            }
        }
        sort(&$cys);
        
        return $cys;
    }
    
    function setCSPs(&$CSPs){
        
        $countClasses = count($CSPs);
        for($i=2;$i<=$countClasses;$i++){
            $countBonds = count($CSPs[$i]);
            for($j=0;$j<$countBonds;$j++){
                $bonds = array_keys($CSPs[$i][$j]);
                $cys = array();
                $csp = array();
                for($k=0;$k<count($bonds);$k++){
                    $cys[] = (int)substr($bonds[$k], 0, strpos($bonds[$k], "-"));
                    $cys[] = (int)substr($bonds[$k], strpos($bonds[$k], "-")+1);
                }
                for($k=0;$k<count($cys)-1;$k++){
                    $csp[] = abs($cys[$k+1]-$cys[$k]);
                }
                $CSPs[$i][$j]['CSP'] = $csp;
                unset($cys);
                unset($csp);
            }
        }        
    }

?>
