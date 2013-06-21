<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html dir="ltr" xmlns="http://www.w3.org/1999/xhtml">
    <!-- #BeginTemplate "master.dwt" -->
    <head>
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
        <!-- #BeginEditable "doctitle" -->
        <title>Datasets</title>
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
                    <li class="selected"><a href="datasets.php" onmouseover="Tip('MS2DB+ Datasets')" onmouseout="UnTip()">Datasets</a></li>
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
                <div id="column_l">
                    <!-- #BeginEditable "content" -->
                    <table class="content" id="datasets" cellpadding="0" cellspacing="0" summary="" width="80%">
                        <tr>
                            <td class="list">
                                <ol>
                                    <li class="spacing">
                                        <a href="#Lactoglobulin">Lactoglobulin</a>
                                    </li>
                                    <li class="spacing">
                                        <a href="#Sialyltransferase St8Sia IV">Sialyltransferase St8Sia IV</a>
                                    </li>
                                    <li class="spacing">
                                        <a href="#Fucosyltransferase 7">Fucosyltransferase 7</a>
                                    </li>
                                    <li class="spacing">
                                        <a href="#Core 2-branching enzyme">Core 2-branching enzyme</a>
                                    </li>
                                    <li class="spacing">
                                        <a href="#Lysozyme C">Lysozyme C</a>
                                    </li>
                                    <li class="spacing">
                                        <a href="#Fucosyltransferase 3">Fucosyltransferase 3</a>
                                    </li>
                                    <li class="spacing">
                                        <a href="#Beta-1,4-galactosyltransferase 1">Beta-1,4-galactosyltransferase 1</a>
                                    </li>
                                    <li class="spacing">
                                        <a href="#Muscle-type aldolase">Muscle-type aldolase</a>
                                    </li>
                                    <li class="spacing">
                                        <a href="#Aspartoacylase">Aspartoacylase</a>
                                    </li>
                                </ol>
                            </td>
                        </tr>
                        <tr>
                            <td class="note">
                                File formats other than the mzXML, mzData, mzML and DTA can be converted to a supported 
                                MS/MS data file using one of the following applications:
                                <a target="_blank" href="http://sourceforge.net/projects/sashimi/files/Trans-Proteomic%20Pipeline%20%28TPP%29/">TPP</a>,
                                <a target="_blank" href="http://proteowizard.sourceforge.net/download.html">ProteoWizard</a> or
                                <a target="_blank" href="http://www.proteomecommons.org/current/531/">ProteomeCommons.org IO</a>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <table>
                                    <tr>
                                        <td>
                                            <h4><a name="Lactoglobulin"></a>Lactoglobulin</h4>
                                        </td>
                                        <td>
                                            (<a href="http://www.uniprot.org/uniprot/P02754" target="_blank">Swiss-Prot ID P02754</a>)
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td class="justified">
                                <ul>
                                    <li>
                                        Function: Lactoglobulin is a primary component of whey, it binds retinol and is probably involved in the transport of that molecule.
                                    </li>
                                    <dd>&nbsp;</dd>
                                    <li>
                                        FASTA Sequence: Click <a href="http://www.uniprot.org/uniprot/P02754.fasta" target="_blank">here</a>
                                    </li>
                                    <dd>&nbsp;</dd>
                                    <table>
                                        <tr>
                                            <td>
                                                <li>Digestion:</li>
                                            </td>
                                            <td>
                                                <b><i>Tryptic</i></b> digested
                                            </td>
                                            <td>
                                                <ul>
                                                    <li>
                                                        MS/MS files: Click <a href="./datasets/lactoglobulin-trypsin.zip" target="_blank">here</a>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                    </table>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <table>
                                    <tr>
                                        <td>
                                            <h4><a name="Sialyltransferase St8Sia IV"></a>Sialyltransferase St8Sia IV</h4>
                                        </td>
                                        <td>
                                            (<a href="http://www.uniprot.org/uniprot/Q92187" target="_blank">Swiss-Prot ID Q92187</a>)
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td class="justified">
                                <ul>
                                    <li>
                                        Function: Sialyltransferase St8Sia IV catalyzes the polycondensation of alpha-2,8-linked sialic acid required for the synthesis of polysialic acid (PSA), which is present on the embryonic neural cell adhesion molecule (N-CAM), necessary for plasticity of neural cells.
                                    </li>
                                    <dd>&nbsp;</dd>
                                    <li>
                                        FASTA Sequence: Click <a href="http://www.uniprot.org/uniprot/Q92187.fasta" target="_blank">here</a>
                                    </li>
                                    <dd>&nbsp;</dd>
                                    <table>
                                        <tr>
                                            <td>
                                                <li>Digestion:</li>
                                            </td>
                                            <td>
                                                <b><i>Chymotryptic</i></b> digested
                                            </td>
                                            <td>
                                                <ul>
                                                    <li>
                                                        MS/MS files: Click <a href="./datasets/sia8d-chymotrypsin.zip" target="_blank">here</a>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                    </table>
                                    <dd>&nbsp;</dd>
                                    <li>
                                        Transmembrane region from <b>8</b>th AA until <b>20</b>th AA <i>(source UniProtKB)</i>.
                                    </li>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <table>
                                    <tr>
                                        <td>
                                            <h4><a name="Fucosyltransferase 7"></a>Fucosyltransferase 7</h4>
                                        </td>
                                        <td>
                                            (<a href="http://www.uniprot.org/uniprot/Q11130" target="_blank">Swiss-Prot ID Q11130</a>)
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td class="justified">
                                <ul>
                                    <li>
                                        Function: Fucosyltransferase 7 may catalyze alpha-1,3 glycosidic linkages involved in the expression of sialyl Lewis X antigens.
                                    </li>
                                    <dd>&nbsp;</dd>
                                    <li>
                                        FASTA Sequence: Click <a href="http://www.uniprot.org/uniprot/Q11130.fasta" target="_blank">here</a>
                                    </li>
                                    <dd>&nbsp;</dd>
                                    <table>
                                        <tr>
                                            <td>
                                                <li>Digestion:</li>
                                            </td>
                                            <td>
                                                <b><i>Tryptic</i></b> digested
                                            </td>
                                            <td>
                                                <ul>
                                                    <li>
                                                        MS/MS files: Click <a href="./datasets/FucT7-trypsin.zip" target="_blank">here</a>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>

                                            </td>
                                            <td>
                                                <b><i>Chymotryptic</i></b> digested
                                            </td>
                                            <td>
                                                <ul>
                                                    <li>
                                                        MS/MS files: Click <a href="./datasets/FucT7-chymotrypsin.zip" target="_blank">here</a>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                    </table>
                                    <dd>&nbsp;</dd>
                                    <li>
                                        Transmembrane region from <b>15</b>th AA until <b>36</b>th AA <i>(source UniProtKB)</i>.
                                    </li>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <table>
                                    <tr>
                                        <td>
                                            <h4><a name="Core 2-branching enzyme"></a>Core 2-branching enzyme</h4>
                                        </td>
                                        <td>
                                            (<a href="http://www.uniprot.org/uniprot/Q09324" target="_blank">Swiss-Prot ID Q09324</a>)
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td class="justified">
                                <ul>
                                    <li>
                                        Function: Core 2-branching enzyme forms critical branches in O-glycans.
                                    </li>
                                    <dd>&nbsp;</dd>
                                    <li>
                                        FASTA Sequence: Click <a href="http://www.uniprot.org/uniprot/Q09324.fasta" target="_blank">here</a>
                                    </li>
                                    <dd>&nbsp;</dd>
                                    <table>
                                        <tr>
                                            <td>
                                                <li>Digestion:</li>
                                            </td>
                                            <td>
                                                <b><i>Tryptic</i></b> digested
                                            </td>
                                            <td>
                                                <ul>
                                                    <li>
                                                        MS/MS files: Click <a href="./datasets/GnT-II-trypsin.zip" target="_blank">here</a>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>

                                            </td>
                                            <td>
                                                <b><i>Chymotryptic</i></b> digested
                                            </td>
                                            <td>
                                                <ul>
                                                    <li>
                                                        MS/MS files: Click <a href="./datasets/GnT-II-chymotrypsin.zip" target="_blank">here</a>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                    </table>
                                    <dd>&nbsp;</dd>
                                    <li>
                                        Transmembrane region from <b>10</b>th AA until <b>32</b>th AA <i>(source UniProtKB)</i>.
                                    </li>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <table>
                                    <tr>
                                        <td>
                                            <h4><a name="Lysozyme C"></a>Lysozyme C</h4>
                                        </td>
                                        <td>
                                            (<a href="http://www.uniprot.org/uniprot/P00698" target="_blank">Swiss-Prot ID P00698</a>)
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td class="justified">
                                <ul>
                                    <li>
                                        Function: Lysozymes have primarily a bacteriolytic function; those in tissues and body fluids are associated with the monocyte-macrophage system and enhance the activity of immunoagents.
                                    </li>
                                    <dd>&nbsp;</dd>
                                    <li>
                                        FASTA Sequence: Click <a href="http://www.uniprot.org/uniprot/P00698.fasta" target="_blank">here</a>
                                    </li>
                                    <dd>&nbsp;</dd>
                                    <table>
                                        <tr>
                                            <td>
                                                <li>Digestion:</li>
                                            </td>
                                            <td>
                                                <b><i>Tryptic</i></b> digested
                                            </td>
                                            <td>
                                                <ul>
                                                    <li>
                                                        MS/MS files: Click <a href="./datasets/lysozyme-trypsin.zip" target="_blank">here</a>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                    </table>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <table>
                                    <tr>
                                        <td>
                                            <h4><a name="Fucosyltransferase 3"></a>Fucosyltransferase 3</h4>
                                        </td>
                                        <td>
                                            (<a href="http://www.uniprot.org/uniprot/P21217" target="_blank">Swiss-Prot ID P21217</a>)
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td class="justified">
                                <ul>
                                    <li>
                                        Function: Fucosyltransferase 3 may catalyze alpha-1,3 and alpha-1,4 glycosidic linkages involved in the expression of Vim-2, Lewis A, Lewis B, sialyl Lewis X and Lewis X/SSEA-1 antigens. May be involved in blood group Lewis determination; Lewis-positive (Le+) individuals have an active enzyme while Lewis-negative (Le-) individuals have an inactive enzyme.
                                    </li>
                                    <dd>&nbsp;</dd>
                                    <li>
                                        FASTA Sequence: Click <a href="http://www.uniprot.org/uniprot/P21217.fasta" target="_blank">here</a>
                                    </li>
                                    <dd>&nbsp;</dd>
                                    <table>
                                        <tr>
                                            <td>
                                                <li>Digestion:</li>
                                            </td>
                                            <td>
                                                <b><i>Tryptic</i></b> digested
                                            </td>
                                            <td>
                                                <ul>
                                                    <li>
                                                        MS/MS files: Click <a href="./datasets/FUT3-trypsin.zip" target="_blank">here</a>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                    </table>
                                    <dd>&nbsp;</dd>
                                    <li>
                                        Transmembrane region from <b>16</b>th AA until <b>34</b>th AA <i>(source UniProtKB)</i>.
                                    </li>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <table>
                                    <tr>
                                        <td>
                                            <h4><a name="Beta-1,4-galactosyltransferase 1"></a>Beta-1,4-galactosyltransferase 1</h4>
                                        </td>
                                        <td>
                                            (<a href="http://www.uniprot.org/uniprot/P08037" target="_blank">Swiss-Prot ID P08037</a>)
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td class="justified">
                                <ul>
                                    <li>
                                        Function: Beta-1,4-galactosyltransferase catalyzes the production of lactose in the lactating mammary gland and could also be responsible for the synthesis of complex-type N-linked oligosaccharides in many glycoproteins as well as the carbohydrate moieties of glycolipids (Golgi complex form). The cell surface form functions as a recognition molecule during a variety of cell to cell and cell to matrix interactions, as those occurring during development and egg fertilization, by binding to specific oligosaccharide ligands on opposing cells or in the extracellular matrix.
                                    </li>
                                    <dd>&nbsp;</dd>
                                    <li>
                                        FASTA Sequence: Click <a href="http://www.uniprot.org/uniprot/P08037.fasta" target="_blank">here</a>
                                    </li>
                                    <dd>&nbsp;</dd>
                                    <table>
                                        <tr>
                                            <td>
                                                <li>Digestion:</li>
                                            </td>
                                            <td>
                                                <b><i>Tryptic</i></b> digested
                                            </td>
                                            <td>
                                                <ul>
                                                    <li>
                                                        MS/MS files: Click <a href="./datasets/b1-4GalT-trypsin.zip" target="_blank">here</a>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                    </table>
                                    <dd>&nbsp;</dd>
                                    <li>
                                        Transmembrane region from <b>1</b>st AA until <b>44</b>th AA <i>(source UniProtKB)</i>.
                                    </li>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <table>
                                    <tr>
                                        <td>
                                            <h4><a name="Muscle-type aldolase"></a>Muscle-type aldolase</h4>
                                        </td>
                                        <td>
                                            (<a href="http://www.uniprot.org/uniprot/P00883" target="_blank">Swiss-Prot ID P00883</a>)
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td class="justified">
                                <ul>
                                    <li>
                                        FASTA Sequence: Click <a href="http://www.uniprot.org/uniprot/P00883.fasta" target="_blank">here</a>
                                    </li>
                                    <dd>&nbsp;</dd>
                                    <table>
                                        <tr>
                                            <td>
                                                <li>Digestion:</li>
                                            </td>
                                            <td>
                                                <b><i>Tryptic</i></b> digested
                                            </td>
                                            <td>
                                                <ul>
                                                    <li>
                                                        MS/MS files: Click <a href="./datasets/Aldolase-trypsin.zip" target="_blank">here</a>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                    </table>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <table>
                                    <tr>
                                        <td>
                                            <h4><a name="Aspartoacylase"></a>Aspartoacylase</h4>
                                        </td>
                                        <td>
                                            (<a href="http://www.uniprot.org/uniprot/Q9R1T5" target="_blank">Swiss-Prot ID Q9R1T5</a>)
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td class="justified">
                                <ul>
                                    <li>
                                        Function: Aspartoacylase Catalyzes the deacetylation of N-acetylaspartic acid (NAA) to produce acetate and L-aspartate. NAA occurs in high concentration in brain and its hydrolysis NAA plays a significant part in the maintenance of intact white matter.
                                    </li>
                                    <dd>&nbsp;</dd>
                                    <li>
                                        FASTA Sequence: Click <a href="http://www.uniprot.org/uniprot/Q9R1T5.fasta" target="_blank">here</a>
                                    </li>
                                    <dd>&nbsp;</dd>
                                    <table>
                                        <tr>
                                            <td>
                                                <li>Digestion:</li>
                                            </td>
                                            <td>
                                                <b><i>Tryptic</i></b> digested
                                            </td>
                                            <td>
                                                <ul>
                                                    <li>
                                                        MS/MS files: Click <a href="./datasets/aspa-trypsin.zip" target="_blank">here</a>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                    </table>
                                </ul>
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