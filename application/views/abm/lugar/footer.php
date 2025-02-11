<link rel="stylesheet" href="<?=base_url()?>/assets/vendor/jquery-ui/jquery-ui.css">
    <!-- SELECT 2-->
<script src="<?=base_url()?>assets/vendor/select2/dist/js/select2.js" ></script>
<script src="<?=base_url()?>/assets/vendor/jquery-ui/jquery-ui.js"></script>
<style type="text/css">
      .custom-tooltip {
    text-align: left;
    font-size: 14px;
    padding: 5px;
    background-color: #ffffff; /* Fondo claro */
    border: 1px solid #4e73df; /* Borde gris */
    border-radius: 15px; /* Esquinas redondeadas */
    color: #343a40; /* Texto oscuro */
}
/* Cambiar el fondo y los estilos del tooltip */
.tooltip-inner {
    background-color: #FFFFFF !important; /* Cambia el fondo a un color claro */
    color: #343a40 !important; /* Cambia el color del texto */
    border: 0px solid #ced4da; /* Agrega un borde gris */
    border-radius: 4px; /* Redondea las esquinas */
    font-size: 14px; /* Ajusta el tamaño del texto */
    text-align: left; /* Alinea el texto a la izquierda */
}
.image-container {
    display: flex; /* Para alinear imágenes horizontalmente (opcional) */
    gap: 5px; /* Espaciado entre imágenes */
    justify-content: center;
}

/* Imágenes con tamaño uniforme */
.image-container img {
    width: 200px; /* Ancho deseado */
    height: 150px; /* Alto deseado */
    object-fit: contain; /* Mantiene la proporción original */
    border: 1px solid #ddd; /* Opcional: Borde decorativo */
    border-radius: 4px; /* Opcional: Bordes redondeados */
    background-color: #f8f9fa; /* Opcional: Fondo para espacios vacíos */
}

    </style>
<script>
$(document).ready(function(){
  $(function () {
    $('[data-toggle="tooltip"]').tooltip({
        html: true // Permite contenido HTML en los tooltips
    });
});

darLugar();

function darLugar(){
    let url = '<?php echo(base_url());?>abm/LugarABM/darLugares';
     $.post(url).done(function(resp){
      $('table tbody').html(resp);
    });
}
    // Eliminar registro
  $(document).on("click", ".eliminar", function () {
    let fila = $(this).closest("tr");
    let id = fila.data("id");
    let mensaje = "¿Estás seguro de eliminar este Lugar?";
    let urlValidar = "<?= base_url(); ?>abm/lugarABM/validarLugar";

    $.post(urlValidar, { id: id }, function (respuesta) {
        let validacion = JSON.parse(respuesta);

        if (validacion == true) {
            if (confirm(mensaje)) {
                let urlEliminar = "<?= base_url(); ?>abm/lugarABM/eliminarLugar";

                $.post(urlEliminar, { id: id }, function (respuesta) {
                    let res = JSON.parse(respuesta);

                    if (res == true) {
                        $('div .modal').html(`
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-info text-white text-center p-2">
                                        <p class="modal-title text-center">Eliminar Movimiento</p>
                                    </div>
                                    <div class="modal-body">
                                        <br>
                                        <div class="form-group">
                                            <label class="text-primary">El Lugar se eliminó correctamente.</label>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary btncancelar" data-dismiss="modal">Cerrar</button>
                                    </div>
                                </div>
                            </div>
                        `);
                    } else {
                        $('div .modal').html(`
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger text-white text-center p-2">
                                        <p class="modal-title text-center">Eliminar Lugar</p>
                                    </div>
                                    <div class="modal-body">
                                        <br>
                                        <div class="form-group">
                                            <label class="text-primary">Error al eliminar el Lugar.</label>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary btncancelar" data-dismiss="modal">Cerrar</button>
                                    </div>
                                </div>
                            </div>
                        `);
                    }

                    $('#modal').modal("show");
                    darLugar();
                });
            }
        } else {
            $('div .modal').html(`
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white text-center p-2">
                            <p class="modal-title text-center">Eliminar Lugar</p>
                        </div>
                        <div class="modal-body">
                            <br>
                            <div class="form-group">
                                <label class="text-primary">Este lugar tiene dinero, deje el lugar vacío y vuelva a intentarlo.</label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btncancelar" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            `);
            $('#modal').modal("show");
        }
    });
});


    // Activar registro
    $(document).on("click", ".activar", function() {
        let fila = $(this).closest("tr");
        let id = fila.data("id");
        let mensaje = "¿Esta seguro de activar nuevamente este Lugar?";

        if (confirm(mensaje)) {
            let url = "<?= base_url(); ?>abm/lugarABM/activarLugar";

            $.post(url, { id: id}, function(respuesta) {
                let res = JSON.parse(respuesta);
            
                if (res==true) {
               
                   $('div .modal').html(`
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header bg-info text-white text-center p-2">
                      <p class="modal-title text-center " >Eliminar Movimieno</p>
                    </div>
                    
                    <div class="modal-body"><br>
                      <div class="form-group">
                              <label class="text-primary" >El Lugar se activo correctamente.</label>
                      </div>
                    </div>
                    
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary btncancelar" data-dismiss="modal">Cerrar</button>
                    </div>
                  </div>
            </div>`);
        
           
                } else {
                       $('div .modal').html(`
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header bg-danger text-white text-center p-2">
                      <p class="modal-title text-center " >Activar Lugar</p>
                    </div>
                    
                    <div class="modal-body"><br>
                      <div class="form-group">
                              <label class="text-primary" >Error al activar el Lugar.</label>
                      </div>
                    </div>
                    
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary btncancelar" data-dismiss="modal">Cerrar</button>
                    </div>
                  </div>
            </div>`);
                }
                $('#modal').modal("show");
              darLugar();
            });
        }
    });
/////SECCION DE ALTA PARA NUEVO LUGAR//////

  //BTN DE CARGA DE IMAGEN DESTRO DEL MODAL, AQUI, solo se carga la imagen para visualizar pero no se guarda
$(document).on("change","#imgLugarNuevo",function(){
    let url = '<?php echo(base_url());?>abm/LugarABM/cargarImagenLugarNuevo';
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

});
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
          <div class="form-group ">  
              <label for="moneda">Moneda:</label>
                    <select id="moneda" name="moneda">
                        <option value="1">Peso</option>
                        <option value="0">Dólar</option>
                    </select>
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
  //Guardar el alta de un nuevo lugar
$(document).on("click","#btnGuardarLugar",function(){
  var nombreLugar=$('#nombrelugarNuevo').val();
  var moneda = $('#moneda').val(); 

  if(nombreLugar.length>0){
    if (nombreLugar.length>2){
      url = '<?php echo(base_url());?>abm/LugarABM/guardarLugarNuevo';
      formData = new FormData();
      if (!$('#imgLugarNuevo')[0].files[0]){
        $('#error').html('Debe seleccionar una imagen');
        setTimeout(function(){$('#error').html('');},2000);
      }else{
        files = $('#imgLugarNuevo')[0].files[0];
        formData.append('image',files);
        formData.append('nombre',nombreLugar);
        formData.append('tipoMoneda',moneda);
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
                    darLugar();
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

});
</script>