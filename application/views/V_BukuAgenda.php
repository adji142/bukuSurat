<?php
    require_once(APPPATH."views/parts/Header.php");
    require_once(APPPATH."views/parts/Sidebar.php");
    $active = 'daftarmesin';
?>

<div id="content">
	<div id="content-header">
		<div id="breadcrumb"> <a href="<?php echo base_url(); ?>" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="current">Buku Agenda</a> </div>
	</div>
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="widget-box">
				<div class="widget-title"> 
		            <h5>Buku Agenda</h5>
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
		        	<div id="title_modal">Data Agenda
		        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				          <span aria-hidden="true">&times;</span>
				        </button>
		        	</div>
		        </h5>
		    </div>

		    <div class="modal-body">
		    	<form class="form-horizontal" enctype='application/json' id="post_">
		    		<div class="control-group">
						<label class="control-label">Tanggal Pelaksanaan :</label>
						<div class="controls controls-row">
				          <input type="date" class="span2 m-wrap" id="TglAgenda" name="TglAgenda" required="" value="<?php echo date("Y-m-d"); ?>">
				          <input type="hidden" class="span3 m-wrap" id="id" name="id">
				          <input type="hidden" class="span3 m-wrap" id="formtype" name="formtype" value="add">
				        </div>
					</div>
					<div class="control-group">
						<label class="control-label">No. Surat :</label>
						<div class="controls controls-row">
				        	<input type="text" placeholder="No. Surat" class="span2 m-wrap" id="BaseRef" name="BaseRef" required="" onfocusout="SearchData(true)">
			          		<a class="btn btn-default m-wrap" id="Lookupsearch">...</a>
				        </div>
					</div>
					<div class="control-group">
						<label class="control-label">Nama Contact Person :</label>
						<div class="controls controls-row">
				        	<input type="text" placeholder="Nama Contact Person" class="span2 m-wrap" id="NamaCP" name="NamaCP" required="">
				        </div>
					</div>
					<div class="control-group">
						<label class="control-label">No. Contact Person :</label>
						<div class="controls controls-row">
				        	<input type="text" placeholder="No. Contact Person" class="span2 m-wrap" id="NoCP" name="NoCP" required="">
				        </div>
					</div>
					<div class="control-group">
						<label class="control-label">Lokasi Agenda :</label>
						<div class="controls controls-row">
				        	<input type="text" placeholder="Lokasi Agenda" class="span2 m-wrap" id="LokasiAgenda" name="LokasiAgenda" required="">
				        </div>
					</div>
					<div class="control-group">
						<label class="control-label">Keterangan :</label>
						<div class="controls controls-row">
				        	<input type="text" placeholder="Keterangan" class="span2 m-wrap" id="Keterangan" name="Keterangan" required="">
				        </div>
					</div>
		            <button class="btn btn-primary" id="btn_Save">Save</button>
		    	</form>
		    </div>

		</div>
	</div>
</div>

<div class="modal hide" id="ModalLookup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><div id="title_modal">Lookup Surat</div></h5>
      </div>
      <div class="modal-body">
        <!-- Input from hire -->
        <button class="btn btn-danger" id="rst_btn">Reset Filter Table</button>
        <table class="table table-bordered data-table" id="Lookup_list">
        	<thead>
              <tr>
              	<th>No.Agenda</th>
              	<th>No.Surat</th>
              	<th>Asal Surat</th>
              	<th>Tgl Surat</th>
              	<th>Jenis Surat</th>
              </tr>
            </thead>
            <tbody id="load_data">
              
            </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <!-- <button type="button" class="btn btn-primary" id="Save_Btn">Save changes</button> -->
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
	          url: "<?=base_url()?>Apps/GetAgendaSurat",
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
		        url     : '<?=base_url()?>Apps/CRUD_Agenda',
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

		// Selected Lookup
		$('#Lookup_list').on('click','tr',function () {
			var return_IDData = 1;
			var lookup_id = $(this).find("#lookup_id").text();
			var lookup_fullname = $(this).find("#lookup_fullname").text();
			
			$('#BaseRef').val($(this).find("#lookup_NoAgenda").text());

			$('#ModalLookup').modal('toggle');
		});

		$('#Lookupsearch').click(function () {
			SearchData(false);
		});
		$('#rst_btn').click(function () {
			$('#xsrc').val('');
			SearchData();
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
		            // allowDeleting: true,
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
		            fileName: "Daftar Agenda Surat"
		        },
		        columns: [
		        	{
		                dataField: "id",
		                caption: "id",
		                allowEditing:false,
		                visible : true
		            },
		            {
		                dataField: "TglAgenda",
		                caption: "Tgl. Agenda",
		                allowEditing:false,
		                visible : true
		            },
		            {
		                dataField: "CreatedBy",
		                caption: "Pelaksana",
		                allowEditing:false,
		                visible : true
		            },
		            {
		                dataField: "NoAgenda",
		                caption: "No. Agenda",
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
		                dataField: "JenisSurat",
		                caption: "Jenis Surat",
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
function SearchData() {
			var param = $('#BaseRef').val();
			$('#ModalLookup').modal('toggle');
			$.ajax({
	          type: "post",
	          url: "<?=base_url()?>Apps/GetAgendaLookup",
	          data: {param:param},
	          dataType: "json",
	          success: function (response) {
	          	if (response.success == true) {
	          		if (response.count == 1) {
	          			$.each(response.data,function (k,v) {
				            $('#BaseRef').val(v.NoAgenda);
				          });
	          		}
	          		else{
	          			var html = '';
				        var i;
				        for (i = 0; i < response.data.length; i++) {
				          html += '<tr>' +
				                  '<td id = "lookup_NoAgenda">' + response.data[i].NoAgenda+'</td>' +
				                  '<td>' + response.data[i].NomorSurat +'</td>'+
				                  '<td>' + response.data[i].AsalSurat +'</td>'+
				                  '<td>' + response.data[i].TanggalSurat +'</td>'+
				                  '<td>' + response.data[i].JenisSurat +'</td></tr>';
				        }
				        $('#load_data').html(html);
				        $('#ModalLookup').modal('show');
				        $('#ModalLookup').modal('toggle');
				        $('#ModalLookup').modal('show');
	          		}
	          	}
	          }
	        });
		}
</script>