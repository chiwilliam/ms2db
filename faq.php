<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html dir="ltr" xmlns="http://www.w3.org/1999/xhtml">
    <!-- #BeginTemplate "master.dwt" -->
    <head>
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
        <!-- #BeginEditable "doctitle" -->
        <title>FAQ</title>
        <!-- #EndEditable -->
        <link href="styles/style1.css" media="screen" rel="stylesheet" title="CSS" type="text/css" />
        <link href="styles/style.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="js/wz_jsgraphics.js"></script>
    </head>
    <body>
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
                    <li><a href="help.php" onmouseover="Tip('MS2DB+ Help')" onmouseout="UnTip()">Help</a></li>
                    <li class="selected"><a href="faq.php" onmouseover="Tip('MS2DB+ FAQ')" onmouseout="UnTip()">FAQ</a></li>
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
                                <ol style="margin-left:-250px">
                                    <li class="spacing">
                                        <a href="#UniqueFeatures">What are the unique features of MS2DB+?</a>
                                    </li>
                                    <li class="spacing">
                                        <a href="#FileTypes">Does it provide support to multiple file types?</a>
                                    </li>
                                    <li class="spacing">
                                        <a href="#OpenSource">Is MS2DB+ an open source application?</a>
                                    </li>
                                    <li class="spacing">
                                        <a href="#Effectiveness">Why is MS2DB+ so effective?</a>
                                    </li>
                                    <li class="spacing">
                                        <a href="#Global">How MS2DB+ determines a global disulfide topology?</a>
                                    </li>
                                    <li class="spacing">
                                        <a href="#Results">Are MS2DB+ results affected by the specifics of the different mass spectrometers?</a>
                                    </li>
                                    <li class="spacing">
                                        <a href="#Fragmentation">Does MS2DB+ support different fragmentation methods?</a>
                                    </li>
                                    <li class="spacing">
                                        <a href="#Dataset">Is the dataset large enough to validate the algorithm?</a>
                                    </li>
                                    <li class="spacing">
                                        <a href="#Comparison">How well MS2DB+ performs when compared to other gold standard methods in the area?</a>
                                    </li>
                                </ol>
                            </td>
                        </tr>
                        <tr><td colspan="2"><h4><a name="UniqueFeatures"></a>1. What are the unique features of MS2DB+?</h4></td></tr>
                        <tr><td class="justified">
                                MS2DB+ uses a rich fragmentation model. To the best of our knowledge, MS2DB+ is the first publicly
                                available software (and algorithmic work) that analyzes multiple (twelve) ion types (a, a*, ao, b*,
                                bo, b, c, x, y*, yo, y, z) in MS/MS data for determining disulfide (S-S) bonds.
                                <br></br>
                                From an algorithmic perspective, MS2DB+ is also the first work that solves the problem of generating
                                and matching theoretical spectra with experimental spectra in polynomial time. This is possible
                                because the method only generates the few (theoretical) disulfide bonded peptide configurations whose mass
                                is close to the (given) experimental spectra. This is distinct from the exhaustive approach of generating
                                all possible peptide combinations and subsequently testing and discarding most of these
                                (as is done in other applications such as MassMatrix and MS2Links).
                                <br></br>
                                MS2DB+ uses a global optimization strategy based on the match-scores of each individual bonds to determine
                                the optimal consistent disulfide topology of the molecule.
                            </td>
                        </tr>
                        <tr><td colspan="2"><h4><a name="FileTypes"></a>2. Does it provide support to multiple file types?</h4></td></tr>
                        <tr><td class="justified">
                                MS2DB+ supports most of the common XML-based MS/MS file formats, including mzXML, mzData, and mzML, besides
                                Sequest DTA.
                            </td>
                        </tr>
                        <tr><td colspan="2"><h4><a name="OpenSource"></a>3. Is MS2DB+ an open source application?</h4></td></tr>
                        <tr><td class="justified">
                                Yes, MS2DB+ is an open source application. Its source code is available
                                <a target="_blank" href="http://code.google.com/p/ms2db/source/checkout"><span style="color:blue;">here</span></a>
                            </td>
                        </tr>
                        <tr><td colspan="2"><h4><a name="Effectiveness"></a>4. Why is MS2DB+ so effective?</h4></td></tr>
                        <tr><td class="justified">
                                MS2DB+ uses a two-stage trimming process: first, any theoretical S-S bonded combination which exceeds 
                                the precursor ion mass being matched is automatically discarded. Second, the
                                proposed method trims the search space based on the subset sum approximation algorithm. The contribution
                                of the second step becomes substantial when the fragmentation possibilities (in the confirmatory matching)
                                rapidly grow due to:
                                <ol style="list-style-type:lower-roman">
                                    <li>The consideration of multiple ion types in the fragmentation of the precursor ions.</li>
                                    <li>The possibility of a disulfide bonded structure being formed by more than two cysteine-containing
                                    peptides (more than one disulfide bond)</li>
                                    <li>The cases when the experimental fragmentation of precursor
                                    ions generated spectra with large amount of peaks (fragment ions) with significant intensity values.</li>
                                </ol>                                
                                On average, for the data used on this research, almost 98% of the search space (with up to 4.6 million
                                theoretical fragments for the protein FT III) was trimmed while still obtaining the correct disulfide
                                connectivity for the proteins studied.

                            </td>
                        </tr>
                        <tr><td colspan="2"><h4><a name="Global"></a>5. How MS2DB+ determines a global disulfide topology?</h4></td></tr>
                        <tr><td class="justified">
                                A "local" (putative bond-level) view of the disulfide connectivity is formed once proteins data are analyzed
                                by the two-level initial and confirmatory matching steps. The putative bonds however, may not form a globally
                                consistent connectivity pattern. Consequently, the disulfide bonds determined after the two-step matching
                                process need to be coalesced to obtain a globally consistent topology.
                                <br></br>
                                MS2DB+ models this problem as that of obtaining a maximum-weight matching in a graph G(V, E), where the
                                cysteines constitute the set of vertices V and the putative disulfide bonds constitute the set of edges E.
                                The match scores are used as weights for the edges.
                            </td>
                        </tr>
                        <tr><td colspan="2"><h4><a name="Results"></a>6. Are MS2DB+ results affected by the specifics of the different mass spectrometers?</h4></td></tr>
                        <tr><td class="justified">
                                The design of MS2DB+ in no way assumes the use of any specific technology. As long as the input data is in one of the 
                                formats: Sequest DTA, mzXML, mzData, or mzML, the software is usable. The data analysis in MS2DB+ is designed in
                                such a way so as to minimize the influence of variability in the data (which can be caused, among others due to the use
                                of different spectrometers). This includes:
                                <ol style="list-style-type:lower-roman">
                                    <li>"Matching windows" surrounding each experimental precursor and product ion being matched based on the charge state of the ions.
                                        The use of these windows avoids missing matches due to small differences between experimental and theoretical ion masses caused
                                        due to systemic noise which can vary between spectrometers. Although these windows are automatically calculated, users can
                                        manually tune them.</li>
                                    <li>Experimental MS/MS product ions  are selected based on their relative intensity. Our framework does not assume a fixed threshold
                                        to filter the product ions having significant abundance. Instead, the filtering is based on the abundance relative to the maximum
                                        abundance found in the set of product ions being analyzed. Thus, the formation of the search space is independent from the resolution
                                        and noise ratio of the mass spectrometer being used.</li>
                                    <li>The fragmentation model in MS2DB+ allows for different ion types which can arise from spectrometers using different dissociation modes
                                        such as ETD, ECD, or EDD.</li>
                                </ol>                                
                            </td>
                        </tr>
                        <tr><td colspan="2"><h4><a name="Fragmentation"></a>7. Does MS2DB+ support different fragmentation methods?</h4></td></tr>
                        <tr><td class="justified">
                                MS2DB+ supports many different fragmentation methods available, including: Collision-induced Dissiciation (CID), Electron-transfer dissociation (ETD),
                                Electron-capture dissociation (ECD), and Electron-detachment dissociation (EDD). The variability of the ions formed in these methods was the
                                main motivation to consider multiple ion types in the analysis of the data.
                                <br></br>
                                In MS2DB+, the user can independently select different ion types
                                while determining disulfide topologies. This option allows users to plug in MS/MS data generated using any of the dissociation methods
                                mentioned (CID, ECD, ETD, EDD). The figure below lists the different dissociation methods and the main ions generated by each method.
                                As can be seen, all these ions are accounted for in the fragmentation model of MS2DB+.
                                <br></br>
                                <img style="margin-left:180px;" alt="Dissociation methods" src="images/dissociationmethods.png" id="dissociationmethods"></img>
                            </td>
                        </tr>
                        <tr><td colspan="2"><h4><a name="Dataset"></a>8. Is the dataset large enough to validate the algorithm?</h4></td></tr>
                        <tr><td class="justified">
                                A better evaluation of the method would be possible with a larger dataset. We however note that for disulfide
                                bond analysis, the use of mass spectra for nine proteins (as has been done by us) represents one of the largest
                                data sets that have been analyzed in MS-based methods. For comparison purposes, we have summarized the size of
                                the data sets used in some of the well known papers that have addressed this problem in the recent past in the
                                figure below. As can be seen, the size of the data set in our validation studies is by far the largest
                                (MS2Links used 5 molecules and comes next).
                                <br></br>
                                <img style="margin-left:50px;" alt="Dataset sizes" src="images/datasetsize.png" id="datasetsize"></img>
                                <br></br>
                                Unfortunately, there is no publicly available repository (along the lines of PDB) for MS/MS spectra that can be 
                                used for access to more data. We believe, creating such a standardized publicly available resource will be very
                                helpful, among others, towards detailed testing and comparisons of solutions. We hope that the MS community
                                would look into the creation of such a resource.
                            </td>
                        </tr>
                        <tr><td colspan="2"><h4><a name="Comparison"></a>9. How well MS2DB+ performs when compared to other gold standard methods in the area?</h4></td></tr>
                        <tr><td class="justified">
                                MS2DB+ was compared, using the nine datasets available, with MassMatrix as well as with the gold standard predictive
                                applications (DiANNA,ISULFIND and PreCys), which all use sequence information. To the best of our knowledge, such a
                                comparative study was conducted for the first time in this area. The figure below summarizes the results –
                                which show MS2DB+ to have outperformed all these systems.
                                <br></br>
                                <img style="margin-left:50px;" alt="Methods comparison" src="images/methodscomparison.png" id="methodscomparison"></img>
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