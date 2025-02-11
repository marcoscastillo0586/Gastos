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