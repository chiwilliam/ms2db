<?php
/**
 * Description of ConfirmedMatchclass
 *
 * @author William
 */
class ConfirmedMatchclass {

    //function to screen fragments from a DTA file. The goal is to find all fragments
    //Do 3% screening and consider only the highest intensity picks as matches,
    //according to threshold
    public function screenDataHighPicks($data, $intensityLimit, $threshold, $method = 'median'){

        //get maximum intensity
        $count = count($data);
        $maxIntensity = 0;
        for($i=0;$i<$count;$i++){
            $intensity = (int)substr($data[$i],strpos($data[$i], " ")+1);
            //$value = (float)substr($data[$i],0,strpos($data[$i], " "));
            if($intensity > $maxIntensity){
                $maxIntensity = $intensity;
            }
        }

        //$intensity limit * maximum intensity = screening parameter
        $intensityScreen = (int)(round($maxIntensity*$intensityLimit));

        //screen fragments whose intensity is higher that intensityScreen
        $values = array();
        for($i=0;$i<$count;$i++){
            $intensity = (int)substr($data[$i],strpos($data[$i], " ")+1);
            if($intensity >= $intensityScreen){
                $values[] = $data[$i];
            }
        }
        unset($data);

        //use median values
        $count = count($values);
        $newvalues = array();

        for($i=0;$i<$count;$i++){
            
            //get all values starting from minumum to search for the highest pick
            $intensity = (int)substr($values[$i],strpos($values[$i], " ")+1);
            $value = (float)substr($values[$i],0,strpos($values[$i], " "));

            //considering all peaks
            if($method == 'all'){
                $newvalues[$intensity] = $value;
            }
            else{
                //calculating the median values based on a 2Da window
                if(isset($tmp))
                    unset($tmp);
                $tmp = array();
                $tmp[$intensity] = $value;
                for($j=$i+1;$j<$count;$j++){
                    //next pick information
                    $intensityNP = (int)substr($values[$j],strpos($values[$j], " ")+1);
                    $valueNP = (float)substr($values[$j],0,strpos($values[$j], " "));
                    // +2*threshold due to +-threshold
                    if($valueNP <= ($value + (2*$threshold))){
                        $tmp[$intensityNP] = $valueNP;
                    }
                    else{
                        break;
                    }
                }

                if(count($tmp) > 1){

                    $keys = array_keys($tmp);
                    $total = count($keys);

                    if($total%2 == 0){
                        //get two middle values
                        $intensity1 = $keys[(int)($total/2)-1];
                        $intensity2 = $keys[(int)($total/2)];
                        if($intensity1 >= $intensity2){
                            $newvalues[$intensity1] = $tmp[$intensity1];
                        }
                        else{
                            $newvalues[$intensity2] = $tmp[$intensity2];
                        }

                    }
                    else{
                        $intensity = $keys[(int)($total/2)];
                        $value = $tmp[$intensity];
                        $newvalues[$intensity] = $value;
                    }

                    //avoid repetition. starts search by next value (not analyzed yet)
                    $i = $i+$total-1;
                }
                else{
                    $newvalues[key(&$tmp)] = $tmp[key(&$tmp)];
                }
            }
            
        }
       
        unset($values);

        return $newvalues;
    }

    //function to screen fragments from a DTA file. The goal is to find 50 fragments
    //Try 5% screening. If it retrieves more than 50, consider best 50 (highest intensity)
    //If it doesnt retrieve 50, try 4%, then 3% screening and retrieve better 50.
    public function screenData($data, $intensityLimit, $recordsLimit){

        //store original set in case another screening is necessary
        $tmp = $data;

        //get maximum intensity
        end(&$data);
        //5% of maximum intensity
        $maxIntensity = key(&$data);
        $intensityScreen = (int)(round($maxIntensity*$intensityLimit));
        //$move pointer to first fragment
        reset(&$data);
        //screen fragments whose intensity is lower that intensityScreen
        
        while($intensity = (int)(key(&$data))){
            if($intensity < $intensityScreen){
                unset($data[$intensity]);
            }
            next(&$data);
        }

        $totalRecords = count($data);
        while($totalRecords < $recordsLimit){
            if($intensityLimit < 0.02){
                return $data;
            }
            else{
            $intensityLimit -= 0.01;
            $data = $this->screenData($tmp, $intensityLimit, $recordsLimit);
            $totalRecords = count($data);
            }
        }

        switch($totalRecords){
            case ($totalRecords <= $recordsLimit):
                return $data;
                break;
            case ($totalRecords > $recordsLimit):
                reset(&$data);
                $j = 0;
                while($j < ($totalRecords-$recordsLimit)){
                    $intensity = key(&$data);
                    next(&$data);
                    unset($data[$intensity]);
                    $j++;
               }
               return $data;
                break;
        }
        
    }

    public function formFMS($peptides, $cysteines){

        $FMS = array();

        //check if only intrabond case
        $pepNumber = count($peptides);

        if($pepNumber == 1){
            //intrabond only
            $this->retrieveIntraBondFMSElements(&$FMS, $peptides[0], $cysteines[0]);
        }
        else{
            for($i=0;$i<count($pepNumber);$i++){
                //intrabond only or fragments
                $this->retrieveIntraBondFMSElements(&$FMS, $peptides[$i], $cysteines[$i], $i);
            }

            //treat interbonds
            $this->retrieveInterBondFMSElements(&$FMS, $peptides, $cysteines);
        }

        return $FMS;
    }

    public function retrieveIntraBondFMSElements(&$FMS, $peptide, $cysteines, $fragtype = -1){

        $AAs = new AAclass();

        //Y-ions
        if($fragtype < 0 || $fragtype%2 == 0){
            $fragtype = 'Y';
        }
        else{
            $fragtype = 'y';
        }
        for($i=0;$i<strlen($peptide);$i++){
            $fragment = substr($peptide,$i);
            $peplength = strlen($peptide);
            $mass = $AAs->calculatePeptideMass($fragment,"CM");

            if($i<= $cysteines[(count($cysteines)-2)]){
                //subtract 2Da due to disulfide bond
                $mass -= 2.01564;
            }
            
            //OH on C-terminus and H on N-terminus mass plus 1Da for Y ions
            //because of an extra H in the amino group NH3+
            $mass += 19.01838;
            $FMS[(int)(round($mass))] = array("mass" => $mass,
                "fragment" => $fragment, "peptide" => $peptide,
                "ion" => ($fragtype.($peplength-$i)));
        }
        //B-ions
        if($fragtype < 0 || $fragtype%2 == 0){
            $fragtype = 'B';
        }
        else{
            $fragtype = 'b';
        }
        for($i=strlen($peptide);$i>0;$i--){
            $fragment = substr($peptide,0,$i);
            $mass = $AAs->calculatePeptideMass($fragment,"CM");

            if($i>$cysteines[count($cysteines)-1]){
                //subtract 2Da due to disulfide bond
                $mass -= 2.01564;
            }

            //H on N-terminus mass
            $mass += 1.00782;
            $FMS[(int)(round($mass))] = array("mass" => $mass,
                "fragment" => $fragment, "peptide" => $peptide,
                "ion" => ($fragtype.($i)));
        }
    }

    public function retrieveInterBondFMSElements(&$FMS, $peptides, $cysteines){
        
        $AAs = new AAclass();

        $cysPos = array();

        //search for cysteines on peptides
        //FOR FIRST PEPTIDE
        //if 1 cysteine per peptide mark that cysteine for both b-ions and y-ions
        if(count($cysteines[0]) == 1){
            $cysPos["b0"] = $cysteines[0][0];
            $cysPos["y0"] = $cysPos["b0"];
        }
        //if more than 1 cysteine per peptide mark the first cysteine for b-ions and the last cysteine for y-ions
        //this way will allow all possible interbond (disulfide bond) combinations
        else{
            $cysPos["b0"] = $cysteines[0][0];
            $cysPos["y0"] = $cysteines[0][count($cysteines[0])-1];
        }

        //FOR SECOND PEPTIDE
        if(count($cysteines[1]) == 1){
            $cysPos["b1"] = $cysteines[1][0];
            $cysPos["y1"] = $cysPos["b1"];
        }
        //if more than 1 cysteine per peptide mark the first cysteine for b-ions and the last cysteine for y-ions
        //this way will allow all possible interbond (disulfide bond) combinations
        else{
            $cysPos["b1"] = $cysteines[1][1];
            $cysPos["y1"] = $cysteines[1][count($cysteines[1])-1];
        }

        //calculate peptide lengths
        $length0 = strlen($peptides[0]);
        $length1 = strlen($peptides[1]);

        //define possible combinations
        //b-ions peptide 0 with b-ions peptide 1
        for($i=($length0-1);$i>=$cysPos["b0"];$i--){
            for($j=($length1-1);$j>=$cysPos["b1"];$j--){

                $fragment1 = substr($peptides[0], 0, $i+1);
                $fragment2 = substr($peptides[1], 0, $j+1);

                $mass1 = $AAs->calculatePeptideMass($fragment1,"CM");
                //add 1Da for H on N-terminus
                $mass1 += 1.00782;
                $mass2 = $AAs->calculatePeptideMass($fragment2,"CM");
                //add 1Da for H on N-terminus
                $mass2 += 1.00782;
                
                $mass = $mass1+$mass2;
                //remove 2Da for 2 H lost when disulfide bond is formed
                $mass -= 2.01564;

                if(((strpos($fragment1,'C') > 0) || substr($fragment1, 0, 1) == 'C') 
                    && ((strpos($fragment2,'C') > 0) || substr($fragment2, 0, 1) == 'C')){
                    $FMS[(int)(round($mass))] = array("mass" => $mass,
                        "fragment" => ($fragment1.'<=>'.$fragment2),
                        "peptide" => ($peptides[0].'<=>'.$peptides[1]),
                        "ion" => ('B'.($i+1).'<=>'.'b'.($j+1)));
                }
            }
        }

        //define possible combinations
        //b-ions peptide 0 with y-ions peptide 1
        for($i=($length0-1);$i>=$cysPos["b0"];$i--){
            for($j=0;$j<=$cysPos["y1"];$j++){

                $fragment1 = substr($peptides[0], 0, $i+1);
                $fragment2 = substr($peptides[1], $j, $length1-$j);

                $mass1 = $AAs->calculatePeptideMass($fragment1,"CM");
                //add 1Da for H on N-terminus
                $mass1 += 1.00782;
                $mass2 = $AAs->calculatePeptideMass($fragment2,"CM");
                //add 17Da for OH on N-terminus and 1Da for H on C-terminus
                $mass2 += 18.01056;
                //add 1Da for the proton in the Y ion
                $mass2 += 1.00782;

                $mass = $mass1+$mass2;
                //remove 2Da for 2 H lost when disulfide bond is formed
                $mass -= 2.01564;

                if(((strpos($fragment1,'C') > 0) || substr($fragment1, 0, 1) == 'C')
                    && ((strpos($fragment2,'C') > 0) || substr($fragment2, 0, 1) == 'C')){
                    $FMS[(int)(round($mass))] = array("mass" => $mass,
                        "fragment" => ($fragment1.'<=>'.$fragment2),
                        "peptide" => ($peptides[0].'<=>'.$peptides[1]),
                        "ion" => ('B'.($i+1).'<=>'.'y'.($length1-$j)));
                }
            }
        }

        //define possible combinations
        //y-ions peptide 0 with b-ions peptide 1
        for($i=0;$i<=$cysPos["y0"];$i++){
            for($j=($length1-1);$j>=$cysPos["b1"];$j--){

                $fragment1 = substr($peptides[0], $i, $length0-$i);
                $fragment2 = substr($peptides[1], 0, $j+1);

                $mass1 = $AAs->calculatePeptideMass($fragment1,"CM");
                //add 17Da for OH on N-terminus and 1Da for H on C-terminus
                $mass1 += 18.01056;
                //add 1Da for the proton in the Y ion
                $mass1 += 1.00782;
                $mass2 = $AAs->calculatePeptideMass($fragment2,"CM");
                //add 1Da for H on N-terminus
                $mass2 += 1.00782;

                $mass = $mass1+$mass2;
                //remove 2Da for 2 H lost when disulfide bond is formed
                $mass -= 2.01564;

                if(((strpos($fragment1,'C') > 0) || substr($fragment1, 0, 1) == 'C')
                    && ((strpos($fragment2,'C') > 0) || substr($fragment2, 0, 1) == 'C')){
                    $FMS[(int)(round($mass))] = array("mass" => $mass,
                        "fragment" => ($fragment1.'<=>'.$fragment2),
                        "peptide" => ($peptides[0].'<=>'.$peptides[1]),
                        "ion" => ('Y'.($length0-$i).'<=>'.'b'.($j+1)));
                }
            }
        }

        //define possible combinations
        //y-ions peptide 0 with y-ions peptide 1
        for($i=0;$i<=$cysPos["y0"];$i++){
            for($j=0;$j<=$cysPos["y1"];$j++){

                $fragment1 = substr($peptides[0], $i, $length0-$i);
                $fragment2 = substr($peptides[1], $j, $length1-$j);

                $mass1 = $AAs->calculatePeptideMass($fragment1,"CM");
                //add 17Da for OH on N-terminus and 1Da for the H on C-terminus
                $mass1 += 18.01056;
                //add 1Da for the extra proton at Y ion
                $mass1 += 1.00782;

                $mass2 = $AAs->calculatePeptideMass($fragment2,"CM");
                //add 17Da for OH on N-terminus and 1Da for the H on C-terminus
                $mass2 += 18.01056;
                //add 1Da for the extra proton at Y ion
                $mass1 += 1.00782;

                $mass = $mass1+$mass2;
                //remove 2Da for 2 H lost when disulfide bond is formed
                $mass -= 2.01564;

                if(((strpos($fragment1,'C') > 0) || substr($fragment1, 0, 1) == 'C')
                    && ((strpos($fragment2,'C') > 0) || substr($fragment2, 0, 1) == 'C')){
                    $FMS[(int)(round($mass))] = array("mass" => $mass,
                        "fragment" => ($fragment1.'<=>'.$fragment2),
                        "peptide" => ($peptides[0].'<=>'.$peptides[1]),
                        "ion" => ('Y'.($length0-$i).'<=>'.'y'.($length1-$j)));
                }
            }
        }
        
    }

    public function Cmatch($FMS,$TML, $precursor, $CMthreshold){

        $matches = array();

        /*
        //Confirmed match minimum threshold +- 0.5; therefore subtract 1
        $minMass = ($TML[0]["mass"])-1;
        //Confirmed match maximum threshold +- 1.5; therefore add 3
        $maxMass = ($TML[count($TML)-1]["mass"])+3;

        $FMS = $this->shrinkFMS($FMS, $minMass, $maxMass);
        */
        
        reset(&$FMS);
        while($tmp = current(&$FMS)){
            $mass = $tmp["mass"];
            /*
            if($mass <= 2000){
                $CMthreshold *= 1;
            }
            else{
                if($mass <= 3000){
                    $CMthreshold *= 1.5;
                }
                else{
                    $CMthreshold *= 2.0;
                }
            }
            */
            for($i=0;$i<count($TML);$i++){
                if($mass > ($TML[$i]["mass"] - $CMthreshold) &&
                   $mass < ($TML[$i]["mass"] + $CMthreshold)){
                    //debugging
                    //tries to identify good CMs
                    //check mass from all the structures, including the variance, depending on the charge state
                    $tmp["matches"] = array("FMS" => $mass, "TML" => $TML[$i]["mass"], "variance" => $TML[$i]["charge"]/2);
                    if(($mass - $TML[$i]["mass"]) > 0){
                        $tmp["deviation"] = ($mass - $TML[$i]["mass"]);
                    }
                    else{
                        $tmp["deviation"] = ($TML[$i]["mass"] - $mass);
                    }

                    //for debugging
                    $FMSkey = key(&$FMS);
                    $TMLkey = $i;
                    $tmp["debug"] = array("FMS" => $FMSkey, "TML" => $TMLkey);
                    //end debugging

                    $matches[] = $tmp;
                }
            }
            next(&$FMS);
        }

        return $matches;

    }

    public function FMSPolynomial($TML, $peptides, $cysteines, $CMthreshold, $alliontypes, $delta){

        $result = array();

        //PolynomialSubsetSum
        $counter=0;

        $FMS = array();
        $CM = array();

        $AAs = new AAclass();
        $Common = new Commonclass();
        
        //get value used to trim lists
        //second parameter: average or median
        //$delta = $AAs->getDeltaCM($peptides,'average');
        if($delta == 0){
            $delta = $AAs->getDeltaCMRegression($peptides);
        }
        
        /*
        //calculate delta and implement regression analysis
        //$regression = $AAs->getDeltaCMDebug($peptides,'average');
        $regression = $AAs->getDeltaCMRegression($peptides);
        if(isset($regression)){
            $result = array();
            $result['FMS'] = array();
            $result['CM'] = array();
            $result['REGRESSION'] = $regression;
            return $result;
        }
        */
        
        $fragments = $Common->generateFragments($peptides,$alliontypes);

        $total = count($peptides);
        
        $countTML = count($TML);
        for($k=0;$k<$countTML;$k++){

            $precursorMass = $TML[$k]['mass'];
            $precursorCharge = $TML[$k]['charge'];
            $precursorIntensity = $TML[$k]['intensity'];

            $list1 = array();
            $list1['0000-000'] = array('mass' => 0, 'fragment' => "", 'peptide' => "", 
                                       'ion' => "", 'cysteines' => 0);
            $list2 = array();

            for($w=0;$w<$total;$w++){

                unset($list2);
                $list1keys = array_keys($list1);
                for($z=0;$z<count($list1keys);$z++){

                    $list1fragments = $list1[$list1keys[$z]];

                    $frags = count($fragments[$w]);

                    for($i=0;$i<$frags;$i++){
                        
                        //calculate mass
                        $SSbond = false;
                        $list1mass = $list1fragments["mass"]+$fragments[$w][$i]["mass"];

                        //calculate # of free cysteines and # of S-S bonds
                        $existingcysteines = $list1fragments["cysteines"]+$fragments[$w][$i]["cysteines"];
                        $existingSSbond = substr_count($list1fragments["ion"], "<=>");

                        //check if a new disulfide bond is possible
                        if($existingcysteines >= 2 &&
                            $existingcysteines > ((2*$existingSSbond)-1)+2){
                                $list1mass -= 2.01564;
                                $SSbond = true;
                        }

                        if(($SSbond == true || $list1fragments["mass"] == 0) &
                            ($list1mass <= ($precursorMass + ($CMthreshold+($precursorCharge-1)/2)))){

                                //generate index
                                $list1mass = round($list1mass, 3);
                                $tmp = explode('.', ((string)$list1mass));
                                //ensure sorting works by adjusting index XXXX-XXX digits
                                while(strlen($tmp[0]) < 4)
                                    $tmp[0] = '0'.$tmp[0];
                                while(strlen($tmp[1]) < 3)
                                    $tmp[1] = $tmp[1].'0';
                                $index = $tmp[0].'-'.$tmp[1];

                                //populate array
                                if($list1fragments["mass"] == 0){
                                    $list2[$index] = array('mass' => $list1mass,
                                        'fragment' => $fragments[$w][$i]["fragment"],
                                        'peptide' => $fragments[$w][$i]["peptide"],
                                        'ion' => $fragments[$w][$i]["ion"],
                                        'cysteines' => $fragments[$w][$i]["cysteines"]);
                                }
                                else{
                                    $list2[$index] = array('mass' => $list1mass,
                                        'fragment' => $list1fragments["fragment"]."<=>".$fragments[$w][$i]["fragment"],
                                        'peptide' => $list1fragments["peptide"]."<=>".$fragments[$w][$i]["peptide"],
                                        'ion' => $list1fragments["ion"]."<=>".$fragments[$w][$i]["ion"],
                                        'cysteines' => ($list1fragments["cysteines"]+$fragments[$w][$i]["cysteines"]));
                                }

                                if($list1mass >= ($precursorMass - ($CMthreshold+($precursorCharge-1)/2))){

                                    unset($match);
                                    $match = array();

                                    $match["mass"] = $list2[$index]["mass"];
                                    $match["fragment"] = $list2[$index]["fragment"];
                                    $match["peptide"] = $list2[$index]["peptide"];
                                    $match["ion"] = $list2[$index]["ion"];
                                    $match["cysteines"] = $list2[$index]["cysteines"];
                                    $match["intensity"] = $precursorIntensity;

                                    //check how many b/y ions and how many a/c/x/z ions
                                    $ions = $this->calculateCMIonsDistribution($list2[$index]["ion"]);

                                    //tries to identify good CMs
                                    //check mass from all the structures, including the variance, depending on the charge state
                                    $match["matches"] = array("FMS" => $list1mass, "TML" => $precursorMass);

                                    if(($list1mass - $precursorMass) > 0){
                                        $match["deviation"] = ($list1mass - $precursorMass);
                                    }
                                    else{
                                        $match["deviation"] = ($precursorMass - $list1mass);
                                    }

                                    //for debugging
                                    $match["debug"] = array("FMS" => $index, "TML" => $k, "by" => $ions["by"], "others" => $ions["others"]);
                                    //end debugging

                                    $CM[] = $match;
                                }
                        }
                    }
                }

                //merge and trim list
                if(isset($list2)){
                    $list1 = array_merge($list1, $list2);
                    ksort(&$list1);

                    $list1 = $AAs->trimListKeepBigger($list1,$delta);
                    
                    //$list1 = $AAs->trimListKeepSmaller($list1,$delta);
                    //$list1alpha = $AAs->trimListKeepSmaller($list1,$delta);
                    //$list1beta = $AAs->trimListKeepBigger($list1,$delta);
                    //$list1 = array_merge($list1alpha, $list1beta);
                }

                $FMS = array_merge($list1, $FMS);
            }
        }

        unset($list1);
        unset($list2);

        ksort(&$FMS);

        unset($result);
        $result = array();

        $result['FMS'] = $FMS;
        $result['CM'] = $CM;

        return $result;

    }

    public function expandTMLByCharges($data, $precursor, $TMLthreshold){

        $results = array();

        $TML = array();

        //get maximum intensity
        $AAs = new AAclass();
        $max_intensity = $AAs->getMaximumIntensity(array_keys($data));

        //subtract 1 due to the fact that in a DTA format file, the precursor mass
        //is measured as M+H.
        $precursormass = (float)substr($precursor, 0, strpos($precursor, " "))-1;
        $charge = (int)(substr($precursor, strpos($precursor, " ")+1,1));

        $count = count($data);
        $keys = array_keys($data);

        for($c=1; $c<=$charge; $c++){
            //formula => M = m/z * n - n => m/z = (M+n)/n
            //derived from m/z = (M+nH)^+n, where n = charge state and m/z mass to charge ratio
            for($i=0;$i<$count;$i++){
                $mzvalue = $data[$keys[$i]];
                //$massvalue = ($mzvalue*$c)-$c;
                $massvalue = ($mzvalue*$c);
                if($massvalue <= ($precursormass+$TMLthreshold)){
                    $TML[] = array("mass" => $massvalue, "charge" => $c, "intensity" => ($keys[$i]/$max_intensity), "%highpeak" => number_format(($keys[$i]/$max_intensity)*100).'%');
                }
            }
        }
/*
            //formula => M = m/z * n - n => m/z = (M+n)/n
            //derived from m/z = (M+nH)^+n, where n = charge state and m/z mass to charge ratio
            $frag3max = ($precursormass+3)/3;
            $frag2max = ($precursormass+2)/2;
            $frag1max = ($precursormass+1)/1;

            for($i=0;$i<count($data);$i++){
                $value = $data[$i];
                switch($value){
                    case ($value <= $frag3max):
                        //fragment can be +3, +2 or +1
                        $TML[] = array("mass" => ($value*3)-3, "charge" => 3);
                        $TML[] = array("mass" => ($value*2)-2, "charge" => 2);
                        $TML[] = array("mass" => ($value*1)-1, "charge" => 1);
                        break;
                    case ($value <= $frag2max):
                        //fragment can be just +2 or +1
                        $TML[] = array("mass" => ($value*2)-2, "charge" => 2);
                        $TML[] = array("mass" => ($value*1)-1, "charge" => 1);
                        break;
                    case ($value <= $frag1max):
                        //fragment can be only +1
                        $TML[] = array("mass" => ($value*1)-1, "charge" => 1);
                        break;
                }
            }
*/
        
        //sort TML by mass
        sort(&$TML);

        $results['TML'] = $TML;
        $results['maxintensity'] = $max_intensity;

        return $results;
    }

    public function shrinkFMS($data, $minMass, $maxMass){

        reset(&$data);
        while($tmp = current(&$data)){
            $var = $tmp["mass"];
            if($var < $minMass){
                unset($data[key(&$data)]);
            }
            else
                break;
        }
        end(&$data);
        while($tmp = current(&$data)){
            $var = $tmp["mass"];
            if($var > $maxMass){
                array_pop(&$data);
                end(&$data);
            }
            else
                break;
        }

        return $data;
    }

    public function calculateMassSpaceSizeConsideringIntensity($MassSpace){

        $size = 0;
        
        for($i=0;$i<count($MassSpace);$i++){
            $size += $MassSpace[$i]['intensity'];
        }

        return $size;
    }

    public function calculateCMIonsDistribution($fragment){
        
        $ions = array();
        $by = 0;
        $others = 0;

        for($i=0;$i<count($fragment);$i++){
            $frags = explode('<=>', $fragment);
            for($j=0;$j<count($frags);$j++){
                if(strpos(strtoupper($frags[$j]),"B") === false && strpos(strtoupper($frags[$j]),"Y") === false){
                    $others++;
                }
                else{
                    $by++;
                }
            }
        }

        $ions['by'] = $by;
        $ions['others'] = $others;

        return $ions;
    }

}
?>
