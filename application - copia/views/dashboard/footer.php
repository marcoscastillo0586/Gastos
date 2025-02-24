    <!-- Page level plugins -->
    <script src="<?=base_url()?>assets/vendor/chart.js/Chart.min.js"></script>
    <script src="<?=base_url()?>assets/vendor/chart.js/Chart.js"></script>
    <!-- Page level custom scripts -->
    <script src="<?=base_url()?>assets/vendor/jquery/jquery.min.js"></script>
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


  $('.chkcustom').click();
  $('.customCheckSite').click();
  graficoLimiteGastos();
  
  $(document).on("change","input[id^='customCheckSite']",function(){
    if($(this).is(':checked')){
      $( ".chkcustom" ).prop( "checked", true );
      sumaTotal();
    }
    else{ 
      $( ".chkcustom" ).prop( "checked", false ); 
      sumaTotal();
    }
  });

  function sumaTotal(){
    let  suma  = 0;
    $(".chkcustom").each(function(){
       if($(this).is(':checked')){
        let mString = $(this).data('monto')!= 0 ?$(this).data('monto'): 0;
        let mFloat  = parseFloat(mString);
          suma = suma + mFloat;
       }
       else{
        console.log('deschequeado');}
    });

       let sumaTotal =number_format(suma,2,',','.'); 
      $('#totalGeneral').html(`<p>TOTAL: <strong>$ ${sumaTotal}</strong> </p>`);
  }

  $(document).on("change","input[id^='customCheck-']",function(){
    sumaTotal();
  });
      //icono de cada tarjeta de limites
  $(document).on("click","a[id^='deleteLimite-']",function(){
      
  $('div .modal').html(`
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-warning text-white text-center p-2">
            <p class="modal-title text-center" >Eliminar Limite</p>
          </div>
          
          <div class="modal-body"><br>
            <label><strong>¿ Seguro quiere eliminar este limite de gastos ?</strong></label>
            <small class="form-text text-muted">Esta acción no se podra revertir</small>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary btncancelar" data-dismiss="modal">Cancelar</button>
            <button id="btnConfirmarEliminarLimite" data-id=`+$(this).data('id')+` type="button" class="btn btn-danger">Eliminar</button>
          </div>
        </div>
      </div>`);
      $('#modal').modal("show");



  });
  $(document).on("click","#btnConfirmarEliminarLimite",function(){
    let id = $(this).data('id');
         datos= {id:id};
    url = '<?php echo(base_url());?>Login/eliminarLimiteGastosPorId';
    $.post(url,datos).done(function(resp){
      var res = JSON.parse(resp); 
      graficoLimiteGastos();
        $('#modal').modal("hide");
      });
    

  })  
  $(document).on("click","a[id^='editLimite-']",function(){
    let id = $(this).data('id');
    datos= { id:id };
    url = '<?php echo(base_url());?>Login/LimitePorId';
    $.post(url,datos).done(function(resp){
     var res = JSON.parse(resp); 
      $('div .modal').html(`
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-info text-white text-center p-2">
            <p class="modal-title text-center" >Editar Limite</p>
          </div>
          
          <div class="modal-body"><br>
            <label>Limite</label>
            <input type="number" class="form-control" id="nuevoLimite" value="`+res[0].limite+`">
            <small  class="form-text text-muted"> Ingrese un nuevo valor para el limite de gasto </small>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary btncancelar" data-dismiss="modal">Cancelar</button>
            <button id="btnConfirmarEditLimite" data-id=`+id+` type="button" class="btn btn-primary">Guardar</button>
          </div>
        </div>
      </div>`);
      $('#modal').modal("show");
    });
  });
  
$(document).on("click", "#btnConfirmarEditLimite", function() {
    const id = $(this).data('id');
    const nuevoLimite = $('#nuevoLimite').val();
    const datos = { monto: nuevoLimite, id: id };
    const url = '<?php echo(base_url());?>Login/editarLimiteGastos';
    $.post(url, datos)
        .done(function(resp) {
            const res = JSON.parse(resp);
            $('#modal .modal-content').html(`
                <div class="modal-header  ${res.status === 'success' ? 'bg-success' : 'bg-danger'} text-white text-center p-2">
                    <p class="modal-title">Edición de Límite</p>
                </div>
                <div class="modal-body text-center">
                    <label class="${res.status === 'success' ? 'text-success' : 'text-danger'}"><strong>${res.message}</strong></label>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            `);
            $('#modal').modal('show');
            graficoLimiteGastos();
        })
        .fail(function() {
            // Manejo de errores: si la petición falla
            $('#modal .modal-content').html(`
                <div class="modal-header bg-danger text-white text-center p-2">
                    <p class="modal-title">Error</p>
                </div>
                <div class="modal-body text-center">
                    <label><strong>Ocurrió un error al procesar la solicitud. Intenta nuevamente.</strong></label>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            `);
            $('#modal').modal('show');
        });
});


$(document).on("click", "a[id^='renovarLimite-']", function() {
    const id = $(this).data('id');
    const datos = { id: id };
    const url = '<?php echo(base_url());?>Login/renovarLimite';

    $.post(url, datos)
        .done(function(resp) {
            const res = JSON.parse(resp); // La respuesta ya está en JSON

            // Crear contenido del modal con la respuesta
            const modalContent = `
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header ${res.status === 'success' ? 'bg-success' : 'bg-danger'} text-white text-center p-2">
                            <p class="modal-title text-center">Renovar Limite</p>
                        </div>
                        <div class="modal-body text-center">
                            <label><strong>${res.message}</strong></label>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>`;

            // Actualizar el contenido del modal y mostrarlo
            $('#modal .modal-content').html(modalContent);
            $('#modal').modal('show');

            // Actualizar gráfico de límites
            graficoLimiteGastos();
        })
        .fail(function() {
            // En caso de error en la petición
            const errorModalContent = `
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white text-center p-2">
                            <p class="modal-title text-center">Error</p>
                        </div>
                        <div class="modal-body text-center">
                            <label><strong>Hubo un problema al procesar tu solicitud. Intenta nuevamente.</strong></label>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>`;
            
            $('#modal .modal-content').html(errorModalContent);
            $('#modal').modal('show');
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
      var ctx = document.getElementById("myPieChart");
      var ctx2 = document.getElementById("myPieChart2");


function graficoLimiteGastos(){
  url = '<?php echo(base_url());?>Login/darGraficoLimitesGasto';
  $.post(url).done(function(resp){
   var res = JSON.parse(resp); 
   res.sort((a, b) => a.nombreCategoria.localeCompare(b.nombreCategoria));
       html='';
       color='';
    if(res!==0){
  $.each(res, function(i, item){
       excedenteFavor = '';
       let valor = 0;
       let renovar='';
    if(item.porcentaje <= 25){
        color='info';
    }
     if(item.porcentaje > 25 && item.porcentaje < 50){
        color='success';
    }

    if(item.porcentaje >= 50 && item.porcentaje < 75 ){
    color="warning";
    }

    if(item.porcentaje >= 75){
      color='danger';
      
    }
     if(item.porcentaje > 100){
      color='danger';
      formatoExcedente =  number_format(item.resto*-1, 2, ',', '.')
      excedenteFavor= '<strong>EXCEDENTE: </strong>$ '+formatoExcedente;
    }else{
     formatoExcedente =  number_format(item.resto, 2, ',', '.')
      excedenteFavor= '<strong>SALDO RESTANTE:</strong> $ '+formatoExcedente;
    }
     if(item.monto_consumo_actual==null){
     consumoActual = 0;
     
    }else{

      consumoActual = item.monto_consumo_actual; 
     
    }
      if (item.porcentaje < 100){
        
      }

      if (item.renovar==1){
          renovar = '<a id="renovarLimite-'+item.id_limite_gasto+'" data-id="'+item.id_limite_gasto+'" class="btn btn-success btn-circle" data-toggle="tooltip" title="Renovar Limite"><i class="fas fa-check"></i></a>';
      }

    html+=`  <div class="col-md-6"  >
                <div class="card shadow mb-2">
                 <div class="card-header py-3">
  <label class=" card-title text-primary mr-5">
    `+item.nombreCategoria.toUpperCase()+`
  </label>
                <span class="porcentaje-header">
                  <div class="progress mb-4">
                    <div class="progress-bar bg-`+color+`" role="progressbar" style="width: `+item.porcentaje+`%"
                      aria-valuenow="`+item.porcentaje+`" aria-valuemin="0" aria-valuemax="100">
                    </div>
                  </div>
                  <label class="float-right">`+item.porcentaje+`%</label>
                </span>
  <div class="box-tools pull-right" style="position: absolute; top: 10px; right: 10px;">
    <button type="button" class="btn btn-box-tool" data-card-widget="collapse" title="Collapse">
      <i class="fas fa-plus"></i>
    </button>
  </div>
</div>

                  <div class="card-body collapse" >
                    <p> <strong> LUGAR:</strong> `+item.nombreLugar.toUpperCase()+`</p>
                    <p> <strong> DESDE:</strong> `+item.desde+` <strong> HASTA:</strong> `+item.hasta +`</p>
                    <p><strong>LIMITE: </strong>$ `+number_format(item.monto_limite, 2, ',', '.')+`</p>
                    <p><strong>CONSUMO ACTUAL:</strong> $ `+number_format(consumoActual, 2, ',', '.')+`</p>
                    <p>`+excedenteFavor+`</p> 
                    <span class="float-right">`+item.porcentaje+`%</span>
                  <div class="progress mb-4">
                    <div class="progress-bar bg-`+color+`" role="progressbar" style="width: `+item.porcentaje+`%"
                      aria-valuenow="`+item.porcentaje+`" aria-valuemin="0" aria-valuemax="100">
                    </div>
                  </div>
              <div class="text-center">
                <a id="deleteLimite-`+item.id_limite_gasto+`" data-id="`+item.id_limite_gasto+`"  class="btn btn-danger btn-circle" data-toggle="tooltip" title="Eliminar"><i class="fas fa-trash"></i></a>
                <a id="editLimite-`+item.id_limite_gasto+`" data-id="`+item.id_limite_gasto+`" class="btn btn-warning btn-circle" data-toggle="tooltip" title="Editar Limite"><i class="fas fa-pencil-alt"></i></a>
                `+renovar+`
                  </div>
                  </div>
                </div>
              </div>`;
  $('#limitesDeConsumos').html(html);

 
  })
  $('#limitesGastos .card-body').html(html);
    }
   }); 
}


 // Aseguramos que las tarjetas estén colapsadas al cargar la página
    $('#limitesDeConsumos .card-body').addClass('collapse'); // Aseguramos que las tarjetas estén colapsadas desde el principio

    // Al hacer clic en el botón de colapso
    
        $(document).on("click","[data-card-widget='collapse']",function(){
       var card = $(this).closest('.card'); // Encuentra la tarjeta más cercana
        var body = card.find('.card-body'); // Encuentra el cuerpo de la tarjeta
        var icon = $(this).find('i'); // Encuentra el ícono dentro del botón
        var porcentajeHeader = card.find('.porcentaje-header'); // Encuentra el span con la clase 'porcentaje-header'

        // Colapsa o descolapsa el cuerpo de la tarjeta
        body.toggleClass('collapse'); // Alterna la clase 'collapse'
        
        // Cambia el ícono entre 'fa-plus' y 'fa-minus'
        if (body.hasClass('collapse')) {
            icon.removeClass('fa-minus').addClass('fa-plus'); // Colapsa: cambia a 'fa-plus'
            porcentajeHeader.show(); // Oculta el porcentaje cuando la tarjeta está colapsada
      
        } else {
            icon.removeClass('fa-plus').addClass('fa-minus'); // Expande: cambia a 'fa-minus'
            porcentajeHeader.hide(); // Muestra el porcentaje cuando la tarjeta está expandida
       
        }
  
    });


})
</script>