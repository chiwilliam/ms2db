<?php
/**
 * Description of AAclass
 *
 * @author William
 */
class AAclass {

    var $AAs = array();

    public function  __construct() {

        /*Average mass
        $this->AAs['A'] = 71.0788;
        $this->AAs['R'] = 156.1875;
        $this->AAs['N'] = 114.1038;
        $this->AAs['D'] = 115.0886;
        $this->AAs['C'] = 103.1388;
        $this->AAs['E'] = 129.1155;
        $this->AAs['Q'] = 128.1307;
        $this->AAs['G'] = 57.0519;
        $this->AAs['H'] = 137.1411;
        $this->AAs['I'] = 113.1594;
        $this->AAs['L'] = 113.1594;
        $this->AAs['K'] = 128.1741;
        $this->AAs['M'] = 131.1926;
        $this->AAs['O'] = 114.1472;
        $this->AAs['F'] = 147.1766;
        $this->AAs['P'] = 97.1167;
        $this->AAs['S'] = 87.0782;
        $this->AAs['T'] = 101.1051;
        $this->AAs['W'] = 186.2132;
        $this->AAs['Y'] = 163.1760;
        $this->AAs['V'] = 99.1326;
        */

        //Monoisotopic values
        $this->AAs['A'] = 71.03711;
        $this->AAs['R'] = 156.10111;
        $this->AAs['N'] = 114.04293;
        $this->AAs['D'] = 115.02694;
        $this->AAs['C'] = 103.00919;
        $this->AAs['E'] = 129.04259;
        $this->AAs['Q'] = 128.05858;
        $this->AAs['G'] = 57.02146;
        $this->AAs['H'] = 137.05891;
        $this->AAs['I'] = 113.08406;
        $this->AAs['L'] = 113.08406;
        $this->AAs['K'] = 128.09496;
        $this->AAs['M'] = 131.04049;
        $this->AAs['O'] = 114.07931;
        $this->AAs['F'] = 147.06841;
        $this->AAs['P'] = 97.05276;
        $this->AAs['S'] = 87.03203;
        $this->AAs['T'] = 101.04768;
        $this->AAs['W'] = 186.07931;
        $this->AAs['Y'] = 163.06333;
        $this->AAs['V'] = 99.06841;
        

    }

    public function  __get($name) {
        return $this->AAs[$name];
    }

    public function calculatePeptideMass($peptide, $matchtype = ""){
        $mass = (float)0.0;

        $AAs = str_split($peptide, 1);

        for($i=0;$i<count($AAs);$i++){
            $mass += $this->__get($AAs[$i]);
        }
        //add OH + H mass to each peptide in the disulfide bond structure for the Initial Match
        //For the confirmed match, do not add because fragment do not have the OH + H groups
        if($matchtype == "IM")
            $mass += 18.01056;
        
        return $mass;
    }

    //function that calculates the masses of all peptide structures on DMS
    public function calculateMassSpaceMass($DMS){

        $keys = array_keys($DMS);

        for($i=0;$i<count($keys);$i++){
            $mass = 0;

            for($j=0;$j<count($DMS[$keys[$i]]["peptides"]);$j++){
                $mass += $this->calculatePeptideMass($DMS[$keys[$i]]["peptides"][$j],"IM");
            }

            if($j>1)
            {
                //subtract H lost with disulfide bond
                //interchain
                $mass -= (2.01564*count($DMS[$keys[$i]]["peptides"])-2.01564);
            }
            else
            {
                //subtract H lost with disulfide bond
                //intrachain
                $mass -= 2.01564;
            }
                
            $DMS[$keys[$i]]["mass"] = $mass;
        }

        return $DMS;

    }

    public function getDeltaRegression($peptides){

        $total = count($peptides);
        $keys = array_keys($peptides);

        $first = (int)substr($keys[0],0,4);
        $last = (int)substr($keys[$total-1],0,4);

        $sum = 0;
        for($i=0;$i<$total;$i++){
            $sum += (int)substr($keys[$i],0,4);
        }
        $average = $sum/$total;
        
        //delta - considers only four proteins (beta-lg, st8siaIV, FucT7, b1-4GalT)
        $delta = 0.007124495*(($last-$first)/$average)-0.000616872*$total+0.036634436;
        //delta2 - considers all proteins, except GnT2
        $delta2 = 0.013938868*(($last-$first)/$average)-0.001082447*$total+0.039490416;
        $difference = (string)((int)((($delta2-$delta)/$delta2)*100)).'%';

        if($delta < 0){
            $delta = 0.0;
        }
        
        return $delta;

    }

    public function getDelta($peptides, $method = 'average'){

        $total = count($peptides);
        $keys = array_keys($peptides);

        $first = (int)substr($keys[0],0,4);
        $last = (int)substr($keys[$total-1],0,4);

        if($method == 'average'){
            $sum = 0;
            for($i=0;$i<$total;$i++){
                $sum += (int)substr($keys[$i],0,4);
            }
            $average = $sum/$total;
            $delta = (double)($last-$first)/$average;
        }
        //median
        else{
            if($total%2 == 0){
                $median = (int)substr($keys[(int)($total/2)],0,4);
            }
            else{
                $median = (int)substr($keys[((int)($total/2)+1)],0,4);
            }
            $delta = (double)($last-$first)/$median;
        }

        $delta = (double)$delta/(2*count($peptides));

        return $delta;
    }

    public function getDeltaDebug($peptides, $method = 'average'){

        $total = count($peptides);
        $keys = array_keys($peptides);

        $first = (int)substr($keys[0],0,4);
        $last = (int)substr($keys[$total-1],0,4);

        if($method == 'average'){
            $sum = 0;
            for($i=0;$i<$total;$i++){
                $sum += (int)substr($keys[$i],0,4);
            }
            $average = $sum/$total;
            $coeficient = (double)($last-$first)/$average;
        }
        //median
        else{
            if($total%2 == 0){
                $median = (int)substr($keys[(int)($total/2)],0,4);
            }
            else{
                $median = (int)substr($keys[((int)($total/2)+1)],0,4);
            }
            $coeficient = (double)($last-$first)/$median;
        }

        $delta = (double)$coeficient/(2*count($peptides));

        $results = array();
        $results['delta'] = $delta;
        $results['2k'] = 2*count($peptides);
        $results['ccpmax'] = $last;
        $results['cppmin'] = $first;
        if($method == 'average'){
            $results['ccpavg'] = $average;
        }
        else{
            $results['ccpavg'] = $median;
            $results['coeficient'] = $coeficient;
        }

        return $results;
    }

    public function removeImpossibleCombinations($list){

        $trimmed = array();

        $keys = array_keys($list);

        for($i=0;$i<count($keys);$i++){
            $structure = $list[$keys[$i]]['peptides'];
            if(count($structure) == 1){
                $trimmed[$keys[$i]] = $list[$keys[$i]];
            }
            else{
                $remove = 'no';
                for($j=0;$j<count($structure);$j++){
                    $peptide = $structure[$j];
                    if($keys[$i] == "2994-37728-000"){
                        $stop = "here";
                    }
                    for($k=$j+1;$k<count($structure);$k++){
                        if(strpos($structure[$k],$peptide) === false
                           && strpos($peptide,$structure[$k]) === false){
                            //do nothing
                        }
                        else{
                            $remove = 'yes';
                            break;
                        }
                    }
                    if($remove == 'yes'){
                        break;
                    }
                }
                if($remove == 'no'){
                    $trimmed[$keys[$i]] = $list[$keys[$i]];
                }
            }
        }
        return $trimmed;
    }

    public function trimListKeepBigger($list,$delta){

        $trimmed = array();

        $keys = array_keys($list);

        $index = count($keys)-1;

        $trimmed[$keys[$index]] = $list[$keys[$index]];

        $last = (int)(substr($keys[$index], 0, 4));

        for($i=($index-1); $i>=0;$i--){
            $current = (int)(substr($keys[$i],0,4));
            //isset($list[$keys[$i]]['IM']) accounts for IMs that would be trimmed
            //In this case, if more than one IM is found the peptides involved are not trimmed
            if($last > ((1+$delta)*$current) || isset($list[$keys[$i]]['IM'])){
                $trimmed[$keys[$i]] = $list[$keys[$i]];
                $last = $current;
            }
            else{
                $value = (1+$delta)*$current;
            }
        }

        $totalList = count($keys);
        $totalTrimmed = count($trimmed);

        ksort($trimmed);

        return $trimmed;
    }

    public function trimListKeepSmaller($list,$delta){

        $trimmed = array();

        $keys = array_keys($list);

        $index = 0;

        $trimmed[$keys[$index]] = $list[$keys[$index]];

        $last = (int)(substr($keys[$index], 0, 4));

        for($i=1; $i<count($keys);$i++){
            $current = (int)(substr($keys[$i],0,4));
            if($last < ((1-$delta)*$current)){
                $trimmed[$keys[$i]] = $list[$keys[$i]];
                $last = $current;
            }
        }

        $totalList = count($keys);
        $totalTrimmed = count($trimmed);

        return $trimmed;
    }

    public function getDeltaCM($peptides, $method = 'average'){

        //In this function I'm not considering if it is a B or Y ion.
        //Im also not considering that it loses 2Da per S-S bond
        //REASON: Low influence in final delta result

        $total = count($peptides);
        //biggest mass: W - 186.2132Da
        $min = 186.2132;
        for($i=0;$i<$total;$i++){
            $average[$i] = 0;
        }
        //smallest mass: G - 57.0519Da
        $max = 57.0519;

        for($i=0;$i<$total;$i++){

            $sum = 0;
            $AAs = str_split($peptides[$i]);
            $countAAs = count($AAs);
            for($j=0;$j<$countAAs;$j++){
                $mass = $this->calculatePeptideMass($AAs[$j]);
                if($mass < $min){
                    $min = $mass;
                }
                if($mass > $max){
                    $max = $mass;
                }
                $sum += $mass;
            }
            $average[$i] = $sum/$countAAs;
            $counttotalAAs[$i] = $countAAs;

        }

        $overallaverage = 0;
        $overallAAs = 0;
        
        for($i=0;$i<$total;$i++){
            $overallaverage += $average[$i];
            $overallAAs += $counttotalAAs[$i];
        }
        //Didn't use 0 to avoid divison by 0 in case an error happens
        if($overallaverage == 0)
            $overallaverage = 1;
        if($overallAAs == 0)
            $overallAAs = 1;

        $overallaverage = $overallaverage/$total;
        $overallAAs = $overallAAs/$total;

        $delta = (double)($max-$min)/$overallaverage;
        $delta = (double)$delta/(2*$overallAAs);

        return $delta;

    }

        public function getDeltaCMRegression($peptides){

        //In this function I'm not considering if it is a B or Y ion.
        //Im also not considering that it loses 2Da per S-S bond
        //REASON: Low influence in final delta result

        $total = count($peptides);
        //biggest mass: W - 186.2132Da
        $min = 186.2132;
        for($i=0;$i<$total;$i++){
            $average[$i] = 0;
        }
        //smallest mass: G - 57.0519Da
        $max = 57.0519;

        for($i=0;$i<$total;$i++){

            $sum = 0;
            $AAs = str_split($peptides[$i]);
            $countAAs = count($AAs);
            for($j=0;$j<$countAAs;$j++){
                $mass = $this->calculatePeptideMass($AAs[$j]);
                if($mass < $min){
                    $min = $mass;
                }
                if($mass > $max){
                    $max = $mass;
                }
                $sum += $mass;
            }
            $average[$i] = $sum/$countAAs;
            $counttotalAAs[$i] = $countAAs;

        }

        $overallaverage = 0;
        $overallAAs = 0;

        for($i=0;$i<$total;$i++){
            $overallaverage += $average[$i];
            $overallAAs += $counttotalAAs[$i];
        }
        //Didn't use 0 to avoid divison by 0 in case an error happens
        if($overallaverage == 0)
            $overallaverage = 1;
        if($overallAAs == 0)
            $overallAAs = 1;

        $overallaverage = $overallaverage/$total;
        $overallAAs = $overallAAs/$total;

        //delta - considers only four proteins (beta-lg, st8siaIV, FucT7, b1-4GalT)
        $delta = 0.006174423*(($max-$min)/$overallaverage)-0.002213736*$total+0.061904351;
        //delta2 - considers all proteins, except GnT2
        $delta2 = 0.034571535*(($max-$min)/$overallaverage)-0.003093646*$total+0.050730533;
        $difference = ((($delta2-$delta)/$delta2)*100);

        /*
        $results = array();
        $results['delta'] = $delta;
        $results['delta2'] = $delta;
        $results['diff'] = $difference;
        $results['%'] = (int)$difference;

        return $results;
        */

        return $delta2;

    }

    public function getDeltaCMDebug($peptides, $method = 'average'){

        //In this function I'm not considering if it is a B or Y ion.
        //Im also not considering that it loses 2Da per S-S bond
        //REASON: Low influence in final delta result

        $total = count($peptides);
        //biggest mass: W - 186.2132Da
        $min = 186.2132;
        for($i=0;$i<$total;$i++){
            $average[$i] = 0;
        }
        //smallest mass: G - 57.0519Da
        $max = 57.0519;

        for($i=0;$i<$total;$i++){

            $sum = 0;
            $AAs = str_split($peptides[$i]);
            $countAAs = count($AAs);
            for($j=0;$j<$countAAs;$j++){
                $mass = $this->calculatePeptideMass($AAs[$j]);
                if($mass < $min){
                    $min = $mass;
                }
                if($mass > $max){
                    $max = $mass;
                }
                $sum += $mass;
            }
            $average[$i] = $sum/$countAAs;
            $counttotalAAs[$i] = $countAAs;

        }

        $overallaverage = 0;
        $overallAAs = 0;

        for($i=0;$i<$total;$i++){
            $overallaverage += $average[$i];
            $overallAAs += $counttotalAAs[$i];
        }
        //Didn't use 0 to avoid divison by 0 in case an error happens
        if($overallaverage == 0)
            $overallaverage = 1;
        if($overallAAs == 0)
            $overallAAs = 1;

        $overallaverage = $overallaverage/$total;
        $overallAAs = $overallAAs/$total;

        $delta = (double)($max-$min)/$overallaverage;
        $delta = (double)$delta/(2*$overallAAs);

        $results = array();
        $results['AAmax'] = $max;
        $results['AAmin'] = $min;
        $results['AAavg'] = $overallaverage;
        $results['||p||'] = $overallAAs;
        $results['gama'] = $delta;
        return $results;

    }

    public function formatFASTAsequence($fastaProtein){

        $tmp = explode("\r\n", $fastaProtein);
        for($i=0;$i<count($tmp);$i++){
            if(substr($tmp[$i], 0, 1) == '>'){
                unset($tmp[$i]);
            }
        }
        $fastaProtein = implode("", $tmp);

        $fastaProtein = strtoupper($fastaProtein);

        $fastaProtein = str_replace("-", "", $fastaProtein);
        $fastaProtein = str_replace("*", "", $fastaProtein);

        $AAs = str_split($fastaProtein, 1);
        $length = count($AAs);

        for($i=0;$i<$length;$i++){
            $isValid = $this->isAAValid($AAs[$i]);
            if($isValid == false){
                $fastaProtein = false;
                break;
            }
        }

        return $fastaProtein;
    }

    public function isAAValid($AA){
        $result = false;

        if($AA == "A" || $AA == "R" || $AA == "N" || $AA == "D" || $AA == "C" ||
           $AA == "E" || $AA == "Q" || $AA == "G" || $AA == "H" || $AA == "I" ||
           $AA == "L" || $AA == "K" || $AA == "M" || $AA == "O" || $AA == "F" ||
           $AA == "P" || $AA == "S" || $AA == "T" || $AA == "W" || $AA == "Y" ||
           $AA == "V" ){

            $result = true;
        }
        
        return $result;
    }

    public function possibleBonds($sequence,$transmembranefrom,$transmembraneto){
        
        $cysteines = array();
        $bonds = array();
        $length = strlen($sequence);

        for($i=1;$i<=$length;$i++){
            $AA = substr($sequence, $i-1,1);
            if($AA == "C" && ($i < (int)$transmembranefrom || $i > (int)$transmembraneto)){
                $cysteines[] = $i;
            }
        }
        
        $length = count($cysteines);

        for($i=0;$i<$length-1;$i++){
            for($j=$i+1;$j<$length;$j++)
            {
                $bonds[] = $cysteines[$i]."-".$cysteines[$j];
            }
        }

        return $bonds;
    }

    public function getMaximumIntensity($intensities){
        
        $max = $intensities[0];

        for($i=1;$i<count($intensities);$i++){
            if($intensities[$i] > $max){
                $max = $intensities[$i];
            }
        }

        return $max;
    }
    
    public function getDeltaInfo($CCP){
        
        $result = array();
        $avg = 0;
        
        $keys = array_keys($CCP);
        $count = count($keys);
        
        for($i=0;$i<$count;$i++){
            $keys[$i] = ((int)(str_replace("-", ".", substr($keys[$i], 0, 6))));
        }
        
        for($i=0;$i<$count;$i++){
            $avg += $keys[$i];
        }
        $avg = $avg/$count;
        
        $result['min'] = $keys[0];
        $result['max'] = $keys[$count-1];
        $result['avg'] = $avg;
        $result['count'] = $count;
        
        return $result;
    }

}
?>
