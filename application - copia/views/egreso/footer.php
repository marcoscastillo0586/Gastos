  <link rel="stylesheet" href="<?=base_url()?>/assets/vendor/jquery-ui/jquery-ui.css">
  
  <script src="<?=base_url()?>/assets/vendor/jquery-ui/jquery-ui.js"></script>
 
<script>

$(document).ready(function(){
  var cmbLugares = <?php echo json_encode($cmblugares); ?>;


  $("#from").datepicker({ dateFormat: "dd/mm/yy" }).datepicker("setDate", "0");
  $( function() {
      var dateFormat = "dd/mm/yy",
      from = $( "#from" ).datepicker({
        closeText: 'Cerrar',
        setDate:new Date(),
        prevText: '<Ant',
        nextText: 'Sig>',
        currentText: 'Hoy',
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Juv', 'Vie', 'Sáb'],
        dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
        weekHeader: 'Sm',
        dateFormat: 'dd/mm/yy',
         // defaultDate: "+1w",
          changeMonth: true,
          numberOfMonths: 1
        })
 
    function getDate( element ) { var date; try {date = $.datepicker.parseDate( dateFormat, element.value );} catch( error ) { date = null;} return date;}
});

  cargarCategoria();
  cargarConceptoIndividual(0);
  $(document).on("click",".imgCard",function(){
    //comprobamos si existe una imagen seleccionada
    if ( $( ".imgCard" ).hasClass( "imgCard-selected" ) ) {
      /*en el caso que exista ya una imagen seleccionada la eliminamos para que únicamente solo se tenga una imagen seleccionada*/
      $(".imgCard").removeClass("imgCard-selected");
    }
      //añadimos la clase de la imagen seleccionada
    $(this).addClass("imgCard-selected");
  });

  var cantRenglones = 0;


            ////////////   AGREGAR RENGLON /////////////////////
  $(document).on("click", ".agregarRenglon", function () {
    cantRenglones++;

    var url = '<?php echo(base_url());?>Egreso/darCategoriasJSArray';
    $.post(url).done(function (resp) {
      var res = JSON.parse(resp);
        var optionsHtml = '';
         optionsHtml += '<option value="0"> </option>';
        res.forEach(function (option) {
            optionsHtml += '<option value="' + option.value + '">' + option.nombre + '</option>';
        });

        var cmb = `<select class="form-control text-uppercase press cmbcategoria"  name="categoria" id="cmbcategoria_` + cantRenglones + `">
                  ` + optionsHtml + `</select>`;
        $('#principal #formPrincipal').append(`<div class="form-row md-6" style="justify-content: center;">
            <div class="form-group col-md-2" style="top: 0.5em;text-align: end;">
                <i class="fas fa-trash text-danger fs-1 eliminar"style="cursor:pointer;"></i>
            </div>
            <div class="form-group col-md-4">
              <input type="text" list="listaConceptos_`+cantRenglones+`" class="form-control text-uppercase concepto" id="concepto_`+cantRenglones+`" placeholder="">
        <datalist id="listaConceptos_`+cantRenglones+`">
        </datalist>
            </div> 
            <div class="form-group col-md-2">
                <input type="number" class="form-control press montoIngresar" id="montoIngresar_` + cantRenglones + `" placeholder="">
            </div>
            <div class="form-group col-md-3">
                ` + cmb + `            
            </div>
        </div>`);
     
        cargarConceptoIndividual(cantRenglones);
        $(".agregarRenglon").prop("disabled", "disabled");
    });
});

  $('#formPrincipal').on("change",".press",function(){ 
    var form = $(this).parents("form#formPrincipal ");
        check = checkCampos(form);
        if(check) {$(".agregarRenglon").prop("disabled", false);}
        else {$(".agregarRenglon").prop("disabled", "disabled");}
  })

  //Función para comprobar los campos de texto
  function checkCampos(obj) {
    var inputRellenados = true;
    var selectRellenados = true;
    obj.find("input").each(function(){
      var $this = $(this);
      if ( $this.hasClass('press')){if( $this.val().length <= 0  ) {inputRellenados = false;}}
    });

    obj.find("select").each(function(){
    var $this = $(this);
      if ($this.hasClass('press')){if($this.find('option:selected').text()=='') {selectRellenados = false;}}
    });

    if( inputRellenados == false || selectRellenados == false ){return false;}else {return true;}
  } 



  function cargarCategoria(){
    url = '<?php echo(base_url());?>Egreso/darCategoriasJSArray';
    $.post(url).done(function(resp){
      var res= JSON.parse(resp); 
      var optionsHtml = '';
         optionsHtml += '<option value=" "></option>';
        res.forEach(function (option) {
        optionsHtml += '<option value="' + option.value + '">' + option.nombre + '</option>';
        });
        $('#cmbcategoria_0').html(optionsHtml);


    });
  }

  function cargarConceptoIndividual(renglon){
    url = '<?php echo(base_url());?>Egreso/darConcepto';
    $.post(url).done(function(resp){
      var res= JSON.parse(resp); 

      var res = JSON.parse(resp);
        var optionsHtml = '';
        res.forEach(function (option) {
            optionsHtml += '<option value="' + option.nombre + '">' + option.nombre + '</option>';
        });
        $('#listaConceptos_'+renglon).html(optionsHtml);
    });


  }

 $(document).on("blur", ".montoIngresar", function() {
  var montoTotal = 0;
  var montoTotalCalcular = 0;
      /* Recorro cada input de monto ingresado en el formulario principal y valido que no este vacio*/ 
      $("form#formPrincipal").find(".montoIngresar").each(function(){
        if ($(this).val() == ''){comprobante='0';}
        else{
         
          id = $(this).attr('id');
             
              montoTotal = montoTotal+parseFloat($(this).val());
              montoTotalCalcular = montoTotal+parseFloat($(this).val());
            }
      });  
         $('#SumSubTotal').html('Subtotal: $'+montoTotal);
});

var objDatos = [];
            /////BTN DE REGISTRAR GASTO //////
  $(document).on("click","#btnRegistrarGasto",function(){
     objDatos = [];
    if( $(".imgCard").hasClass("imgCard-selected")) 
    { 
      /*Declaracion de variables*/ 
      let  arrayConcepto = []; let arrayMonto = [];let arrayCategoria = [];let arrayNomCat = [];let objNomCat = {};let objConcepto = {};let objMonto = {};let objCategoria = {};let comprobante = '1';let montoTotal = 0;
      
      if ($('#conceptoGeneral').val()==''){comprobante='0';}



          /* Recorro cada input de concepto ingresado en el formulario principal y valido que no este vacio*/ 
      $("form#formPrincipal").find(".concepto").each(function(){
        if ($(this).val() == ''){comprobante='0';}
        else{
              //objConcepto = {concepto:$(this).val()};  
              arrayConcepto.push(objConcepto);
            }
      });
      /* Recorro cada input de monto ingresado en el formulario principal y valido que no este vacio*/ 
      $("form#formPrincipal").find(".montoIngresar").each(function(){
        if ($(this).val() == ''){comprobante='0';}
        else{
         
          id = $(this).attr('id');
              objMonto = {monto:$(this).val()};  
              arrayMonto.push(objMonto);
              montoTotal = montoTotal+parseFloat($(this).val());
              montoTotalCalcular = montoTotal+parseFloat($(this).val());
            }
      });

 
      
      /* Recorro cada categoria ingresada en el formulario principal y valido que no este vacio*/
      $("form#formPrincipal").find(".cmbcategoria").each(function(){
        categoria = $("#"+$(this).attr("id")+" option:selected").text();
        if (categoria==' '){comprobante='0';}
        else{
            objCategoria = {categoria:categoria};  
            arrayCategoria.push(objCategoria);
        }
      });
      for (var i = 0; i < arrayCategoria.length; i++){
        arrayNomCat+= arrayCategoria[i].categoria+', ';
      };
      nombreCategoria = arrayNomCat.substring(0, arrayNomCat.length -2);
      if (comprobante=='0'){
          $('div .modal').html(`    
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header bg-info text-white text-center p-2">
                    <p class="modal-title text-center " > Faltan datos </p>
                  </div>  
          
                  <div class="modal-body"><br>
                    <div class="form-group">
                            <label class="text-primary" >Debe completar Todos Los campos</label>
                    </div>
                  </div>
          
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary " data-dismiss="modal">Cerrar</button>
                  </div>
              </div>
            </div>`);
             $('#modal').modal("show");
          
      }else
            {

            $("form#formPrincipal").find(".concepto").each(function(index) {
              let concepto = $('#concepto_' + index).val();
              let categoria = $('#cmbcategoria_' + index + " option:selected").text();
              let catId = $('#cmbcategoria_'+index).attr("id");  
              let id_categoria = $("#"+catId+" option:selected").val();
              let monto = number_format(parseFloat($('#montoIngresar_' + index).val()),2,',','.'); 
            
              objDatos[index] = {
                concepto: concepto,
                categoria: categoria,
                id_categoria: id_categoria,
                monto: monto
              };
          });
         

            // Itera sobre cada opción en objDatos y agrega una fila a la tabla para cada una
             //  let montoTotal = Number(montoTotal.toFixed(2));
                 montoTotal =number_format(Number(montoTotal.toFixed(2)),2,',','.'); 
                 montoTotalCalcular =Number(montoTotalCalcular.toFixed(2)); 
              let concepto        = $('#concepto_0').val();
              let id_lugar        = $(".imgCard-selected").attr('data-imglugar');
              let nombreLugar     = $(".imgCard-selected").attr('data-nombreLugar');
              let datos           = {monto:montoTotalCalcular,id_lugar:id_lugar};
              let url             = '<?php echo(base_url());?>Egreso/consultarSaldo';
              $.post(url, datos).done(function(resp) {
        if (resp == 1) {
        let tablaHTML = " "; // Variable para almacenar la tabla HTML
       

     // Construir la tabla HTML con los datos del objeto objDatos
        $.each(objDatos, function(index, opcion) {
            tablaHTML += "<tr>" +
                "<td>" + opcion.concepto + "</td>" +
                "<td>" + opcion.categoria + "</td>" +
                "<td> $ " + opcion.monto + "</td>" +
                "</tr>";
        });

        // Construir el contenido completo de la modal con la tabla HTML generada
        let modalContent = `
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white text-center p-2">
                        <p class="modal-title text-center">Datos a Debitar</p>
                    </div>
                    <div class="modal-body">
       
                        <br>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">Concepto</th>
                                    <th scope="col">Categoria</th>
                                    <th scope="col">Monto</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${tablaHTML} <!-- Aquí se inserta la tabla HTML generada -->
                            </tbody>
                            <tfooter>
                                <tr>   
                                    <th colspan="2" scope="col">Total</th>
                                    <td> $ ${montoTotal}  </td>
                                </tr>
                            </tfooter>
                        </table>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btncancelar" data-dismiss="modal">Cancelar</button>
                        <button id="btnConfirmarGasto" type="button" class="btn btn-primary">Confirmar Gasto</button>
                    </div>
                </div>
            </div>`;

        // Insertar el contenido de la modal en el div modal
        $('div .modal').html(modalContent);
        $('#modal').modal("show"); // Mostrar la modal
    } else {
        // Si no hay datos disponibles, mostrar otro contenido en la modal
        $('div .modal').html(`
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-info text-white text-center p-2">
                        <p class="modal-title text-center">Datos de Debito</p>
                    </div>
                    <div class="modal-body"><br>
                        <div class="form-group">
                            <h3 class="text-danger">No dispone de $${montoTotal} en ${nombreLugar}.</h3>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btncancelar" data-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>`);
        $('#modal').modal("show");
    }
});

            }
    }else{
              $('div .modal').html(`    
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header bg-warning text-white text-center p-2">
                    <p class="modal-title text-center " > ¿Quedaron datos sin completar?</p>
                  </div>  
          
                  <div class="modal-body"><br>
                    <div class="form-group">
                            <label class="text-primary" > Debe seleccionar un lugar para realizar el débito </label>
                    </div>
                  </div>
          
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary " data-dismiss="modal">Cerrar</button>
                  </div>
              </div>
            </div>`);
             $('#modal').modal("show");
    }
  });


    /////BTN DE CONFIRMAR GASTO //////
  $(document).on("click","#btnConfirmarGasto",function(){
         var  totalRegistros=0;
              arrayGasto=[];
              objGasto = {}; 
              var arrayMonto = [];
          arrayCategoria = [];
          arrayConcepto=[]; 
        
            //concepto
        $("form#formPrincipal").find(".concepto").each(function(){
                  var $thisconcepto = $(this);
                  if ($thisconcepto.hasClass('concepto')){
                    conceptoId = $thisconcepto.attr("id");  
                    concepto = $("#"+conceptoId).val();
                  }
                  if (concepto==''){
                    comprobante='0';
                   }else{
                    objConcepto = {concepto:concepto};  
                    arrayConcepto.push(objConcepto);
                   }
                });

                //combo categoria
                $("form#formPrincipal").find(".cmbcategoria").each(function(){
                  var $thisCategoria = $(this);
                  if ($thisCategoria.hasClass('cmbcategoria')){
                    catId = $thisCategoria.attr("id");  
                    categoria = $("#"+catId+" option:selected").val();
                  }
                  if (categoria==''){
                    comprobante='0';
                   }else{
                    objCategoria = {categoria:categoria};  
                    arrayCategoria.push(objCategoria);
                   }
                });

            //monot a ingresar
          $("form#formPrincipal").find(".montoIngresar").each(function(){
            totalRegistros++;
            var $thisMonto = $(this);
              if ($thisMonto.hasClass('montoIngresar')){
                monto = $thisMonto.val();  
              }
              if (monto==''){
                comprobante='0';
                   }else{
                    objMonto = {monto:monto};  
                    arrayMonto.push(objMonto);
                   }
          });

             
   
   for (var i = 0; i < arrayMonto.length; i++){
    var gastoMonto = arrayMonto[i].monto;
          gastoCategoria = arrayCategoria[i].categoria;
          concepto = arrayConcepto[i].concepto;   
          conceptoindividual = arrayConcepto[i].concepto;   
          objGasto = { monto:gastoMonto,categoria:gastoCategoria,concepto:concepto };
          arrayGasto.push(objGasto); 
   }

                var montoTotal=0;
                for (var i = 0; i < arrayMonto.length; i++){
                    var montoParcial = arrayMonto[i].monto;
                        montoTotal = montoTotal+parseFloat(montoParcial);
                }; 
      var conceptoGeneral      = $('#conceptoGeneral').val();
          id_lugar      = $(".imgCard-selected" ).attr('data-imglugar');
          
            fecha = $("#from").val();
            if (fecha=''){
                  $("#from").datepicker({ dateFormat: "dd/mm/yy" }).datepicker("setDate", "0");
                   fecha = $("#from").val();
            }else{
                   fecha = $("#from").val();
            }

          datos = {conceptoGeneral:conceptoGeneral,id_lugar:id_lugar,gasto:arrayGasto,fecha:fecha};
          url = '<?php echo(base_url());?>Egreso/guardarGasto';
      $.post(url,datos).done(function(resp){
        if (resp==1){
            $('div .modal').html(`    
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header bg-success text-white text-center p-2">
                    <p class="modal-title text-center " >Datos Ingresados</p>
                  </div>  
          
                  <div class="modal-body"><br>
                    <div class="form-group">
                            <label class="text-muted" >El Gasto Se Registro De Manera Exitosa.</label>
                    </div>
                  </div>
          
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btnFinalizarRegistro" data-dismiss="modal">Cerrar</button>
                  </div>
              </div>
            </div>`);
             $('#modal').modal("show");
             $('#montoIngresar').val('');
             $('#conceptoGeneral').val('');
             $('#cmbcategoria :selected').val('');
             $( ".imgCard-selected" ).removeClass('imgCard-selected');
          }else{alert('Algo salio mal');    }
        });
  })

   

  // Evento que selecciona la fila y la elimina 
  $(document).on("click",".btnFinalizarRegistro",function(){

    $('#formPrincipal').trigger("reset");
    
    $('.card').removeClass("imgCard-selected");
    $("#from").datepicker({ dateFormat: "dd/mm/yy" }).datepicker("setDate", "0");
    $('.eliminar').closest('.form-row').remove();
     cantRenglones = 0;
  });
  $(document).on("click",".eliminar",function(){
        //selecciono el elemento padre, el elemento padre es quien contiene al elemento que deseo eliminar
    $(this).closest('.form-row').remove();
    $(".agregarRenglon").prop("disabled", false);
        //reccorro el formulario principal para encontrar todos los input con la clase press y validar sus datos
    $("form#formPrincipal").find("input").each(function(){
      var $this = $(this);
        if ($this.hasClass('press')){
          if($this.val().length <= 0 ){
            $this.closest('.form-row').remove();
          }
        }
    }); 

    $("form#formPrincipal").find("select").each(function(){
      var $this = $(this);
        if ($this.hasClass('press')){
          if($this.find('option:selected').text()=='') {
            $this.closest('.form-row').remove();
          }
        }
    });
  });
 //Fin de icono de cada tarjeta de limites
 function number_format(number, decimals, dec_point, thousands_point) {
    if (number == null || !isFinite(number)){throw new TypeError("number is not valid");}
    if (!decimals) { var len = number.toString().split('.').length; decimals = len > 1 ? len : 0;}
    if (!dec_point) {dec_point = '.';}
    if (!thousands_point) {thousands_point = ',';}
    number = parseFloat(number).toFixed(decimals);
    number = number.replace(".", dec_point);
    var splitNum = number.split(dec_point);
    splitNum[0] = splitNum[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousands_point);
    number = splitNum.join(dec_point);
    return number;
  }

  $(document).on("click", ".li-tab", function() {
    // Desactivar el tab activo actual
    $(".li-tab.active").removeClass("active");

    // Activar el tab clickeado
    $(this).addClass("active");
});
    // Activa el tab correspondiente cuando se hace clic en él
    $(".li-tab").click(function(e) {
        e.preventDefault(); // Evita el comportamiento predeterminado
        sendToServer();
        // Eliminar la clase 'active' de todos los tabs y panes
        $(".li-tab").removeClass("active");
        $(".li-tab").removeClass("active");

        // Añadir la clase 'active' al tab y contenido correspondiente
        $(this).addClass("active");
        $($(this).attr("href")).addClass("active");
    });

/////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////// LECTOR DE QR  ///////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////
  // Configura el lector de QR usando html5-qrcode
  const html5QrCode = new Html5Qrcode("reader");
   // Iniciar el escaneo de QR con la cámara
   html5QrCode.start(
            { facingMode: "environment" },  // Usar la cámara trasera
            {
                //fps: 230, // Fotogramas por segundo
                qrbox: { width: 185, height: 195 },
                fps: 230, // Velocidad de escaneo en cuadros por segundo
        disableFlip: false, // No voltear la imagen en cámaras frontales
      
            },
            onScanSuccess,
            onScanError
        ).catch(err => {
            console.error("Error al iniciar el escaneo: ", err);
        });
        function sendToServer(decodedText) {
          console.log("sendToServer Código QR escaneado con éxito: ", decodedText);
            let url = '<?php echo(base_url());?>/Egreso/procesar_qr';
            miurl='http://apps01.coto.com.ar/TicketMobile/Ticket/NDE1Ny85NjY2LzIwMjUwMTMxLzEzMS8wMDAxMjEzOTM4MA==';
            //let datos = { qrData:decodedText};
            let datos = { qrData:miurl};
            $.post(url, datos, function(resp) {
        // Aquí resp ya es un objeto JavaScript, no necesitas usar JSON.parse()
        if (resp.status === 'success') {
       
          let modalContent = `
            <div class="modal-dialog modal-xl">
  <div class="modal-content">
    <div class="modal-header bg-success text-white text-center p-2">
      <h5 class="modal-title w-100">Datos a Debitar</h5>
    </div>
    <div class="modal-body">
      <div class="table-responsive" id="tabla-tiket">
        <div class="form-row justify-content-center">
          <div class="form-group col-md-2">
            <label for="concepto">Fecha <span class="text-danger">*</span></label>
            <input type="text" id="fromTiket" name="from" class="form-control text-uppercase">
          </div>
          <div class="form-group col-md-2">
            <label for="lugarTiket">Lugar <span class="text-danger">*</span></label>
            <select name="lugarTiket" class="form-control" id='lugarTiket'>${cmbLugares}</select>
          </div>
          <div class="form-group col-md-7">
            <label for="concepto">Concepto General del Gasto <span class="text-danger">*</span></label>
            <input type="text" class="form-control text-uppercase" id="conceptoGeneralTiket" placeholder="">
          </div>
        </div>
      </div>

      <table class="table table-bordered" id="table-tiket">
        <thead>
          <tr>
            <th scope="col">Imagen</th>
            <th scope="col">Concepto</th>
            <th scope="col">Precio</th>
            <th scope="col">Categoría</th>
          </tr>
        </thead>
        <tbody>
          ${resp.data.tabla} <!-- Aquí se inserta la tabla HTML generada -->
        </tbody>
        <tfoot>
          <tr>
            <th colspan="3" class="text-end">Total</th>
            <th>${resp.data.total}</th>
          </tr>
        </tfoot>
      </table>
    </div>
    <div id="errorModal"></div> 
        <div id="errorModalLugar"></div> 
        <div id="errorModalConcepto"></div> 
        
    <div class="modal-footer">
      <button type="button" class="btn btn-default btncancelar" data-dismiss="modal">Cancelar</button>
      <button id="btnConfirmarGastoTiket" type="button" class="btn btn-primary">Confirmar Gasto</button>
    </div>
  </div>
</div>
`;

        // Insertar el contenido de la modal en el div modal
        $('div .modal').html(modalContent);
        $('#modal').modal("show"); // Mostrar la modal

        $("#fromTiket").datepicker({ dateFormat: "dd/mm/yy" }).datepicker("setDate", "0");
            console.log(resp.data.total);
           // console.log(resp.data.products);
        } else {
            alert('Error: ' + resp.message);
        }
    })
    .fail(function(error) {
        alert('NO DONE');
        console.error("Error al enviar datos al servidor: ", error);
    });
}
        function onScanSuccess(decodedText, decodedResult) {
            //console.log("Código QR escaneado con éxito: ", decodedText);
            sendToServer(decodedText);
        }
        function onScanError(errorMessage) { /*console.error("Error durante el escaneo: ", errorMessage);*/  }
  
        
        /////BTN DE CONFIRMAR GASTO tiket //////
   $(document).on("click","#btnConfirmarGastoTiket",function(){
         var  totalRegistros = 0;
              arrayGasto = [];
              objGasto = {}; 
         var arrayMonto = [];
             arrayCategoria = [];
             arrayConcepto=[];
             comprobante = 1;             

             if (!$("#lugarTiket").val().trim()) {
              $('#errorModalLugar').html(`
                   <div class="form-group">
                    <label class="text-danger">Debe completar el campo Lugar</label>
                </div>`);
                comprobante = 0;
            }
            if (!$("#conceptoGeneralTiket").val().trim()) {
              $('#errorModalConcepto').html(`
                   <div class="form-group">
                    <label class="text-danger">Debe completar el campo Concepto</label>
                </div>`);
                comprobante = 0;
            }

             if (comprobante==1){
            //concepto
            $("form#formPrincipal").find(".concepto").each(function(){
                  var $thisconcepto = $(this);
                  if ($thisconcepto.hasClass('concepto')){
                    conceptoId = $thisconcepto.attr("id");  
                    concepto = $("#"+conceptoId).val();
                  }
                  if (concepto==''){
                    comprobante='0';
                   }else{
                    objConcepto = {concepto:concepto};  
                    arrayConcepto.push(objConcepto);
                   }
                });

                //combo categoria
                $("form#formPrincipal").find(".cmbcategoria").each(function(){
                  var $thisCategoria = $(this);
                  if ($thisCategoria.hasClass('cmbcategoria')){
                    catId = $thisCategoria.attr("id");  
                    categoria = $("#"+catId+" option:selected").val();
                  }
                  if (categoria==''){
                    comprobante='0';
                   }else{
                    objCategoria = {categoria:categoria};  
                    arrayCategoria.push(objCategoria);
                   }
                });

            //monot a ingresar
          $("form#formPrincipal").find(".montoIngresar").each(function(){
            totalRegistros++;
            var $thisMonto = $(this);
              if ($thisMonto.hasClass('montoIngresar')){
                monto = $thisMonto.val();  
              }
              if (monto==''){
                comprobante='0';
                   }else{
                    objMonto = {monto:monto};  
                    arrayMonto.push(objMonto);
                   }
          });
           
   
   for (var i = 0; i < arrayMonto.length; i++){
    var gastoMonto = arrayMonto[i].monto;
          gastoCategoria = arrayCategoria[i].categoria;
          concepto = arrayConcepto[i].concepto;   
          conceptoindividual = arrayConcepto[i].concepto;   
          objGasto = { monto:gastoMonto,categoria:gastoCategoria,concepto:concepto };
          arrayGasto.push(objGasto); 
   }

                var montoTotal=0;
                for (var i = 0; i < arrayMonto.length; i++){
                    var montoParcial = arrayMonto[i].monto;
                        montoTotal = montoTotal+parseFloat(montoParcial);
                }; 
      var conceptoGeneral      = $('#conceptoGeneralTiket').val();
          id_lugar      = $(".imgCard-selected" ).attr('data-imglugar');
          
            fecha = $("#from").val();
            if (fecha=''){
                  $("#from").datepicker({ dateFormat: "dd/mm/yy" }).datepicker("setDate", "0");
                   fecha = $("#from").val();
            }else{
                   fecha = $("#from").val();
            }

          datos = {conceptoGeneral:conceptoGeneral,id_lugar:id_lugar,gasto:arrayGasto,fecha:fecha};
          url = '<?php echo(base_url());?>Egreso/guardarGasto';
      $.post(url,datos).done(function(resp){
        if (resp==1){
            $('div .modal').html(`    
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header bg-success text-white text-center p-2">
                    <p class="modal-title text-center " >Datos Ingresados</p>
                  </div>  
          
                  <div class="modal-body"><br>
                    <div class="form-group">
                            <label class="text-muted" >El Gasto Se Registro De Manera Exitosa.</label>
                    </div>
                  </div>
          
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btnFinalizarRegistro" data-dismiss="modal">Cerrar</button>
                  </div>
              </div>
            </div>`);
             $('#modal').modal("show");
             $('#montoIngresar').val('');
             $('#conceptoGeneral').val('');
             $('#cmbcategoria :selected').val('');
             $( ".imgCard-selected" ).removeClass('imgCard-selected');
          }else{alert('Algo salio mal');    }
        });

      }
  });
/////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////


});
</script>

