<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html dir="ltr" xmlns="http://www.w3.org/1999/xhtml">

    <!-- #BeginTemplate "master.dwt" -->

    <head>
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
        <!-- #BeginEditable "doctitle" -->
        <title>Advanced Analysis</title>
        <!-- #EndEditable -->
        <link href="styles/style1.css" media="screen" rel="stylesheet" title="CSS" type="text/css" />
        <link href="styles/style.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="js/wz_jsgraphics.js"></script>
        <script type="text/javascript" src="js/functions.js"></script>
    </head>

    <body>
        <?php include_once("analyticstracking.php") ?>
        <script type="text/javascript" src="js/wz_tooltip.js"></script>
        <!-- Begin Container -->
        <div id="container">
            <!-- Begin Masthead -->
            <div id="masthead">
                <div id="mastheadleft">
                    <a href="index.php" class="noborders"><img alt="MS2DBp logo" src="images/ms2db+logo.gif" onmouseover="Tip('MS2DBp Project')" onmouseout="UnTip()"/></a>
                </div>
                <div id="masterheadcenter">
                    Efficient Disulfide Bond Determination Using Mass Spectrometry
                </div>
                <div id="mastheadright">
                    <a href="http://www.sfsu.edu" class="noborders" target="_blank"><img alt="SFSU Logo" src="images/sfsu.jpg" onmouseover="Tip('SFSU http://www.sfsu.edu')" onmouseout="UnTip()"/></a>
                </div>
            </div>
            <!-- End Masthead -->
            <!-- Begin Navigation -->
            <div id="navigation" class="horizontalmenu">
                <ul>
                    <li><a href="index.php" onmouseover="Tip('MS2DB+ Home Page')" onmouseout="UnTip()">Home</a></li>
                    <li><a href="stdanalysis.php" onmouseover="Tip('MS2DB+ for Beginner users')" onmouseout="UnTip()">Standard Analysis</a></li>
                    <li class="selected"><a href="advanalysis.php" onmouseover="Tip('MS2DB+ for Advanced users')" onmouseout="UnTip()">Advanced Analysis</a></li>
                    <li><a href="datasets.php" onmouseover="Tip('MS2DB+ Datasets')" onmouseout="UnTip()">Datasets</a></li>
                    <li><a href="publications.php" onmouseover="Tip('MS2DB+ Publications')" onmouseout="UnTip()">Publications</a></li>
                    <li><a href="contactus.php" onmouseover="Tip('MS2DB+ Contact Us')" onmouseout="UnTip()">Contact Us</a></li>
                    <li><a href="help.php" onmouseover="Tip('MS2DB+ Help')" onmouseout="UnTip()">Help</a></li>
                    <li><a href="faq.php" onmouseover="Tip('MS2DB+ FAQ')" onmouseout="UnTip()">FAQ</a></li>
                </ul>
            </div>
            <!-- End Navigation -->
            <!-- Begin Page Content -->
            <div id="page_content">
                <!-- Begin Left Column -->
                <div id="readme">
                    "Point the mouse over each input field or output result to read its description.
                    For more details, please visit our <a class="alwaysblue" target="_blank" href="help.php"><b>HELP</b></a> section."
                </div>
                <div id="column_l">
                    <!-- #BeginEditable "content" -->
                    <form action="kernel.php?mode=advanced" name="submitForm" enctype="multipart/form-data" method="post">
                        <div>
                            <table class="input">
                                <tr>
                                    <td colspan="2"><p /></td>
                                </tr>
                                <tr>
                                    <td colspan="2"><p /></td>
                                </tr>
                                <tr class="input">
                                    <td class="inputleft">
                                        <label>Upload a MS/MS data file:</label>
                                    </td>
                                    <td class="inputright">
                                        <input type="file" id="zipFile" name="zipFile" size="90"
                                               onmouseover="Tip('Upload one of the following formats: mzXML, mzData, mzML or a ZIP containing DTA files')"
                                               onmouseout="UnTip()" />
                                    </td>
                                </tr>
                                <tr class="input">
                                    <td class="inputleft">
                                        <label>Enter FASTA protein sequence:</label>
                                    </td>
                                    <td class="inputright">
                                        <textarea id="fastaProtein" name="fastaProtein" rows="5" cols="77"
                                                  onmouseover="Tip('Enter a protein sequence in FASTA format (a.k.a Pearson format)')"
                                                  onmouseout="UnTip()"
                                                  ><?php if(isset($fastaProtein)) {echo $fastaProtein;}?></textarea>
                                    </td>
                                </tr>
                                <tr class="input">
                                    <td class="inputleft">
                                        <label>Choose protease used in digestion:</label>
                                    </td>
                                    <td class="inputright">
                                        <select id="protease" name="protease"
                                                onmouseover="Tip('Choose a protease using to digest the protein sequence entered above')"
                                                onmouseout="UnTip()">
                                            <option <?php if(!isset($protease)) {$protease = "T";} if($protease == "T") {echo "selected";} ?> value="T">Trypsin</option>
                                            <option <?php if(!isset($protease)) {$protease = "T";} if($protease == "C") {echo "selected";} ?> value="C">Chymotrypsin</option>
                                            <option <?php if(!isset($protease)) {$protease = "T";} if($protease == "TC") {echo "selected";} ?> value="TC">Trypsin + Chymotrypsin</option>
                                            <option <?php if(!isset($protease)) {$protease = "T";} if($protease == "G") {echo "selected";} ?> value="G">Glu-C</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr class="input">
                                    <td class="inputleft">
                                        <label id="lbions" onmouseover="Tip('Select which ion types will be considered in the analysis')" onmouseout="UnTip()">Multi-ion types selection:</label>
                                    </td>
                                    <td class="inputright">
                                        <i>a</i><input id="iona" type="checkbox" name="iona" value="a" 
                                            <?php if(!isset($alliontypes['a']) || strlen($alliontypes['a']) > 0) echo "checked";?> />
                                        <i>ao</i><input id="iona" type="checkbox" name="ionao" value="ao"
                                            <?php if(strlen($alliontypes['ao']) > 0) echo "checked";?> />
                                        <i>a*</i><input id="iona" type="checkbox" name="ionas" value="as" 
                                            <?php if(strlen($alliontypes['as']) > 0) echo "checked";?> />
                                        <i>b</i><input id="ionb" type="checkbox" name="ionb" value="b" 
                                            <?php if(!isset($alliontypes['b']) || strlen($alliontypes['b']) > 0) echo "checked";?> />
                                        <i>b*</i><input id="ionbs" type="checkbox" name="ionbs" value="bs" 
                                            <?php if(!isset($alliontypes['bs']) || strlen($alliontypes['bs']) > 0) echo "checked";?> />
                                        <i>bo</i><input id="ionbo" type="checkbox" name="ionbo" value="bo" 
                                            <?php if(!isset($alliontypes['bo']) || strlen($alliontypes['bo']) > 0) echo "checked";?> />
                                        <i>c</i><input id="ionc" type="checkbox" name="ionc" value="c" 
                                            <?php if(!isset($alliontypes['c']) || strlen($alliontypes['c']) > 0) echo "checked";?> />
                                        <i>x</i><input id="ionx" type="checkbox" name="ionx" value="x" 
                                            <?php if(!isset($alliontypes['x']) || strlen($alliontypes['x']) > 0) echo "checked";?> />
                                        <i>y</i><input id="iony" type="checkbox" name="iony" value="y" 
                                            <?php if(!isset($alliontypes['y']) || strlen($alliontypes['y']) > 0) echo "checked";?> />
                                        <i>y*</i><input id="ionys" type="checkbox" name="ionys" value="ys" 
                                            <?php if(!isset($alliontypes['ys']) || strlen($alliontypes['ys']) > 0) echo "checked";?> />
                                        <i>yo</i><input id="ionyo" type="checkbox" name="ionyo" value="yo" 
                                            <?php if(!isset($alliontypes['yo']) || strlen($alliontypes['yo']) > 0) echo "checked";?> />
                                        <i>z</i><input id="ionz" type="checkbox" name="ionz" value="z" 
                                            <?php if(!isset($alliontypes['z']) || strlen($alliontypes['z']) > 0) echo "checked";?> />
                                    </td>
                                </tr>
                                <!--
                                <tr class="input">
                                    <td class="inputleft">
                                        <label>Multi-ion types selection:</label>
                                    </td>
                                    <td class="inputright">
                                        <select id="ions" name="ions"
                                                onmouseover="Tip('Select which ion types will be considered in the analysis')"
                                                onmouseout="UnTip()">
                                            <option <?php //if(!isset($alliontypes)){$alliontypes = "all";} if($alliontypes == "all"){echo "selected";} ?> value="all">a b bo b* c x y yo y* z</option>
                                            <option <?php //if(!isset($alliontypes)){$alliontypes = "all";} if($alliontypes == "by"){echo "selected";} ?> value="by">b and y </option>
                                            <option <?php //if(!isset($alliontypes)){$alliontypes = "all";} if($alliontypes == "aby+"){echo "selected";} ?> value="aby+">a b bo b* y yo y*</option>
                                            <option <?php //if(!isset($alliontypes)){$alliontypes = "all";} if($alliontypes == "cxz"){echo "selected";} ?> value="cxz">c x and z</option>
                                        </select>
                                    </td>
                                </tr>
                                -->
                                <tr class="input">
                                    <td class="inputleft">
                                        <label>Number of missing cleavages:</label>
                                    </td>
                                    <td class="inputright">
                                        <select id="missingcleavages" name="missingcleavages"
                                                onmouseover="Tip('Choose the number of missing cleavages. The default is 2')"
                                                onmouseout="UnTip()">
                                            <option <?php if(!isset($missingcleavages)) {$missingcleavages = "-1";} if($missingcleavages == "-1") {echo "selected";} ?> value="-1">Optional</option>
                                            <option <?php if(!isset($missingcleavages)) {$missingcleavages = "-1";} if($missingcleavages == "0") {echo "selected";} ?> value="0">0</option>
                                            <option <?php if(!isset($missingcleavages)) {$missingcleavages = "-1";} if($missingcleavages == "1") {echo "selected";} ?> value="1">1</option>
                                            <option <?php if(!isset($missingcleavages)) {$missingcleavages = "-1";} if($missingcleavages == "2") {echo "selected";} ?> value="2">2</option>
                                            <option <?php if(!isset($missingcleavages)) {$missingcleavages = "-1";} if($missingcleavages == "3") {echo "selected";} ?> value="3">3</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr class="input">
                                    <td class="inputleft">
                                        <label>Region where bonds aren't expected:</label>
                                    </td>
                                    <td class="inputright">
                                        <input id="transmembranefrom" name="transmembranefrom" value=""
                                               onmouseover="Tip('Protein transmembrane region start')"
                                               onmouseout="UnTip()" size="4" maxlength="4"></input>
                                        <label>to</label>
                                        <input id="transmembraneto" name="transmembraneto" value=""
                                               onmouseover="Tip('Protein transmembrane region end')"
                                               onmouseout="UnTip()" size="4" maxlength="4"></input>
                                        <span style="color:red;font-size:10px;">(optional)</span>
                                    </td>
                                </tr>
                                <!-- Commented for MS2DB+
                                <tr class="input">
                                    <td class="inputleft">
                                        <label>Also use predictive techniques?</label>
                                    </td>
                                    <td class="inputright">
                                        <select id="predictive" name="predictive"
                                                onmouseover="Tip('Include the predictive framework (SVM-models and CSPs) in the analysis of the disulfide connectivity to improve results accuracy')"
                                                onmouseout="UnTip()">
                                            <option <?php if(!isset($predictive)){$predictive = "Y";} if($predictive == "Y"){echo "selected";} ?> value="Y">Yes</option>
                                            <option <?php if(!isset($predictive)){$predictive = "Y";} if($predictive == "N"){echo "selected";} ?> value="N">No</option>                                                
                                        </select>
                                        <span style="color:red;font-size:10px;">(default: yes)</span>
                                    </td>
                                </tr>
                                -->
                                <tr class="input">
                                    <td colspan="2">
                                        <table class="advancedusers">
                                            <tr class="advancedusers">
                                                <td class="advancedusersleft">Initial Matching threshold:</td>
                                                <td class="advancedusersright">
                                                    <input type="text" id="IMthreshold" name="IMthreshold" size="5" value="<?php if (isset($IMthreshold)) {echo $IMthreshold;}else {echo '1.0';} ?>"
                                                           onmouseover="Tip('Threshold used during the matching between precursor ions and disulfide bonded structures')"
                                                           onmouseout="UnTip()"></input>
                                                    * (default: +-1.0)
                                                </td>
                                            </tr>
                                            <tr class="advancedusers">
                                                <td class="advancedusersleft">MS/MS intensity/abundance threshold:</td>
                                                <td class="advancedusersright">
                                                    <input type="text" id="IntensityLimit" name="IntensityLimit" size="5" value="<?php if (isset($IntensityLimit)) {echo $IntensityLimit;}else {echo '0.10';} ?>"
                                                           onmouseover="Tip('Lowest m/z intensity limit. (IntensityLimit x Maximum Intensity)')"
                                                           onmouseout="UnTip()"></input>
                                                    * (default: 0.10)
                                                </td>
                                            </tr>
                                            <tr class="advancedusers">
                                                <td class="advancedusersleft">Confirmatory Matching threshold:</td>
                                                <td class="advancedusersright">
                                                    <input type="text" id="CMthreshold" name="CMthreshold" size="5" value="<?php if (isset($CMthreshold)) {echo $CMthreshold;}else {echo '1.0';} ?>"
                                                           onmouseover="Tip('Threshold used while matching fragments from a DTA file with fragment ions from the matched precursor ion')"
                                                           onmouseout="UnTip()"></input>
                                                    * (default: +-1.0)
                                                </td>
                                            </tr>
                                            <tr class="advancedusers">
                                                <td class="advancedusersleft">&#949; value:</td>
                                                <td class="advancedusersright">
                                                    <input type="text" id="epsilon" name="epsilon" size="20" value="<?php if (isset($epsilon) && $epsilon > 0) {echo $epsilon;}else {echo '';} ?>"
                                                           onmouseover="Tip('Initial Matching trimming parameter epsilon')"
                                                           onmouseout="UnTip()"></input>
                                                    (optional)
                                                </td>
                                            </tr>
                                            <tr class="advancedusers">
                                                <td class="advancedusersleft">&#948; value:</td>
                                                <td class="advancedusersright">
                                                    <input type="text" id="delta" name="delta" size="20" value="<?php if (isset($delta) && $delta > 0) {echo $delta;}else {echo '';} ?>"
                                                           onmouseover="Tip('Confirmatory Matching trimming parameter delta')"
                                                           onmouseout="UnTip()"></input>
                                                    (optional)
                                                </td>
                                            </tr>
                                            <tr class="advancedusers">
                                                <td class="advancedusersleft">Match Scoring threshold:</td>
                                                <td class="advancedusersright">
                                                    <input type="text" id="VSthreshold" name="VSthreshold" size="5" value="<?php if (isset($VSthreshold)) {echo $VSthreshold;}else {echo '80';} ?>"
                                                           onmouseover="Tip('A disulfide bond is identified if its match score is greater than this threshold.')"
                                                           onmouseout="UnTip()"></input>
                                                    * (default: 80 <i>%</i>)
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr class="input">
                                    <td colspan="2" align="center">
                                        <input type="submit" id="submit" size="200" name="submit" value="Search Disulfide Bonds"
                                               onmouseover="Tip('Click Search Disulfide Bonds button to process your request')"
                                               onmouseout="UnTip()" onclick="showProcessing()" />
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2"><p /></td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <p class="processing">
                                            <img id="loadingimage" style="visibility:hidden;" src="images/loading.gif"></img>
                                            <label id="processing"></label>
                                        </p>
                                    </td>
                                </tr>
                                <tr id="bondsdiv">
                                    <td class="inputleft">
                                        <label style="visibility:hidden">Disulfide Bonds</label>
                                    </td>
                                    <td class="inputright">
                                        <?php
                                            if(strtoupper(substr($message, 0, 5)) == "ERROR") {echo '<font color="red">';}else {echo '<font color="blue">';}
                                            if(isset($message)) {echo $message;}echo '</font>';
                                        ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </form>
                    <div id="graphdiv" class="graph">
                        <script type="text/javascript" src="js/graph.js"></script>
                            <?php echo $SSgraph; ?>
                            <?php echo $SSgraphJS; ?>
                    </div>
                    <div id="listofbondsdiv" class="listofbonds">
                        <table align="center" id="bonds" width="800">
                            <tr><td></td></tr>
                            <?php
                            if(isset($bonds)) {
                                if(count($bonds) == -1) {
                                    $rows = (((int)(strlen($fastaProtein)/60)+1)*2);
                                    $cols = 6;
                                    for($i=0;$i<$rows;$i++) {
                                        echo '<tr width="720px">';
                                        for($j=0;$j<$cols;$j++) {
                                            if($i%2 == 0) {
                                                echo '<td align="right" width="120px">';
                                                echo ((($i/2)*60)+(($j+1)*10))/10;
                                                echo "<u>0</u>";
                                            }
                                            else {
                                                if($i == $rows-1 && $j == $cols-1)
                                                    echo '<td align="center" width="120px">';
                                                else
                                                    echo '<td align="right" width="120px">';
                                                $output = substr($fastaProtein,((($i-1)/2)*60)+($j*10),10);
                                                for($k=0;$k<count($bonds);$k++) {
                                                    $c1 = substr($bonds[$k],0,strpos($bonds[$k],"-"));
                                                    $c2 = substr($bonds[$k],strpos($bonds[$k],"-")+1);
                                                    if($c1 >= ((($i-1)/2)*60)+($j*10) && $c1 <= (((($i-1)/2)*60)+($j*10)+10)) {
                                                        $start = (((($i-1)/2)*60)+($j*10))+1;
                                                        $output = substr($output,0,($c1-$start)).'<font color="red"><u><b>'.
                                                                substr($output,($c1-$start),1).'</b></u></font>'.
                                                                substr($output,($c1-$start+1));
                                                    }
                                                    if($c2 >= ((($i-1)/2)*60)+($j*10) && $c2 <= (((($i-1)/2)*60)+($j*10)+10)) {
                                                        $start = (((($i-1)/2)*60)+($j*10))+1;
                                                        $output = substr($output,0,($c2-$start)).'<font color="red"><u><b>'.
                                                                substr($output,($c2-$start),1).'</b></u></font>'.
                                                                substr($output,($c2-$start+1));
                                                    }
                                                }
                                                echo $output;
                                            }
                                            echo '</td>';
                                        }
                                        echo '</tr>';
                                    }
                                }
                            }
                            ?>
                        </table>
                        <?php
                        if(isset($debug)) {
                            echo $debug;
                        }
                        ?>
                    </div>
                </div>
                <!-- End Left Column -->
                <!-- End Page Content -->
                <!-- Begin Footer -->
                <?php include "footer.php" ?>
                <!-- End Footer -->
                </div>
        </div><!-- End Container -->
    </body>
    <!-- #EndTemplate -->
</html>
