<script type="text/javascript" src="speedtest.js"></script>
<script type="text/javascript">

//LIST OF TEST SERVERS. See documentation for details if needed
var SPEEDTEST_SERVERS=[
    {   //this is my demo server, remove it
        name:"Prueba de velocidad", //user friendly name for the server
        server:"//fi.openspeed.org/", //URL to the server. // at the beginning will be replaced with http:// or https:// automatically
        dlURL:"garbage.php",  //path to download test on this server (garbage.php or replacement)
        ulURL:"empty.php",  //path to upload test on this server (empty.php or replacement)
        pingURL:"empty.php",  //path to ping/jitter test on this server (empty.php or replacement)
        getIpURL:"getIP.php"  //path to getIP on this server (getIP.php or replacement)
    }
    //add other servers here, comma separated
];


//INITIALIZE SPEEDTEST
var s=new Speedtest(); //create speedtest object

s.onupdate=function(data){ //callback to update data in UI
    I("ip").textContent=data.clientIp;
    I("dlText").textContent=(data.testState==1&&data.dlStatus==0)?"...":data.dlStatus;
    I("ulText").textContent=(data.testState==3&&data.ulStatus==0)?"...":data.ulStatus;
    I("pingText").textContent=data.pingStatus;
    I("jitText").textContent=data.jitterStatus;
}
s.onend=function(aborted){ //callback for test ended/aborted
    I("startStopBtn").className=""; //show start button again
    if(aborted){ //if the test was aborted, clear the UI and prepare for new test
        initUI();
    } else {
        // alert( 'terminado' );

        var descarga = parseFloat( $('#dlText').text() );
        var subida = parseFloat( $('#ulText').text() );
        var latencia = parseFloat( $('#pingText').text() );


        if ( (descarga < 15) || (subida < 2.5) || (latencia > 250) ) {
            
            var resumenMedicion = "<br>Lamentamos informate que puedes presentar fallos en la transmisión de tu clase,<br> te sugerimos contactar a tu proveedor de internet para mejorar tu conexión.";
            $( '#resumenMedicion' ).removeClass('text-danger').removeClass('text-success').addClass('text-danger').html( '<i class="fas fa-times fa-2x mb-3 animated rotateIn text-danger"></i>' + resumenMedicion );

            $( '#contenedor_respuesta' ).html( '<a  class="btn btn-outline-danger btn-rounded waves-effect animated swing" id="btn_aceptar">Acepto y continuar</a>' );
        
        } else {
            
            var resumenMedicion = "<br>Los resultados fueron favorables, <br>esperamos que no sufras inconvenientes en la video-clase."; 
            $( '#resumenMedicion' ).removeClass('text-danger').removeClass('text-success').addClass('text-success').html( '<i class="fas fa-check fa-2x mb-3 animated rotateIn text-success"></i>' + resumenMedicion );


            $( '#contenedor_respuesta' ).html( '<a  class="btn btn-outline-success btn-rounded waves-effect animated swing" id="btn_aceptar">Acepto y continuar</a>' );
        
        }


        var id_sub_hor = '<?php echo $_POST['id_sub_hor']; ?>';


        $( '#btn_aceptar' ).on('click', function(event) {
            event.preventDefault();
            /* Act on the event */

            $("#contenedor_video_sala").html('<h3 class="text-center grey-text"><i class="fas fa-cog fa-spin"></i> Cargando...</h3>');
            $.ajax({
              url: 'server/obtener_sala_video_materia.php',
              type: 'POST',
              data: { id_sub_hor, descarga, subida, latencia },
              success: function ( respuesta ) {


                $("#contenedor_video_sala").html( respuesta );

              }
            });


        });


        
        
        


        // alert( descarga );
    }

}
function selectServer(){ //called after loading server list
    s.selectServer(function(server){ //run server selection. When the server has been selected, display it in the UI
        I("startStopBtn").style.display=""; //show start/stop button again
        I("serverId").textContent=server.name; //show name of test server
    });
}
function loadServers(){ //called when the page is fully loaded
    I("startStopBtn").style.display="none"; //hide start/stop button during server selection
    if(typeof SPEEDTEST_SERVERS === "string"){
        //load servers from url
        s.loadServerList(SPEEDTEST_SERVERS,function(servers){
            //list loaded
            SPEEDTEST_SERVERS=servers;
            selectServer();
        });
    }else{
        //hardcoded list of servers, already loaded
        s.addTestPoints(SPEEDTEST_SERVERS);
        selectServer();
    }
    
}



function startStop(){ //start/stop button pressed
    if(s.getState()==3){
        //speedtest is running, abort
        s.abort();
    }else{
        //test is not running, begin
        s.start();
        I("startStopBtn").className="running";
        

        $( '#resumenMedicion' ).html( '' );
        $( '#contenedor_respuesta' ).html( '' );
        // I("datosCuriosos").textContent="La conexion correcta...";
    }
}

//function to (re)initialize UI
function initUI(){
    I("dlText").textContent="";
    I("ulText").textContent="";
    I("pingText").textContent="";
    I("jitText").textContent="";
    I("ip").textContent="";
}

function I(id){return document.getElementById(id);}
</script>

<style type="text/css">
    html,body{
        border:none; padding:0; margin:0;
        background:#FFFFFF;
        color:#202020;
    }
    body{
        text-align:center;
        font-family:"Roboto",sans-serif;
    }
    h1{
        color:#404040;
    }
    #startStopBtn{
        display:inline-block;
        margin:0 auto;
        color:#6060AA;
        background-color:rgba(0,0,0,0);
        border:0.15em solid #6060FF;
        border-radius:0.3em;
        transition:all 0.3s;
        box-sizing:border-box;
        width:8em; height:3em;
        line-height:2.7em;
        cursor:pointer;
        box-shadow: 0 0 0 rgba(0,0,0,0.1), inset 0 0 0 rgba(0,0,0,0.1);
    }
    #startStopBtn:hover{
        box-shadow: 0 0 2em rgba(0,0,0,0.1), inset 0 0 1em rgba(0,0,0,0.1);
    }
    #startStopBtn.running{
        background-color:#FF3030;
        border-color:#FF6060;
        color:#FFFFFF;
    }
    #startStopBtn:before{
        content:"Iniciar";
    }
    #startStopBtn.running:before{
        content:"Cancelar";
    }
    #test{
        margin-top:2em;
        margin-bottom:12em;
    }
    div.testArea{
        display:inline-block;
        width:14em;
        height:9em;
        position:relative;
        box-sizing:border-box;
    }
    div.testName{
        position:absolute;
        top:0.1em; left:0;
        width:100%;
        font-size:1.4em;
        z-index:9;
    }
    div.meterText{
        position:absolute;
        bottom:1.5em; left:0;
        width:100%;
        font-size:2.5em;
        z-index:9;
    }
    #dlText{
        color:#6060AA;
    }
    #ulText{
        color:#309030;
    }
    #pingText,#jitText{
        color:#AA6060;
    }
    div.meterText:empty:before{
        color:#505050 !important;
        content:"0.00";
    }
    div.unit{
        position:absolute;
        bottom:2em; left:0;
        width:100%;
        z-index:9;
    }
    div.testGroup{
        display:inline-block;
    }
    @media all and (max-width:65em){
        body{
            font-size:1.5vw;
        }
    }
    @media all and (max-width:40em){
        body{
            font-size:0.8em;
        }
        div.testGroup{
            display:block;
            margin: 0 auto;
        }
    }

</style>

<p class="text-danger" >
    A continuación, realizaremos una medición de tu conexión a internet. <br>
    Verificaremos si dispones de los requisitos necesarios para evitar <br> 
    errores en la calidad de transmisión.
</p>

<h5 id="resumenMedicion">
    
</h5>

<p class="font-weight-normal font-italic text-primary">Presiona "Iniciar" para continuar</p>



<div id="startStopBtn" onclick="startStop()"></div>
<div id="serverId">Cargando...</div>





<div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8">
        <div class="row">
            <div class="col-md-2">
                
            </div>
            <div class="col-md-8">
                <span class="badge badge-success">Descarga recomendado 20Mbps</span>
                <span class="badge badge-success">Subida recomendado 3Mbps</span>
                <span class="badge badge-success">Latencia recomendado 250ms</span>
            </div>
            <div class="col-md-2">
                
            </div>
        </div>
        
    </div>
    <div class="col-md-2"></div>
</div>

<div id="test">
    <div class="testGroup">


        <div class="testArea">
            <div class="testName">
                Descarga
            </div>
            <div id="dlText" class="meterText"></div>
            <div class="unit">Mbps</div>
        </div>

        <div class="testArea">

            <div class="testName">Subida</div>
            <div id="ulText" class="meterText"></div>
            <div class="unit">Mbps</div>


        </div>


    </div>
    <div class="testGroup">
        <div class="testArea">
            <div class="testName">Latencia</div>
            <div id="pingText" class="meterText"></div>
            <div class="unit">ms</div>
        </div>
        <div class="testArea" style="display: none;">
            <div class="testName">Jitter</div>
            <div id="jitText" class="meterText"></div>
            <div class="unit">ms</div>
        </div>
    </div>
    <div id="ipArea">
        Dirección IP: <span id="ip"></span>

        <div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8">
        <div class="row">
            <div class="col-md-4">
                
            </div>
            <div class="col-md-4" id="contenedor_respuesta">
               
            </div>
            <div class="col-md-4">
                
            </div>
        </div>
        
    </div>
    <div class="col-md-2"></div>
</div>
    </div>
</div>




<script type="text/javascript">
    initUI();
    loadServers();
</script>