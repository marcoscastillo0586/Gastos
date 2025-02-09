<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3"> Control de Gastos </div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="<?=base_url()?>Menu/">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Menú Principal</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Nav Item - nuevo ingreso -->
            <li class="nav-item">
                <a class="nav-link" href="<?=base_url()?>Ingreso/">
               <i class="fas fa-chart-line iconMenu"></i>
                    <span> Registrar Ingreso </span>
                </a>
            </li>
              <!-- Nav Item - Cargar Gastos -->
            <li class="nav-item">
                <a class="nav-link" href="<?=base_url()?>Egreso/">
                    <i class="fas fa-coins iconMenu"></i>
                    
                    <span> Registrar Gastos </span>
                </a>
            </li> 

            <li class="nav-item">
                <a class="nav-link" href="<?=base_url()?>Movimiento/">
             <i class="far fa-calendar-alt iconMenu"></i>
                    <span> Ver Movimientos </span>
                </a>
            </li>


            <li class="nav-item">
                <a class="nav-link" href="<?=base_url()?>Transferencia/">
                   <i class="fas fa-exchange-alt iconMenu"></i>
                    <span> Realizar Transferencia </span>
                </a>
            </li>

             <li class="nav-item">
                <a class="nav-link" href="<?=base_url()?>TopeGasto">
                
                  <i class="fab fa-creative-commons-nc iconMenu"></i>
                    <span> Registrar límites </span>
                </a>
            </li>

             <!-- Nav Item - Gastos Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-wrench"></i>
                    <span>GRAFICOS</span>
                </a>
                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="<?=base_url()?>graficos/ConsumoPorCategoria/">Consumo Por categoria</a>
                        <a class="collapse-item" href="<?=base_url()?>graficos/ComparativaMensual">Comparativa mensual</a>
                    </div>
                </div>
            </li>

     <!-- Nav Item - Gastos Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseABM"
                    aria-expanded="true" aria-controls="collapseABM">
                    <i class="fas fa-fw fa-wrench"></i>
                    <span>ABM</span>
                </a>
                <div id="collapseABM" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="<?=base_url()?>abm/MovimientoABM/">Movimientos</a>
                        <a class="collapse-item" href="<?=base_url()?>abm/LugarABM">Lugar</a>
                    </div>
                </div>
            </li>
           
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>
        <!-- End of Sidebar -->
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
                  <!-- Main Content -->
            <div id="content">
                <!-- Topbar -->
                <nav class="navbar navbar-expand  bg-white topbar mb-1 static-top shadow">
                    <div class="w-100 text-center">
                        <?php if (!empty($titulo)): ?>
                        <h4 class="mb-0"><?= htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8') ?></h4>
                        <?php endif; ?>
                    </div>
                </nav>
                <!-- End of Topbar -->
 <?php echo $page ?> 