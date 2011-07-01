function showProcessing(){
    document.getElementById("processing").innerHTML = "PROCESSING...";
    document.getElementById("loadingimage").style.visibility = "visible";
    document.getElementById("bondsdiv").innerHTML = "";
    document.getElementById("graphdiv").innerHTML = "";
    document.getElementById("listofbondsdiv").innerHTML = "";
    document.getElementById("bondsdiv").style.visibility = "hidden";
    document.getElementById("graphdiv").style.visibility = "hidden";
    document.getElementById("listofbondsdiv").style.visibility = "hidden";
}