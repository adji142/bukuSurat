<?php
    require_once(APPPATH."views/parts/Header.php");
    require_once(APPPATH."views/parts/Sidebar.php");
    $active = 'daftarmesin';
?>
<div id="content">
	<div id="content-header">
		<div id="breadcrumb"> <a href="<?php echo base_url(); ?>" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="current">Klasifikasi Surat</a> </div>
	</div>
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="widget-box">
				<div class="widget-title"> 
		            <h5>Klasifikasi Surat</h5>
		        </div>
		        <div class="widget-content">
		        	<!-- <button type="button" class="btn btn-mini btn-info" data-toggle="modal" data-target="#modal_">
						  Tambah Pelayan
					</button> -->
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
        	var where_field = '';
        	var where_value = '';
        	var table = 'transaksi';

	        $.ajax({
	          type: "post",
	          url: "<?=base_url()?>Apps/GetKlasifikasi",
	          data: {'id':$('#id').val()},
	          dataType: "json",
	          success: function (response) {
	          	bindGrid(response.data);
	          }
	        });
        });

		// Eksekusi Insert / Update Ke Database
		$('#post_').submit(function (e) {
			$('#btn_Save').text('Tunggu Sebentar.....');
		    $('#btn_Save').attr('disabled',true);

		    e.preventDefault();
			var me = $(this);

			$.ajax({
		        type    :'post',
		        url     : '<?=base_url()?>Apps/CRUD_Klasifikasi',
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
	          url: "<?=base_url()?>Apps/GetKlasifikasi",
	          data: {'id':id},
	          dataType: "json",
	          success: function (response) {
          		$.each(response.data,function (k,v) {
		            $('#id').val(v.id);
		            $('#NamaKlasifikasi').val(v.NamaKlasifikasi);
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
		            allowAdding:true,
		            allowUpdating: true,
		            allowDeleting: true,
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
		            fileName: "Daftar Klasifikasi"
		        },
		        columns: [
		        	{
		                dataField: "id",
		                caption: "id",
		                allowEditing:false,
		                visible : true
		            },
		            {
		                dataField: "NamaKlasifikasi",
		                caption: "Nama Klasifikasi",
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
					        url     : '<?=base_url()?>Apps/CRUD_Klasifikasi',
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