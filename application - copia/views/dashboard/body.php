	  
	<!-- Begin Page Content -->
	<div class="container-fluid">
		<div class="d-sm-flex align-items-center position-relative">
			<h4 class="h4 text-gray-800" style="margin: 4px;">Seleccionar</h4>
			<!-- CHECK -->
			<div class="custom-control custom-checkbox position-relative" style="margin-left: 12px;">
				<input type="checkbox" class="custom-control-input customCheckSite" id="customCheckSite">
				<label class="custom-control-label" for="customCheckSite"></label>
			</div>
		</div>

			<!-- Content Row -->
	  <div class="row row-cols-xl-12 row-cols-md-4">
			<?php foreach ($montoLugares as $key => $value) { ?>
				<div class="col-xl-2 col-md-3" style="padding-bottom: 10px;">
					<div class="card border-left-primary shadow h-100 py-2" >
						<div class="card-body" style="padding-top: 0.25rem;"> 
							<div class="row no-gutters align-items-center">
								<div class="col text-center">
									<div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
										<?php echo htmlspecialchars($value->nombre, ENT_QUOTES, 'UTF-8'); ?>
									</div>
									<div class="custom-control custom-checkbox" style="top: -35px; left: 55%;">
										<input type="checkbox" class="custom-control-input chkcustom" data-monto="<?php echo htmlspecialchars($value->montoFloat, ENT_QUOTES, 'UTF-8'); ?>" id="customCheck-<?php echo htmlspecialchars($key, ENT_QUOTES, 'UTF-8'); ?>">
										<label class="custom-control-label" for="customCheck-<?php echo htmlspecialchars($key, ENT_QUOTES, 'UTF-8'); ?>"></label>
									</div>
										<!-- Contenedor de imagen con tamaño fijo -->
									<div class="col-auto image-container">
										<img src="<?php echo base_url(htmlspecialchars($value->img, ENT_QUOTES, 'UTF-8')); ?>" alt="<?php echo htmlspecialchars($value->nombre, ENT_QUOTES, 'UTF-8'); ?>"  style="width: 100%; height: 100%; object-fit: cover;">
									  

									</div>
									<?php if ($value->tipoMoneda == 2) { ?>
									<div class="cotizacionDolar text-xs font-weight-bold text-primary text-uppercase mb-1">
										<?php htmlspecialchars($value->nombreMoneda, ENT_QUOTES, 'UTF-8'); ?>: 
										<?php echo "$ " . htmlspecialchars($value->cotizacion, ENT_QUOTES, 'UTF-8'); ?>
									</div>
									<div class="text-gray-900 text-center">
										<label data-toggle="tooltip" title="<div class='custom-tooltip'>Valor en pesos:<br><span class='text-info font-weight-bold'>$<?= htmlspecialchars($value->valorPesos, ENT_QUOTES, 'UTF-8'); ?></span></div>">
											<?php
											$montoDolar = empty($value->montoDolar) ? 0 : $value->montoDolar;
											echo ("$$ " . $montoDolar);
											?>
										</label>
									</div>
									<?php } else { ?>
										<div class="text-gray-900 text-center" style="font-size: 96%;">
											<?php
											$monto = empty($value->monto) ? 0 : $value->monto;
											echo ("$ ".$monto);
											?>
										</div>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php } ?>
		</div>

		   
			<span id="totalGeneral">
					 <p>TOTAL: <strong> $ <?php print_r($sumaTotal); ?></strong> </p>
					 <!--Ahorro sale de la diferencia de los ingresos en la casa y banco y limites de gastos -->
					</span>
			 
					<hr style="width: 80%;background-color: #2d59f1;">
					<hr style="background-color: #2d59f1;">
					<hr style="width: 80%;background-color: #2d59f1;">
			
						<div class="align-items-center justify-content-between mb-4">
						<h5 class="h5 mb-0 text-gray-800 text-center">Límites de gastos</h5>
						
					</div>

					<!-- Content Row -->
		   <div class="row " id="limitesDeConsumos"></div>
		   <div class="row">
					<!-- Content Column -->                   
					</div>
				</div>
				<!-- /.container-fluid -->
			</div>
			<!-- End of Main Content -->
<!-- Modal --> 
<div class="modal fade" id="modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
</div>