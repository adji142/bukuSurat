<?php
    require_once(APPPATH."views/parts/Header.php");
    require_once(APPPATH."views/parts/Sidebar.php");
    $active = 'daftarmesin';

    $this->db->query('truncate data_uji');
?>
	<div id="content">
		<div id="content-header">
			<div id="breadcrumb"> <a href="<?php echo base_url(); ?>" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="current">Proses Data</a> </div>
		</div>
		<div class="container-fluid">
			<div class="row-fluid">
				<div class="span6">
					<div class="widget-box">
						<div class="widget-title"> 
				            <h5>Data Latih</h5>
				        </div>
				        <div class="widget-content">
				        	<form enctype='application/json' id="paramLatih">
				        		<div class="controls controls-row">
						           <input type="month" class="span3 m-wrap" name="tglawal_a" id="tglawal_a">
						           <input type="month" class="span3 m-wrap" name="tglakhir_a" id="tglakhir_a">
						           <button id="prosesLatih" class="span6 m-wrap btn btn-primary">Proses</button>
						        </div>
				        	</form>
				        	<div class="dx-viewport demo-container">
					        	<div id="data-grid-demo">
					        		<div id="gridContainer_latih">
					        		</div>
					        	</div>
					        </div>
						</div>
					</div>
				</div>
				<div class="span6">
					<div class="widget-box">
						<div class="widget-title"> 
				            <h5>Data Uji</h5>
				        </div>
				        <div class="widget-content">
				        	<form enctype='application/json' id="paramUji">
				        		<div class="controls controls-row">
						           <input type="month" class="span3 m-wrap" name="tglawal_b" id="tglawal_b">
						           <input type="month" class="span3 m-wrap" name="tglakhir_b" id="tglakhir_b">
						           <button id="prosesUji" class="span6 m-wrap btn btn-primary">Proses</button>
						        </div>
				        	</form>
				        	<div class="dx-viewport demo-container">
					        	<div id="data-grid-demo">
					        		<div id="gridContainer_uji">
					        		</div>
					        	</div>
					        </div>
						</div>
					</div>
				</div>
				<!-- <div class="span12"> -->
				<div class="widget-box">
			        <div class="widget-content">
			        	<div class="controls controls-row">
			        		<button class="span12 btn btn-danger" name="proses" id="proses">Proses Perhitungan NBC</button>
			        	</div>
					</div>
				</div>
				<!-- </div> -->
				<!-- hasil -->
				<div class="widget-box">
					<div class="widget-title"> 
			            <h5>Hasil Perhitungan</h5>
			        </div>
			        <div class="widget-content">
			        	<div id="load_data_perhit"></div>
					</div>
				</div>

			</div>
		</div>
	</div>
<?php
    require_once(APPPATH."views/parts/Footer.php");
?>

<script type="text/javascript">
	$(function () {
		var data_uji;
        $(document).ready(function () {
	        // $.ajax({
	        //   type: "post",
	        //   url: "<?=base_url()?>Apps/initialData",
	        //   // data: {id:where_field,where_value:where_value,table:table},
	        //   dataType: "json",
	        //   success: function (response) {
	        //   	if (response.success) {
	        //   		$('#load_data').html(
	        //   			"<p>Generating Data ............. Done</p>"+
	        //   			"<div id = 'load_data_perhit'> </div>"
	        //   		);
	        //   	}
	        //   	else{
	        //   		$('#load_data').html(
	        //   			"<p>"+response.message+"</p>"
	        //   		);
	        //   	}
	        //   }
	        // });
        });

        $('#paramLatih').submit(function (e) {
        	$('#prosesLatih').text('Tunggu Sebentar.....');
		    $('#prosesLatih').attr('disabled',true);

			e.preventDefault();
			var me = $(this);

			$.ajax({
		        type    :'post',
		        url     : '<?=base_url()?>Apps/GetDataLatih',
		        data    : me.serialize(),
		        dataType: 'json',
		        success : function (response) {
		          if(response.success == true){
		            bindGrid_Latih(response.data);
		          }
		        }
		      });
			$('#prosesLatih').text('Proses');
		    $('#prosesLatih').attr('disabled',false);
        });

        $('#paramUji').submit(function (e) {
        	$('#prosesUji').text('Tunggu Sebentar.....');
		    $('#prosesUji').attr('disabled',true);

			e.preventDefault();
			var me = $(this);

			$.ajax({
		        type    :'post',
		        url     : '<?=base_url()?>Apps/GetDataUji',
		        data    : me.serialize(),
		        dataType: 'json',
		        success : function (response) {
		          if(response.success == true){
		            bindGrid_Uji(response.data);
		            data_uji = response.data;
		          }
		        }
		      });
			$('#prosesUji').text('Proses');
		    $('#prosesUji').attr('disabled',false);
        });
        $('#proses').click(function () {
        	$('#btn_Save').text('Tunggu Sebentar.....');
		    $('#btn_Save').attr('disabled',true);

		    var uuid = generateUUID();
			// input parameter dari form
			var jmlpembelian = $('#pembelian').val();
			var interval = $('#interval').val();
			var lokasi = $('#lokasi').val();

			// local variable

			var P_Berpotensi = 0;
			var P_tdkBerpotensi = 0;

			var P_SB_BP = 0;
			var P_SB_TBP = 0;
			var P_B_BP = 0;
			var P_B_TBP = 0;
			var P_S_BP=0;
			var P_S_TBP =0;
			var P_SS_BP =0;
			var P_SS_TBP=0;

			var P_H_BP = 0;
			var P_H_TBP=0;
			var P_M_BP = 0;
			var P_M_TBP=0;

			var P_D_BP=0;
			var P_D_TBP=0;
			var P_J_BP =0;
			var P_J_TBP =0;


			// hasil
			var H_SB_BP = 0;
			var H_SB_TBP = 0;
			var H_B_BP = 0;
			var H_B_TBP = 0;
			var H_S_BP=0;
			var H_S_TBP =0;
			var H_SS_BP =0;
			var H_SS_TBP=0;

			var H_H_BP = 0;
			var H_H_TBP=0;
			var H_M_BP = 0;
			var H_M_TBP=0;

			var H_D_BP=0;
			var H_D_TBP=0;
			var H_J_BP =0;
			var H_J_TBP =0;


			// perhitungan Menghitung P(X|Ci) untuk setiap kelas

			var P_pembelian_Y = 0;
			var P_interval_Y = 0;
			var P_lokasi_Y = 0;

			var P_pembelian_N = 0;
			var P_interval_N = 0;
			var P_lokasi_N = 0;

			$.ajax({
		        type    :'post',
		        url     : '<?=base_url()?>Apps/GetDataTest',
		        data    : {'id':''},
		        dataType: 'json',
		        success : function (response) {
		          if(response.success == true){
		          	// hitung P(Ci) Berpotensi
		          	P_Berpotensi = response.berpotensi/response.testcount;
		          	// hitung P(Ci) Tidak Berpotensi
		          	P_tdkBerpotensi = response.tdkberpotensi/response.testcount;

		          	// hitung P(X|Ci) untuk setiap kelas
		          	$.each(response.data,function (k,v) {
		          		// Jumlah pembelian
		          		// console.log(v.interval.toLowerCase()+' - '+v.target.toLowerCase());
	          			if (v.pembelian.toLowerCase() == 'sangat banyak' && v.target.toLowerCase() == 'berpotensi') {
	          				P_SB_BP = P_SB_BP+1;
	          			}
	          			else if (v.pembelian.toLowerCase() == 'sangat banyak' && v.target.toLowerCase() == 'tidak berpotensi') {
	          				P_SB_TBP = P_SB_TBP+1;
	          			}
	          			else if (v.pembelian.toLowerCase() == 'banyak' && v.target.toLowerCase() == 'berpotensi') {
	          				P_B_BP = P_B_BP+1;
	          			}
	          			else if (v.pembelian.toLowerCase() == 'banyak' && v.target.toLowerCase() == 'tidak berpotensi') {
	          				P_B_TBP = P_B_TBP+1;
	          			}
	          			else if (v.pembelian.toLowerCase() == 'sedikit' && v.target.toLowerCase() == 'berpotensi') {
	          				P_S_BP = P_S_BP+1;
	          			}
	          			else if (v.pembelian.toLowerCase() == 'sedikit' && v.target.toLowerCase() == 'tidak berpotensi') {
	          				P_S_TBP = P_S_TBP+1;
	          			}
	          			else if (v.pembelian.toLowerCase() == 'sangat sedikit' && v.target.toLowerCase() == 'berpotensi') {
	          				P_SS_BP = P_SS_BP+1;
	          			}
	          			else if (v.pembelian.toLowerCase() == 'sangat sedikit' && v.target.toLowerCase() == 'tidak berpotensi') {
	          				P_SS_TBP = P_SS_TBP+1;
	          			}
	          			// Interval Waktu
	          			if (v.interval.toLowerCase() == 'harian' && v.target.toLowerCase() == 'berpotensi') {
	          				P_H_BP = P_H_BP+1;
	          				console.log(P_H_BP);
	          			}
	          			else if (v.interval.toLowerCase() == 'harian' && v.target.toLowerCase() == 'tidak berpotensi') {
	          				P_H_TBP = P_H_TBP+1;
	          			}
	          			else if (v.interval.toLowerCase() == 'mingguan' && v.target.toLowerCase() == 'berpotensi') {
	          				P_M_BP = P_M_BP+1;
	          			}
	          			else if (v.interval.toLowerCase() == 'mingguan' && v.target.toLowerCase() == 'tidak berpotensi') {
	          				P_M_TBP = P_M_TBP+1;
	          			}
	          			// Lokasi
	          			if (v.lokasi.toLowerCase() == 'dekat' && v.target.toLowerCase() == 'berpotensi') {
	          				P_D_BP = P_D_BP+1;
	          			}
	          			else if (v.lokasi.toLowerCase() == 'dekat' && v.target.toLowerCase() == 'tidak berpotensi') {
	          				P_D_TBP = P_D_TBP+1;
	          			}
	          			else if (v.lokasi.toLowerCase() == 'jauh' && v.target.toLowerCase() == 'berpotensi') {
	          				P_J_BP = P_J_BP+1;
	          			}
	          			else if (v.lokasi.toLowerCase() == 'jauh' && v.target.toLowerCase() == 'tidak berpotensi') {
	          				P_J_TBP = P_J_TBP+1;
	          			}
			        });

		          	H_SB_BP 	= P_SB_BP/response.berpotensi;
					H_SB_TBP 	= P_SB_TBP/response.tdkberpotensi;
					H_B_BP 		= P_B_BP/response.berpotensi;
					H_B_TBP 	= P_B_TBP/response.tdkberpotensi;
					H_S_BP 		= P_S_BP/response.berpotensi;
					H_S_TBP 	= P_S_TBP/response.tdkberpotensi;
					H_SS_BP 	= P_SS_BP/response.berpotensi;
					H_SS_TBP 	= P_SS_TBP/response.tdkberpotensi;

					H_H_BP 		= P_H_BP/response.berpotensi;
					H_H_TBP 	= P_H_TBP/response.tdkberpotensi;
					H_M_BP 		= P_M_BP/response.berpotensi;
					H_M_TBP 	= P_M_TBP/response.tdkberpotensi;

					H_D_BP 		= P_D_BP/response.berpotensi;
					H_D_TBP 	= P_D_TBP/response.tdkberpotensi;
					H_J_BP 		= P_J_BP/response.berpotensi;
					H_J_TBP 	= P_J_TBP/response.tdkberpotensi;

					console.log(H_S_TBP);
					console.log(round(H_S_TBP,2));
					var html = "<pre>"+
	          				"Jumlah Pelanggan 		= "+response.testcount+"<br>"+
	          				"Jumlah Data Berpotensi		= "+response.berpotensi+"<br>"+
	          				"Jumlah Data Tidak berpotensi 	= "+response.tdkberpotensi+"<br>"+
	          				"_______________________________________________________________<br>"+
	          				"Nilai P(Ci)<br>"+
	          				"_______________________________________________________________<br>"+
	          				"P (Class =<font color = 'red'>“Berpotensi”</font>) 	= "+response.berpotensi+"/"+response.testcount+" = <font color = 'red'>"+round(P_Berpotensi,2)+"</font><br>"+
	          				"P (Class =<font color = 'red'>“Tidak Berpotensi”</font>)   = "+response.tdkberpotensi+"/"+response.testcount+" <font color = 'red'>= "+round(P_tdkBerpotensi,2)+"</font><br>"+
	          				"_______________________________________________________________<br>"+
	          				"Menghitungan P(X|Ci) Untuk Setiap kelas i <br>"+
	          				"_______________________________________________________________<br>"+
	          				"P (Jml Pembelian = <font color = 'red'>“Sangat Banyak”</font> | Target = <font color = 'red'>“Berpotensi”</font>) 			= " +P_SB_BP+"/"+response.berpotensi+" = <font color = 'red'> "+round(H_SB_BP,2)+"</font><br>"+
	          				"P (Jml Pembelian = <font color = 'red'>“Sangat Banyak”</font> | Target = <font color = 'red'>“Tidak Berpotensi”</font>)  		= " +P_SB_TBP+"/"+response.tdkberpotensi+" = <font color = 'red'> "+round(H_SB_TBP,2)+"</font><br>"+
	          				"P (Jml Pembelian = <font color = 'red'>“Banyak”</font> | Target = <font color = 'red'>“Berpotensi”</font>)  				= " +P_B_BP+"/"+response.berpotensi+" = <font color = 'red'> "+round(H_B_BP,2)+"</font><br>"+
	          				"P (Jml Pembelian = <font color = 'red'>“Banyak”</font> | Target = <font color = 'red'>“Tidak Berpotensi”</font>) 			= " +P_B_TBP+"/"+response.tdkberpotensi+" = <font color = 'red'> "+round(H_B_TBP,2)+"</font><br>"+
	          				"P (Jml Pembelian = <font color = 'red'>“Sedikit”</font> | Target = <font color = 'red'>“Berpotensi”</font>)  				= " +P_S_BP+"/"+response.berpotensi+" = <font color = 'red'> "+round(H_S_BP,2)+"</font><br>"+
	          				"P (Jml Pembelian = <font color = 'red'>“Sedikit”</font> | Target = <font color = 'red'>“Tidak Berpotensi”</font>)			= " +P_S_TBP+"/"+response.tdkberpotensi+" = <font color = 'red'> "+round(H_S_TBP,2)+"</font><br>"+
	          				"P (Jml Pembelian = <font color = 'red'>“Sangat Sedikit”</font> | Target = <font color = 'red'>“Berpotensi”</font>)			= " +P_SS_BP+"/"+response.berpotensi+" = <font color = 'red'> "+round(H_SS_BP,2)+"</font><br>"+
	          				"P (Jml Pembelian = <font color = 'red'>“Sangat Sedikit”</font> | Target = <font color = 'red'>“Tidak Berpotensi”</font>) 		= " +P_SS_TBP+"/"+response.tdkberpotensi+" = <font color = 'red'> "+round(H_SS_TBP,2)+"</font><br><br>"+
	          				"P (Interval Waktu = <font color = 'red'>“Harian”</font> | Target = <font color = 'red'>“Berpotensi”</font>) 				= " +P_H_BP+"/"+response.berpotensi+" = <font color = 'red'> "+round(H_H_BP,2)+"</font><br>"+
	          				"P (Interval Waktu = <font color = 'red'>“Harian”</font> | Target = <font color = 'red'>“Tidak Berpotensi”</font>) 			= " +P_H_TBP+"/"+response.tdkberpotensi+" = <font color = 'red'> "+round(H_H_TBP,2)+"</font><br>"+
	          				"P (Interval Waktu = <font color = 'red'>“Mingguan”</font> | Target = <font color = 'red'>“Berpotensi”</font>) 	 		= " +P_M_BP+"/"+response.berpotensi+" = <font color = 'red'> "+round(H_M_BP,2)+"</font><br>"+
	          				"P (Interval Waktu = <font color = 'red'>“Mingguan”</font> | Target = <font color = 'red'>“Tidak Berpotensi”</font>) 			= " +P_M_TBP+"/"+response.tdkberpotensi+" = <font color = 'red'> "+round(H_M_TBP,2)+"</font><br><br>"+
	          				"P (Lokasi= <font color = 'red'>“Dekat”</font> | Target = <font color = 'red'>“Berpotensi”</font>) 					= " +P_D_BP+"/"+response.berpotensi+" = <font color = 'red'> "+round(H_D_BP,2)+"</font><br>"+
	          				"P (Lokasi= <font color = 'red'>“Dekat”</font> | Target = <font color = 'red'>“Tidak Berpotensi”</font>) 				= " +P_D_TBP+"/"+response.tdkberpotensi+" = <font color = 'red'> "+round(H_D_TBP,2)+"</font><br>"+
	          				"P (Lokasi= <font color = 'red'>“Jauh”</font> | Target = <font color = 'red'>“Berpotensi”</font>) 		 			= " +P_J_BP+"/"+response.berpotensi+" = <font color = 'red'> "+round(H_J_BP,2)+"</font><br>"+
	          				"P (Lokasi= <font color = 'red'>“Jauh”</font> | Target = <font color = 'red'>“Tidak Berpotensi”</font>) 				= " +P_J_TBP+"/"+response.tdkberpotensi+" = <font color = 'red'> "+round(H_J_TBP,2)+"</font><br>"+
	          				"_______________________________________________________________<br>";
	          			$.each(data_uji,function (k,v) {
	          				var perhit;

	          				// perhitungan Menghitung P(X|Ci) untuk setiap kelas--- Berpotensi
							switch(v.pembelian) {
							  case "Sangat Banyak":
							    P_pembelian_Y = H_SB_BP;
							    break;
							  case "Banyak":
							    P_pembelian_Y = H_B_BP;
							    break;
							  case "Sedikit":
							    P_pembelian_Y = H_S_BP;
							    break;
							  case "Sangat Sedikit":
							    P_pembelian_Y = H_SS_BP;
							    break;
							  default:
							  	P_pembelian_Y = 0;
							}
							switch(v.interval){
								case "Harian":
									P_interval_Y = H_H_BP;
									break;
								case "Mingguan":
									P_interval_Y = H_M_BP;
									break;
								default:
									P_interval_Y = 0;
									
							}

							switch(v.lokasi){
								case "Dekat":
									P_lokasi_Y = H_D_BP;
									break;
								case "Jauh":
									P_lokasi_Y = H_J_BP;
									break;
								default:
									P_lokasi_Y = 0;
							}

					// perhitungan Menghitung P(X|Ci) untuk setiap kelas--- Tidak Berpotensi
	          				switch(v.pembelian) {
							  case "Sangat Banyak":
							    P_pembelian_N = H_SB_TBP;
							    break;
							  case "Banyak":
							    P_pembelian_N = H_B_TBP;
							    break;
							  case "Sedikit":
							    P_pembelian_N = H_S_TBP;
							    break;
							  case "Sangat Sedikit":
							    P_pembelian_N = H_SS_TBP;
							    break;
							  default:
							  	P_pembelian_N = 0;
							}

							switch(v.interval){
								case "Harian":
									P_interval_N = H_H_TBP;
									break;
								case "Mingguan":
									P_interval_N = H_M_TBP;
									break;
								default:
									P_interval_N = 0;
							}

							switch(v.lokasi){
								case "Dekat":
									P_lokasi_N = H_D_TBP;
									break;
								case "Jauh":
									P_lokasi_N = H_J_TBP;
									break;
								default:
									P_lokasi_N = 0;
							}

							var stepc = '';
							var stepd = '';

							// Masukan rumus

							var hasilberpotensi = P_pembelian_Y * P_interval_Y * P_lokasi_Y;
							var hasiltidakberpotensi = P_pembelian_N * P_interval_N * P_lokasi_N;

							console.log(v.nmcust + P_pembelian_Y +"*"+ P_interval_Y +"*"+ P_lokasi_Y);

							if (hasilberpotensi > hasiltidakberpotensi) {
								stepc = 'Berpotensi';
							}
							else if(hasilberpotensi < hasiltidakberpotensi){
								stepc = 'Tidak Berpotensi';
							}
							else{
								stepc = 'Unknown';
							}

							// Menghitung P(X|Ci)*P(Ci)

							var hasilberpotensi_2 = hasilberpotensi * P_Berpotensi;
							var hasiltidakberpotensi_2 = hasiltidakberpotensi * P_tdkBerpotensi;

							if (hasilberpotensi_2 > hasiltidakberpotensi_2) {
								stepd = 'Berpotensi';
							}
							else if(hasilberpotensi_2 < hasiltidakberpotensi_2){
								stepd = 'Tidak Berpotensi';
							}
							else{
								stepd = 'Unknown';
							}

							perhit ="<pre>" +
								"Menghitung P(X|Ci) untuk setiap kelas <br>" +
								"=====================================<br>"+
								"P(X | level = Berpotensi)		= " +round(P_pembelian_Y,2) +"*"+ round(P_interval_Y,2) +"*" +round(P_lokasi_Y,2)+" = " + round(hasilberpotensi,4)+"<br>"+
								"P(X | level = Tidak Berpotensi)		= " +round(P_pembelian_N,2) +"*"+ round(P_interval_N,2) +"*" +round(P_lokasi_N,2)+" = " + round(hasiltidakberpotensi,4) + "<br>"+
								"__________________________________________________________________<br>"+
								"__________________________________________________________________<br>"+
							"Menghitung P(X|Ci) * P(Ci) <br>"+
							"==========================<br>"+
								"P(X|level = Berpotensi) * P(target=Berpotensi)			= <br>" +round(hasilberpotensi,2) +"*"+ round(P_Berpotensi,2) +" = " + round(hasilberpotensi_2,4) + "<br>" +
								"P(X|level = Tidak Berpotensi) * P(target=Tidak Berpotensi)		=<br>"+round(hasiltidakberpotensi,2) +"*"+ round(P_tdkBerpotensi,2) +" = " + round(hasiltidakberpotensi_2,4) + "<br>" +
							"</pre>";
							console.log(perhit);
							$.ajax({
						        type    :'post',
						        url     : '<?=base_url()?>Apps/appendhistory',
						        data    : {'KodeUji':uuid,'NamaPelanggan':v.nmcust,'JmlPembelian':v.pembelian,'Interval':v.interval,'Lokasi':v.lokasi,'Target':stepd,'Perhitungan':perhit,'jnsusaha':v.KelompokUsaha,'kdcust':v.kdcust},
						        dataType: 'json',
						        success : function (response) {
						          if(response.success == true){
						            console.log('done');
						          }
						          else{
						            console.log(response.message);
						          }
						        }
						      })

							// html += v.nmcust + "<=========================================>" + stepd + "<br>";
	          			});

		            $('#load_data_perhit').html(html);

		            $.ajax({
				        type    :'post',
				        url     : '<?=base_url()?>Apps/GetDataUji_final',
				        data    : {'uuid':uuid},
				        dataType: 'json',
				        success : function (response) {
				          if(response.success == true){
				            bindGrid_Uji(response.data);
				          }
				        }
				      });
						// insert ke tabel history

	          // 			$.ajax({
					      //   type    :'post',
					      //   url     : '<?=base_url()?>Apps/appendhistory',
					      //   data    : {hasil:finalResult,pembelian:jmlpembelian,interval:interval,lokasi:lokasi},
					      //   dataType: 'json',
					      //   success : function (response) {
					      //     if(response.success == true){
					      //       console.log('done');
					      //     }
					      //     else{
					      //       console.log(response.message);
					      //     }
					      //   }
					      // })
		          }
		          else{
		            $('#load_data_perhit').html(
	          			"<p>"+response.message+"</p>"
	          		);
		          }
		        }
		      });
        });
		
		function bindGrid_Latih(data) {

			$("#gridContainer_latih").dxDataGrid({
				allowColumnResizing: true,
		        dataSource: data,
		        keyExpr: "id",
		        showBorders: true,
		        allowColumnReordering: true,
		        allowColumnResizing: true,
		        columnAutoWidth: true,
		        showBorders: true,
		        paging: {
		            enabled: true
		        },
		        editing: {
		            mode: "row",
		            texts: {
		                confirmDeleteMessage: ''  
		            }
		        },
		        columns: [
		            {
		                dataField: "id",
		                caption: "id",
		                allowEditing:false,
		                visible : false
		            },
		            {
		                dataField: "nmcust",
		                caption: "Nama Pelanggan",
		                allowEditing:false
		            },
		            {
		                dataField: "KelompokUsaha",
		                caption: "Status Usaha",
		                allowEditing:false
		            },
		            {
		                dataField: "pembelian",
		                caption: "Jumlah Pembelian",
		                allowEditing:false
		            },
		            {
		                dataField: "interval",
		                caption: "Interval Waktu",
		                allowEditing:false
		            },
		            {
		                dataField: "lokasi",
		                caption: "Lokasi",
		                allowEditing:false
		            },
		            {
		                dataField: "target",
		                caption: "Target",
		                allowEditing:false
		            },
		        ],
		        onEditingStart: function(e) {
		            GetData(e.data.id);
		        },
		        onInitNewRow: function(e) {
		            // logEvent("InitNewRow");
		            $('#modal_').modal('show');
		        },
		        onRowInserting: function(e) {
		            // logEvent("RowInserting");
		        },
		        onRowInserted: function(e) {
		            // logEvent("RowInserted");
		            // alert('');
		            // console.log(e.data.onhand);
		            // var index = e.row.rowIndex;
		        },
		        onRowUpdating: function(e) {
		            // logEvent("RowUpdating");
		            
		        },
		        onRowUpdated: function(e) {
		            // logEvent(e);
		        },
		        onRowRemoving: function(e) {
		        	id = e.data.id;
		        	Swal.fire({
					  title: 'Apakah anda yakin?',
					  text: "anda akan menghapus data di baris ini !",
					  icon: 'warning',
					  showCancelButton: true,
					  confirmButtonColor: '#3085d6',
					  cancelButtonColor: '#d33',
					  confirmButtonText: 'Yes, delete it!'
					}).then((result) => {
					  if (result.value) {
					  	var table = 'app_setting';
					  	var field = 'id';
					  	var value = id;

					  	$.ajax({
					        type    :'post',
					        url     : '<?=base_url()?>Apps/remove',
					        data    : {table:table,field:field,value:value},
					        dataType: 'json',
					        success : function (response) {
					          if(response.success == true){
					            Swal.fire(
							      'Deleted!',
							      'Your file has been deleted.',
							      'success'
							    ).then((result)=>{
					              location.reload();
					            });
					          }
					          else{
					            Swal.fire({
					              type: 'error',
					              title: 'Woops...',
					              text: response.message,
					              // footer: '<a href>Why do I have this issue?</a>'
					            }).then((result)=>{
					            	location.reload();
					            });
					          }
					        }
					      });
					    
					  }
					  else{
					  	location.reload();
					  }
					})
		        },
		        onRowRemoved: function(e) {
		        	// console.log(e);
		        },
				onEditorPrepared: function (e) {
					// console.log(e);
				}
		    });

		    // add dx-toolbar-after
		    // $('.dx-toolbar-after').append('Tambah Alat untuk di pinjam ');
		}

		function bindGrid_Uji(data) {

			$("#gridContainer_uji").dxDataGrid({
				allowColumnResizing: true,
		        dataSource: data,
		        keyExpr: "id",
		        showBorders: true,
		        allowColumnReordering: true,
		        allowColumnResizing: true,
		        columnAutoWidth: true,
		        showBorders: true,
		        paging: {
		            enabled: true
		        },
		        editing: {
		            mode: "row",
		            texts: {
		                confirmDeleteMessage: ''  
		            }
		        },
		        columns: [
		            {
		                dataField: "id",
		                caption: "id",
		                allowEditing:false,
		                visible : false
		            },
		            {
		                dataField: "nmcust",
		                caption: "Nama Pelanggan",
		                allowEditing:false
		            },
		            {
		                dataField: "KelompokUsaha",
		                caption: "Status Usaha",
		                allowEditing:false
		            },
		            {
		                dataField: "pembelian",
		                caption: "Jumlah Pembelian",
		                allowEditing:false
		            },
		            {
		                dataField: "interval",
		                caption: "Interval Waktu",
		                allowEditing:false
		            },
		            {
		                dataField: "lokasi",
		                caption: "Lokasi",
		                allowEditing:false
		            }
		            // {
		            //     dataField: "target",
		            //     caption: "Target",
		            //     allowEditing:false
		            // },
		        ],
		        onEditingStart: function(e) {
		            GetData(e.data.id);
		        },
		        onInitNewRow: function(e) {
		            // logEvent("InitNewRow");
		            $('#modal_').modal('show');
		        },
		        onRowInserting: function(e) {
		            // logEvent("RowInserting");
		        },
		        onRowInserted: function(e) {
		            // logEvent("RowInserted");
		            // alert('');
		            // console.log(e.data.onhand);
		            // var index = e.row.rowIndex;
		        },
		        onRowUpdating: function(e) {
		            // logEvent("RowUpdating");
		            
		        },
		        onRowUpdated: function(e) {
		            // logEvent(e);
		        },
		        onRowRemoving: function(e) {
		        	id = e.data.id;
		        	Swal.fire({
					  title: 'Apakah anda yakin?',
					  text: "anda akan menghapus data di baris ini !",
					  icon: 'warning',
					  showCancelButton: true,
					  confirmButtonColor: '#3085d6',
					  cancelButtonColor: '#d33',
					  confirmButtonText: 'Yes, delete it!'
					}).then((result) => {
					  if (result.value) {
					  	var table = 'app_setting';
					  	var field = 'id';
					  	var value = id;

					  	$.ajax({
					        type    :'post',
					        url     : '<?=base_url()?>Apps/remove',
					        data    : {table:table,field:field,value:value},
					        dataType: 'json',
					        success : function (response) {
					          if(response.success == true){
					            Swal.fire(
							      'Deleted!',
							      'Your file has been deleted.',
							      'success'
							    ).then((result)=>{
					              location.reload();
					            });
					          }
					          else{
					            Swal.fire({
					              type: 'error',
					              title: 'Woops...',
					              text: response.message,
					              // footer: '<a href>Why do I have this issue?</a>'
					            }).then((result)=>{
					            	location.reload();
					            });
					          }
					        }
					      });
					    
					  }
					  else{
					  	location.reload();
					  }
					})
		        },
		        onRowRemoved: function(e) {
		        	// console.log(e);
		        },
				onEditorPrepared: function (e) {
					// console.log(e);
				}
		    });

		    // add dx-toolbar-after
		    // $('.dx-toolbar-after').append('Tambah Alat untuk di pinjam ');
		}
		function round(value, decimals) {
		  return Number(Math.round(value+'e'+decimals)+'e-'+decimals);
		}
		function generateUUID() { // Public Domain/MIT
		    var d = new Date().getTime();//Timestamp
		    var d2 = (performance && performance.now && (performance.now()*1000)) || 0;//Time in microseconds since page-load or 0 if unsupported
		    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
		        var r = Math.random() * 16;//random number between 0 and 16
		        if(d > 0){//Use timestamp until depleted
		            r = (d + r)%16 | 0;
		            d = Math.floor(d/16);
		        } else {//Use microseconds since page-load if supported
		            r = (d2 + r)%16 | 0;
		            d2 = Math.floor(d2/16);
		        }
		        return (c === 'x' ? r : (r & 0x3 | 0x8)).toString(16);
		    });
		}
	});
</script>
