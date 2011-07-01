<?php

class Chartingclass {

    var $chartSize;
    var $chartTitle;
    var $chartTitleFormat;
    var $chartAxis;
    var $chartLabel;
    var $chartType;
    var $chartColor;
    var $chart_X_Length;
    var $chart_Y_Length;
    var $chartMarkers;
    var $chartData;
    var $url;

    public function __construct(){
        $this->chartSize = 'chs=400x300';
        $this->chartTitle = 'chtt=';
        $this->chartTitleFormat = 'chts=0000FF,14';
        $this->chartAxis = 'chxt=x,y,x';
        $this->chartLabel = 'chxl=2:||m/z';
        $this->chartType = 'cht=lxy';
        $this->chartColor = 'chco=000000,000000';
        $this->chart_X_Length = 'chxr=0,200.0,2000.0,200.0';
        $this->chart_Y_Length = '1,0.0,1.0,0.05';
        $this->chartMarkers = 'chm=v,000000,0,,1,,h';
        $this->chartData = 'chd=t0:';
        $this->url = 'http://chart.apis.google.com/chart?';
    }

    public function  __get($name) {
        return $this->$name;
    }

    public function  __set($name, $value) {
        $this->$name = $value;
    }

    public function setChartTitle($title){
        $this->chartTitle = ($this->__get('chartTitle')).$title;
    }

    public function setChartData($data){

        $chartData = $this->__get('chartData');
        $chartX = "";
        $chartY = "";
        $x = 0.0;
        $y = 0.0;
        for($i=0;$i<count($data);$i++){
            $x = $this->get_X_value($data[$i]["mass"]);
            $y = $this->get_Y_value($data[$i]["intensity"]);
            if(strlen($chartX) == 0 && strlen($chartY) == 0){
                $chartX .= $x;
                $chartY .= $y;
            }
            else{
                $chartX .= ','.$x;
                $chartY .= ','.$y;
            }            
        }
        $chartData = $chartData.$chartX.'|'.$chartY;
        $this->__set("chartData", $chartData);
    }

    protected function generateURL(){
        $url  = $this->__get('url');
        $url .= $this->__get('chartSize').'&';
        $url .= $this->__get('chartTitle').'&';
        $url .= $this->__get('chartTitleFormat').'&';
        $url .= $this->__get('chartAxis').'&';
        $url .= $this->__get('chartLabel').'&';
        $url .= $this->__get('chartType').'&';
        $url .= $this->__get('chartColor').'&';
        $url .= $this->__get('chart_X_Length').'|';
        $url .= $this->__get('chart_Y_Length').'&';
        $url .= $this->__get('chartMarkers').'&';
        $url .= $this->__get('chartData');

        return $url;
    }

    protected function setMarkers($data){
        $chartMarkers = $this->__get('chartMarkers');
        $count = count($data);
        for($i=0;$i<$count;$i++){
            $intensity = str_ireplace(',', '', $data[$i]['intensity']);
            $intensity = (float)$intensity;
            //change to 0.4 if enable setMarkers
            if($intensity >= 0.0){
                $mass = str_ireplace(',', '', $data[$i]['mass']);
                $mass = (float)$mass;
                $chartMarkers .= '|'.'t'.$mass.'+'.$data[$i]['ions'].'%2B'.$data[$i]['charge'].',,0,'.$i.',9,,h';
            }
        }
        $this->__set('chartMarkers', $chartMarkers);
    }

    protected function get_X_value($datapoint){
        $range = explode(',', $this->__get('chart_X_Length'));
        $datapoint = str_ireplace(',', '', $datapoint);
        $datapoint = ((float)$datapoint);
        $min = ((float)$range[1]);
        $max = ((float)$range[2]);
        $value = ($datapoint-$min)/(($max-$min)/100);
        $value = number_format($value,1);
        return $value;
    }

    protected function get_Y_value($datapoint){
        $datapoint = str_ireplace(',', '', $datapoint);
        $value = ((float)($datapoint))*100.0;
        return (int)$value;
    }

    public function getChart($title,$data){
        
        $this->setChartTitle($title);
        $this->setChartData($data);
        //disabled now because markers are overlapping. Fix later!
        //$this->setMarkers($data);
        $url = $this->generateURL();
        return $url;
    }

    public function prepareData($TML,$CM){
        $data = array();

        for($i=0;$i<count($CM);$i++){
            $mass = str_ireplace(',','',$TML[$CM[$i]['debug']['TML']]['mass']);
            $mass = (float)$mass;
            $charge = (int)$TML[$CM[$i]['debug']['TML']]['charge'];
            $m_z = ($mass+$charge)/$charge;
            $data[$i]['mass'] = number_format($m_z,1);
            $data[$i]['intensity'] = number_format(((float)($TML[$CM[$i]['debug']['TML']]['intensity'])),1);
            $data[$i]['ions'] = str_ireplace('<=>', '', $CM[$i]['ion']);
            $data[$i]['charge'] = $charge;
        }
        /*
        $data[0]['mass'] = 1000;
        $data[0]['intensity'] = 0.9;
        $data[0]['ions'] = 'b1y1';
        $data[0]['charge'] = '2';
        $data[1]['mass'] = 300;
        $data[1]['intensity'] = 0.8;
        $data[1]['ions'] = 'B1Y1';
        $data[1]['charge'] = '3';
        $data[2]['mass'] = 500;
        $data[2]['intensity'] = 0.2;
        $data[2]['ions'] = 'x3z3';
        $data[2]['charge'] = '1';
        $data[3]['mass'] = 800;
        $data[3]['intensity'] = 0.4;
        $data[3]['ions'] = 'a4c4';
        $data[3]['charge'] = '3';
        */
        return $data;
    }
}
?>
