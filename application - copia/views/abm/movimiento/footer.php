<link rel="stylesheet" href="<?=base_url()?>/assets/vendor/jquery-ui/jquery-ui.css">
    <!-- SELECT 2-->
<script src="<?=base_url()?>assets/vendor/select2/dist/js/select2.js" ></script>
<script src="<?=base_url()?>/assets/vendor/jquery-ui/jquery-ui.js"></script>
<script>
     function darMovimientos() {
        let url = '<?= base_url(); ?>abm/MovimientoABM/darMovimientos';
        let datos = { desde: '', hasta: '', categoria: '0' };

        $.post(url, datos, function(resp) {
            $('table tbody').html(resp);
        });
    }
$(document).ready(function(){
$('#cmbcategoria').select2({maximumSelectionLength: 5,width: "300px"}).val('1');
$('#cmbcategoria').trigger('change');

$('#cmblugar').select2({maximumSelectionLength: 5,width: "300px"}).val('1');
$('#cmblugar').trigger('change');



darMovimientos();
cargarCategoria();
cargarLugar();



function cargarCategoria(){
    url = '<?php echo(base_url());?>abm/MovimientoABM/darCategoriasJS';
    $.post(url).done(function(resp){
      var res = JSON.parse(resp); 
        $('#cmbcategoria').html(res);
        $('#cmbcategoria').val('0');
    });
}  

function cargarLugar(){
    url = '<?php echo(base_url());?>abm/MovimientoABM/darLugarJS';
    $.post(url).done(function(resp){
      var res = JSON.parse(resp); 
      $('#cmblugar').html(res);
      $('#cmblugar').val('0');
    });
}

  $(document).on("change","#cmbcategoria",function(){
   buscarDatos();
  }); 
  $(document).on("change","#cmblugar",function(){
   buscarDatos();
  });


    $( function() {
      var dateFormat = "dd/mm/yy",
      from = $( "#from" ).datepicker({
        closeText: 'Cerrar',
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
        }).on( "change", function() {
          to.datepicker( "option", "minDate", getDate( this ) );
          to.datepicker( "option", "disabled", false );
   if($("#from").datepicker("getDate") === null || $("#to").datepicker("getDate") === null) { 
               $("#btnBuscar").prop("disabled", "disabled");
      }else{ 
          $("#btnBuscar").prop("disabled", false);
      }

        }),
      to = $( "#to" ).datepicker({
        closeText: 'Cerrar',
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
        numberOfMonths: 1,
        disabled :true
      }).on( "change", function() {
        from.datepicker( "option", "maxDate", getDate( this ) );
      if($("#from").datepicker("getDate") === null || $("#to").datepicker("getDate") === null) { 
               $("#btnBuscar").prop("disabled", "disabled");
      }else{ 
          var desde = $.datepicker.formatDate('yy-mm-dd', from.datepicker("getDate"));
          var hasta = $.datepicker.formatDate('yy-mm-dd', to.datepicker("getDate"));
          $("#btnBuscar").prop("disabled", false);
      }
      });
 
    function getDate( element ) { var date; try {date = $.datepicker.parseDate( dateFormat, element.value );} catch( error ) { date = null;} return date;}});
    

    $(document).on("click","#btnBuscar",function(){
      buscarDatos();
  });
   $(document).on("click",".chkcustom",function(){
      buscarDatos();
  });

       
  $(document).on("click",".mostrarmas",function(){
      var id = $(this).data('id');
      $(".mitdoculto_"+id).toggle();
  });
  function buscarDatos(){
    let desde = $.datepicker.formatDate('yy-mm-dd', $( "#from" ).datepicker("getDate"));
    let hasta = $.datepicker.formatDate('yy-mm-dd', $( "#to" ).datepicker("getDate"));
    let categoria = $('#cmbcategoria').val();
    let lugar = $('#cmblugar').val();
    let idCat = '';
    let idLugar = '';
    //recorro el array con el objeto y los concateno en un string para mostrar 
    for (var i = 0; i < categoria.length; i++){
                 idCat+= categoria[i]+',';
    };
    
    //recorro el array con el objeto y los concateno en un string para mostrar 
    for (var i = 0; i < lugar.length; i++){
                 idLugar+= lugar[i]+',';
    };
      // quito los dos ultimos elementos del array esto quita la, y el espacio al final del string 
      idCat = idCat.substring(0, idCat.length -1);
     // quito los dos ultimos elementos del array esto quita la, y el espacio al final del string 
      idLugar = idLugar.substring(0, idLugar.length -1);

    let datos = { desde:desde,hasta:hasta,categoria:idCat,lugar:idLugar };
    let url = '<?php echo(base_url());?>abm/MovimientoABM/darMovimientos';
     $.post(url,datos).done(function(resp){
      $('table tbody').html(resp);
    });
  }

  $('th').click(function(){
    var orden='';
    var table = $(this).parents('table').eq(0)
var elemento = $(this);
    //console.log($(this).data("name"));
    this.asc = !this.asc
      if (this.asc==1){
          orden = $(this).data("name")+ " ASC";
      }else{
          orden = $(this).data("name")+ " DESC";
      }
    //console.log(orden);
    setIcon($(this), this.asc);

  let desde = $.datepicker.formatDate('yy-mm-dd', $( "#from" ).datepicker("getDate"));
    let hasta = $.datepicker.formatDate('yy-mm-dd', $( "#to" ).datepicker("getDate"));
    let categoria = $('#cmbcategoria').val();
    let lugar = []; 
    $(".chkcustom").each(function(){
       if($(this).is(':checked')){
         lugar.push($(this).data('idlugar'));
       }
    });

    let datos = { desde:desde,hasta:hasta,categoria:categoria,lugar:lugar,orderBy:orden};
    let url = '<?php echo(base_url());?>abm/MovimientoABM/darMovimientos';
     $.post(url,datos).done(function(resp){
      $('table tbody').html(resp);
    });


  })

  function setIcon(element, asc) {
    $("th").each(function(index) {
      $(this).removeClass("sorting");
      $(this).removeClass("asc");
      $(this).removeClass("desc");
    });
    element.addClass("sorting");
    if (asc) element.addClass("asc");
    else element.addClass("desc");
  }

 // Función para filtrar filas según el texto ingresado
    $('#buscador').on('input', function () {
        let textoBusqueda = $(this).val().toLowerCase(); // Texto ingresado en el buscador

        if (textoBusqueda === "") {
         buscarDatos();
        } else {
            // Si hay texto en el buscador, realizar la búsqueda
            $('table tbody tr').each(function () {
                let fila = $(this);
                
                // Busca únicamente en las columnas "Concepto" (3) y "Monto" (4)
                let textoColumnas = fila.find('td:nth-child(3), td:nth-child(4)').text().toLowerCase();
  
                let columnas = fila.find('td:nth-child(3), td:nth-child(4)');
          
                // Mostrar u ocultar la fila según si contiene el texto buscado
                if (textoColumnas.includes(textoBusqueda)) {
                    fila.show();
                      columnas.each(function () {
                        let textoOriginal = $(this).text();
                        let textoResaltado = textoOriginal.replace(
                            new RegExp(`(${textoBusqueda})`, "gi"),
                            '<mark>$1</mark>'
                        );
                        $(this).html(textoResaltado);
                    });
                } else {
                    fila.hide();
                }
            });
        }
    });


});


   

    // Eliminar registro
    $(document).on("click", ".eliminar", function() {
        let fila = $(this).closest("tr");
        let id = fila.data("id");
        let tipo = fila.data("tipo");
        let mensaje = tipo === "encabezado" ? 
            "¿Estás seguro de eliminar este movimiento y todos sus detalles?" :
            "¿Quieres eliminar este gasto individualmente?";

        if (confirm(mensaje)) {
            let url = "<?= base_url(); ?>abm/MovimientoABM/eliminarMovimiento";

            $.post(url, { id: id, tipo: tipo }, function(respuesta) {
                let res = JSON.parse(respuesta);
            
                if (res==true) {

                    if (tipo === "encabezado") {
                        // Eliminar encabezado y detalles de la vista
                        $('tr[data-id="'+id+'"]').remove();
                        $('.oculto_' + id).remove();
                    } else {
                        // Eliminar solo el detalle
                        fila.remove();
                    }
                   $('div .modal').html(`
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header bg-info text-white text-center p-2">
                      <p class="modal-title text-center " >Eliminar Movimieno</p>
                    </div>
                    
                    <div class="modal-body"><br>
                      <div class="form-group">
                              <label class="text-primary" >El movimineto se elimino correctamente.</label>
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
                      <p class="modal-title text-center " >Eliminar Movimieno</p>
                    </div>
                    
                    <div class="modal-body"><br>
                      <div class="form-group">
                              <label class="text-primary" >Error al eliminar el movimientos.</label>
                      </div>
                    </div>
                    
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary btncancelar" data-dismiss="modal">Cerrar</button>
                    </div>
                  </div>
            </div>`);
                }
                $('#modal').modal("show");
                  darMovimientos();
            });
        }
    });

    // Mostrar/Ocultar detalles
    $(document).on("click", ".mostrarmas", function() {
        let id = $(this).data("id");
        $(".oculto_" + id).toggle();
    });

    // Editar celda al hacer click
$(document).on("click", ".editable", function() {
    // Si la fila es no editable, no hacer nada
    if ($(this).closest("tr").hasClass("no-editable")) return;

    let celda = $(this);
    let valorOriginal = celda.text().trim();
    let idDetalle = celda.closest("tr").data("id");
    let columna = celda.data("columna");

    // Verificar si la celda que se quiere editar es el concepto general de la fila madre
    if (celda.closest("tr").data("tipo") === "encabezado" && columna === "concepto_general") {
        // Solo permitir editar el concepto general de la fila madre
        if (celda.find("input").length) return;

        let input = $("<input>", {
            type: "text",
            value: valorOriginal,
            class: "form-control",
            blur: function() { 
                guardarCambio(valorOriginal,celda, idDetalle, columna, input.val());
            },
            keypress: function(event) { 
                if (event.which == 13) { 
                    guardarCambio(valorOriginal,celda, idDetalle, columna, input.val());
                }
            }
        });

        celda.html(input);
        input.focus();
        return;
    }

    // Editar las celdas de detalle normalmente
    if (celda.find("input").length) return;

    let input = $("<input>", {
        type: "text",
        value: valorOriginal,
        class: "form-control",
        blur: function() { 
            guardarCambio(valorOriginal,celda, idDetalle, columna, input.val());
        },
        keypress: function(event) { 
            if (event.which == 13) { 
                guardarCambio(valorOriginal,celda, idDetalle, columna, input.val());
            }
        }
    });

    celda.html(input);
    input.focus();
});


    function guardarCambio(valorOriginal,celda, idTabla, columna, nuevoValor) {

        let url = "<?= base_url(); ?>abm/MovimientoABM/actualizarMovimiento";

  
  // Si el nuevo valor es el mismo que el original, no hacemos nada
    if (nuevoValor === valorOriginal) {
        console.log("No hay cambios, salida temprana.");
         darMovimientos();
        return; // No se hace nada, porque no hubo cambios
    }{


        $.post(url, { idTabla: idTabla, columna: columna, nuevoValor: nuevoValor }, function(respuesta) {
            
            if (respuesta== true) {
                celda.text(nuevoValor);
                darMovimientos();
            } else {
                alert("Error al actualizar");
                celda.text(celda.find("input").val());
            }
        }, "json");
     }
    }

</script>


