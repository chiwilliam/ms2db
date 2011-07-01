<?php
    ini_set("memory_limit", "3000M");
    set_time_limit(0);

    include 'functionsDAT.php';

    //ALL BONDS
    $filename1 = "C:\\Users\\William\\Desktop\\SFSU\\SVM\\Uniprot\\SFSU\\bonds";
    $filename2 = "C:\\Users\\William\\Desktop\\SFSU\\SVM\\Uniprot\\SFSU\\bonds1";
    
    $resultfilename = "C:\\Users\\William\\Desktop\\SFSU\\SVM\\Uniprot\\SFSU\\allbonds";
    
    $filestr1 = file_get_contents($filename1);
    $filestr2 = file_get_contents($filename2);
    
    $filestr1 = $filestr1.$filestr2;
    
    file_put_contents($resultfilename, $filestr1);
    
    unset($filestr1);
    unset($filestr2);
    
    echo "SFSU disulfide bonded proteins processed!<br/><br/>";
    
    //SWISSPROT
    $files = 46;
    $resultfilename = "C:\\Users\\William\\Desktop\\SFSU\\SVM\\Uniprot\\SWISSPROT\\allsvm";
    $filestr = "";
    $filename = "C:\\Users\\William\\Desktop\\SFSU\\SVM\\Uniprot\\SWISSPROT\\svm";
    $exception = 0;
    
    for($i=0;$i<$files;$i++){
        if($i==0){
            $filestr .= file_get_contents($filename);
        }
        else{
            if($i != $exception){
                $filestr .= file_get_contents($filename.$i);
            }
        }        
    }
    
    file_put_contents($resultfilename, $filestr);
    
    echo "Swissprot disulfide bonded proteins processed!<br/><br/>";
    
?>
