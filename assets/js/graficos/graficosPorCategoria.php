    Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
    Chart.defaults.global.defaultFontColor = '#858796';
    function graficoConsumo(){
      var ctx = document.getElementById("myPieChart");
          url = '<?php echo(base_url());?>Login/darGraficoCategoria';

      $.post(url).done(function(resp){
       var res= JSON.parse(resp); 
       const labelsCategorias = res.categorias.map(function(e){return e;});
       const labelsMonto = res.monto.map(function(e){return e;});
       const labelColor = res.color.map(function(e){return e;});
       const labelColorHover = res.colorHover.map(function(e){return e;});
      if (res.categorias.length>0){ 

          var myPieChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
              labels: labelsCategorias,
              datasets: [{
                data: labelsMonto,
                backgroundColor:labelColor ,
                hoverBackgroundColor: labelColorHover,
              }],
            },
            options: {
              maintainAspectRatio: false,
              tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                caretPadding: 10,
              },
              legend: {
                display: true
              },
              cutoutPercentage: 80,
            },
          });
        }
        else{     
            $("#consumoCategorias").append('No hay datos para mostrar')
        }
      });

    }
  