<div id="principal">
  <form id="formPrincipal">
    <div class="form-row ml-1 mr-1 md-3"  style="justify-content: space-around;">
      <div class="form-group col-md-2">
        <label for="montoIngresar">Desde </label>
            <input class="form-control" type="text" id="from" name="from">
      </div>
    
      <div class="form-group col-md-2">
        <label for="concepto">Hasta </label>
    <input class="form-control" type="text" id="to" name="to">
      </div>
     
      <div class="form-group col-md-2">
        <label> Limite </label>
        <input type="number" class="form-control press montoIngresar" id="montoIngresar" placeholder="">
      </div>
      
      <div class="form-group col-md-2">
        <label> Categoria  </label>
      <select class="form-control text-uppercase press cmbcategoria" name="categoria[]" multiple="multiple" id="cmbcategoria">
      </select> 
      </div>
    </div>

  </form>
</div>
<form>
  <div class="float-right mr-5">
    <button type="button" class="btn btn-outline-success" id="btnRegistrarLimite"> Registrar Limite </button>
  </div>
<br>
<h4>  ¿ En que lugar/es desea aplicar el Limite de gasto ? </h4>
<br>
  <div class="row row-cols-1 row-cols-md-3 divLugares" >
    <?php echo($lugares)?>
  </div> 
  <br> 
</form>




<!-- Modal --> 
<div class="modal fade" id="modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">



</div>


</div><!-- div que cierra el main del body menu-->
<script>
function noPunto(event){
  
    var e = event || window.event;
    var key = e.keyCode || e.which;

    if ( key === 110 || key === 190) {     
        
       e.preventDefault();     
    }
}
</script>