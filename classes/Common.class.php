<?php
/**
 * Description of Commonclass
 *
 * @author William
 */
class Commonclass {

    public function sortByCysteines(&$array){
        uksort(&$array,array($this,"_cmpCysteines"));
    }

    //sort array by cysteines
    private function _cmpCysteines($a,$b){
        $indexA = explode('-',$a);
        $indexB = explode('-',$b);
        if((int)$indexA[0] > (int)$indexB[0]){
            return -1;
        }
        if((int)$indexA[0] < (int)$indexB[0]){
            return 1;
        }
        if((int)$indexA[0] == (int)$indexB[0]){
            if((int)$indexA[1] >= (int)$indexB[1]){
                return -1;
            }
            else{
                return 1;
            }
        }
    }

    public function sortByMass(&$array){
        uksort(&$array,array($this,"_cmpMass"));
    }

    //sort array by mass
    private function _cmpMass($a,$b){
        $indexA = explode('-',$a);
        $indexB = explode('-',$b);
        if((int)$indexA[0] > (int)$indexB[0]){
            return -1;
        }
        if((int)$indexA[0] < (int)$indexB[0]){
            return 1;
        }
        if((int)$indexA[0] == (int)$indexB[0]){
            if((int)$indexA[1] > (int)$indexB[1]){
                return -1;
            }
            if((int)$indexA[1] < (int)$indexB[1]){
                return 1;
            }
            if((int)$indexA[1] == (int)$indexB[1]){
                if((int)$indexA[2] > (int)$indexB[2]){
                    return -1;
                }
                else{
                    return 1;
                }
            }
        }
    }

    //get highest precursor ion mass
    public function getMaxPrecursorMass($PML){

        reset(&$PML);
        $max = key(&$PML);

        $value = $PML[$max];
        $value = (double)substr($value, 0, strpos($value,' '));

        return $value;
    }

    //get lowest precursor ion mass
    public function getMinPrecursorMass($PML){

        end(&$PML);
        $min = key(&$PML);

        $value = $PML[$min];
        $value = (double)substr($value, 0, strpos($value,' '));

        return $value;
    }

    //get number of AA from precursor ion of maximum mass
    public function getMaxPrecursorAAs($PML){

        reset(&$PML);
        $max = key(&$PML);
        
        return substr($max,0,strpos($max,"-"));
    }

    //get number of AA from precursor ion of minimum mass
    public function getMinPrecursorAAs($PML){

        end(&$PML);
        $min = key(&$PML);

        return substr($min,0,strpos($min,"-"));
    }

    //function to shrink DMS to only possible disulfide bonded structures
    public function shrinkDMS($DMS,$min,$max){

        $keys = array_keys($DMS);

        for($i=0;$i<count($keys);$i++){

            $test = $DMS[$keys[$i]];

            $mass = (int)(substr($keys[$i],0,strpos($keys[$i],"-")));
            if($mass < $min || $mass > $max){
                unset($DMS[$keys[$i]]);
            }
        }

        return $DMS;
    }

    /*function to shrink PML to consider number similar masses from DMS values
     * For example:
     * One DMS value has key: 23-3-0 (means the structure has 23 AAs)
     * Therefore consider values on PML, whose number of AA's is 22,23,24
     * which means: PML-1 <= DMS <= PML+1
     * +-1 is because the variation is less than the mass of a single AA
     */
    public function shrinkPML($PML,$DMS){

        $keysDMS = array_keys($DMS);
        $keysPML = array_keys($PML);

        for($i=0;$i<count($keysDMS);$i++){
            $keysDMS[$i] = (int)(substr($keysDMS[$i],0,strpos($keysDMS[$i],"-")));
        }

        for($i=0;$i<count($keysPML);$i++){
            $AAPML = (int)(substr($keysPML[$i],0,strpos($keysPML[$i],"-")));
            if(in_array(($AAPML-1), $keysDMS) || in_array($AAPML, $keysDMS) || in_array(($AAPML+1), $keysDMS)){
                //do nothing
            }
            else{
                unset($PML[$keysPML[$i]]);
            }
        }
        
        return $PML;
    }

    //function to get starting position of a peptide in a protein sequence
    public function getStartPosition($disulfideBondedPeptides,$peptide){

        $startposition = 0;
        
        $keys = array_keys($disulfideBondedPeptides);
        for($i=0;$i<count($keys);$i++){
            $pep = $disulfideBondedPeptides[$keys[$i]]['sequence'];
            if($pep == $peptide){
                $startposition = $disulfideBondedPeptides[$keys[$i]]['start'];
                break;
            }
        }
        
        return $startposition;
    }

    //function to execute Gabow's algorithm given a input file
    public function executeGabow($graph, $root){

        //input string that will be converted to the input file for wmatch
        $input = "";
        //initialize total number of edges. Will be used in line 1 of the input file
        $totaledges = 0;

        //initialize total number of vertices. Will be used in line 1 of the input file
        $vertices = count($graph);

        //create some complementary arrays to convert the position of the cysteines
        //(label) to an index that will be used in the input file for wmatch
        $keys = array_keys($graph);
        sort(&$keys,SORT_NUMERIC);
        for($i=0;$i<$vertices;$i++){
            $index[$keys[$i]] = $i+1;
        }

        //for each vertex, add its edges with respective weightes to the input file
        for($i=0;$i<$vertices;$i++){
            
            //unset variables to avoid it from carrying garbage
            if(isset($matches))unset($matches);
            if(isset($edges))unset($edges);
            if(isset($edgeskeys))unset($edgeskeys);

            //given one vertex, find all the possible edges
            $matches = $graph[$keys[$i]];
            for($j=0;$j<count($matches);$j++){
                //indexes are the cysteine positions converted into an integer that
                //will be interpreted by the wmatch executable and values are the
                //edge weights
                $edges[$index[$matches[$j]]] += 1;
            }

            //add to total edges
            $totaledges += count($edges);

            //mount vertex info to populate the vertex line in the input file
            $vertex = $index[$keys[$i]];
            $label = $keys[$i];
            $edgeskeys = array_keys($edges);
            //#edges label 0 0 \n\n
            $input .= count($edgeskeys)." ".$label." 0 0 \n\n";

            //for each vertex, calculate the possible edges with its respective weight
            //hold edge weights on array values
            ksort(&$edges,SORT_NUMERIC);
            //hold edge indexes on array values
            sort(&$edgeskeys,SORT_NUMERIC);
            for($k=0;$k<count($edges);$k++){
                //#index #weight \n\n
                $input .= $edgeskeys[$k]." ".$edges[$edgeskeys[$k]]." \n\n";
            }
        }

        //calculate final number of edges. divide by 2 because are edges are duplicated
        //i.e. 142-292 and 292-142 are the same S-S bond
        //totaledges needs to be a natural number
        if($totaledges%2 > 0){
            $totaledges--;
        }
        $totaledges = $totaledges/2;
        //first line of input file: carries total number of vertices and edges
        //#vertices #edges U \n\n
        $input = $vertices." ".$totaledges." U \n\n".$input."\n";

        //prepare file paths
        $path = $root."/gabow/".$vertices.$totaledges."U";

        if($_ENV['OS'] == "Windows_NT"){
            $path = str_replace("/", "\\", $path);
        }

        $extensionIN = ".in";
        $extensionOUT = ".out";

        //delete old files if they exist
        if(file_exists($path.$extensionIN)){
            unlink($path.$extensionIN);
        }

        //save input string to input file
        file_put_contents($path.$extensionIN, $input);

        //write command to be executed to run wmatch executable
        $command = "";
        if($_ENV['OS'] == "Windows_NT"){
            $command = $root."/gabow/wmatch.exe ".
                       $path.$extensionIN." > ".$path.$extensionOUT;
            $command = str_replace("/", "\\", $command);
        }
        else{
            $command = $root."/gabow/./wmatch ".
                       $path.$extensionIN." > ".$path.$extensionOUT;
        }
        
        //execute command
        exec($command);

        //delete files created
        if(file_exists($path.$extensionIN)){
            unlink($path.$extensionIN);
        }
        if(file_exists($path.$extensionOUT)){
            //read output file to output string
            $output = file_get_contents($path.$extensionOUT);
            unlink($path.$extensionOUT);
        }

        //extract maximum weighted match results
        $results = array();
        if($_ENV['OS'] == "Windows_NT"){
            $output = str_replace("\r\n", " ", $output);
            $results = explode(" ", trim($output));
        }
        else{
            for($l=0;$l<strlen($output);$l++){
                $value = substr($output, $l, 1);
                if(strlen(trim($value)) > 0){
                    $results[] = $value;
                }
            }
        }

        //create an array with all chosen V-E-V (vertex-edge-vertex) combinations
        for($i=0;$i<count($results);$i+=2){
            //dilsufide bond: i.e. 142-292
            $bond = $keys[$results[$i]-1]."-".$keys[$results[$i+1]-1];
            //reverse disulfide bond to avoid creating duplicated disulfide bonds
            $reversebond = $keys[$results[$i+1]-1]."-".$keys[$results[$i]-1];
            $exist = false;
            //if disulfide bond already exists, skip without creating a duplicate
            if(count($bonds) > 0){
                $exist = array_search($reversebond, $bonds);
            }
            //if disulfide bond is new, add to S-S bonds array
            if($exist === false && $results[$i] != "0" && $results[$i+1] != "0"){
                $bonds[] = $keys[$results[$i]-1]."-".$keys[$results[$i+1]-1];
            }
        }

        //return final array with selected disulfide bonds according to Gabow's
        //algorithm to solve maximum weighted matching problems
        return $bonds;
    }

    //function to generate the different ion types (a, b, c, x, y, z) for peptides
    //necessary for the validation step of the experiment.
    public function generateFragments($peptides, $alltypes = "all"){

        $AAs = new AAclass();

        $fragments = array();
        $fragtype = "";
        $total = count($peptides);

        for($p=0;$p<$total;$p++){
            //peptide sequence
            $peptide = $peptides[$p];

            if($alltypes == "all" || $alltypes == "by" || $alltypes == "aby+"){
                //Y-ions
                if($p%2 == 0){
                    $fragtype = 'Y';
                }
                else{
                    $fragtype = 'y';
                }
                for($i=0;$i<strlen($peptide);$i++){
                    $fragment = substr($peptide,$i);
                    $peplength = strlen($peptide);
                    $mass = $AAs->calculatePeptideMass($fragment,"CM");

                    //check if peptide contains cysteines
                    $cyscount = substr_count($fragment, 'C');

                    //OH on C-terminus and H on N-terminus mass plus 1Da for Y ions
                    //because of an extra H in the amino group NH3+
                    $mass += 19.01838;
                    $fragments[$p][] = array("mass" => $mass,
                        "fragment" => $fragment, "peptide" => $peptide,
                        "ion" => ($fragtype.($peplength-$i)), "cysteines" => $cyscount);
                }

                //B-ions
                if($p%2 == 0){
                    $fragtype = 'B';
                }
                else{
                    $fragtype = 'b';
                }
                for($i=strlen($peptide);$i>0;$i--){
                    $fragment = substr($peptide,0,$i);
                    $mass = $AAs->calculatePeptideMass($fragment,"CM");

                    //check if peptide contains cysteines
                    $cyscount = substr_count($fragment, 'C');

                    //H on N-terminus mass
                    $mass += 1.00782;
                    $fragments[$p][] = array("mass" => $mass,
                        "fragment" => $fragment, "peptide" => $peptide,
                        "ion" => ($fragtype.($i)), "cysteines" => $cyscount);
                }
            }

            if($alltypes == "all" || $alltypes == "aby+"){

                //Yo-ions (-H2O)
                if($p%2 == 0){
                    $fragtype = 'Yo';
                }
                else{
                    $fragtype = 'yo';
                }
                for($i=0;$i<strlen($peptide);$i++){
                    $fragment = substr($peptide,$i);
                    $peplength = strlen($peptide);
                    $mass = $AAs->calculatePeptideMass($fragment,"CM");

                    //check if peptide contains cysteines
                    $cyscount = substr_count($fragment, 'C');

                    //OH on C-terminus and H on N-terminus mass plus 1Da for Y ions
                    //because of an extra H in the amino group NH3+
                    $mass += 19.01838;
                    //losing water H20
                    $mass -= 18.01464;
                    $fragments[$p][] = array("mass" => $mass,
                        "fragment" => $fragment, "peptide" => $peptide,
                        "ion" => ($fragtype.($peplength-$i)), "cysteines" => $cyscount);
                }

                //Y*-ions (-NH3)
                if($p%2 == 0){
                    $fragtype = 'Y*';
                }
                else{
                    $fragtype = 'y*';
                }
                for($i=0;$i<strlen($peptide);$i++){
                    $fragment = substr($peptide,$i);
                    $peplength = strlen($peptide);
                    $mass = $AAs->calculatePeptideMass($fragment,"CM");

                    //check if peptide contains cysteines
                    $cyscount = substr_count($fragment, 'C');

                    //OH on C-terminus and H on N-terminus mass plus 1Da for Y ions
                    //because of an extra H in the amino group NH3+
                    $mass += 19.01838;
                    //losing NH3
                    $mass -= 17.03018;
                    $fragments[$p][] = array("mass" => $mass,
                        "fragment" => $fragment, "peptide" => $peptide,
                        "ion" => ($fragtype.($peplength-$i)), "cysteines" => $cyscount);
                }

                //Bo-ions (-H2O)
                if($p%2 == 0){
                    $fragtype = 'Bo';
                }
                else{
                    $fragtype = 'bo';
                }
                for($i=strlen($peptide);$i>0;$i--){
                    $fragment = substr($peptide,0,$i);
                    $mass = $AAs->calculatePeptideMass($fragment,"CM");

                    //check if peptide contains cysteines
                    $cyscount = substr_count($fragment, 'C');

                    //H on N-terminus mass
                    $mass += 1.00782;
                    //losing water H20
                    $mass -= 18.01464;
                    $fragments[$p][] = array("mass" => $mass,
                        "fragment" => $fragment, "peptide" => $peptide,
                        "ion" => ($fragtype.($i)), "cysteines" => $cyscount);
                }

                //B*-ions (-NH3)
                if($p%2 == 0){
                    $fragtype = 'B*';
                }
                else{
                    $fragtype = 'b*';
                }
                for($i=strlen($peptide);$i>0;$i--){
                    $fragment = substr($peptide,0,$i);
                    $mass = $AAs->calculatePeptideMass($fragment,"CM");

                    //check if peptide contains cysteines
                    $cyscount = substr_count($fragment, 'C');

                    //H on N-terminus mass
                    $mass += 1.00782;
                    //losing NH3
                    $mass -= 17.03018;
                    $fragments[$p][] = array("mass" => $mass,
                        "fragment" => $fragment, "peptide" => $peptide,
                        "ion" => ($fragtype.($i)), "cysteines" => $cyscount);
                }
            
                //A-ions
                if($p%2 == 0){
                    $fragtype = 'A';
                }
                else{
                    $fragtype = 'a';
                }
                for($i=strlen($peptide);$i>0;$i--){
                    $fragment = substr($peptide,0,$i);
                    $mass = $AAs->calculatePeptideMass($fragment,"CM");

                    //check if peptide contains cysteines
                    $cyscount = substr_count($fragment, 'C');

                    //adding H on N-terminus mass and subtracting CO on C-terminus
                    $mass -= 26.9978;
                    $fragments[$p][] = array("mass" => $mass,
                        "fragment" => $fragment, "peptide" => $peptide,
                        "ion" => ($fragtype.($i)), "cysteines" => $cyscount);
                }

                /*
                //Ao-ions (-H20)
                if($p%2 == 0){
                    $fragtype = 'Ao';
                }
                else{
                    $fragtype = 'ao';
                }
                for($i=strlen($peptide);$i>0;$i--){
                    $fragment = substr($peptide,0,$i);
                    $mass = $AAs->calculatePeptideMass($fragment,"CM");

                    //check if peptide contains cysteines
                    $cyscount = substr_count($fragment, 'C');

                    //adding H on N-terminus mass and subtracting CO on C-terminus
                    $mass -= 26.9978;
                    //losing water H20
                    $mass -= 18.01464;
                    $fragments[$p][] = array("mass" => $mass,
                        "fragment" => $fragment, "peptide" => $peptide,
                        "ion" => ($fragtype.($i)), "cysteines" => $cyscount);
                }

                //A*-ions (-NH3)
                if($p%2 == 0){
                    $fragtype = 'A*';
                }
                else{
                    $fragtype = 'a*';
                }
                for($i=strlen($peptide);$i>0;$i--){
                    $fragment = substr($peptide,0,$i);
                    $mass = $AAs->calculatePeptideMass($fragment,"CM");

                    //check if peptide contains cysteines
                    $cyscount = substr_count($fragment, 'C');

                    //adding H on N-terminus mass and subtracting CO on C-terminus
                    $mass -= 26.9978;
                    //losing NH3
                    $mass -= 17.03018;
                    $fragments[$p][] = array("mass" => $mass,
                        "fragment" => $fragment, "peptide" => $peptide,
                        "ion" => ($fragtype.($i)), "cysteines" => $cyscount);
                }
                */
            }
            
            if($alltypes == "all" || $alltypes == "cxz"){
                
                //C-ions
                if($p%2 == 0){
                    $fragtype = 'C';
                }
                else{
                    $fragtype = 'c';
                }
                for($i=strlen($peptide);$i>0;$i--){
                    $fragment = substr($peptide,0,$i);
                    $mass = $AAs->calculatePeptideMass($fragment,"CM");

                    //check if peptide contains cysteines
                    $cyscount = substr_count($fragment, 'C');

                    //H on N-terminus and NH3 on C-terminus
                    $mass += 18.0380;
                    $fragments[$p][] = array("mass" => $mass,
                        "fragment" => $fragment, "peptide" => $peptide,
                        "ion" => ($fragtype.($i)), "cysteines" => $cyscount);
                }

                //X-ions
                if($p%2 == 0){
                    $fragtype = 'X';
                }
                else{
                    $fragtype = 'x';
                }
                for($i=0;$i<strlen($peptide);$i++){
                    $fragment = substr($peptide,$i);
                    $peplength = strlen($peptide);
                    $mass = $AAs->calculatePeptideMass($fragment,"CM");

                    //check if peptide contains cysteines
                    $cyscount = substr_count($fragment, 'C');

                    //OH on C-terminus and CO on N-terminus
                    $mass += 45.0084;
                    $fragments[$p][] = array("mass" => $mass,
                        "fragment" => $fragment, "peptide" => $peptide,
                        "ion" => ($fragtype.($peplength-$i)), "cysteines" => $cyscount);
                }

                //Z-ions
                if($p%2 == 0){
                    $fragtype = 'Z';
                }
                else{
                    $fragtype = 'z';
                }
                for($i=0;$i<strlen($peptide);$i++){
                    $fragment = substr($peptide,$i);
                    $peplength = strlen($peptide);
                    $mass = $AAs->calculatePeptideMass($fragment,"CM");

                    //check if peptide contains cysteines
                    $cyscount = substr_count($fragment, 'C');

                    //adding OH on C-terminus and subtracting NH on N-terminus
                    $mass += 1.9882;
                    $fragments[$p][] = array("mass" => $mass,
                        "fragment" => $fragment, "peptide" => $peptide,
                        "ion" => ($fragtype.($peplength-$i)), "cysteines" => $cyscount);
                }
            }

        }

        return $fragments;
    }

    public function removeBondsWithinTransmembraneRegion($bonds,$from,$to){

        $newbonds = array();

        for($i=0;$i<count($bonds);$i++){
            $cysteines = explode('-', $bonds[$i]['bond']);
            if(($cysteines[0] > $from && $cysteines[0] < $to) ||
               ($cysteines[1] > $from && $cysteines[1] < $to)){
                //bond is not possible
            }
            else{
                $newbonds[] = $bonds[$i];
            }
        }

        return $newbonds;
    }

    private function calculatep2($totalIons, $CMthreshold, $detectionrange){

        $p2 = 2.0*$totalIons*$CMthreshold;
        $p2 = $p2/$detectionrange;

        return $p2;
    }

    private function calculateI($TML, $CM){
        
        $matches = count($CM);
        $intensity = 0.0;
        for($i=0;$i<$matches;$i++){
            $intensity += $TML[$CM[$i]["debug"]["TML"]]["intensity"];
        }
        
        return $intensity;
    }

    private function calculateImean($TML, $p2){
        
        $ions = count($TML);
        $intensity = 0.0;
        for($i=0;$i<$ions;$i++){
            $intensity += $TML[$i]["intensity"];
        }

        $intensity = $intensity/$ions;
        $intensity = $ions*$p2*$intensity;
        
        return $intensity;
    }

    private function calculateIvariance($TML, $p2){
        
        $mean = $this->calculateImean($TML, $p2);
        $ions = count($TML);
        $varianceI = 1.0/($ions-1.0);
        $tmp = 0.0;
        for($i=0;$i<$ions;$i++){
            $tmp += pow(($TML[$i]["intensity"]-$mean),2);
        }
        $varianceI = $varianceI*$tmp;

        $variance = $ions*$p2*(1.0-$p2)*pow($mean,2);
        $variance += $ions*$p2*$varianceI;
        
        return $variance;
    }

    //this function uses an approximation scheme, due to a gauss error function
    //in the integral solution, but it is apparently wrong
    private function calculatePPalfa2($I, $mean, $variance){

        $alfa = 0.0;
        $term1 = exp((-3.0*pow($mean,2))/(8*$variance));
        $term2_1 = pow(((sqrt(2)/(2*sqrt($variance)))*($I-($mean/2))),2);
        $term2_2 = 1.413251*$term2_1;
        $term2_3 = 1.140012*$term2_1;
        $term2 = sqrt(1-exp(-1.0*$term2_1*($term2_2/$term2_3)));

        $alfa = $term1*$term2;
        return $alfa;
    }

    private function calculatePPalfa($I, $mean, $variance){

        $alfa = 0.0;

        $term1 = 1/(sqrt(2*pi()*$variance));
        $term2 = exp((-1.0*pow($mean,2)/(2*$variance))+($mean*$I/$variance)-(pow($I,2)/sqrt(2*$variance)));

        $alfa = $term1*$term2*$I;

        return $alfa;
    }

    private function factorial($value){
        $scale = 500;
        if($value == 0){
            return 1;
        }
        return bcmul($value, $this->factorial(bcsub($value, '1'), $scale), $scale);
    }

    public function calculatePPvalue($TML, $CM, $CMthreshold, $detectionrange){

        $totalIons = count($TML);
        $p2 = $this->calculatep2($totalIons, $CMthreshold, $detectionrange);
        $numMatches = count($CM);
        $alfa = 0.0;
        for($i=$numMatches;$i<=$totalIons;$i++){
            $tmp = $this->factorial($totalIons);
            $tmp2 = $this->factorial($i);
            $tmp2 = bcmul($tmp2,$this->factorial(($totalIons-$i)),500);
            $tmp = bcdiv($tmp,$tmp2,500);
            $tmp = $tmp*(pow($p2,$i));
            $tmp = $tmp*(pow((1.0-$p2),($totalIons-$i)));
            $alfa += $tmp;
        }
        if($alfa == 0.0){
            $alfa = pow($p2,$i);
        }
        //p value
        $beta = -1.0*log10($alfa);

        return $beta;
    }

    public function calculatePP2value($TML, $CM, $CMthreshold, $detectionrange){

        $totalIons = count($TML);
        $I = $this->calculateI($TML, $CM);
        $p2 = $this->calculatep2($totalIons, $CMthreshold, $detectionrange);
        $mean = $this->calculateImean($TML, $p2);
        $variance = $this->calculateIvariance($TML, $p2);

        $alfa = $this->calculatePPalfa($I, $mean, $variance);

        //pp value
        $beta = -1*log10($alfa);

        return $beta;
    }
    
    public function removeBondsInTransmembraneRegion($pbonds,$transmembranefrom,$transmembraneto){
        
        $bonds = array();
        
        $transmembranefrom = (int)$transmembranefrom;
        $transmembraneto = (int)$transmembraneto;
        
        if($transmembranefrom > 0 and $transmembraneto > 0){
            $keys = array_keys($pbonds);
            for($i=0;$i<count($keys);$i++){
                $cys1 = (int)substr($keys[$i], 0, strpos($keys[$i], "-"));
                $cys2 = (int)substr($keys[$i], strpos($keys[$i], "-")+1);
                if($cys1 > $transmembraneto || $cys2 < $transmembranefrom){
                    $bonds[$keys[$i]] = $pbonds[$keys[$i]];
                }
            }
        }   
        else{
            $bonds = $pbonds;
        }
        
        return $bonds;
        
    }
    
    //$zipFile["tmp_name"]
    //$zipFile["name"]
    public function readMSMSFiles($root, $tmp_name, $name, $filetype){
        
        $PML = array();
        $PMLNames = array();
        
        if($filetype == "application/octet-stream"){
            
            $extension = strtoupper(substr(strrchr($name,"."),1));
            
            if($extension == "MZXML" || $extension == "MZML" || $extension == "MZDATA"){
                
                echo "test";
                exit();
                
                $data = file_get_contents($tmp_name);

                $path = $root."/DTA/".$name;
                if(!is_dir($path)){
                    mkdir($path);
                }
                //save to File
                $path .= "/1.mzXML";
                file_put_contents($path, $data);

                //convert File to DTA file(s)
                $DTApath = $root."/DTA/".$name."/1/";
                $this->convertMZXMLtoDTA($root, $path);

                //process each DTA file
                $listFiles = array();

                $handle = opendir($DTApath);
                while (false !== ($file = readdir($handle))) {
                    if(strlen($file) > 3){
                        $listFiles[] = $file;
                    }                            
                }

                $numFiles = count($listFiles);
                for($i=0;$i<$numFiles;$i++)
                {
                    $data = file_get_contents($DTApath.$listFiles[$i]);

                    //subtract one due to DTA format for precursor ions mass Mr
                    //index is number of AA - number of charges - calculated mass
                    $index = substr($data,0,strpos($data,"."))."-".
                             substr($data,(strpos($data," ")+1),1)."-".(string)$iterations;
                    $iterations++;

                    if(strlen($data) > 0){

                        $PML[$index] = substr($data,0,strpos($data," ",strlen(substr($data,0,strpos($data," ")))+1));
                        $PMLNames[$index] = $listFiles[$i];

                        //store data in a local file
                        $path = $root."/DTA/".$name."/".$index.".txt";
                        file_put_contents($path, $data);
                        $path = $root."/DTA/".$name."/".$listFiles[$i];
                        file_put_contents($path, $data);
                    }                        
                }
            }
        }
        else
        {        
            $zip = zip_open($tmp_name);
            if($zip){
                $dirPath = $root."/DTA/".$name;

                if(!is_dir($dirPath)){
                    mkdir($dirPath);
                }
                $mzIteration = 0;
                $iterations = 0;
                while($zip_entry = zip_read($zip)){
                    if(zip_entry_open($zip, $zip_entry)){

                        $filename = zip_entry_name($zip_entry);
                        $extension = strtoupper(substr(strrchr($filename,"."),1));

                        if($extension == "MZXML" || $extension == "MZML" || $extension == "MZDATA"){

                            //load MZXML data
                            $data = zip_entry_read($zip_entry,zip_entry_filesize($zip_entry));
                            $mzIteration++;

                            //save to File
                            $path = $root."/DTA/".$name."/".$mzIteration.".mzXML";
                            file_put_contents($path, $data);

                            //convert File to DTA file(s)
                            $DTApath = $root."/DTA/".$name."/".$mzIteration."/";
                            $this->convertMZXMLtoDTA($root, $path);

                            //process each DTA file
                            $listFiles = array();

                            $handle = opendir($DTApath);
                            while (false !== ($file = readdir($handle))) {
                                if(strlen($file) > 3){
                                    $listFiles[] = $file;
                                }                            
                            }

                            $numFiles = count($listFiles);
                            for($i=0;$i<$numFiles;$i++)
                            {
                                $data = file_get_contents($DTApath.$listFiles[$i]);

                                //subtract one due to DTA format for precursor ions mass Mr
                                //index is number of AA - number of charges - calculated mass
                                $index = substr($data,0,strpos($data,"."))."-".
                                         substr($data,(strpos($data," ")+1),1)."-".(string)$iterations;
                                $iterations++;

                                if(strlen($data) > 0){

                                    $PML[$index] = substr($data,0,strpos($data," ",strlen(substr($data,0,strpos($data," ")))+1));
                                    $PMLNames[$index] = $listFiles[$i];

                                    //store data in a local file
                                    $path = $root."/DTA/".$name."/".$index.".txt";
                                    file_put_contents($path, $data);
                                    $path = $root."/DTA/".$name."/".$listFiles[$i];
                                    file_put_contents($path, $data);
                                }                        
                            }
                        }

                        if($extension == "DTA"){

                            $data = zip_entry_read($zip_entry,zip_entry_filesize($zip_entry));

                            //subtract one due to DTA format for precursor ions mass Mr
                            //index is number of AA - number of charges - calculated mass
                            /*
                            $index = (string)((int)((substr($data,0,strpos($data," "))-1.0) / $me))."-".
                                     substr($data,(strpos($data," ")+1),1)."-".
                                     substr($data,0,strpos($data,"."))."-".(string)$iterations;
                            */
                            $index = substr($data,0,strpos($data,"."))."-".
                                     substr($data,(strpos($data," ")+1),1)."-".(string)$iterations;
                            $iterations++;

                            if(strlen($data) > 0){

                                $PML[$index] = substr($data,0,strpos($data," ",strlen(substr($data,0,strpos($data," ")))+1));
                                $PMLNames[$index] = $filename;

                                //store data in a local file
                                $path = $root."/DTA/".$name."/".$index.".txt";
                                file_put_contents($path, $data);
                                $begin = 0;
                                if(strpos($filename, "/") > 0){
                                    $begin = strpos($filename, "/")+1;
                                }                            
                                $path = $root."/DTA/".$name."/".substr($filename,$begin);
                                file_put_contents($path, $data);
                            }
                        }
                    }
                }
            }
        }
        
        return array("PML" => $PML, "PMLNames" => $PMLNames);
    }
    
    public function convertMZXMLtoDTA($root, $mzXMLpath){
        
        //generate files
        $pathtoexec = "";
        if($_ENV['OS'] == "Windows_NT"){
            $pathtoexec = $root."/MSMSconversion/MzXML2Search.exe ";
            $pathtoexec = str_replace("/", "\\", $pathtoexec);
        }
        else{
            $pathtoexec = $root."/MSMSconversion/./MzXML2Search ";
        }
        
        $cmd = $pathtoexec." -dta ".$mzXMLpath;
        exec($cmd);
        
    }
    
    public function getMinMaxScorePredictive($bonds){
        $minmax = array();
        
        $minmax['min'] = 1000;
        $minmax['max'] = 0;
        
        $keys = array_keys($bonds);
        $count = count($keys);
        for($i=0;$i<$count;$i++){
            $score = $bonds[$keys[$i]]['score'] + $bonds[$keys[$i]]['similarity'];
            if($score > $minmax['max']){
                $minmax['max'] = $score;
            }
            if($score < $minmax['min']){
                $minmax['min'] = $score;
            }            
        }
        
        return $minmax;
    }
    
    public function getMinMaxScoreMSMS($bonds){
        $minmax = array();
        
        $minmax['min'] = 1000;
        $minmax['max'] = 0;
        $minmax['ppmin'] = 1000;
        $minmax['ppmax'] = 0;
        $minmax['pp2min'] = 1000;
        $minmax['pp2max'] = 0;
        
        $keys = array_keys($bonds);
        $count = count($keys);
        for($i=0;$i<$count;$i++){
            
            //score
            if($bonds[$keys[$i]]['score'] > $minmax['max']){
                $minmax['max'] = $bonds[$keys[$i]]['score'];
            }
            if($bonds[$keys[$i]]['score'] < $minmax['min']){
                $minmax['min'] = $bonds[$keys[$i]]['score'];
            }
            
            //ppvalue
            if(!is_infinite($bonds[$keys[$i]]['ppvalue'])){
                if($bonds[$keys[$i]]['ppvalue'] > $minmax['ppmax']){
                    $minmax['ppmax'] = $bonds[$keys[$i]]['ppvalue'];
                }
                if($bonds[$keys[$i]]['ppvalue'] < $minmax['ppmin']){
                    $minmax['ppmin'] = $bonds[$keys[$i]]['ppvalue'];
                }
            }
            
            //pp2value
            if(!is_infinite($bonds[$keys[$i]]['pp2value'])){
                if($bonds[$keys[$i]]['pp2value'] > $minmax['pp2max']){
                    $minmax['pp2max'] = $bonds[$keys[$i]]['pp2value'];
                }
                if($bonds[$keys[$i]]['pp2value'] < $minmax['pp2min']){
                    $minmax['pp2min'] = $bonds[$keys[$i]]['pp2value'];
                }
            }
        }        
        
        return $minmax;
    }
}
?>