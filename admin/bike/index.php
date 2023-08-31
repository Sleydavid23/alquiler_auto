<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>
<div class="card card-outline card-primary">
	<div class="card-header">
		<h3 class="card-title">Lista de Automóviles</h3>
		<div class="card-tools">
			<a href="?page=bike/manage_bike" class="btn btn-flat btn-primary"><span class="fas fa-plus"></span>  Crear Nuevo</a>
		</div>
	</div>
	<div class="card-body">
		<div class="container-fluid">
        <div class="container-fluid">
			<table class="table table-bordered table-striped">
				<colgroup>
					<col width="5%">
					<col width="15%">
					<col width="25%">
					<col width="30%">
					<col width="10%">
					<col width="15%">
				</colgroup>
				<thead>
					<tr class="bg-navy disabled">
						<th>#</th>
						<th>Fecha Creación</th>
						<th>Categoría</th>
						<th>Información Vehículo</th>
						<th>Cantidad</th>
						<th>Estado</th>
						<th>Acción</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$i = 1;
						$qry = $conn->query("SELECT b.*,c.category, bb.name as brand from `bike_list` b inner join categories c on b.category_id = c.id inner join brand_list bb on b.brand_id = bb.id order by b.bike_model asc ");
						while($row = $qry->fetch_assoc()):
							foreach($row as $k=> $v){
								$row[$k] = trim(stripslashes($v));
							}
                            $row['description'] = strip_tags(stripslashes(html_entity_decode($row['description'])));
					?>
						<tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td><?php echo date("Y-m-d H:i",strtotime($row['date_created'])) ?></td>
							<td><?php echo $row['category'] ?></td>
							<td class="lh-1" >
								<small><span class="text-muted">Marca:</span> <?php echo $row['brand'] ?></small><br>
								<small><span class="text-muted">Modelo:</span> <?php echo $row['bike_model'] ?></small>
							</td>
							<td class="text-end"><?php echo number_format($row['quantity']) ?></td>
							<td class="text-center">
                                <?php if($row['status'] == 1): ?>
                                    <span class="badge badge-success">Activo</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">Inactivo</span>
                                <?php endif; ?>
                            </td>
							<td align="center">
								 <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
				                  		Acción
				                    <span class="sr-only">Toggle Dropdown</span>
				                  </button>
				                  <div class="dropdown-menu" role="menu">
								  	<a class="dropdown-item" href="<?php echo base_url ?>?p=view_bike&id=<?php echo md5($row['id']) ?>" target="_blank"><span class="fa fa-eye text-dark"></span> Ver</a>
				                    <div class="dropdown-divider"></div>
				                    <a class="dropdown-item" href="?page=bike/manage_bike&id=<?php echo $row['id'] ?>"><span class="fa fa-edit text-primary"></span> Editar</a>
				                    <div class="dropdown-divider"></div>
				                    <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span> Eliminar</a>
				                  </div>
							</td>
						</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		$('.delete_data').click(function(){
			_conf("Estás segur@ de eliminar este vehículo?","delete_bike",[$(this).attr('data-id')])
		})
		$('.table td,.table th').addClass('px-2 py-1')
		$('.table').dataTable({
			columnDefs: [
				{ targets: [5, 6], orderable: false}
			],
			initComplete:function(settings, json){
				$('.table td,.table th').addClass('px-2 py-1')
			}
		});
	})
	function delete_bike($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_bike",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("Ocurrió un error",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.reload();
				}else{
					alert_toast("Ocurrió un error",'error');
					end_loader();
				}
			}
		})
	}
</script>