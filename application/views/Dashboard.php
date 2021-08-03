<?php
    require_once(APPPATH."views/parts/Header.php");
    require_once(APPPATH."views/parts/Sidebar.php");
    $active = 'dashboard';
?>
<!-- <style type="text/css">
  #background{
    position:absolute;
    z-index:0;
    background:white;
    display:block;
    min-height:100%; 
    min-width:100%;
    color:yellow;
}

#bg-text
{
    color:lightgrey;
    font-size:60px;
    transform:rotate(300deg);
    -webkit-transform:rotate(300deg);
}
</style> -->
<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> <a href="index.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a></div>
  </div>
<!--End-breadcrumbs-->

    <div class="row-fluid">
      <div id="background">
        <center><h1>Sistem Administrasi Pengarsipan Surat <br> Keluarahan Mojosongo</h1>
          <br>
          <img src="<?php echo base_url() ?>Assets/1.jpeg">
        </center>
      </div>
      <!-- <center><h1>KLASIFIKASI PELANGGAN MENGGUNAKAN <br> METODE NAIVE BAYES</h1></center> -->
    </div>
  </div>
</div>


<?php
    require_once(APPPATH."views/parts/Footer.php");
?>