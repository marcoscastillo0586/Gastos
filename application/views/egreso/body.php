<div id="principal">
  <div class="form-row md-6" style="justify-content: center;">
      <div class="form-group col-md-2">
      </div>
      <div class="form-group col-md-11">
        <label for="concepto"> Concepto General del Gasto <span class="text-danger">*</span> </label>
        <input type="text" class="form-control text-uppercase" id="conceptoGeneral" placeholder="">
      </div>   
      <div class="form-group col-md-2">
      </div>
      <div class="form-group col-md-3">
      </div>
    </div>

  <form id="formPrincipal">
   
    <div class="form-row md-6" style="justify-content: center;">
      <div class="form-group col-md-2">
        <label for="concepto"> Fecha  <span class="text-danger">*</span></label>
            <input type="text" id="from" name="from" class="form-control text-uppercase">
      </div>
      
      <div class="form-group col-md-4">
        <label for="concepto"> Concepto Individual <span class="text-danger">*</span> </label>
        <input type="text" list="listaConceptos_0" class="form-control text-uppercase concepto" id="concepto_0" placeholder="">
        <datalist id="listaConceptos_0">
        </datalist>
      </div>   
      
     
      <div class="form-group col-md-2">
        <label> Monto<span class="text-danger">*</span></label>
        <input type="number" class="form-control press montoIngresar" id="montoIngresar_0" placeholder="">
      </div>
      
      <div class="form-group col-md-3">
        <label> Categoria<span class="text-danger">*</span> </label>
          <a href="#" class="badge badge-success agregarCategoria pt-1" style="height: 20px;"> Nueva Categor&iacute;a</a>
      <select class="form-control text-uppercase press cmbcategoria" name="categoria" id="cmbcategoria_0"></select>
      </div>
    </div>

  </form>
</div>
<div class="col-md-12" style="display: flex;align-items: le;justify-content: flex-start;left: 21%;">
  <span id="SumSubTotal"></span>

</div>
<div class="col-md-12" style="display: flex;align-items: center;justify-content: center;">
  <button type="button" class="btn btn-outline-primary  agregarRenglon" disabled > Agregar Rengl&oacute;n </button>
</div>
<form>
  <div class="float-right mr-5">
    <button type="button" class="btn btn-outline-success" id="btnRegistrarGasto"> Registrar Gasto </button>
  </div>

<br>
<h4 class="pl-5 text-left">  Seleccione un lugar para realizar el d&eacute;bito </h4>
<br>
  <div class="row row-cols-1 row-cols-md-3 divLugares" >
    <?php echo($lugares)?>
  </div>

  <br> 
  <div  style="position: relative;width: 100%;bottom: 1px;"> 
  </div>
</form>


<!-- Modal --> 
<div class="modal" id="modal" data-backdrop="static" data-keyboard="false" tabindex="-1"  aria-hidden="true">



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

   

