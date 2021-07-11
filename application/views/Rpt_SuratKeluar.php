<?php
    require_once(APPPATH."views/parts/Header.php");
    require_once(APPPATH."views/parts/Sidebar.php");
    $active = 'daftarmesin';
?>

<div id="content">
	<div id="content-header">
		<div id="breadcrumb"> <a href="<?php echo base_url(); ?>" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="current">Laporan Surat Keluar</a> </div>
	</div>
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="widget-box">
				<div class="widget-title"> 
		            <h5>Laporan Surat Keluar</h5>
		        </div>
		        <div class="widget-content">
		        	<!-- <button type="button" class="btn btn-mini btn-info" data-toggle="modal" data-target="#modal_">
						  Tambah Pelayan
					</button> -->
					<div class="control-group">
						<div class="controls controls-row">
							<input type="date" class="span2 m-wrap" id="TglAwal" name="TglAwal" value="<?php echo date('Y-m-d') ?>">
							<input type="date" class="span2 m-wrap" id="TglAkhir" name="TglAkhir" value="<?php echo date('Y-m-d') ?>">
								<button class="btn btn-info" id="btproses" name="btproses">Proses</button>
						</div>
					</div>
					<div class="dx-viewport demo-container">
			        	<div id="data-grid-demo">
			        		<div id="gridContainer">
			        		</div>
			        	</div>
			        </div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal hide" id="modal_" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
		        <h5 class="modal-title" id="exampleModalLabel">
		        	<div id="title_modal">Data Klasifikasi
		        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				          <span aria-hidden="true">&times;</span>
				        </button>
		        	</div>
		        </h5>
		    </div>

		    <div class="modal-body">
		    	<form class="form-horizontal" enctype='application/json' id="post_">
		    		<div class="control-group">
						<label class="control-label">Nama :</label>
						<div class="controls controls-row">
				          <input type="text" class="span2 m-wrap" id="NamaKlasifikasi" name="NamaKlasifikasi" required="" placeholder="Nama Klasifikasi">
				          <input type="hidden" class="span3 m-wrap" id="id" name="id">
				          <input type="hidden" class="span3 m-wrap" id="formtype" name="formtype" value="add">
				        </div>
					</div>
		            <button class="btn btn-primary" id="btn_Save">Save</button>
		    	</form>
		    </div>

		</div>
	</div>
</div>

<?php
    require_once(APPPATH."views/parts/Footer.php");
?>

<script type="text/javascript">
	$(function () {
		// Get Data From Database
		$(document).ready(function () {
        	$('#btproses').click();
        });

		$('#btproses').click(function () {
			var where_field = '';
        	var where_value = '';
        	var table = 'transaksi';

	        $.ajax({
	          type: "post",
	          url: "<?=base_url()?>Apps/LaporanSuratkeluar",
	          data: {'TglAwal':$('#TglAwal').val(), 'TglAkhir':$('#TglAkhir').val()},
	          dataType: "json",
	          success: function (response) {
	          	bindGrid(response.data);
	          }
	        });
		})
		// Eksekusi Insert / Update Ke Database
		$('#post_').submit(function (e) {
			$('#btn_Save').text('Tunggu Sebentar.....');
		    $('#btn_Save').attr('disabled',true);

		    e.preventDefault();
			var me = $(this);

			$.ajax({
		        type    :'post',
		        url     : '<?=base_url()?>Apps/',
		        data    : me.serialize(),
		        dataType: 'json',
		        success : function (response) {
		          if(response.success == true){
		            $('#modal_').modal('toggle');
		            Swal.fire({
		              type: 'success',
		              title: 'Horay..',
		              text: 'Data Berhasil disimpan!',
		              // footer: '<a href>Why do I have this issue?</a>'
		            }).then((result)=>{
		              location.reload();
		            });
		          }
		          else{
		            $('#modal_').modal('toggle');
		            Swal.fire({
		              type: 'error',
		              title: 'Woops...',
		              text: response.message,
		              // footer: '<a href>Why do I have this issue?</a>'
		            }).then((result)=>{
		            	$('#modal_').modal('show');
			            $('#btn_Save').text('Save');
			            $('#btn_Save').attr('disabled',false);
		            });
		          }
		        }
		      });
		});

		// Melakukan Reload Saat modal Close
		$('.close').click(function() {
        	location.reload();
        });

		// Find Data
        function GetData(id) {
			// console.log(id);
			var where_field = 'id';
        	var where_value = id;
        	var table = 'transaksi';
			$.ajax({
	          type: "post",
	          url: "<?=base_url()?>Apps/",
	          data: {'id':id},
	          dataType: "json",
	          success: function (response) {
          		$.each(response.data,function (k,v) {
		            $('#id').val(v.id);
					$('#formtype').val("edit");

					$('#modal_').modal('show');
		          });
	          }
	        });
		}

		function bindGrid(data) {
			$("#gridContainer").dxDataGrid({
				allowColumnResizing: true,
		        dataSource: data,
		        keyExpr: "NoAgenda",
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
		        searchPanel: {
		            visible: true,
		            width: 240,
		            placeholder: "Search..."
		        },
		        export: {
		            enabled: true,
		            fileName: "Daftar Surat Masuk"
		        },
		        onExporting: function(e) {
			      var workbook = new ExcelJS.Workbook();
			      var worksheet = workbook.addWorksheet('Surat Keluar');
			      
			      DevExpress.excelExporter.exportDataGrid({
			        component: e.component,
			        worksheet: worksheet,
			        topLeftCell: { row: 4, column: 1 }
			      }).then(function(cellRange) {
			        // header
			        var headerRow = worksheet.getRow(2);
			        headerRow.height = 30;
			        worksheet.mergeCells(2, 1, 2, 8);

			        headerRow.getCell(1).value = 'Nama Instansi disini';
			        headerRow.getCell(1).font = { name: 'Segoe UI Light', size: 22 };
			        headerRow.getCell(1).alignment = { horizontal: 'center' };
			        

			        var headerRow = worksheet.getRow(3);
			        headerRow.height = 30;
			        worksheet.mergeCells(3, 1, 3, 8);

			        headerRow.getCell(1).value = 'Alamat dan deskripsi Instansi di sini';
			        headerRow.getCell(1).font = { name: 'Segoe UI Light', size: 18 };
			        headerRow.getCell(1).alignment = { horizontal: 'center' };

			        // footer
			        var footerRowIndex = cellRange.to.row + 2;
			        var footerRow = worksheet.getRow(footerRowIndex);
			        worksheet.mergeCells(footerRowIndex, 1, footerRowIndex, 8);
			        
			        footerRow.getCell(1).value = '';
			        footerRow.getCell(1).font = { color: { argb: 'BFBFBF' }, italic: true };
			        footerRow.getCell(1).alignment = { horizontal: 'right' };
			      }).then(function() {
			        workbook.xlsx.writeBuffer().then(function(buffer) {
			          saveAs(new Blob([buffer], { type: 'application/octet-stream' }), 'DaftarSuratKeluar.xlsx');
			        });
			      });
			      e.cancel = true;
			    },
		        columns: [
		        	{
		                dataField: "NoAgenda",
		                caption: "No. Agenda",
		                allowEditing:false,
		                visible : true
		            },
		            {
		                dataField: "NomorSurat",
		                caption: "Nomor Surat",
		                allowEditing:false,
		                visible : true
		            },
		            {
		                dataField: "AsalSurat",
		                caption: "Asal Surat",
		                allowEditing:false,
		                visible : true
		            },
		            {
		                dataField: "TanggalSurat",
		                caption: "Tanggal Surat",
		                allowEditing:false,
		                visible : true
		            },
		            {
		                dataField: "TglPelaksanaanSurat",
		                caption: "Tgl Pelaksanaan",
		                allowEditing:false,
		                visible : true
		            },
		            {
		                dataField: "NamaKlasifikasi",
		                caption: "Klasifikasi Surat",
		                allowEditing:false,
		                visible : true
		            },
		            {
		                dataField: "IsiSurat",
		                caption: "Isi Surat",
		                allowEditing:false,
		                visible : true
		            },
		            {
		                dataField: "WO",
		                caption: "Dimusnahkan",
		                allowEditing:false,
		                visible : true
		            },

		        ],
		        onEditingStart: function(e) {
		            GetData(e.data.id);
		        },
		        onInitNewRow: function(e) {
		            // logEvent("InitNewRow");
		            $('#modal_').modal('show');
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

					  	$.ajax({
					        type    :'post',
					        url     : '<?=base_url()?>Apps/',
					        data    : {'id':id,'formtype':'delete'},
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
			});
		}
	});
</script>