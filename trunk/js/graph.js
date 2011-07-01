var jg_doc = new jsGraphics("graphdiv"); // draw directly into document

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

    jg_doc.setColor(color);
    jg_doc.setStroke(stroke);

    var rowC1 = parseInt(C1/totalperrow);
    var rowC2 = parseInt(C2/totalperrow);
    var colC1 = C1%30;
    var colC2 = C2%30;
    var correction = 0;

    if (rowC1 == rowC2){
        //they are in the same row
        var Xpoints = new Array((colC1*Hspacing)+(Hspacing-Hspacing/2),(colC1*Hspacing)+(Hspacing-Hspacing/2),
            (colC2*Hspacing)+(Hspacing-Hspacing/2),(colC2*Hspacing)+(Hspacing-Hspacing/2));
        var Ypoints = new Array((rowC1*Vspacing),(rowC1*Vspacing)-(Vspacing/4),
            (rowC2*Vspacing)-(Vspacing/4),(rowC2*Vspacing));
        jg_doc.drawPolyline(Xpoints, Ypoints);
    }
    else{
        //they are in different rows
        if(rowC2 > rowC1){
            if(colC1 == colC2){
                jg_doc.drawLine((colC1*Hspacing)+(Hspacing-Hspacing/2),(rowC1*Vspacing)+(Vspacing),
                    (colC2*Hspacing)+(Hspacing-Hspacing/2),(rowC2*Vspacing));
            }
            else{
                if(colC2 > colC1){
                    jg_doc.drawLine(((colC1*Hspacing)+(Hspacing))-correction,((rowC1*Vspacing)+(Vspacing-Vspacing/4))-correction,
                        (colC2*Hspacing)+correction,((rowC2*Vspacing)+(Vspacing-3*Vspacing/4))+correction);
                }
                else{
                    jg_doc.drawLine((colC1*Hspacing)+correction,((rowC1*Vspacing)+(Vspacing-Vspacing/4))+correction,
                        ((colC2*Hspacing)+(Hspacing-Hspacing/4))-correction,(rowC2*Vspacing)-correction);
                }
            }
        }
        else{
            if(colC2 == colC1){
                jg_doc.drawLine((colC2*Hspacing)+(Hspacing-Hspacing/2),(rowC2*Vspacing)+(Vspacing),
                    (colC1*Hspacing)+(Hspacing-Hspacing/2),(rowC1*Vspacing));
            }
            else{
                if(colC1 > colC2){
                    jg_doc.drawLine(((colC2*Hspacing)+(Hspacing))-correction,((rowC2*Vspacing)+(Vspacing-Vspacing/4))-correction,
                        (colC1*Hspacing)+correction,(rowC1*Vspacing)+correction);
                }
                else{
                    jg_doc.drawLine((colC2*Hspacing)+correction,((rowC2*Vspacing)+(Vspacing-Vspacing/4))+correction,
                        ((colC1*Hspacing)+(Hspacing-Hspacing/4))-correction,(rowC1*Vspacing)-correction);
                }
            }
        }
    }

    /*
            jg_doc.drawLine(110,20,110,60);
            jg_doc.drawLine(120,15,340,25);
            jg_doc.drawLine(100,15,15,45);
            jg_doc.drawPolyline(new Array(400+10,400+10,540+10,540+10), new Array(40,40-5,40-5,40));
        */

    jg_doc.paint(); // draws, in this case, directly into the document
}




