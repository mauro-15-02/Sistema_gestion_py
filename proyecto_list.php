<?php include'db_connect.php' ?>
<div class="col-lg-12">
	<div class="card card-outline card-success">
		<div class="card-header">
            <?php if($_SESSION['login_type'] != 3): ?>
			<div class="card-tools">
				<a class="btn btn-block btn-sm btn-default btn-flat border-primary" href="./index.php?page=new_project"><i class="fa fa-plus"></i> Agregar nuevo proyecto</a>
			</div>
            <?php endif; ?>
		</div>
		<div class="card-body">
			<table class="table tabe-hover table-condensed" id="list">
				<colgroup>
					<col width="5%">
					<col width="35%">
					<col width="15%">
					<col width="15%">
					<col width="20%">
					<col width="10%">
				</colgroup>
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th>Proyecto</th>
						<th>Fecha de Comienzo</th>
						<th>Fecha de Finalización</th>
						<th>Estado</th>
						<th>Acción</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$stat = array("Pendiente", "Empezado", "En progreso", "En espera", "Vencido", "Terminado");
					$where = "";
					if($_SESSION['login_type'] == 2){
						$where = " where manager_id = '{$_SESSION['login_id']}' ";
					}elseif($_SESSION['login_type'] == 3){
						$where = " where concat('[',REPLACE(usuario_ids,',','],['),']') LIKE '%[{$_SESSION['login_id']}]%' ";
					}
					$qry = $conn->query("SELECT * FROM proyecto_list $where order by nombre asc");
					while($row= $qry->fetch_assoc()):
						$trans = get_html_translation_table(HTML_ENTITIES,ENT_QUOTES);
						unset($trans["\""], $trans["<"], $trans[">"], $trans["<h2"]);
						$desc = strtr(html_entity_decode($row['descripcion']),$trans);
						$desc=str_replace(array("<li>","</li>"), array("",", "), $desc);

					 	$tprog = $conn->query("SELECT * FROM tarea_list where proyecto_id = {$row['id']}")->num_rows;
		                $cprog = $conn->query("SELECT * FROM tarea_list where proyecto_id = {$row['id']} and status = 3")->num_rows;
						$prog = $tprog > 0 ? ($cprog/$tprog) * 100 : 0;
		                $prog = $prog > 0 ?  number_format($prog,2) : $prog;
		                $prod = $conn->query("SELECT * FROM productividad_usuario where proyecto_id = {$row['id']}")->num_rows;
						if($row['status'] == 0 && strtotime(date('Y-m-d')) >= strtotime($row['fecha_de_inicio'])):
						if($prod  > 0  || $cprog > 0)
		                  $row['status'] = 2;
		                else
		                  $row['status'] = 1;
						elseif($row['status'] == 0 && strtotime(date('Y-m-d')) > strtotime($row['fin_fecha'])):
						$row['status'] = 4;
						endif;
					?>
					<tr>
						<th class="text-center"><?php echo $i++ ?></th>
						<td>
							<p><b><?php echo ucwords($row['nombre']) ?></b></p>
							<p class="truncate"><?php echo strip_tags($desc) ?></p>
						</td>
						<td><b><?php echo date("d-m-Y",strtotime($row['fecha_de_inicio'])) ?></b></td>
						<td><b><?php echo date("d-m-Y",strtotime($row['fin_fecha'])) ?></b></td>
						<td class="text-center">
							<?php
							  if($stat[$row['status']] =='Pendiente'){
							  	echo "<span class='badge badge-secondary'>{$stat[$row['status']]}</span>";
							  }elseif($stat[$row['status']] =='Empezado'){
							  	echo "<span class='badge badge-primary'>{$stat[$row['status']]}</span>";
							  }elseif($stat[$row['status']] =='En progreso'){
							  	echo "<span class='badge badge-info'>{$stat[$row['status']]}</span>";
							  }elseif($stat[$row['status']] =='En espera'){
							  	echo "<span class='badge badge-warning'>{$stat[$row['status']]}</span>";
							  }elseif($stat[$row['status']] =='Vencido'){
							  	echo "<span class='badge badge-danger'>{$stat[$row['status']]}</span>";
							  }elseif($stat[$row['status']] =='Terminado'){
							  	echo "<span class='badge badge-success'>{$stat[$row['status']]}</span>";
							  }
							?>
						</td>
						<td class="text-center">
							<button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
		                      Acción
		                    </button>
		                    <div class="dropdown-menu" style="">
		                      <a class="dropdown-item view_project" href="./index.php?page=view_project&id=<?php echo $row['id'] ?>" data-id="<?php echo $row['id'] ?>">Ver</a>
		                      <div class="dropdown-divider"></div>
		                      <?php if($_SESSION['login_type'] != 3): ?>
		                      <a class="dropdown-item" href="./index.php?page=edit_project&id=<?php echo $row['id'] ?>">Editar</a>
		                      <div class="dropdown-divider"></div>
		                      <a class="dropdown-item delete_project" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Eliminar</a>
		                  <?php endif; ?>
		                    </div>
						</td>
					</tr>	
				<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<style>
	table p{
		margin: unset !important;
	}
	table td{
		vertical-align: middle !important
	}
</style>
<script>
	$(document).ready(function(){
		$('#list').dataTable()
	
	$('.delete_project').click(function(){
	_conf("¿Estás seguro de eliminar el proyecto?","delete_project",[$(this).attr('data-id')])
	})
	})
	function delete_project($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_project',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Datos eliminados correctamente",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	}
</script>