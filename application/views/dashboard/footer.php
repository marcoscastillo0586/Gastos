    <!-- Page level plugins -->
    <script src="<?=base_url()?>assets/vendor/chart.js/Chart.min.js"></script>
    <script src="<?=base_url()?>assets/vendor/chart.js/Chart.js"></script>
    <!-- Page level custom scripts -->
    <script src="<?=base_url()?>assets/vendor/jquery/jquery.min.js"></script>
<script>
$(document).ready(function(){
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


/*
 $(document).on("click",".consumoCategoria",function(){
          var id = $(this).data('id');
          graficoConsumo(id);

 })*/

      var ctx = document.getElementById("myPieChart");
      var ctx2 = document.getElementById("myPieChart2");


function graficoLimiteGastos(){
  url = '<?php echo(base_url());?>Login/darGraficoLimitesGasto';
  $.post(url).done(function(resp){
   var res = JSON.parse(resp); 
       html='';
       color='';
     
    if(res!==0){
    
  $.each(res, function(i, item){
       excedenteFavor = '';
       valor = 0;
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

   html+=`  <div class="col-md-6 mb-4" ">
                <div class="card shadow mb-4">
                  <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary text-center">`+item.nombreCategoria.toUpperCase()+`</h6>
                  </div>
                  <div class="card-body">
                    <p> <strong> LUGAR:</strong> `+item.nombreLugar.toUpperCase()+`</p>
                    <p><strong>LIMITE: </strong>$ `+number_format(item.monto_limite, 2, ',', '.')+`</p>
                    <p><strong>CONSUMO ACTUAL:</strong> $ `+number_format(consumoActual, 2, ',', '.')+`</p>
                    <p>`+excedenteFavor+`</p> 
                    <span class="float-right">`+item.porcentaje+`%</span>
                  <div class="progress mb-4">
              <div class="progress-bar bg-`+color+`" role="progressbar" style="width: `+item.porcentaje+`%"
                aria-valuenow="`+item.porcentaje+`" aria-valuemin="0" aria-valuemax="100">
              </div>
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

})
</script>