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

darCategoria();

function darCategoria(){
    let url = '<?php echo(base_url());?>abm/CategoriaABM/darcategoria';
     $.post(url).done(function(resp){
      $('table tbody').html(resp);
    });
}
    // Eliminar registro
  $(document).on("click", ".eliminar", function () {
    let fila = $(this).closest("tr");
    let id   = fila.data("id");
    let mensaje = "¿Estás seguro de eliminar esta Categoría?";
            if (confirm(mensaje)) {
                let urlEliminar = "<?= base_url(); ?>abm/categoriaABM/eliminarCategoria";

                $.post(urlEliminar, { id: id }, function (respuesta) {
                    let res = JSON.parse(respuesta);

                    if (res == true) {
                        $('div .modal').html(`
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-info text-white text-center p-2">
                                        <p class="modal-title text-center">Eliminar Categoría</p>
                                    </div>
                                    <div class="modal-body">
                                        <br>
                                        <div class="form-group">
                                            <label class="text-primary">La categoría se eliminó correctamente.</label>
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
                                        <p class="modal-title text-center">Eliminar Categoría</p>
                                    </div>
                                    <div class="modal-body">
                                        <br>
                                        <div class="form-group">
                                            <label class="text-primary">Error al eliminar la categoría.</label>
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
                 darCategoria();
                });
            }
        
    
});


    // Activar registro
    $(document).on("click", ".activar", function() {
        let fila = $(this).closest("tr");
        let id = fila.data("id");
        let mensaje = "¿Esta seguro de activar nuevamente este Lugar?";

        if (confirm(mensaje)) {
            let url = "<?= base_url(); ?>abm/categoriaABM/activarCategoria";

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
                      <p class="modal-title text-center">Activar Lugar</p>
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
                 darCategoria();
            });
        }
    });

});
</script>