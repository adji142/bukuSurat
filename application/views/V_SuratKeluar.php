<?php
    require_once(APPPATH."views/parts/Header.php");
    require_once(APPPATH."views/parts/Sidebar.php");
    $active = 'daftarmesin';
?>
<div id="content">
	<div id="content-header">
		<div id="breadcrumb"> <a href="<?php echo base_url(); ?>" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="current">Surat Keluar</a> </div>
	</div>
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="widget-box">
				<div class="widget-title"> 
		            <h5>Surat Keluar</h5>
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
		        	<div id="title_modal">Data Surat Masuk
		        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				          <span aria-hidden="true">&times;</span>
				        </button>
		        	</div>
		        </h5>
		    </div>

		    <div class="modal-body">
		    	<form class="form-horizontal" enctype='application/json' id="post_">
		    		<div class="control-group">
						<label class="control-label">No. Agenda :</label>
						<div class="controls controls-row">
				          <input type="text" class="span2 m-wrap" id="NoAgenda" name="NoAgenda" required="" placeholder="No. Agenda" >
				          <input type="hidden" class="span3 m-wrap" id="formtype" name="formtype" value="add">
				        </div>
					</div>
					<div class="control-group">
						<label class="control-label">No. Agenda :</label>
						<div class="controls controls-row">
				          <select id="KodeKlasifikasiSurat" name="KodeKlasifikasiSurat" class="span2 m-wrap">
				          	<option value="">Pilih Klasifikasi</option>
				          	<?php
				          		$rs = $this->db->query("SELECT * FROM tklasifikasi");
				          		foreach ($rs->result() as $key) {
				          			echo "<option value='".$key->id."'>".$key->NamaKlasifikasi."</option>";
				          		}
				          	?>
				          </select>
				        </div>
					</div>
					<div class="control-group">
						<label class="control-label">Tujuan Surat :</label>
						<div class="controls controls-row">
				          <input type="text" class="span2 m-wrap" id="AsalSurat" name="AsalSurat" required="" placeholder="Asal Surat">
				        </div>
					</div>
					<div class="control-group">
						<label class="control-label">Tanggal Surat :</label>
						<div class="controls controls-row">
				          <input type="date" class="span2 m-wrap" id="TanggalSurat" name="TanggalSurat" required="" placeholder="Tanggal Surat">
				        </div>
					</div>
					<div class="control-group">
						<label class="control-label">Nomor Surat :</label>
						<div class="controls controls-row">
				          <input type="text" class="span2 m-wrap" id="NomorSurat" name="NomorSurat" required="" placeholder="Nomor Surat">
				        </div>
					</div>
					<div class="control-group">
						<label class="control-label">Perihal dan Isi Surat :</label>
						<div class="controls controls-row">
				          <input type="text" class="span2 m-wrap" id="IsiSurat" name="IsiSurat" required="" placeholder="Isi Surat">
				        </div>
					</div>
					<div class="control-group">
						<label class="control-label">Attachment :</label>
						<div class="controls controls-row">
					        <input type="file" id="Attachment" name="Attachment" accept=".png,.jpg,.doc,.docx,.pdf" />
					        <!-- <img src="" id="profile-img-tag" width="200" /> -->
					              <!-- <textarea id="picture_base64" name="picture_base64"></textarea> -->
					        <textarea id="picture_base64" name="picture_base64" style="display: none;"></textarea>
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
	var _URL = window.URL || window.webkitURL;
    var _URLePub = window.URL || window.webkitURL;
	$(function () {
		// Get Data From Database
		$(document).ready(function () {
			const d = new Date();
			var year = d.getFullYear();
			var mont = d.getMonth() + 1;
			var fixmonth = '';
			console.log(mont.length);
			if (mont.toString().length == 1) {
				fixmonth = '0'+mont.toString();
			}
			else{
				fixmonth = mont;
			}

			

        	var where_field = '';
        	var where_value = '';
        	var table = 'transaksi';

	        $.ajax({
	          type: "post",
	          url: "<?=base_url()?>Apps/GetSuratKeluar",
	          data: {'NoAgenda':$('#NoAgenda').val()},
	          dataType: "json",
	          success: function (response) {
	          	bindGrid(response.data);
	          }
	        });

	        $.ajax({
	          type: "post",
	          url: "<?=base_url()?>Apps/Getindex",
	          data: {'Kolom':'NoAgenda','Table':'datasurat','Prefix':'K-'+year.toString()+fixmonth.toString()},
	          dataType: "json",
	          success: function (response) {
	          	// bindGrid(response.data);
	          	// $('#NoAgenda').val(response.nomor);
	          }
	        });
        });
		// Prosess Attachment
		$("#Attachment").change(function(){
	      var file = $(this)[0].files[0];
	      img = new Image();
	      img.src = _URL.createObjectURL(file);
	      var imgwidth = 0;
	      var imgheight = 0;
	      img.onload = function () {
	        imgwidth = this.width;
	        imgheight = this.height;
	        $('#width').val(imgwidth);
	        $('#height').val(imgheight);
	      }
	      readURL(this);
	      encodeImagetoBase64(this);
	      // alert("Current width=" + imgwidth + ", " + "Original height=" + imgheight);
	    });

	    function readURL(input) {
	      if (input.files && input.files[0]) {
	        var reader = new FileReader();
	          
	        reader.onload = function (e) {
	            $('#profile-img-tag').attr('src', e.target.result);
	        }
	        reader.readAsDataURL(input.files[0]);
	      }
	    }
	    function encodeImagetoBase64(element) {
	      $('#picture_base64').val('');
	        var file = element.files[0];
	        var reader = new FileReader();
	        reader.onloadend = function() {
	          // $(".link").attr("href",reader.result);
	          // $(".link").text(reader.result);
	          $('#picture_base64').val(reader.result);
	        }
	        reader.readAsDataURL(file);
	    }
		// Eksekusi Insert / Update Ke Database
		$('#post_').submit(function (e) {
			$('#btn_Save').text('Tunggu Sebentar.....');
		    $('#btn_Save').attr('disabled',true);

		    var NoAgenda = $('#NoAgenda').val();
			var KodeKlasifikasiSurat = $('#KodeKlasifikasiSurat').val();
			var AsalSurat = $('#AsalSurat').val();
			var TanggalSurat = $('#TanggalSurat').val();
			var NomorSurat = $('#NomorSurat').val();
			var IsiSurat = $('#IsiSurat').val();

		    var picture = $('#Attachment').prop('files')[0];
		    var picture_base64 = $('#picture_base64').val();
		    var formtype = $('#formtype').val();

		    var form_data = new FormData(this);
		    e.preventDefault();
			// var me = $(this);

			$.ajax({
		        type    :'post',
		        url     : '<?=base_url()?>Apps/CRUD_SuratKeluar',
		        data    : form_data,
		        dataType: 'json',
		        processData: false,
          		contentType: false,
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
	          url: "<?=base_url()?>Apps/GetSuratKeluar",
	          data: {'NoAgenda':id},
	          dataType: "json",
	          success: function (response) {
          		$.each(response.data,function (k,v) {
          			$('#NoAgenda').val(v.NoAgenda);
					$('#KodeKlasifikasiSurat').val(v.KodeKlasifikasiSurat).change();
					$('#AsalSurat').val(v.AsalSurat);
					$('#TanggalSurat').val(v.TanggalSurat);
					$('#NomorSurat').val(v.NomorSurat);
					$('#IsiSurat').val(v.IsiSurat);

					$('#picture_base64').val(v.base64File);
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
		            allowAdding:true,
		            allowUpdating: true,
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
		        columns: [
		        	{
		                dataField: "NoAgenda",
		                caption: "No Agenda",
		                allowEditing:false,
		                visible : true
		            },
		            {
		                dataField: "NamaKlasifikasi",
		                caption: "Nama Klasifikasi",
		                allowEditing:false
		            },
		            {
		                dataField: "AsalSurat",
		                caption: "Tujuan Surat",
		                allowEditing:false
		            },
		            {
		                dataField: "TanggalSurat",
		                caption: "Tanggal Surat",
		                allowEditing:false
		            },
		            {
		                dataField: "NomorSurat",
		                caption: "Nomor Surat",
		                allowEditing:false
		            },
		            {
		                dataField: "IsiSurat",
		                caption: "Isi Surat",
		                allowEditing:false
		            },
		            {
		            	dataField: "FileItem",
		                caption : "View File",
		                allowEditing : false,
		                cellTemplate: function(cellElement, cellInfo) {
		                	var html = "";
		                	html += "<a target='_blank' href = '"+cellInfo.data.LinkFile+"'>View File</a>";
		                	cellElement.append(html);
		                }
		            },
		        ],
		        onEditingStart: function(e) {
		            GetData(e.data.NoAgenda);
		        },
		        onInitNewRow: function(e) {
		            // logEvent("InitNewRow");
		            $('#modal_').modal('show');
		        },
		        onRowRemoving: function(e) {
		        	id = e.data.NoAgenda;
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
					        url     : '<?=base_url()?>Apps/CRUD_SuratMasuk',
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