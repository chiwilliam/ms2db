<?php
$result1 = array();
$result2 = array();
$result3 = array();
$result4 = array();
$result5 = array();
$result6 = array();
$result7 = array();
$result8 = array();
$var1 = 0;
$var2 = 0;
$var3 = 0;
$var4 = 0;
$var5 = 0;
$var6 = 0;
$var7 = 0;
$var8 = 0;

set_time_limit(0);

echo "SVMGUIDE2<br/><br/>";

$cmd1 = "python C:\\Users\\William\\Desktop\\SFSU\\SVM\\libsvm-2.91\\tools\\easy.py C:\\Users\\William\\Desktop\\SFSU\\SVM\\datasets\\svmguide2\\svmguide2";

$cmd2 = "C:\\Users\\William\\Desktop\\SFSU\\SVM\\libsvm-2.91\\windows\\svm-scale -l -1 -u 1 C:\\Users\\William\\Desktop\\SFSU\\SVM\\datasets\\svmguide2\\svmguide2 > svmguide2.scale";
$cmd3 = "C:\\Users\\William\\Desktop\\SFSU\\SVM\\libsvm-2.91\\windows\\svm-train -v 5 svmguide2.scale";
$cmd4 = "python C:\\Users\\William\\Desktop\\SFSU\\SVM\\libsvm-2.91\\tools\\grid.py svmguide2.scale";

$a1 = exec($cmd1,&$result1,&$var1);

echo $result1[2].'<br/><br/>';

exit;

$a2 = exec($cmd2,&$result2,&$var2);
$a3 = exec($cmd3,&$result3,&$var3);
$a4 = exec($cmd4,&$result4,&$var4);

echo $a3.'<br/>'.$a4;

echo '<br/><br/><br/>SVMGUIDE1<br/><br/>';

$cmd1 = "python C:\\Users\\William\\Desktop\\SFSU\\SVM\\libsvm-2.91\\tools\\easy.py C:\\Users\\William\\Desktop\\SFSU\\SVM\\datasets\\svmguide1\\svmguide1 C:\\Users\\William\\Desktop\\SFSU\\SVM\\datasets\\svmguide1\\svmguide1.t";

$cmd2 = "C:\\Users\\William\\Desktop\\SFSU\\SVM\\libsvm-2.91\\windows\\svm-scale -l -1 -u 1 -s range1 C:\\Users\\William\\Desktop\\SFSU\\SVM\\datasets\\svmguide1\\svmguide1 > svmguide1.scale";
$cmd3 = "C:\\Users\\William\\Desktop\\SFSU\\SVM\\libsvm-2.91\\windows\\svm-scale -r range1 C:\\Users\\William\\Desktop\\SFSU\\SVM\\datasets\\svmguide1\\svmguide1.t > svmguide1.t.scale";
$cmd4 = "C:\\Users\\William\\Desktop\\SFSU\\SVM\\libsvm-2.91\\windows\\svm-train svmguide1.scale";
$cmd5 = "C:\\Users\\William\\Desktop\\SFSU\\SVM\\libsvm-2.91\\windows\\svm-predict svmguide1.t.scale svmguide1.scale.model svmguide1.t.predict";

$cmd6 = "python C:\\Users\\William\\Desktop\\SFSU\\SVM\\libsvm-2.91\\tools\\grid.py svmguide1.scale";

$cmd7 = "C:\\Users\\William\\Desktop\\SFSU\\SVM\\libsvm-2.91\\windows\\svm-train -c 2 -g 2 svmguide1.scale";
$cmd8 = "C:\\Users\\William\\Desktop\\SFSU\\SVM\\libsvm-2.91\\windows\\svm-predict svmguide1.t.scale svmguide1.scale.model svmguide1.t.predict";

$a1 = exec($cmd1,&$result1,&$var1);

echo $result1[5].'<br/><br/>';

$a2 = exec($cmd2,&$result2,&$var2);
$a3 = exec($cmd3,&$result3,&$var3);
$a4 = exec($cmd4,&$result4,&$var4);
$a5 = exec($cmd5,&$result5,&$var5);
$a6 = exec($cmd6,&$result6,&$var6);
$a7 = exec($cmd7,&$result7,&$var7);
$a8 = exec($cmd8,&$result8,&$var8);

echo $a5.'<br/>'.$a6.'<br/>'.$a8;

?>
