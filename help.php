<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html dir="ltr" xmlns="http://www.w3.org/1999/xhtml">
    <!-- #BeginTemplate "master.dwt" -->
    <head>
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
        <!-- #BeginEditable "doctitle" -->
        <title>Help</title>
        <!-- #EndEditable -->
        <link href="styles/style1.css" media="screen" rel="stylesheet" title="CSS" type="text/css" />
        <link href="styles/style.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="js/wz_jsgraphics.js"></script>
    </head>
    <body>
        <?php include_once("analyticstracking.php") ?>
        <script type="text/javascript" src="js/wz_tooltip.js"></script>
        <!-- Begin Container -->
        <div id="container">
            <!-- Begin Header -->
            <?php include "header.php" ?>
            <!-- End Header -->
            <!-- Begin Navigation -->
            <div id="navigation" class="horizontalmenu">
                <ul>
                    <li><a href="index.php" onmouseover="Tip('MS2DB+ Home Page')" onmouseout="UnTip()">Home</a></li>
                    <li><a href="stdanalysis.php" onmouseover="Tip('MS2DB+ for Beginner users')" onmouseout="UnTip()">Standard Analysis</a></li>
                    <li><a href="advanalysis.php" onmouseover="Tip('MS2DB+ for Advanced users')" onmouseout="UnTip()">Advanced Analysis</a></li>
                    <li><a href="datasets.php" onmouseover="Tip('MS2DB+ Datasets')" onmouseout="UnTip()">Datasets</a></li>
                    <li><a href="publications.php" onmouseover="Tip('MS2DB+ Publications')" onmouseout="UnTip()">Publications</a></li>
                    <li><a href="contactus.php" onmouseover="Tip('MS2DB+ Contact Us')" onmouseout="UnTip()">Contact Us</a></li>
                    <li class="selected"><a href="help.php" onmouseover="Tip('MS2DB+ Help')" onmouseout="UnTip()">Help</a></li>
                    <li><a href="faq.php" onmouseover="Tip('MS2DB+ FAQ')" onmouseout="UnTip()">FAQ</a></li>
                </ul>
            </div>
            <!-- End Navigation -->
            <!-- Begin Page Content -->
            <div id="page_content">
                <!-- Begin Left Column -->
                <div id="column_l">
                    <!-- #BeginEditable "content" -->
                    <table class="content" id="publications" cellpadding="0" cellspacing="0" summary="" width="80%">
                        <tr>
                            <td>
                                <p></p>
                            </td>
                        </tr>
                        <tr>
                            <td class="list">
                                <ol>
                                    <li class="spacing">
                                        <a href="#MultiIons">Multi-Ions</a>
                                    </li>
                                    <li class="spacing">
                                        <a href="#IMthreshold">Initial Matching Threshold</a>
                                    </li>
                                    <li class="spacing">
                                        <a href="#MSMSthreshold">MS/MS Intensity/Abundance Threshold</a>
                                    </li>
                                    <li class="spacing">
                                        <a href="#CMthreshold">Confirmatory Matching Threshold</a>
                                    </li>
                                    <li class="spacing">
                                        <a href="#Epsilon">Trimming Parameter <span style="font-variant:normal;">&#949;</span></a>
                                    </li>
                                    <li class="spacing">
                                        <a href="#Delta">Trimming Parameter <span style="font-variant:normal;">&#948;</span></a>
                                    </li>
                                    <li class="spacing">
                                        <a href="#VSthreshold">Matching Score Threshold</a>
                                    </li>
                                </ol>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <h4><a name="MultiIons"></a>1. Multi-Ions</h4>
                            </td>
                        </tr>
                        <tr>
                            <td align="center" colspan="2">
                                <img alt="fragmentation" id="fragmentation" src="images/cleavages.gif" />
                            </td>
                        </tr>
                        <tr>
                            <td class="justified">
                                <br/>
                                The types of fragment ions observed in an MS/MS spectrum depend on many factors including 
                                primary sequence, the amount of internal energy, how the energy was introduced, charge state, 
                                the dissociation method used, etc.<br/><br/>
                                Different dissociation methods generate different ion types in different abundances.
                                MS2DB+ allows users to analyze MS/MS data considering combinations of up to  
                                twelve of the most commonly generated ions types: a, ao, a*, b, bo, b*, c, x, y, yo, y*, and z.
                                <br/><br/>
                                Some of the most common dissociation methods are: Collision Induced Dissociation (CID),
                                Electron-Transfer Dissociation (ETD), Electron-Capture Dissociation (ECD), 
                                Electron-Detachment Dissociation (EDD) and Infrared Multiphoton Dissociation (IRMPD).
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <h4><a name="IMthreshold"></a>2. Initial Matching Threshold</h4>
                            </td>
                        </tr>
                        <tr>
                            <td class="justified">
                                The Initial Matching threshold is a threshold used to 
                                define a mass window centered on a experimental precursor ion mass value (from a MS/MS file) within 
                                which a correspondence between this ion mass value and a theoretical precursor ion mass value may 
                                be found. The correspondence is defined as an Initial Match (IM).
                                <br/><br/>
                                Both Initial Matching Threshold and Confirmatory Matching threshold are directly dependent on
                                the  mass spectrometer sensitivity. The more sensitive the mass spectrometer is, the smaller
                                the mass window needs to be.
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <h4><a name="MSMSthreshold"></a>3. MS/MS Intensity/Abundance Threshold</h4>
                            </td>
                        </tr>
                        <tr>
                            <td class="justified">
                                The MS/MS Intensity/Abundance threshold is used to remove the fragment peaks 
                                (m/z data points) with low intensity in an experimental spectrum (or MS/MS file). 
                                These peaks represent noisy unreliable data, which do not contribute to the 
                                method's accuracy and efficiency. This lower bound limit allows MS2DB+ to account 
                                only for the meaningful m/z values in the entire spectra being analyzed.
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <h4><a name="CMthreshold"></a>4. Confirmatory Matching Threshold</h4>
                            </td>
                        </tr>
                        <tr>
                            <td class="justified">
                                The Confirmatory Match threshold is a threshold used to define a mass window centered on a 
                                experimental (product) fragment ion mass value within which a correspondence between 
                                this product ion mass value and a theoretical fragment ion mass value may be found.
                                This correspondence is defined as an Confirmatory Match (CM).
                                <br/><br/>
                                Both Initial Matching Threshold and Confirmatory Matching threshold are directly dependent on
                                the  mass spectrometer sensitivity. The more sensitive the mass spectrometer is, the smaller
                                the mass window needs to be.
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <h4><a name="Epsilon"></a>5. Trimming Parameter <span style="font-variant:normal;">&#949;</span></h4>
                            </td>
                        </tr>
                        <tr>
                            <td class="justified">
                                &#949; is a trimming parameter used to trim the set of mass values corresponding to all possible 
                                disulfide-bonded peptide structures that can be obtained from a digested protein (DMS).
                                To trim the DMS set by &#949; means to remove as many elements from DMS as possible without 
                                losing meaningful mass values. The formula to calculate &#949; is:
                                <br/>
                                <img style="margin-left:150px;" alt="epsilon" id="epsilonimg" src="images/epsilon.png" />
                                <br/>
                                We modeled this functional relationship using the following independent variables: 
                                (1) the cysteine-containing peptides (CCP) mass range defined by CCPmax and CCPmin 
                                corresponding to the peptides with highest and lowest mass respectively. 
                                (2) The number of cysteine-containing peptides k. A large k implies that the average 
                                difference in the mass of any two peptide fragments is small. Conversely, a small k 
                                implies fewer fragments with putatively larger differences in their masses. 
                                (3) The cysteine-containing peptides average mass value CCPaverage. 
                                The relationship between &#949; and these other variables is then obtained using 
                                multiple-variable regression.
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <h4><a name="Delta"></a>6. Trimming Parameter <span style="font-variant:normal;">&#948;</span></h4>
                            </td>
                        </tr>
                        <tr>
                            <td class="justified">
                                &#948; is a trimming parameter used to trim the set of mass values of every disulfide-bonded 
                                fragment structure (FMS) that can be obtained from fragment ions, which can be of types a, b, bo, b*, 
                                c, x, y, yo, y* and z. To trim the FMS set by &#948; means to remove as many elements from FMS 
                                as possible without losing meaningful fragment ions mass values (spectrum peaks). The formula 
                                to calculate &#948; is:
                                <br/>
                                <img style="margin-left:150px;" alt="delta" id="deltaimg" src="images/delta.png" />
                                <br/>
                                It was already expected that the functional form of &#948; would depend on the fragments mass range, 
                                as well as their granularity (extent to which fragments are broken down into smaller ions). 
                                In a manner similar to the case for estimating &#949;, we used regression to obtain the specific 
                                functional form for the dependent variable &#948; in terms of the variables AAmax 
                                (the largest amino acid residue mass), AAmin (the smallest amino acid residue mass), 
                                AAaverage (the average amino acid residues mass), and ||p|| (average number of amino acid 
                                residues per fragment).
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <h4><a name="VSthreshold"></a>7. Matching Score Threshold</h4>
                            </td>
                        </tr>
                        <tr>
                            <td class="justified">
                                A match score represents the weight or importance of a potential disulfide bond.
                                This score represents the combined importance of each single peak match within two spectra. 
                                (one theoretical and one experimental). Each specific peak match is weighted according to its 
                                intensity. The match score is given by:
                                <br/>
                                <img style="margin-left:250px;" alt="matchscore" id="matchscoreimg" src="images/matchscore.png" />
                                <br/>
                                In the equation above, the numerator corresponds to the sum of each confirmatory match for a disulfide bond 
                                multiplied by the matched MS/MS fragment normalized intensity value (In). VMi is a binary value which is 
                                set to 1 if a confirmatory match was found for fragment i. The denominator similarly contains the sum of 
                                each experimental MS/MS fragment ion from the MS/MS spectra (TMS) multiplied by In. TMSi is a binary 
                                variable which indicates the presence of a fragment i in the MS/MS spectrum.
                                <br/><br/>
                                <b>We recommend users who did not find the expected results to lower the matching score threshold.</b>
                                The mass spectrometer specificity, the dissociation method used, the tuning of some of the thresholds
                                and/or trimming parameters play an important role in scoring each disulfide bond. Maybe the default
                                matching score threshold used by MS2DB+ turns out to be too "tight" for the data inputed.
                                <br/><br/>
                                The trade-off for lowering the matching score threshold is the possibility of finding false positive
                                S-S bonds. However, researchers consider false negative results to have a more deleterious effect on 
                                protein characterization than false positive results, which justifies lowering the matching score threshold.
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p></p>
                            </td>
                        </tr>
                    </table>
                    <!-- #EndEditable "content" -->
                </div>
                <!-- End Left Column -->
            </div>
            <!-- End Page Content -->
            <!-- Begin Footer -->
            <?php include "footer.php" ?>
            <!-- End Footer -->
        </div><!-- End Container -->
    </body>
    <!-- #EndTemplate -->
</html>