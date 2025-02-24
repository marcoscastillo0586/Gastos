    <style>
      #consumoCategoria {
    width: 100%;  /* O un valor fijo, por ejemplo, 600px */
    height:525px !important;  /* Ajusta la altura según sea necesario */
 
}


    </style>
    <!-- Page level plugins -->
    <script src="<?=base_url()?>assets/vendor/chart.js/Chart.min.js"></script>
    <script src="<?=base_url()?>assets/vendor/chart.js/Chart.js"></script>
    <!-- Page level custom scripts -->
    <link rel="stylesheet" href="<?=base_url()?>/assets/vendor/jquery-ui/jquery-ui.css">
    <script src="<?=base_url()?>/assets/vendor/jquery-ui/jquery-ui.js"></script>
    <script src="<?=base_url()?>assets/vendor/select2/dist/js/select2.js" ></script>
    <script>
$(document).ready(function(){
  $('.customCheckSite').click();
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
  $('#cmbcategoria').select2({maximumSelectionLength: 250,width: "90%"}).val('');
  $('#cmbcategoria').trigger('change');

  $('#cmbconcepto').select2({maximumSelectionLength: 250,width: "90%"}).val('');
  $('#cmbconcepto').trigger('change');

  cargarCategoria();
  cargarConcepto();
//Carga las categorias en el combo
  function cargarCategoria(){
    url = '<?php echo(base_url());?>graficos/ConsumoPorCategoria/darCategoriasJS';
    $.post(url).done(function(resp){
      var res = JSON.parse(resp); 
        $('#cmbcategoria').html(res);
        
    });
}   
//Carga los conceptos en el combo
function cargarConcepto(){
   let desde = $.datepicker.formatDate('yy-mm-dd', $( "#from" ).datepicker("getDate"));
   let hasta = $.datepicker.formatDate('yy-mm-dd', $( "#to" ).datepicker("getDate"));
       datos = { desde:desde,hasta:hasta };
    url = '<?php echo(base_url());?>graficos/ConsumoPorCategoria/darConceptoJS';
    $.post(url,datos).done(function(resp){
      var res = JSON.parse(resp); 
        $('#cmbconcepto').html(res);
        
    });
}  
 $(".chkcustom").click();

  var ctx = document.getElementById("myPieChart");
  //graficoConsumo('mesActual');
  buscarDatos('mesActual');
 
  $(document).on("click","#btnBuscar",function(){ buscarDatos(); });
  $(document).on("click",".filtroCatCon",function(){ 

    if ($('#filtroConcepto:checked').prop('checked')){

    
      $('#divConcepto').removeAttr("hidden");
      $('#divCategoria').attr("hidden",true);
      $("#cmbcategoria").empty().trigger('change') 
      cargarConcepto();      
    }else{ 

      $('#divCategoria').removeAttr("hidden");
      $('#divConcepto').attr("hidden",true);
      $("#cmbconcepto").empty().trigger('change'); 
       cargarCategoria();  
    }
   });
  
  $(document).on("click",".chkcustom",function(){buscarDatos(); });
  $(document).on("change","#cmbcategoria",function(){buscarDatos();});
  $(document).on("change","#cmbconcepto",function(){buscarDatos();});
  
  function buscarDatos(){
    if (window.grafica){window.grafica.clear();window.grafica.destroy();}
      let desde = $.datepicker.formatDate('yy-mm-dd', $( "#from" ).datepicker("getDate"));
      let hasta = $.datepicker.formatDate('yy-mm-dd', $( "#to" ).datepicker("getDate"));
      let lugar = []; 
      let datos='';
      let url='';
      $(".chkcustom").each(function(){
        if($(this).is(':checked')){
          lugar.push($(this).data('idlugar'));
        }
      });
      var categoria = $('#cmbcategoria').val();
      var concepto =  $('#cmbconcepto').val();


      if ($.isArray(categoria) && categoria.length != 0) {
   
                idCat = '';
                for (var i = 0; i < categoria.length; i++){
                  idCat+= categoria[i]+',';
                };
                idCat = idCat.substring(0, idCat.length -1);
                console.log(idCat);
                 datos = { idCat:idCat,desde:desde,hasta:hasta,lugar:lugar };
                 url   = '<?php echo(base_url());?>graficos/consumoPorCategoria/darGraficoSubCategoria';
        } else {
              
       datos = { desde:desde, hasta:hasta, lugar:lugar };
       url   = '<?php echo(base_url());?>graficos/consumoPorCategoria/darGraficoCategoria';
           }
             if (concepto.length != 0) {
            idCon = '';
                for (var i = 0; i < concepto.length; i++){
                  idCon+= concepto[i]+',';
                };
                idCon = idCon.substring(0, idCon.length -1);
                 datos = { idCon:idCon,desde:desde,hasta:hasta,lugar:lugar };
                 url   = '<?php echo(base_url());?>graficos/consumoPorCategoria/darGraficoConcepto';
          }

            

      $.post(url,datos).done(function(resp){
        let res= JSON.parse(resp); 
        const labelsCategorias = res.categorias.map(function(e){return e;});
        const labelsMonto = res.monto.map(function(e){return e;});
        const labelColor = res.color.map(function(e){return e;});
        const labelColorHover = res.colorHover.map(function(e){return e;});
        if (res.categorias.length>0){ 
          $('#totalconsumo').html('<strong> TOTAL DE CONSUMO:  '+res.totalConsumo+'</strong');
          function handleHover(evt, item, legend) {
            legend.chart.data.datasets[0].backgroundColor.forEach((color, index, colors) => {
              colors[index] = index === item.index || color.length === 9 ? color : color + '4D';
            });
            legend.chart.update();
          };
          function handleLeave(evt, item, legend) {
            legend.chart.data.datasets[0].backgroundColor.forEach((color, index, colors) => {
              colors[index] = color.length === 9 ? color.slice(0, -2) : color;
            });
            legend.chart.update();
          };
          window.grafica = new Chart(ctx, {
            type: 'doughnut',
            data: {
                 labels: labelsCategorias,
                 datasets: [{
                             data: labelsMonto,
                             backgroundColor:labelColor ,
                             hoverBackgroundColor: labelColorHover,
                 }],
            },
            options:{
                    responsive: true,
                    maintainAspectRatio: false,
                    tooltips: {
                                backgroundColor: "rgb(255,255,225)",
                                bodyFontColor: "#000",
                                borderColor: '#000',
                                borderWidth: 1,
                                xPadding: 20,
                                yPadding: 20,
                                displayColors: true,
                                caretPadding: 10,
                                bodyfontSize:16,
                    },
                    plugins:{
                      legend: {
                          labels: {
                                  font: {
                                    size: 12, 
                                  }
                          },
                          onClick(e, legendItem, legend){
                          legend.chart.toggleDataVisibility(legendItem.index);
                          legend.chart.update();
                          let arrayCantidad = this.chart.data.datasets[0].data;
                          let arrayDataSet = this.chart.boxes[0].legendItems;
                          let sumaTotal = 0;
                          $.each(arrayCantidad, function(i, item){
                            if(arrayDataSet[i].hidden == false){
                              sumaTotal = sumaTotal+parseInt(item)
                            }
                          })
                          sumaFormato =  number_format(sumaTotal, 2, ',', '.');
                          $('#totalconsumo').html('<strong> TOTAL DE CONSUMO: $ '+sumaFormato+'</strong')
                          },
                        display: true,
                        position:'left',
                        align:'start',
                        onHover: handleHover,
                        onLeave: handleLeave,
                      }   
                    },
                   cutout: '15%', 
                  },
          });  
        }
        else{     
             window.grafica = new Chart(ctx, {
            type: 'doughnut',
            data: {
              labels: ['Sin Datos'],
              datasets: [{
                data: 100,
                backgroundColor:'#000',
                hoverBackgroundColor: '#000',
              }],
            },
    
            options: {
              maintainAspectRatio: false,
              tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: '#dddfeb',
                borderWidth: 0,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                caretPadding: 10,
              },
              legend: {display: true},
              cutoutPercentage: 50,

            },
          });
        }
      });
  }

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
              changeYear: true,
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
        changeYear: true,
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


})
</script>