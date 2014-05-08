<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html dir="ltr" xmlns="http://www.w3.org/1999/xhtml">
    <!-- #BeginTemplate "master.dwt" -->
    <head>
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
        <!-- #BeginEditable "doctitle" -->
        <title>MS2DB+</title>
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
            <?php 
                $page = "index";
                include "menu.php";
            ?>
            <!-- End Navigation -->
            <!-- Begin Page Content -->
            <div id="page_content">
                <!-- Begin Left Column -->
                <div id="column_l">
                    <!-- #BeginEditable "content" -->
                    <table class="initialcontent" id="index" cellpadding="0" cellspacing="0" summary="" width="95%">
                        <tr>
                            <td>
                                <p></p>
                            </td>
                        </tr>
                        <tr>
                            <td class="justified">
                                <p>
                                    A <b>disulfide bond</b>, also called SS-bond or disulfide bridge, is a single covalent bond formed
                                    from the oxidation of sulfhydryl groups. Disulfide bonds play an important role in understanding protein
                                    folding, evolution, and structural properties.
                                </p>
                                <p>
                                    <b>Mass Spectrometry</b> is a powerful analytical technique used for identification of unknown compounds,
                                    quantification of known compounds, and to elucidate the structure and chemical properties of molecules.
                                    It has become the standard high throughput method for protein identification, and more recently,
                                    for protein quantification.
                                </p>
                                <p>
                                    Determining the disulfide bonding pattern in a protein is one of the critical stepping
                                    stones towards obtaining a mechanistic understanding of its structure and function.
                                    Consequently, this problem is a crucial one in contemporary proteomics and structural
                                    bioinformatics.
                                </p>
                            </td>
                            <td class="imageatright">
                                <img alt="" height="300" src="./images/ms2dbp%20picture.jpg" width="464" />
                            </td>
                        </tr>
                        <!-- For MS2DB+ -->
                        <tr>
                            <td colspan="2" class="justified">
                                <b>MS2DB+</b> is an open-source platform-independent web application that efficiently determines 
                                the disulfide linkages in proteins based on tandem mass spectrometry data. 
                                The software can account for multiple ions (a, ao, a*, b, bo, b*, c, x, y, yo, y*, and z) in determining the 
                                disulfide bonds, yet ensuring that the solution is found in polynomial time.
                            </td>
                        </tr>
                        <!-- For MS2DB++
                        <tr>
                            <td colspan="2" class="justified">
                                <b>MS2DB+</b> is an open-source platform-independent web application that efficiently determines 
                                the disulfide linkage in proteins based on mass spectrometry data and machine learning techniques. 
                                The software can account for multiple ions (a, b, bo, b*, c, x, y, yo, y*, and z) in determining the 
                                disulfide bonds, yet ensuring that the solution is found in polynomial time. Predictive techniques are 
                                applied to identify S-S bonds when the MS/MS data provides insufficient resolution. A predictive framework is 
                                formed by a SVM-classifier, cysteine separations profiles (CSPs) and smart filtering techniques to enhance 
                                MS2DB+ accuracy, sensitivity and specificity.
                            </td>
                        </tr>
                        -->
                        <tr>
                            <td colspan="2" class="justified">
                                <p>
                                    The application uses a <i>local-to-global</i> approach to merge putative disulfide bonds found by 
                                    the <i>search-and-match</i> framework into the most likely global disulfide connectivity pattern. 
                                    This optimization technique ensures the connectivity topology determined is consistent.
                                    The disulfide connectivity pattern identified is presented in both graphical and 
                                    tabular user-friendly formats.
                                </p>
                                <p>
                                    In its default mode, <b>MS2DB+</b> is completely automatic and easy-to-use. 
                                    At the same time, expert users are provided with an opportunity to “tune” crucial parameters required in the 
                                    disulfide bond determination process.
                                </p>
                                <p>
                                    If you are not familiar with <b>MS2DB+</b>, check the demo video
                                    below to get started <i>(best video quality: 720p HD and full screen)</i> or click 
                                        <a target="_blank" href="videos/ms2db_demo.wmv"><span style="color:blue;">here</span></a>
                                    to download/access the video directly. The most up-to-date source code is available 
                                        <a target="_blank" href="http://code.google.com/p/ms2db/source/checkout"><span style="color:blue;">here</span></a>.
				</p>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="centered">
                                <!-- Youtube tutorial vide -->
                                <p>
                                    <object width="425" height="344"><param name="movie" value="http://www.youtube.com/v/gu9LOpTWCOY?hl=en&fs=1&hd=1"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/gu9LOpTWCOY?rel=0&hl=en&fs=1&hd=1" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="425" height="344"></embed></object>
                                </p>
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