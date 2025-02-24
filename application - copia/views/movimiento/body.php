<form>
  <div class="form-row ml-5 mr-5" style="justify-content: center;">
    <div class="form-group col-md-4">
      <label for="montoIngresar">Desde </label>
      <input type="text" id="from" name="from">
    </div>
    <div class="form-group col-md-3">
      <label for="concepto">Hasta</label>
      <input type="text" id="to" name="to">
    </div>
    <div class="form-group col-md-3" style="display: flex;align-items: center;justify-content: center;">
      <button type="button" class="btn btn-outline-success" id="btnBuscar" disabled>Buscar</button>
    </div>

    <div class="form-group col-md-12 col-xl-6">
      <div class="row   row-cols-md-12" >
      <label  class="mr-2"> Filtrar por Categoria:   </label>
       <select class="form-control text-uppercase press cmbcategoria" name="categoria[]" multiple="multiple" id="cmbcategoria"></select>
      </div>
      

    </div>

<div class="form-group col-md-12 col-xl-6">
<div class="row   row-cols-md-12 row-cols-xl-12">
      <label style="margin-right: 5%;"> Filtrar por Lugar:  </label>
             <select class="form-control text-uppercase press cmblugar" name="lugar[]" multiple="multiple" id="cmblugar"></select>
      </div>
      </div>
  </div>
  <div class="container-fluid">
    <div class="mb-3">
  <input type="text" id="buscador" class="form-control" placeholder="Buscar por Concepto o Monto">
</div>
  <table class="table  table-bordered">
    <thead class="text-center bg-warning text-white">
      <tr>

        <th data-name="fecha" scope="col">Fecha</th>
        <th data-name="lugar" scope="col">Lugar/Categoria</th>
        <th data-name="concepto" scope="col">Concepto</th>
        <th data-name="monto" scope="col">Monto</th>
      </tr>
    </thead>
    <tbody class="text-dark">
    </tbody>
  </table>
  </div>
</form>
