require('../scss/front.scss');
require('../../node_modules/tinymce/tinymce');
require('../../node_modules/tinymce/themes/silver/index');
require('../../node_modules/tinymce/icons/default/index');
var $ = require( "jquery" );

import 'bootstrap';

//var Highcharts = require('highcharts/highstock'); 
// Load module after Highcharts is loaded
//equire('highcharts/modules/exporting')(Highcharts);

//const Chart = require('chart.js');

// Init wysiwyg
tinymce.init({
    selector: 'textarea.wysiwyg',
});


let etat_menu = false;
$(document).ready(function(){
    console.log("Document est pret!!! " + $("#panneau_lateral").length);
    if($("#panneau_lateral").length)
    {
        $("#panneau_lateral").hide();
        document.getElementById("btn_panneau_lateral").onclick = function() { 
            console.log("CLIKCK");
            if(etat_menu)
            {
                console.log("Menu fermé");
                $("#panneau_lateral").hide();
                $(".nav_box").css("width","96.5%");
                $(".serre_box").css("width","96.5%");
                etat_menu = false;
            }
            else
            {
                console.log("Menu ouvert");
                $("#panneau_lateral").show();
                $(".nav_box").css("width","77%");
                $(".serre_box").css("width","77%");
                etat_menu=true;
            }
        };
    }
    $(".rapport_detail").hide();
    $(".rapport").click(function(){
        $(".rapport_detail").hide();
        var id=$(this).data("id");
        var id_detail = "#rapport_detail_"+id;
        $(id_detail).show();
        console.log("rapport: "+id);
    });
    //********************Graphique****************** */
    let ID_capteur = document.getElementById("ID_capteur").dataset.idcapteur;
    console.log( "ID_capteur = "+ID_capteur);

    let urlTemp = "http://127.0.0.1:8000/api/temperature.json/"+ID_capteur;
    let urlChartTemp="";

    let urlHum = "http://127.0.0.1:8000/api/humidite.json/"+ID_capteur;
    let urlChartHum="";

    let urlHumSol = "http://127.0.0.1:8000/api/humidite_sol.json/"+ID_capteur;
    let urlChartHumSol="";

    let DebutDate="";
    let FinDate="";

    let ChartDim ="500x300";
    $("#AlertGraphique").hide();
    //**********Temperature*************** */
    let xhr = new XMLHttpRequest();
    xhr.open("GET",urlTemp);
    xhr.responseType = "json";
    xhr.send();
    xhr.onload = function(){
        if (xhr.status !=200){
            console.log("Erreur " + xhr.status + " : " + xhr.statusText);
        }
        else{
            console.log(xhr.response.length + " octets  téléchargés\n" + JSON.stringify(xhr.response));
            let data = xhr.response;
            let data_length = data.length;
            let chdTemp = "";
            for (var i = 0; i < data_length; i++) {
                if(i == data_length-1){
                    chdTemp = chdTemp+data[i]["value1"];
                }
                else{
                    chdTemp = chdTemp+data[i]["value1"]+",";
                }
            }
            if(data_length==1)
            {
                $("#AlertGraphique").show();
            }
            else
            {
                $("#AlertGraphique").hide();
                DebutDate = new Date(data[1]["reading_time"]).toLocaleString();
                FinDate = new Date(data[data_length-1]["reading_time"]).toLocaleString();
                console.log(chdTemp);
                urlChartTemp="http://chart.apis.google.com/chart?cht=lc&chd=t:"+chdTemp+"&chs="+ChartDim+"&chg=10&chco=EE3826&chtt=Température C°&chxt=x,y,r&chxl=0:|"+DebutDate+"|"+FinDate;
                console.log(urlChartTemp);
                document.getElementById("ChartTemperature").src=urlChartTemp;
            }
        }
    };//xhr.onload

    //**********************Humidite**************** */
    let xhrH = new XMLHttpRequest();
    xhrH.open("GET",urlHum);
    xhrH.responseType = "json";
    xhrH.send();
    xhrH.onload = function(){
        if (xhrH.status !=200){
            console.log("Erreur " + xhrH.status + " : " + xhrH.statusText);
        }
        else{
            console.log(xhrH.response.length + " octets  téléchargés\n" + JSON.stringify(xhrH.response));
            let data = xhrH.response;
            let data_length = data.length;
            let chdHum = "";
            for (var i = 0; i < data_length; i++) {
                if(i == data_length-1){
                    chdHum = chdHum+data[i]["value2"];
                }
                else{
                    chdHum = chdHum+data[i]["value2"]+",";
                }
            }
            if(data_length==1)
            {
                $("#AlertGraphique").show();
            }
            else
            {
                $("#AlertGraphique").hide();
                DebutDate = new Date(data[1]["reading_time"]).toLocaleString();
                FinDate = new Date(data[data_length-1]["reading_time"]).toLocaleString();
                console.log(chdHum);
                urlChartHum="http://chart.apis.google.com/chart?cht=lc&chd=t:"+chdHum+"&chs="+ChartDim+"&chg=10&chco=4383C6&chtt=Humidité %&chxt=x,y,r&chxl=0:|"+DebutDate+"|"+FinDate;
                console.log(urlChartHum);
                document.getElementById("ChartHumidite").src=urlChartHum;
            }
        }
    };//xhr.onload

    //**********************Humidite Sol**************** */
    let xhrHS = new XMLHttpRequest();
    xhrHS.open("GET",urlHumSol);
    xhrHS.responseType = "json";
    xhrHS.send();
    xhrHS.onload = function(){
        if (xhrHS.status !=200){
            console.log("Erreur " + xhrHS.status + " : " + xhrHS.statusText);
        }
        else{
            console.log(xhrHS.response.length + " octets  téléchargés\n" + JSON.stringify(xhrHS.response));
            let data = xhrHS.response;
            let data_length = data.length;
            let chdHumSol = "";
            for (var i = 0; i < data_length; i++) {
                if(i == data_length-1){
                    chdHumSol = chdHumSol+data[i]["value3"];
                }
                else{
                    chdHumSol = chdHumSol+data[i]["value3"]+",";
                }
            }
            if(data_length==1)
            {
                $("#AlertGraphique").show();
            }
            else
            {
                $("#AlertGraphique").hide();
                DebutDate = new Date(data[1]["reading_time"]).toLocaleString();
                FinDate = new Date(data[data_length-1]["reading_time"]).toLocaleString();
                console.log(chdHumSol);
                urlChartHumSol="http://chart.apis.google.com/chart?cht=lc&chd=t:"+chdHumSol+"&chs="+ChartDim+"&chg=10&chco=74A379&chtt=Humidité du sol %&chxt=x,y,r&chxl=0:|"+DebutDate+"|"+FinDate;
                console.log(urlChartHumSol);
                document.getElementById("ChartHumiditeSol").src=urlChartHumSol;
            }
        }
    };//xhr.onload
});//Document.ready


    
