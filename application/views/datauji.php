<?php
    require_once(APPPATH."views/parts/Header.php");
    require_once(APPPATH."views/parts/Sidebar.php");
    $active = 'daftarmesin';
?>
	<div id="content">
		<div id="content-header">
			<div id="breadcrumb"> <a href="<?php echo base_url(); ?>" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="current">Data Uji</a> </div>
		</div>
		<div class="container-fluid">
			<div class="row-fluid">
				<div class="widget-box">
					<div class="widget-title"> 
			            <h5>Data Uji</h5>
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
	        	<div id="title_modal">Perhitungan
	        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			          <span aria-hidden="true">&times;</span>
			        </button>
	        	</div>
	        </h5>
	    </div>
	    <div class="modal-body">
	    	<div id="perhitungan_show"></div>
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
	        $.ajax({
		        type    :'post',
		        url     : '<?=base_url()?>Apps/GetDataUji_final',
		        data    : {'uuid':''},
		        dataType: 'json',
		        success : function (response) {
		          if(response.success == true){
		            bindGrid(response.data);
		          }
		        }
		      });
        });
        $('.close').click(function() {
        	location.reload();
        });
		function GetData(data) {
			$('#perhitungan_show').html(data);
			$('#modal_').modal('show');
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
		            enabled: false
		        },
		        editing: {
		            mode: "row",
		        },
		        searchPanel: {
		            visible: true,
		            width: 240,
		            placeholder: "Search..."
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
		            {
		                dataField: "Perhitungan",
		                caption: "Perhitungan",
		                allowEditing:false,
		                visible : false
		            },
		            {
		                type: "buttons",
		                width: 110,
		                buttons: [{
		                    text: "Perhitungan",
		                    onClick: function(e) {
		                        // alert(e.data.Perhitungan);
		                        // console.log();
		                        GetData(e.row.data.Perhitungan);
		                    }
		                }]
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
