<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html dir="ltr" xmlns="http://www.w3.org/1999/xhtml">
    <!-- #BeginTemplate "master.dwt" -->
    <head>
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
        <!-- #BeginEditable "doctitle" -->
        <title>Publications</title>
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
                    <li class="selected"><a href="citing.php" onmouseover="Tip('Citing MS2DB+')" onmouseout="UnTip()">Citing MS2DB+</a></li>
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
                    <table class="content" id="publications" cellpadding="0" cellspacing="0" summary="" width="80%">
                        <tr>
                            <td>
                                <br></br>
                                <p><span style="font-size:14px;font-weight:bold;">Please cite the following references for MS2DB+:</span></p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <ul>
                                    <li>
                                        <font face="Times New Roman" size="3">
                                        W. Murad, R. Singh, and T-Y. Yen, "An Efficient Algorithmic Approach for Mass
                                        Spectrometry-Based Disulfide Connectivity Determination in Proteins Using
                                        Multi-Ion Analysis", <i>BMC Bioinformatics</i>, 12 (Suppl 1):S12, 2011
                                        </font>
                                        <a target="_blank" href="http://www.biomedcentral.com/1471-2105/12/S1/S12">
                                            <img alt="[PDF]" border="0" height="16" src="./images/pdf.gif" width="16" />
                                        </a>
                                    </li>
                                    <dd>&nbsp;</dd>
                                    <li>
                                        <font face="Times New Roman" size="3">
                                        W. Murad and R. Singh, "MS2DB+: A software for Determination of Disulfide Bonds 
                                        Using Multi-Ion Analysis", <i>IEEE Transactions on NanoBioscience</i>, 12, Issue 2, pp.69-71, 2013
                                        </font>
                                        <a target="_blank" href="http://ieeexplore.ieee.org/xpl/login.jsp?tp=&arnumber=6335483">
                                            <img alt="[PDF]" border="0" height="16" src="./images/pdf.gif" width="16" />
                                        </a>
                                    </li>
                                </ul>
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