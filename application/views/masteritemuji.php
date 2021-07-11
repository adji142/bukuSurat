<?php
    require_once(APPPATH."views/parts/Header.php");
    require_once(APPPATH."views/parts/Sidebar.php");
    $active = 'daftarmesin';
?>
	<div id="content">
		<div id="content-header">
			<div id="breadcrumb"> <a href="<?php echo base_url(); ?>" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="current">Master Item Uji</a> </div>
		</div>
		<div class="container-fluid">
			<div class="row-fluid">
				<div class="widget-box">
					<div class="widget-title"> 
			            <h5>Master Item Uji</h5>
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
<!-- Modal -->
<div class="modal hide" id="modal_" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog-scrollable" role="document">
  	<div class="modal-content">
  		<div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel">
	        	<div id="title_modal">Tambah Item Uji
	        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			          <span aria-hidden="true">&times;</span>
			        </button>
	        	</div>
	        </h5>
	    </div>
	    <div class="modal-body">
	    	<form class="form-horizontal" enctype='application/json' id="post_">
	    		<div class="control-group">
	    			<label class="control-label">Nama Item</label>
	    			<div class="controls">
	    				<input type="text" name="NamaItem" id="NamaItem" required="" placeholder="Nama Pelanggan">
	    				<input type="hidden" name="id" id="id">
	    				<input type="hidden" name="formtype" id="formtype" value="add">
	    			</div>
	    		</div>
	    		<div class="control-group">
	    			<label class="control-label">Periode</label>
	    			<div class="controls">
	    				<input type="month" class="span3 m-wrap" name="Periode" id="Periode">
	    			</div>
	    		</div>
	    		<div class="control-group">
	    			<label class="control-label">Jumlah Pesanan</label>
	    			<div class="controls">
	    				<select id="JmlPesanan" name="JmlPesanan" class="span3">
		                  <option value="">-- Pilih Jumlah Pesanan --</option>
		                  <option value="Banyak">Banyak</option>
		                  <option value="Sedikit">Sedikit</option>
		                </select>
	    			</div>
	    		</div>
	            <div class="control-group">
	              <label class="control-label">Kesulitan :</label>
	                <div class="control-group">
		              <div class="controls">
		                <select id="Kesulitan" name="Kesulitan" class="span3">
		                  <option value="">-- Pilih Kesulitan --</option>
		                  <option value="Biasa">Biasa</option>
		                  <option value="Rumit">Rumit</option>
		                </select>
		              </div>
		            </div>
	            </div>
	            <div class="control-group">
	              <label class="control-label">Peralatan :</label>
	                <div class="control-group">
		              <div class="controls">
		                <select id="Peralatan" name="Peralatan" class="span3">
		                  <option value="">-- Pilih Peralatan --</option>
		                  <option value="Lengkap">Lengkap</option>
		                  <option value="Kurang">Kurang</option>
		                </select>
		              </div>
		            </div>
	            </div>
	            <div class="control-group">
	              <label class="control-label">Jumlah Pekerja :</label>
	                <div class="control-group">
		              <div class="controls">
		                <select id="JumlahPekerja" name="JumlahPekerja" class="span3">
		                  <option value="">-- Pilih Jumlah Pekerja --</option>
		                  <option value="Banyak">Banyak</option>
		                  <option value="Sedikit">Sedikit</option>
		                </select>
		              </div>
		            </div>
	            </div>
	            <div class="control-group">
	              <label class="control-label">Prediksi :</label>
	                <div class="control-group">
		              <div class="controls">
		                <select id="Prdiksi" name="Prdiksi" class="span3">
		                  <option value="">-- Pilih Prediksi --</option>
		                  <option value="Cepat">Cepat</option>
		                  <option value="Lama">Lama</option>
		                </select>
		              </div>
		            </div>
	            </div>
	            <div class="control-group">
	              <label class="control-label">Kategori Pelanggan :</label>
	                <div class="control-group">
		              <div class="controls">
		                <!-- <select id="jns" name="jns" class="span3">
		                  <option value="">-- Pilih Jenis --</option>
		                  <option value="DL">Data Latih</option>
		                  <option value="DU">Data Uji</option>
		                </select> -->
		                <input type="checkbox" name="DL" id="DL" value="DL"> Data Latih
		                <input type="checkbox" name="DU" id="DU" value="DU"> Data Uji
		              </div>
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
        $(document).ready(function () {
        	var where_field = '';
        	var where_value = '';
        	var table = 'mstr_pelanggan';

	        $.ajax({
	          type: "post",
	          url: "<?=base_url()?>Apps/FindData_XXX",
	          data: {where_field:where_field},
	          dataType: "json",
	          success: function (response) {
	          	bindGrid(response.data);
	          }
	        });
        });
        $('#post_').submit(function (e) {
        	$('#btn_Save').text('Tunggu Sebentar.....');
		    $('#btn_Save').attr('disabled',true);

		    e.preventDefault();
			var me = $(this);

			$.ajax({
		        type    :'post',
		        url     : '<?=base_url()?>Apps/appendItem',
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
        $('.close').click(function() {
        	location.reload();
        });
        $('#jns').change(function() {
        	
        });
		function GetData(id) {
			var where_field = 'id';
        	var where_value = id;
        	var table = 'mstr_itemuji';
			$.ajax({
	          type: "post",
	          url: "<?=base_url()?>Apps/FindData",
	          data: {where_field:where_field,where_value:where_value,table:table},
	          dataType: "json",
	          success: function (response) {
          		$.each(response.data,function (k,v) {
          			console.log(v.KelompokUsaha);
		            $('#NamaItem').val(v.NamaItem);
					$('#JmlPesanan').val(v.JmlPesanan).change();
					$('#Kesulitan').val(v.Kesulitan).change();
					$('#Peralatan').val(v.Peralatan).change();
					$('#JumlahPekerja').val(v.JumlahPekerja).change();
					$('#Prdiksi').val(v.Prdiksi).change();
					$('#Periode').val(v.Periode);
					console.log(v.DL);
					if (v.DU == null) {
						console.log(v.DU);
						$("#DU").prop( "checked", false);
					}
					else{
						$("#DU").prop( "checked", true);	
					}

					if (v.DL == null) {
						$("#DL").prop( "checked", false);	
					}
					else{
						$("#DL").prop( "checked", true);		
					}
					$('#id').val(v.id).change();
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
		            fileName: "Daftar Item"
		        },
		        columns: [
		            {
		                dataField: "id",
		                caption: "id",
		                allowEditing:false,
		                visible : true
		            },
		            {
		                dataField: "NamaItem",
		                caption: "Nama Item",
		                allowEditing:false
		            },
		            {
		                dataField: "JmlPesanan",
		                caption: "Jml Pesanan",
		                allowEditing:false
		            },
		            {
		                dataField: "Kesulitan",
		                caption: "Kesulitan",
		                allowEditing:false
		            },
		            {
		                dataField: "Peralatan",
		                caption: "Peralatan",
		                allowEditing:false
		            },
		            {
		                dataField: "JumlahPekerja",
		                caption: "Jumlah Pekerja",
		                allowEditing:false
		            },
		            {
		                dataField: "Prdiksi",
		                caption: "Prdiksi",
		                allowEditing:false
		            }
		            
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
					  	var table = 'mstr_itemuji';
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
	});
</script>
