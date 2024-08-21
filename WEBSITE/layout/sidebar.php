 <?php 
 
 $page = @$_GET['page'];
 if($page=="dashboard") {
    $dashboardaktif ='active';
 }
 if($page=="data_sensor") {
    $datasensoraktif ='active';
 }
 if($page=="kontrol_pompa") {
    $kontrolpompaaktif ='active';
 }
 ?>          
           
           <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php?page=dashboard">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Penyiram Otomatis <sup></sup></div>
            </a>
            <!-- Divider -->
            <hr class="sidebar-divider my-0">
            <!-- Nav Item - Dashboard -->
            <li class="nav-item <?php echo $dashboardaktif ?>">
            <a class="nav-link " href="index.php?page=dashboard">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>

            </li>
        
            <li class="nav-item <?php echo $datasensoraktif ?>">
                <a class="nav-link " href="index.php?page=data_sensor">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Data Sensor</span></a>
            </li>
            <!-- Nav Item - Charts -->
            <li class="nav-item <?php echo $kontrolpompaaktif ?> ">
            <a class="nav-link " href="index.php?page=kontrol_pompa">
                    <i class="fas fa-fw fa-toggle-on"></i>
                    <span>Kontrol Pompa</span></a>
                    </li>
            </li>z
            </li>
        
          