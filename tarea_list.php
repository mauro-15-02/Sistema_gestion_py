<?php include'db_connect.php' ?>
<div class="col-lg-12">
	<div class="card card-outline card-success">
		<div class="card-header">
			<div class="card-tools">
				<a class="btn btn-block btn-sm btn-default btn-flat border-primary" href="./index.php?page=new_project"><i class="fa fa-plus"></i> Agregar nueva tarea</a>
			</div>
		</div>
		<div class="card-body">
			<table class="table tabe-hover table-condensed" id="list">
				<colgroup>
					<col width="5%">
					<col width="15%">
					<col width="20%">
					<col width="15%">
					<col width="15%">
					<col width="10%">
					<col width="10%">
					<col width="10%">
				</colgroup>
				<thead>
					<tr>
						<th class="text-center">N°</th>
						<th>Proyecto</th>
						<th>Tarea</th>
						<th>Fecha de Comienzo</th>
						<th>Fecha de Finalización</th>
						<th>Estado de proyecto</th>
						<th>Estado de tarea</th>
						<th>Acción</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$where = "";
					if($_SESSION['login_type'] == 2){
						$where = " where p.manager_id = '{$_SESSION['login_id']}' ";
					}elseif($_SESSION['login_type'] == 3){
						$where = " where concat('[',REPLACE(p.usuario_ids,',','],['),']') LIKE '%[{$_SESSION['login_id']}]%' ";
					}
					
					$stat = array("Pendiente", "Empezado", "En progreso", "En espera", "Vencido", "Terminado");
					$qry = $conn->query("SELECT t.*,p.nombre as pnombre,p.fecha_de_inicio,p.status as pstatus, p.fin_fecha,p.id as pid FROM tarea_list t inner join proyecto_list p on p.id = t.proyecto_id $where order by p.nombre asc");
					while($row= $qry->fetch_assoc()):
						$trans = get_html_translation_table(HTML_ENTITIES,ENT_QUOTES);
						unset($trans["\""], $trans["<"], $trans[">"], $trans["<h2"]);
						$desc = strtr(html_entity_decode($row['descripcion']),$trans);
						$desc=str_replace(array("<li>","</li>"), array("",", "), $desc);
						$tprog = $conn->query("SELECT * FROM tarea_list where proyecto_id = {$row['pid']}")->num_rows;
		                $cprog = $conn->query("SELECT * FROM tarea_list where proyecto_id = {$row['pid']} and status = 3")->num_rows;
						$prog = $tprog > 0 ? ($cprog/$tprog) * 100 : 0;
		                $prog = $prog > 0 ?  number_format($prog,2) : $prog;
		                $prod = $conn->query("SELECT * FROM productividad_usuario where proyecto_id = {$row['pid']}")->num_rows;
		                if($row['pstatus'] == 0 && strtotime(date('Y-m-d')) >= strtotime($row['fecha_de_inicio'])):
		                if($prod  > 0  || $cprog > 0)
		                  $row['pstatus'] = 2;
		                else
		                  $row['pstatus'] = 1;
		                elseif($row['pstatus'] == 0 && strtotime(date('Y-m-d')) > strtotime($row['fin_fecha'])):
		                $row['pstatus'] = 4;
		                endif;


					?>
					<tr>
						<td class="text-center"><?php echo $i++ ?></td>
						<td>
							<p><b><?php echo ucwords($row['pnombre']) ?></b></p>
						</td>
						<td>
							<p><b><?php echo ucwords($row['tarea']) ?></b></p>
							<p class="truncate"><?php echo strip_tags($desc) ?></p>
						</td>
						<td><b><?php echo date("d-m-Y",strtotime($row['fecha_de_inicio'])) ?></b></td>
						<td><b><?php echo date("d-m-Y",strtotime($row['fin_fecha'])) ?></b></td>
						<td class="text-center">
							<?php
							  if($stat[$row['pstatus']] =='Pendiente'){
							  	echo "<span class='badge badge-secondary'>{$stat[$row['pstatus']]}</span>";
							  }elseif($stat[$row['pstatus']] =='Empezado'){
							  	echo "<span class='badge badge-primary'>{$stat[$row['pstatus']]}</span>";
							  }elseif($stat[$row['pstatus']] =='En progreso'){
							  	echo "<span class='badge badge-info'>{$stat[$row['pstatus']]}</span>";
							  }elseif($stat[$row['pstatus']] =='En espera'){
							  	echo "<span class='badge badge-warning'>{$stat[$row['pstatus']]}</span>";
							  }elseif($stat[$row['pstatus']] =='Vencido'){
							  	echo "<span class='badge badge-danger'>{$stat[$row['pstatus']]}</span>";
							  }elseif($stat[$row['pstatus']] =='Terminado'){
							  	echo "<span class='badge badge-success'>{$stat[$row['pstatus']]}</span>";
							  }
							?>
						</td>
						<td>
                        	<?php 
                        	if($row['status'] == 1){
						  		echo "<span class='badge badge-secondary'>Pendiente</span>";
                        	}elseif($row['status'] == 2){
						  		echo "<span class='badge badge-primary'>En progreso</span>";
                        	}elseif($row['status'] == 3){
						  		echo "<span class='badge badge-success'>Terminado</span>";
                        	}
                        	?>
                        </td>
						<td class="text-center">
							<button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
		                      Acción
		                    </button>
			                    <div class="dropdown-menu" style="">
			                      <a class="dropdown-item new_productivity" data-pid = '<?php echo $row['pid'] ?>' data-tid = '<?php echo $row['id'] ?>'  data-tarea = '<?php echo ucwords($row['tarea']) ?>'  href="javascript:void(0)">Agregar Productividad</a>
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
	$('.new_productivity').click(function(){
		uni_modal("<i class='fa fa-plus'></i> Nuevo Progreso para: "+$(this).attr('data-tarea'),"manage_progress.php?pid="+$(this).attr('data-pid')+"&tid="+$(this).attr('data-tid'),'large')
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
					alert_toast("Datos eliminados con éxito",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	}
</script>