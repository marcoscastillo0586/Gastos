<form>
    <div class="form-row mx-5" style="justify-content: center;">
        <!-- Fecha -->
        <div class="form-group col-md-3">
            <label for="from">Fecha</label>
            <input type="text" id="from" name="from" class="form-control text-uppercase" placeholder="Seleccione la fecha">
        </div>

        <!-- Concepto -->
        <div class="form-group col-md-3">
            <label for="concepto">Concepto</label>
            <input type="text" class="form-control text-uppercase" id="concepto" placeholder="Ingrese el concepto">
        </div>

        <!-- Monto a Ingresar -->
        <div class="form-group col-md-6">
            <label for="montoIngresar">Monto a Ingresar</label>
            <input type="number" class="form-control" id="montoIngresar" placeholder="Utilice punto solo para los decimales" aria-describedby="montoHelp">
            <small id="montoHelp" class="form-text text-muted">Ejemplo: 100.50</small>
        </div>
    </div>
    <div class="float-right mr-5">
            <button style="position: relative;" type="button" class="btn btn-outline-success" id="btnGuardarIngreso">Guardar Ingreso</button>
        </div>
    <br>
    <h5>Lugar de ingreso</h5>
    <br>

    <!-- Lugares de ingreso -->
    <div class="row row-cols-xl-6 row-cols-md-6 divLugares">
        <?php echo($lugares) ?>
    </div>

    <br>
  
</form>

<!-- Modal -->
<div class="modal fade" id="modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
</div>
</div><!-- div que cierra el main del body menu-->