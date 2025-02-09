  <!-- Jquery STEP-->
   <link rel="stylesheet" href="<?=base_url()?>/assets/vendor/jquery-ui/jquery-ui.css">
    <!-- SELECT 2-->
    <script src="<?=base_url()?>assets/vendor/select2/dist/js/select2.js" ></script>
<script src="<?=base_url()?>/assets/vendor/jquery-ui/jquery-ui.js"></script>
    <style>   .card:hover{background-color: #3EA3DE;color: #fff;}   .imgCard{background-color: #fff;margin:5px;padding:5px;}   .imgCard-selected{border: 6px solid #099a9f;margin:5px;padding:5px;}    </style>
<script>
$(document).ready(function(){
 
$('#cmbcategoria').select2({maximumSelectionLength: 5,width: "300px"});
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
          defaultDate: "+1w",
          changeMonth: true,
          //numberOfMonths: 1
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

  //cargarCategoria();

  $(document).on("click",".imgCard",function(){
    if ( $(this).hasClass( "imgCard-selected" ) ) {
      /*en el caso que exista ya una imagen seleccionada la eliminamos para que únicamente solo se tenga una imagen seleccionada*/
      $(this).removeClass("imgCard-selected");
    }else{
    $(this).addClass("imgCard-selected");
    }

  });

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
    url = '<?php echo(base_url());?>TopeGasto/darCategoriasJS';
    $.post(url).done(function(resp){
      var res= JSON.parse(resp); 
        $('#cmbcategoria').html(res);
    });
  }

            /////BTN DE REGISTRAR GASTO //////
  $(document).on("click","#btnRegistrarLimite",function(){
    if( $(".imgCard").hasClass("imgCard-selected") && $("#from").val()!=='' && $("#to").val()!=='' && $('#cmbcategoria :selected').text()!=='') 
    {
      var desde = $("#from").val();
          hasta = $("#to").val();
          monto = $('#montoIngresar').val();
          nombreCategoria = '';
          objLugar={};
          arrayLugar=[];
          nomLugar = '';
          //recorro los elementos que tengan la clase imgCard-selected, obtengo el nombre lo guardo en un objeto y despues en un array para que quede almacenado
          $(".imgCard-selected").each(function(){
            var $thisLugar    = $(this);
                nombreLugar   = $thisLugar.attr('data-nombreLugar');
                objLugar      = {lugar:nombreLugar};  
                arrayLugar.push(objLugar);
          });
              //recorro el array con el objeto y los concateno en un string para mostrar 
       for (var i = 0; i < arrayLugar.length; i++){
                 nomLugar+= arrayLugar[i].lugar+', ';
      
                };
                // quito los dos ultimos elementos del array esto quita la, y el espacio al final del stgring 
                 nombreLugar = nomLugar.substring(0, nomLugar.length -2);

            var cmbCategoria = $('#cmbcategoria').select2('data')
       for (var i = 0; i < cmbCategoria.length; i++){
                 nombreCategoria+= cmbCategoria[i].text+', ';
        };
          nombreCategoria = nombreCategoria.substring(0, nombreCategoria.length -2);
         
                $('div .modal').html(`
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header bg-info text-white text-center p-2">
                        <p class="modal-title text-center " id="staticBackdropLabel">Datos a Debitar</p>
                      </div>
                      <div class="modal-body">
                      <br>
                
                      <div class="form-group">
                              <label class="text-primary" for="imgLugarNuevo">Desde: `+desde+`</label>
                      </div>
                      <div class="form-group">
                              <label class="text-primary" for="imgLugarNuevo">Hasta: `+hasta+`</label>
                      </div>
                      <div class="form-group ">  
                          <label class="text-primary" for="imgLugarNuevo">Limite De Gasto: $`+monto+`</label>
                      </div>
                      <div class="form-group">
                              <label class="text-primary" for="imgLugarNuevo">Categoria/s: `+nombreCategoria+`</label>
                      </div>
                      <div class="form-group">
                              <label class="text-primary" for="imgLugarNuevo">Lugar/es: `+nombreLugar+`</label>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary btncancelar" data-dismiss="modal">Cancelar</button>
                      
                      <button id="btnConfirmarGasto" type="button" class="btn btn-primary">Confirmar Gasto</button>
                    </div>
                  </div>
                </div>`);
                $('#modal').modal("show");
             
          
            
    }else{

          if ( $("#from").val()=='' || $("#to").val()=='' || $('#cmbcategoria :selected').text()==''){
            var mensaje = 'Debe completar Todos Los campos.';
          }
          else{
           var mensaje = 'Debe Seleccionar al menos un lugar donde aplicar el limite de gastos.'; 
          }

                $('div .modal').html(`    
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header bg-info text-white text-center p-2">
                    <p class="modal-title text-center " id="staticBackdropLabel">Faltan Datos </p>
                  </div>  
          
                  <div class="modal-body"><br>
                    <div class="form-group">
                            <label class="text-primary" >`+mensaje+` </label>
                    </div>
                  </div>
          
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btnFinalizarRegistro" data-dismiss="modal">Cerrar</button>
                  </div>
              </div>
            </div>`);
             $('#modal').modal("show");
    }
  });


    /////BTN DE CONFIRMAR GASTO //////
  $(document).on("click","#btnConfirmarGasto",function(){
         

      var desde = $("#from").val();
          hasta = $("#to").val();
          monto = $('#montoIngresar').val();
          categoria = $('#cmbcategoria').val();
          objLugar={};
          arrayLugar=[];
          nomLugar = '';
          idCat = '';


    //recorro los elementos que tengan la clase imgCard-selected, obtengo el nombre lo guardo en un objeto y despues en un array para que quede almacenado
          $(".imgCard-selected").each(function(){
            var $thisLugar    = $(this);
                idLugar   = $thisLugar.attr('data-imglugar');
                objLugar      = {lugar:idLugar};  
                arrayLugar.push(objLugar);
          });
              //recorro el array con el objeto y los concateno en un string para mostrar 
       for (var i = 0; i < arrayLugar.length; i++){
                 nomLugar+= arrayLugar[i].lugar+',';
      
                };
                // quito los dos ultimos elementos del array esto quita la, y el espacio al final del string 
                 idLugar = nomLugar.substring(0, nomLugar.length -1);

                 //recorro el array con el objeto y los concateno en un string para mostrar 
       for (var i = 0; i < categoria.length; i++){
                 idCat+= categoria[i]+',';
      
                };
                // quito los dos ultimos elementos del array esto quita la, y el espacio al final del string 
                 idCat = idCat.substring(0, idCat.length -1);


          datos = {desde:desde,hasta:hasta,monto:monto,categoria:idCat,lugar:idLugar};
          url = '<?php echo(base_url());?>TopeGasto/guardarLimiteGasto';
  
      $.post(url,datos).done(function(resp){
        if (resp==1){
            $('div .modal').html(`    
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header bg-info text-white text-center p-2">
                    <p class="modal-title text-center " id="staticBackdropLabel">Datos Ingresados</p>
                  </div>  
          
                  <div class="modal-body"><br>
                    <div class="form-group">
                            <label class="text-primary" >El Limite de Gastos Se Registro De Manera Exitosa.</label>
                    </div>
                  </div>
          
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btnFinalizarRegistro" data-dismiss="modal">Cerrar</button>
                  </div>
              </div>
            </div>`);
             $('#modal').modal("show");
             $('#montoIngresar').val('');
             $('#to').val('');
             $('#from').val('');
             $('#concepto').val('');
             $('#cmbcategoria').empty();
             $( ".imgCard-selected" ).removeClass('imgCard-selected');
          }else{alert('Algo salio mal');    }
        });
  })


$(document).on("change","#from,#to ",function(){
  
  if ($("#from").val()!=='' && $("#to").val()!==''){
    var desde = $("#from").val();
        hasta =$("#to").val();
        datos = {desde:desde,hasta:hasta}
        url   = '<?php echo(base_url());?>TopeGasto/verificarCatLim';
          $.post(url,datos).done(function(resp){
      var res= JSON.parse(resp); 
        $('#cmbcategoria').html(res);
    });
  }
})



});
</script>