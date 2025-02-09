    <!-- Page level plugins -->
    <script src="<?=base_url()?>assets/vendor/chart.js/Chart.min.js"></script>
    <script src="<?=base_url()?>assets/vendor/chart.js/Chart.js"></script>

    <!-- Page level custom scripts -->
    <script src="<?=base_url()?>assets/vendor/jquery/jquery.min.js"></script>
<script>
$(document).ready(function(){
 
   gastoMensual();
     
    function gastoMensual(){
      if (window.grafica) {
          window.grafica.clear();
           window.grafica.destroy();
      }
      url = '<?php echo(base_url());?>graficos/ComparativaMensual/darGraficoMensual';
      $.post(url).done(function(resp){
      var res= JSON.parse(resp); 


var Colorbackground = ['rgba(255, 99, 132, 0.5)','rgba(255, 159, 64, 0.5)','rgba(255, 205, 86, 0.5)','rgba(75, 192, 192, 0.5)','rgba(54, 162, 235, 0.5)','rgba(153, 102, 255, 0.5)','rgba(201, 203, 207, 0.5)','rgba(255, 99, 132, 0.5)','rgba(255, 159, 64, 0.5)','rgba(255, 205, 86, 0.5)','rgba(75, 192, 192, 0.5)','rgba(54, 162, 235, 0.5)','rgba(153, 102, 255, 0.5)','rgba(201, 203, 207, 0.5)']
var Colorborder = ['rgb(255, 99, 132)','rgb(255, 159, 64)','rgb(255, 205, 86)','rgb(75, 192, 192)','rgb(54, 162, 235)','rgb(153, 102, 255)','rgb(201, 203, 207)','rgb(255, 99, 132)','rgb(255, 159, 64)','rgb(255, 205, 86)','rgb(75, 192, 192)','rgb(54, 162, 235)','rgb(153, 102, 255)','rgb(201, 203, 207)']
const labels = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','diciembre',];
const data = {
  labels: labels,
  datasets: []
};
const config = {
  type: 'bar',
  data: data,
  options: {
      responsive: true,
    scales: {
      y: {
        beginAtZero: true
      }
    }
  },
};
  const gastoMensual = new Chart(
    document.getElementById('graficoMensual'),
    config
  );
   
   res.forEach( function(valor, indice, array) {
    gastoMensual.data.datasets.push({
    label: valor.categoria,
    data: [valor.Enero,valor.Febrero,valor.Marzo,valor.Abril,valor.Mayo,valor.Junio,valor.Julio,valor.Agosto,valor.Septiembre,valor.Octubre,valor.Noviembre,valor.Diciembre],
    backgroundColor: [Colorbackground[indice]],
    borderColor: [Colorborder[indice]],
    borderWidth: 1
  });


   
      gastoMensual.update(); 
 });





      });

    }


})
</script>