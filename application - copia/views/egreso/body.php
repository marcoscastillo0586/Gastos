<div id="principal">
  <div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
      <li class="active li-tab"><a href="#tab_1" data-toggle="tab">Carga Manual</a></li>
      <li class="li-tab"><a href="#tab_2" data-toggle="tab">Carga Automatica</a></li>
    </ul>
    <div class="tab-content">
      <div class="tab-pane active" id="tab_1">
        <div class="form-group col-md-11">
          <label for="concepto">Concepto General del Gasto <span class="text-danger">*</span></label>
          <input type="text" class="form-control text-uppercase" id="conceptoGeneral" placeholder="">
        </div>
        <form id="formPrincipal">
          <div class="form-row md-6" style="justify-content: center;">
            <div class="form-group col-md-2">
              <label for="concepto">Fecha <span class="text-danger">*</span></label>
              <input type="text" id="from" name="from" class="form-control text-uppercase">
            </div>
            <div class="form-group col-md-4">
              <label for="concepto">Concepto <span class="text-danger">*</span></label>
              <input type="text" list="listaConceptos_0" class="form-control text-uppercase concepto" id="concepto_0" placeholder="">
              <datalist id="listaConceptos_0"></datalist>
            </div>
            <div class="form-group col-md-2">
              <label>Monto<span class="text-danger">*</span></label>
              <input type="number" class="form-control press montoIngresar" id="montoIngresar_0" placeholder="">
            </div>
            <div class="form-group col-md-3">
              <label>Categoría<span class="text-danger">*</span></label>
              <select class="form-control text-uppercase press cmbcategoria" name="categoria" id="cmbcategoria_0"></select>
            </div>
          </div>
        </form>
        <div class="col-md-12" style="display: flex; align-items: le; justify-content: flex-start; left: 21%;">
          <span id="SumSubTotal"></span>
        </div>
        <div class="col-md-12" style="display: flex; align-items: center; justify-content: center;">
          <button type="button" class="btn btn-outline-primary agregarRenglon" disabled>Agregar Renglón</button>
        </div>
        <div class="float-right mr-5">
          <button type="button" class="btn btn-outline-success" id="btnRegistrarGasto">Registrar Gasto</button>
        </div>
        <form>
          <h4 class="pl-5 text-center">Seleccione un lugar para realizar el débito</h4>
          <div class="row row-cols-1 row-cols-md-3 divLugares">
            <?php echo($lugares) ?>
          </div>
          <div style="position: relative; width: 100%; bottom: 1px;"></div>
        </form>
      </div>
      <!-- /.tab2 -->
      <div class="tab-pane fade" id="tab_2">
    <div>
        <div style="margin-bottom: 35px;" class="form-group col-md-11">
            <label for="concepto">Escanee el código QR para la carga automática</label>
        </div>
        <!-- Contenedor para la cámara -->
        <div id="reader" style="width: 80%; max-width: 350px; height: 200px; display: flex; justify-content: center; align-items: center; margin: auto;"></div>
     
    </div>
</div>

    </div>
    <!-- /.tab-content -->
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true"></div>
</div><!-- div que cierra el main del body menu-->

<script>
  function noPunto(event) {
    var e = event || window.event;
    var key = e.keyCode || e.which;

    if (key === 110 || key === 190) {
      e.preventDefault();
    }
  }
</script>
