<script>
$(document).ready(function(){
$('[data-toggle="tooltip"]').tooltip()
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


/////BTN DE GUARDAR TRANSFERENCIA //////
$(document).on("click","#btnRealizarTransferencia",function(){

  if ( $('#cmbOrigen :selected').text()  && $('#cmbDestino :selected').text() && $('#montotransferir').val() ){
    var montotransferir = $('#montotransferir').val();
        origen  = $('#cmbOrigen :selected').text();
        id_origen  = $('#cmbOrigen :selected').val();
        destino = $('#cmbDestino :selected').text();
        datos = {montotransferir:montotransferir,id_lugar:id_origen};
        url = '<?php echo(base_url());?>Transferencia/consultarSaldo';
      $.post(url,datos).done(function(resp){
        if ( resp==1){       
          $('div .modal').html(`
                              <div class="modal-dialog">
                                <div class="modal-content">
                                  <div class="modal-header bg-info text-white text-center p-2">
                                    <p class="modal-title text-center " id="staticBackdropLabel">Datos a Ingresar</p>
                                  </div>
                                  <div class="modal-body">
                                <br>
                                    <div class="form-group ">  
                                        <label class="text-primary" for="imgLugarNuevo">Origen: `+origen+`</label>
                                    </div>
                                    <div class="form-group">
                                            <label class="text-primary" for="imgLugarNuevo">Destino: `+destino+`</label>
                                    </div>
                                    <div class="form-group">
                                            <label class="text-primary" for="imgLugarNuevo">Monto a tranferir: $`+montotransferir+`</label>
                                    </div>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                    <button id="btnConfirmartransferencia" type="button" class="btn btn-primary">Confirmar Ingreso</button>
                                  </div>
                                </div>
                              </div>`);
        }else{
              $('div .modal').html(`
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header bg-info text-white text-center p-2">
                      <p class="modal-title text-center " id="staticBackdropLabel">Datos de Transferencia</p>
                    </div>
                    <div class="modal-body">
                  <br>
                      <div class="form-group">
                              <h3 class="text-danger" >No dispone de $`+montotransferir+` en `+origen+`.</h3>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                  </div>
                </div>`);
                $('#montotransferir').val('');  
                $("#cmbDestino").empty();
                $('#montotransferir').prop("disabled", "disabled");  
                $("#cmbDestino").prop("disabled", "disabled");
        }
            $('#modal').modal("show");
});

  }else{
    var aviso =''
          $('div .modal').html(`
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-info text-white text-center p-2">
          <p class="modal-title text-center " id="staticBackdropLabel">Datos Ingresados</p>
        </div>
        <div class="modal-body">
      <br>
          <div class="form-group">
                  <h3 class="text-danger" >Debe Completar Todos Los Datos.</h3>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>`);
  $('#modal').modal("show");
  }
});

$(document).on("click","#btnConfirmartransferencia",function(){
    var montotransferir = $('#montotransferir').val();
    id_origen = $('#cmbOrigen :selected').val();
    id_destino = $('#cmbDestino :selected').val();
    nombre_origen = $('#cmbOrigen :selected').text();
    nombre_destino = $('#cmbDestino :selected').text();
    concepto = 'Transferencia desde ' + nombre_origen + ' hacia ' + nombre_destino;
    
     // Datos a enviar
    datos = {
        id_origen: id_origen,
        id_destino: id_destino,
        montotransferir: montotransferir,
        concepto: concepto
    };
    url = '<?php echo(base_url());?>Transferencia/guardarTransferencia';

    $.post(url, datos, function(resp) {
        if (resp == 1) {
            $('div .modal').html(`
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-info text-white text-center p-2">
                            <p class="modal-title text-center " id="staticBackdropLabel">Datos Ingresados</p>
                        </div>
                        <div class="modal-body">
                            <br>
                            <div class="form-group">
                                <label class="text-primary">La transferencia se Realizo de Manera Exitosa.</label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            `);
            $('#modal').modal("show");
            $("#cmbDestino").val("");
            $("#cmbOrigen").val("");
            $('#montotransferir').val('');
        } else {
            alert('Algo salio mal');
        }
    });
});




////////////////este es para cuadno esten todos los campos completados habilitar el btn de confirmar guardar
  $(document).on("keyup change","#cmbOrigen,#cmbDestino,#montotransferir",function(){ 
      var lugarSeleccionado = $("#cmbOrigen :selected").val();
      //si estan los tres con datos muestro el boton
      if($('#cmbOrigen :selected').text()!=='' && $('#cmbDestino :selected').text()!=='' && $('#montotransferir').val()!==''  ) {
          $("#btnRealizarTransferencia").prop("disabled", '');
      }else{
          $("#btnRealizarTransferencia").prop("disabled", "disabled");
          $('#montotransferir').prop("disabled", "disabled");  
        //si no estan los tres con datos pregunto si el origen y el destino estan con datos, de ser asi habilito el monto a transferir
        if($('#cmbOrigen :selected').text()!=='' && $('#cmbDestino :selected').text()!=='') {
          $('#montotransferir').prop("disabled",'');
          }
          else {
                $('#montotransferir').val('');  
                $("#cmbDestino").empty();
                $('#montotransferir').prop("disabled", "disabled");  
                $("#cmbDestino").prop("disabled", "disabled");
                if($("#cmbOrigen :selected").text()!==''){
                  var datos = {lugarSeleccionado:lugarSeleccionado};
                  var url = '<?php echo(base_url());?>transferencia/darLugarOrigenJS';
                  $.post(url,datos).done(function(resp){
                    var res= JSON.parse(resp);
                    $("#cmbDestino").html(res);
                  });
                  $("#cmbDestino").prop("disabled", "");
                }else{
                      $("#cmbDestino").prop("disabled", "disabled");
                     }        
          }
        }
    });
  });
</script>