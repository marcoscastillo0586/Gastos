<form>
    <div class="form-row ml-5 mr-5" style="justify-content: center;">
        <div class="form-group col-md-12">
        <div class="d-sm-flex align-items-center position-relative">
            <h4 class="h4 text-gray-800" style="margin: 4px;">Seleccionar</h4>
            <!-- CHECK -->
            <div class="custom-control custom-checkbox position-relative" style="margin-left: 12px;">
                <input type="checkbox" class="custom-control-input customCheckSite" id="customCheckSite">
                <label class="custom-control-label" for="customCheckSite"></label>
            </div>
        </div>
      
            <!-- Lugares -->
            <div class="row">
                <?php foreach ($Lugares as $key => $value): ?>
                    <div class="col-md-2 col-sm-2 d-flex justify-content-center mb-1">
                        <div class="card imgCard border-left-primary shadow-sm"style="width: 7.5rem; border-radius: 6px;">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            <?php echo $value->nombre; ?>
                                        </div>
                                        <div class="custom-control custom-checkbox small" style="top: -27px;left: 100%;">
                                            <input type="checkbox" class="custom-control-input chkcustom" data-idlugar="<?php echo $value->id_lugar; ?>" id="customCheck-<?php echo $value->id_lugar; ?>">
                                            <label class="custom-control-label" for="customCheck-<?php echo $value->id_lugar; ?>"></label>
                                        </div>
                                    </div>
                                    <div style="width: 15px;"></div>
                                    <div class="col-auto">
                                   <img src="<?php echo base_url(htmlspecialchars($value->img, ENT_QUOTES, 'UTF-8')); ?>" alt="<?php echo htmlspecialchars($value->nombre, ENT_QUOTES, 'UTF-8'); ?>"  style="width: 100%; height: 100%; object-fit: cover;">
                                       </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Filtro de Fechas -->
        <div class="form-group col-md-4">
            <label for="from">Desde</label>
            <input type="text" id="from" name="from">
        </div>
        <div class="form-group col-md-4">
            <label for="to">Hasta</label>
            <input type="text" id="to" name="to">
        </div>
        <div class="form-group col-md-4" style="display: flex; align-items: center; justify-content: center;">
            <button type="button" class="btn btn-outline-success" id="btnBuscar" disabled>Buscar</button>
        </div>
    </div>

    <!-- Consumo por categoría -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 col-lg-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Consumo Por categoría</h6>
                        <div>
                            <br>
                            <!-- Filtros por Categoría o Concepto -->
                            <div class="form-check">
                                <input class="filtroCatCon form-check-input" type="radio" name="filtrosCatCon" id="filtroCategoria" checked>
                                <label class="form-check-label" for="filtroCategoria">Categoría</label>
                            </div>
                            <div class="form-check">
                                <input class="filtroCatCon form-check-input" type="radio" name="filtrosCatCon" id="filtroConcepto">
                                <label class="form-check-label" for="filtroConcepto">Concepto</label>
                            </div>
                        </div>

                        <!-- Div Categoría -->
                        <div id="divCategoria" class="form-group mt-3">
                            <label for="cmbcategoria">Categoría</label>
                            <select class="form-control text-uppercase press cmbcategoria" name="categoria[]" multiple="multiple" id="cmbcategoria"></select>
                        </div>

                        <!-- Div Concepto -->
                        <div id="divConcepto" class="form-group mt-3" hidden>
                            <label for="cmbconcepto">Concepto</label>
                            <select class="form-control text-uppercase press cmbconcepto" name="concepto[]" multiple="multiple" id="cmbconcepto"></select>
                        </div>
                    </div>

                    <!-- Gráfico -->
                    <div class="card-body">
                        <div class="chart-pie pt-4 pb-2" id="consumoCategoria">
                            <canvas id="myPieChart"></canvas>
                            <span id="totalconsumo"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
