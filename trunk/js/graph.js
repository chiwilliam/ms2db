var jg_doc1 = new jsGraphics("graphdiv"); // draw directly into document

function myDrawFunction(C1,C2,Hspacing,Vspacing,totalperrow,color,stroke)
{
    /*
         *first position: 0,0
         *last position: (30*20=600),(20*#rows)
         *each horizontal change: +-Hspacing px
         *each vertical change: +-Vspacing px
         *
         *Disulfide Bond between cysteines C1 and C2
         */

    jg_doc1.setColor(color);
    jg_doc1.setStroke(stroke);

    var rowC1 = parseInt(C1/totalperrow);
    var rowC2 = parseInt(C2/totalperrow);
    if(C1 == (rowC1*totalperrow)){
        rowC1--;
    }
    if(C2 == (rowC2*totalperrow)){
        rowC2--;
    }
    var colC1 = C1%totalperrow;
    if(colC1 == 0){
        colC1 = totalperrow;
    }
    var colC2 = C2%totalperrow;
    if(colC2 == 0){
        colC2 = totalperrow;
    }
    var correction = 0;

    if (rowC1 == rowC2){
        //they are in the same row
        var Xpoints = new Array((colC1*Hspacing)+(Hspacing-Hspacing/2),(colC1*Hspacing)+(Hspacing-Hspacing/2),
            (colC2*Hspacing)+(Hspacing-Hspacing/2),(colC2*Hspacing)+(Hspacing-Hspacing/2));
        var Ypoints = new Array((rowC1*Vspacing),(rowC1*Vspacing)-(Vspacing/4),
            (rowC2*Vspacing)-(Vspacing/4),(rowC2*Vspacing));
        jg_doc1.drawPolyline(Xpoints, Ypoints);
    }
    else{
        //they are in different rows
        if(rowC2 > rowC1){
            if(colC1 == colC2){
                jg_doc1.drawLine((colC1*Hspacing)+(Hspacing-Hspacing/2),(rowC1*Vspacing)+(Vspacing),
                    (colC2*Hspacing)+(Hspacing-Hspacing/2),(rowC2*Vspacing));
            }
            else{
                if(colC2 > colC1){
                    jg_doc1.drawLine(((colC1*Hspacing)+(Hspacing))-correction,((rowC1*Vspacing)+(Vspacing-Vspacing/4))-correction,
                        (colC2*Hspacing)+correction,((rowC2*Vspacing)+(Vspacing-3*Vspacing/4))+correction);
                }
                else{
                    jg_doc1.drawLine((colC1*Hspacing)+correction,((rowC1*Vspacing)+(Vspacing-Vspacing/4))+correction,
                        ((colC2*Hspacing)+(Hspacing-Hspacing/4))-correction,(rowC2*Vspacing)-correction);
                }
            }
        }
        else{
            if(colC2 == colC1){
                jg_doc1.drawLine((colC2*Hspacing)+(Hspacing-Hspacing/2),(rowC2*Vspacing)+(Vspacing),
                    (colC1*Hspacing)+(Hspacing-Hspacing/2),(rowC1*Vspacing));
            }
            else{
                if(colC1 > colC2){
                    jg_doc1.drawLine(((colC2*Hspacing)+(Hspacing))-correction,((rowC2*Vspacing)+(Vspacing-Vspacing/4))-correction,
                        (colC1*Hspacing)+correction,(rowC1*Vspacing)+correction);
                }
                else{
                    jg_doc1.drawLine((colC2*Hspacing)+correction,((rowC2*Vspacing)+(Vspacing-Vspacing/4))+correction,
                        ((colC1*Hspacing)+(Hspacing-Hspacing/4))-correction,(rowC1*Vspacing)-correction);
                }
            }
        }
    }

    /*
            jg_doc1.drawLine(110,20,110,60);
            jg_doc1.drawLine(120,15,340,25);
            jg_doc1.drawLine(100,15,15,45);
            jg_doc1.drawPolyline(new Array(400+10,400+10,540+10,540+10), new Array(40,40-5,40-5,40));
        */

    jg_doc1.paint(); // draws, in this case, directly into the document

}




