<h5>Registro de Transferencias</h5>
<hr/>
<form class="row g-3 p-5">
  <div class="col-md-6">
    <label data-toggle="tooltip" data-placement="top" title="Lugar de donde se retirara el dinero" class="form-label">Origen</label>
    <select class="form-control cursor-pointer"  id="cmbOrigen">
      <?php echo $lugarOrigen ?>
      <!-- <option style="background-image:url(male.png);">male</option> -->
    </select>

  </div>
  <div class="col-md-6">
    <label data-toggle="tooltip" data-placement="top" title="Lugar donde se depositara el dinero" class="form-label">Destino</label>
    <select class="form-control"  id="cmbDestino" disabled>
    </select>
  </div>
<br>
<br>
<br>
<br>
<br>
  <div class="col-md-6">
    <label data-toggle="tooltip" data-placement="top" title="Cantidad a transferir" class="form-label">Monto a Transferir</label>
    <input type="number" class="form-control" id="montotransferir" disabled>
  </div>

  <div  style="position: relative;width: 100%;bottom: 1px;"> 
    <div class="float-right mr-5">
      <button type="button" class="btn btn-outline-success" id="btnRealizarTransferencia" disabled>Realizar Transferencia</button>
    </div>
  </div>
</form>

<!-- Modal --> 
<div class="modal fade" id="modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">

</div>


</div><!-- div que cierra el main del body menu-->