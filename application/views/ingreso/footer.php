  <!-- Jquery STEP-->
    <script src="<?=base_url()?>assets/vendor/smartwizard/js/jquery.smartWizard.js"></script>
 <link rel="stylesheet" href="<?=base_url()?>/assets/vendor/jquery-ui/jquery-ui.css">
 <script src="<?=base_url()?>/assets/vendor/jquery-ui/jquery-ui.js"></script>
<style> .card:hover{background-color: #3EA3DE;color: #fff;}   .imgCard{background-color: #fff;margin:5px;padding:5px;}   .imgCard-selected{border: 6px solid #3EA3DE;margin:5px;padding:5px;}</style>
<script>
$(document).ready(function(){
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
 
    function getDate( element ) { var date; try {date = $.datepicker.parseDate( dateFormat, element.value );} catch( error ) { date = null;} return date;}});


/////SECCION DE ALTA PARA NUEVO LUGAR//////
$(document).on("click","#altaLugar",function(){
           
  $('div .modal').html(`
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-info text-white text-center p-2">
          <p class="modal-title text-center " id="staticBackdropLabel">Nuevo Lugar de Ingreso</p>
        </div>
        <div class="modal-body">
      <br>
          <div class="form-group ">  
              <label class="text-primary" for="imgLugarNuevo">Nombre del nuevo lugar:</label>
              <input class="form-control" type="text" placeholder="Nuevo Lugar" id="nombrelugarNuevo">
          </div>
          <div class="form-group">
                  <label class="text-primary" for="imgLugarNuevo">Imagen del nuevo lugar:</label>
                  <input type="file" class="form-control-file" name="imgLugarNuevo" size="25" id="imgLugarNuevo">
                  <img style="width: 20%;" id="imgAltaModal" class="card-img-top" src="<?=base_url()?>assets/default.png">
          </div>
        <div id="error" style="text-align: center;color: #ff0000;"></div>
        </div>
        <div class="modal-footer">
          <button id="cerrarModalAlta" type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button id="btnGuardarLugar" type="button" class="btn btn-primary">Guardar</button>
        </div>
      </div>
    </div>`);
  $('#modal').modal("show");
});

  //BTN DE CARGA DE IMAGEN DESTRO DEL MODAL, AQUI, solo se carga la imagen para visualizar pero no se guarda
$(document).on("change","#imgLugarNuevo",function(){
    var url = '<?php echo(base_url());?>Ingreso/cargarImagenLugarNuevo';
    var formData = new FormData();
    var files = $('#imgLugarNuevo')[0].files[0];
    var dire='<?=base_url()?>';
    formData.append('image',files);
    $.ajax({
            url: url,
            type: 'post',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {

                if (response != 0) {
                    $("#imgAltaModal").attr("src", response);
                } else {
                    alert('Formato de imagen incorrecto.');
                }
            }
        });
        return false;
}); 

$(document).on("change","#cerrarModalAlta",function(){
  $(".img-default").attr("src", "<?=base_url()?>assets/default.png");
  $('#nombrelugarNuevo').val('');
  $('#imgLugarNuevo').val('');
  $('#modal').modal("hide");
  /*var url = '<php echo(base_url());?>Ingreso/eliminarImgTemporal';
  $.post(url).done(function(resp){
  });*/
});


  //Guardar el alta de un nuevo lugar
$(document).on("click","#btnGuardarLugar",function(){
  var nombreLugar=$('#nombrelugarNuevo').val();
  if(nombreLugar.length>0){
    if (nombreLugar.length>2){
      url = '<?php echo(base_url());?>Ingreso/guardarLugarNuevo';
      formData = new FormData();
      if (!$('#imgLugarNuevo')[0].files[0]){
        $('#error').html('Debe seleccionar una imagen');
        setTimeout(function(){$('#error').html('');},2000);
      }else{
        files = $('#imgLugarNuevo')[0].files[0];
        formData.append('image',files);
        formData.append('nombre',nombreLugar);
        $.ajax({
              url: url,
              type: 'post',
              data: formData,
              contentType: false,
              processData: false,
              success: function(response) {
                 if (response != 0) {
                    $(".img-default").attr("src", "<?=base_url()?>assets/default.png");
                    $('#nombrelugarNuevo').val('');
                    $('#imgLugarNuevo').val('');
                    $('#modal').modal("hide");
                    $('#divLugares').html('');
                    cargarLugares()
                  } else { alert('No se Pudo Guardar.'); }
              }
        });  
      }
    }else{
      $('#error').html('El nombre debe tener al menos 3 caracteres');
    }
  }else{
    $('#error').html('El campo nombre no puede estar vacio');
    setTimeout(function(){$('#error').html('');},2000);
  }
  return false;
});

/////FIN DE SECCION DE ALTA PARA NUEVO LUGAR//////

$(document).on("click",".imgCard",function(){
  //comprobamos si existe una imagen seleccionada
  if ( $( ".imgCard" ).hasClass( "imgCard-selected" ) ) {
  /*en el caso que exista ya una imagen seleccionada la eliminamos para que únicamente solo se tenga una imagen seleccionada*/
  $(".imgCard").removeClass("imgCard-selected");
  }
  //añadimos la clase de la imagen seleccionada
  $(this).addClass("imgCard-selected");
});

/////BTN DE GUARDAR INGRESO //////
$(document).on("click","#btnGuardarIngreso",function(){
  if ( $( ".imgCard" ).hasClass( "imgCard-selected" ) &&  $('#montoIngresar').val() && $('#concepto').val() ){
   var monto = $('#montoIngresar').val();
       concepto =$('#concepto').val();
       nombreLugar = $( ".imgCard-selected" ).attr('data-nombreLugar');
  $('div .modal').html(`
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-info text-white text-center p-2">
          <p class="modal-title text-center " id="staticBackdropLabel">Datos a Ingresar</p>
        </div>
        <div class="modal-body">
      <br>
          <div class="form-group ">  
              <label class="text-primary" for="imgLugarNuevo">Monto: $`+monto+`</label>
          </div>
          <div class="form-group">
                  <label class="text-primary" for="imgLugarNuevo">Concepto: `+concepto+`</label>
                  
          </div>
          <div class="form-group">
                  <label class="text-primary" for="imgLugarNuevo">Lugar de Ingreso: `+nombreLugar+`</label>
                  
          </div>
       
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button id="btnConfirmarIngreso" type="button" class="btn btn-primary">Confirmar Ingreso</button>
        </div>
      </div>
    </div>`);
  $('#modal').modal("show");
  }else{
    var aviso =''
          $('div .modal').html(`<div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-info text-white">
        <h4 class="modal-title text-center" id="staticBackdropLabel">Datos Ingresados</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <h3 class="text-danger">Debe Completar Todos Los Datos.</h3>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-info" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>`);
  $('#modal').modal("show");
  }
});

/////BTN DE CONFIRMAR INGRESO //////
$(document).on("click","#btnConfirmarIngreso",function(){
   var id_lugar = $( ".imgCard-selected" ).attr('data-imgLugar');
       monto = $('#montoIngresar').val();
       concepto =$('#concepto').val();
       fecha = $("#from").val();
            if (fecha=''){
                  $("#from").datepicker({ dateFormat: "dd/mm/yy" }).datepicker("setDate", "0");
                   fecha = $("#from").val();
            }else{
                   fecha = $("#from").val();
            }

       datos= {id_lugar:id_lugar,monto:monto,concepto:concepto,fecha:fecha};
       url = '<?php echo(base_url());?>Ingreso/guardarIngreso';
      $.post(url,datos).done(function(resp){
      if (resp==1){
          $('div .modal').html(`
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-info text-white text-center p-2">
          <p class="modal-title text-center " id="staticBackdropLabel">Datos Ingresados</p>
        </div>
        <div class="modal-body">
      <br>
          <div class="form-group">
                  <label class="text-primary" >Los Datos Se Ingresaron de Manera Exitosa.</label>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>`);
  $('#modal').modal("show");
     $('#montoIngresar').val('');
     $('#concepto').val('');
     $( ".imgCard-selected" ).removeClass('imgCard-selected');
        }else{
          alert('Algo salio mal');
        }

  });
})

function cargarLugares(){
  var url = '<?php echo(base_url());?>Ingreso/darLugaresCardJS';
  $.post(url).done(function(resp){
     var res= JSON.parse(resp);  
    
    $('.divLugares').html(res);
    
  });
}

});
</script>