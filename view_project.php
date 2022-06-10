<?php
include 'db_connect.php';
$stat = array("Pendiente", "Empezado", "En progreso", "En espera", "Vencido", "Terminado");
$qry = $conn->query("SELECT * FROM proyecto_list where id = ".$_GET['id'])->fetch_array();
foreach($qry as $k => $v){
	$$k = $v;
}
$tprog = $conn->query("SELECT * FROM tarea_list where proyecto_id = {$id}")->num_rows;
$cprog = $conn->query("SELECT * FROM tarea_list where proyecto_id = {$id} and status = 3")->num_rows;
$prog = $tprog > 0 ? ($cprog/$tprog) * 100 : 0;
$prog = $prog > 0 ?  number_format($prog,2) : $prog;
$prod = $conn->query("SELECT * FROM productividad_usuario where proyecto_id = {$id}")->num_rows;
if($status == 0 && strtotime(date('Y-m-d')) >= strtotime($fecha_de_inicio)):
if($prod  > 0  || $cprog > 0)
  $status = 2;
else
  $status = 1;
elseif($status == 0 && strtotime(date('Y-m-d')) > strtotime($fin_fecha)):
$status = 4;
endif;
$manager = $conn->query("SELECT *,concat(primer_nombre,' ',apellido) as nombre FROM usuarios where id = $manager_id");
$manager = $manager->num_rows > 0 ? $manager->fetch_array() : array();
?>
<div class="col-lg-12">
	<div class="row">
		<div class="col-md-12">
			<div class="callout callout-info">
				<div class="col-md-12">
					<div class="row">
						<div class="col-sm-6">
							<dl>
								<dt><b class="border-bottom border-primary">Nombre del Proyecto</b></dt>
								<dd><?php echo ucwords($nombre) ?></dd>
								<dt><b class="border-bottom border-primary">Descripción</b></dt>
								<dd><?php echo html_entity_decode($descripcion) ?></dd>
							</dl>
						</div>
						<div class="col-md-6">
							<dl>
								<dt><b class="border-bottom border-primary">Fecha de comienzo</b></dt>
								<dd><?php echo date("d-m-Y",strtotime($fecha_de_inicio)) ?></dd>
							</dl>
							<dl>
								<dt><b class="border-bottom border-primary">Fecha de finalización</b></dt>
								<dd><?php echo date("d-m-Y",strtotime($fin_fecha)) ?></dd>
							</dl>
							<dl>
								<dt><b class="border-bottom border-primary">Estado</b></dt>
								<dd>
									<?php
									  if($stat[$status] =='Pendiente'){
									  	echo "<span class='badge badge-secondary'>{$stat[$status]}</span>";
									  }elseif($stat[$status] =='Empezado'){
									  	echo "<span class='badge badge-primary'>{$stat[$status]}</span>";
									  }elseif($stat[$status] =='En progreso'){
									  	echo "<span class='badge badge-info'>{$stat[$status]}</span>";
									  }elseif($stat[$status] =='En espera'){
									  	echo "<span class='badge badge-warning'>{$stat[$status]}</span>";
									  }elseif($stat[$status] =='Vencido'){
									  	echo "<span class='badge badge-danger'>{$stat[$status]}</span>";
									  }elseif($stat[$status] =='Terminado'){
									  	echo "<span class='badge badge-success'>{$stat[$status]}</span>";
									  }
									?>
								</dd>
							</dl>
							<dl>
								<dt><b class="border-bottom border-primary">Manager del proyecto</b></dt>
								<dd>
									<?php if(isset($manager['id'])) : ?>
									<div class="d-flex align-items-center mt-1">
										<img class="img-circle img-thumbnail p-0 shadow-sm border-info img-sm mr-3" src="assets/uploads/<?php echo $manager['avatar'] ?>" alt="Avatar">
										<b><?php echo ucwords($manager['nombre']) ?></b>
									</div>
									<?php else: ?>
										<small><i>Manager eliminado de la base de datos</i></small>
									<?php endif; ?>
								</dd>
							</dl>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<div class="card card-outline card-primary">
				<div class="card-header">
					<span><b>Miembros:</b></span>
					<div class="card-tools">
						<!-- <button class="btn btn-primary bg-gradient-primary btn-sm" type="button" id="manage_team">Manage</button> -->
					</div>
				</div>
				<div class="card-body">
					<ul class="usuarios-list clearfix">
						<?php 
						if(!empty($usuario_ids)):
							$members = $conn->query("SELECT *,concat(primer_nombre,' ',apellido) as nombre FROM usuarios where id in ($usuario_ids) order by concat(primer_nombre,' ',apellido) asc");
							while($row=$members->fetch_assoc()):
						?>
								<li>
			                        <img src="assets/uploads/<?php echo $row['avatar'] ?>" alt="User Image">
			                        <a class="usuarios-list-nombre" href="javascript:void(0)"><?php echo ucwords($row['nombre']) ?></a>
			                        <!-- <span class="usuarios-list-fecha">Today</span> -->
		                    	</li>
						<?php 
							endwhile;
						endif;
						?>
					</ul>
				</div>
			</div>
		</div>
		<div class="col-md-8">
			<div class="card card-outline card-primary">
				<div class="card-header">
					<span><b>Lista de Tareas:</b></span>
					<?php if($_SESSION['login_type'] != 3): ?>
					<div class="card-tools">
						<button class="btn btn-primary bg-gradient-primary btn-sm" type="button" id="new_tarea"><i class="fa fa-plus"></i> Nueva Tarea</button>
					</div>
				<?php endif; ?>
				</div>
				<div class="card-body p-0">
					<div class="table-responsive">
					<table class="table table-condensed m-0 table-hover">
						<colgroup>
							<col width="5%">
							<col width="25%">
							<col width="30%">
							<col width="15%">
							<col width="15%">
						</colgroup>
						<thead>
							<th>N°</th>
							<th>Tarea</th>
							<th>Descripción</th>
							<th>Estado</th>
							<th>Acción</th>
						</thead>
						<tbody>
							<?php 
							$i = 1;
							$tareas = $conn->query("SELECT * FROM tarea_list where proyecto_id = {$id} order by tarea asc");
							while($row=$tareas->fetch_assoc()):
								$trans = get_html_translation_table(HTML_ENTITIES,ENT_QUOTES);
								unset($trans["\""], $trans["<"], $trans[">"], $trans["<h2"]);
								$desc = strtr(html_entity_decode($row['descripcion']),$trans);
								$desc=str_replace(array("<li>","</li>"), array("",", "), $desc);
							?>
								<tr>
			                        <td class="text-center"><?php echo $i++ ?></td>
			                        <td class=""><b><?php echo ucwords($row['tarea']) ?></b></td>
			                        <td class=""><p class="truncate"><?php echo strip_tags($desc) ?></p></td>
			                        <td>
			                        	<?php 
			                        	if($row['status'] == 1){
									  		echo "<span class='badge badge-secondary'>Pendiente</span>";
			                        	}elseif($row['status'] == 2){
									  		echo "<span class='badge badge-primary'>En progreso</span>";
			                        	}elseif($row['status'] == 3){
									  		echo "<span class='badge badge-success'>Terminada</span>";
			                        	}
			                        	?>
			                        </td>
			                        <td class="text-center">
										<button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
					                      Acción
					                    </button>
					                    <div class="dropdown-menu" style="">
					                      <a class="dropdown-item view_tarea" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"  data-tarea="<?php echo $row['tarea'] ?>">ver</a>
					                      <div class="dropdown-divider"></div>
					                      <?php if($_SESSION['login_type'] != 3): ?>
					                      <a class="dropdown-item edit_tarea" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"  data-tarea="<?php echo $row['tarea'] ?>">Editar</a>
					                      <div class="dropdown-divider"></div>
					                      <a class="dropdown-item delete_tarea" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Eliminar</a>
					                  <?php endif; ?>
					                    </div>
									</td>
		                    	</tr>
							<?php 
							endwhile;
							?>
						</tbody>
					</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<b>Progreso de los miembros:</b>
					<div class="card-tools">
						<button class="btn btn-primary bg-gradient-primary btn-sm" type="button" id="new_productivity"><i class="fa fa-plus"></i> Nueva Productividad</button>
					</div>
				</div>
				<div class="card-body">
					<?php 
					$progress = $conn->query("SELECT p.*,concat(u.primer_nombre,' ',u.apellido) as unombre,u.avatar,t.tarea FROM productividad_usuario p inner join usuarios u on u.id = p.usuario_id inner join tarea_list t on t.id = p.tarea_id where p.proyecto_id = $id order by unix_timestamp(p.fecha_de_creacion) desc ");
					while($row = $progress->fetch_assoc()):
					?>
						<div class="post">

		                      <div class="user-block">
		                      	<?php if($_SESSION['login_id'] == $row['usuario_id']): ?>
		                      	<span class="btn-group dropleft float-right">
								  <span class="btndropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;">
								    <i class="fa fa-ellipsis-v"></i>
								  </span>
								  <div class="dropdown-menu">
								  	<a class="dropdown-item manage_progress" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"  data-tarea="<?php echo $row['tarea'] ?>">Editar</a>
			                      	<div class="dropdown-divider"></div>
				                     <a class="dropdown-item delete_progress" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Eliminar</a>
								  </div>
								</span>
								<?php endif; ?>
		                        <img class="img-circle img-bordered-sm" src="assets/uploads/<?php echo $row['avatar'] ?>" alt="user image">
		                        <span class="usernombre">
		                          <a href="#"><?php echo ucwords($row['unombre']) ?>[ <?php echo ucwords($row['tarea']) ?> ]</a>
		                        </span>
		                        <span class="descripcion">
		                        	<span class="fa fa-calendar-day"></span>
		                        	<span><b><?php echo date('d-m-Y',strtotime($row['fecha'])) ?></b></span>
		                        	<span class="fa fa-user-clock"></span>
                      				<span>Comienzo: <b><?php echo date('h:i A',strtotime($row['fecha'].' '.$row['hora_de_inicio'])) ?></b></span>
		                        	<span> | </span>
                      				<span>Finalización: <b><?php echo date('h:i A',strtotime($row['fecha'].' '.$row['fin_tiempo'])) ?></b></span>
	                        	</span>

	                        	

		                      </div>
		                      <!-- /.user-block -->
		                      <div>
		                       <?php echo html_entity_decode($row['comentario']) ?>
		                      </div>

		                      <p>
		                        <!-- <a href="#" class="link-black text-sm"><i class="fas fa-link mr-1"></i> Demo File 1 v2</a> -->
		                      </p>
	                    </div>
	                    <div class="post clearfix"></div>
                    <?php endwhile; ?>
				</div>
			</div>
		</div>
	</div>
</div>
<style>
	.usuarios-list>li img {
	    border-radius: 50%;
	    height: 67px;
	    width: 67px;
	    object-fit: cover;
	}
	.usuarios-list>li {
		width: 33.33% !important
	}
	.truncate {
		-webkit-line-clamp:1 !important;
	}
</style>
<script>
	$('#new_tarea').click(function(){
		uni_modal("New tarea For <?php echo ucwords($nombre) ?>","manage_tarea.php?pid=<?php echo $id ?>","mid-large")
	})
	$('.edit_tarea').click(function(){
		uni_modal("Editar Tarea: "+$(this).attr('data-tarea'),"manage_tarea.php?pid=<?php echo $id ?>&id="+$(this).attr('data-id'),"mid-large")
	})
	$('.view_tarea').click(function(){
		uni_modal("Detalles de la Tarea","view_tarea.php?id="+$(this).attr('data-id'),"mid-large")
	})
	$('#new_productivity').click(function(){
		uni_modal("<i class='fa fa-plus'></i> Nuevo Progreso","manage_progress.php?pid=<?php echo $id ?>",'large')
	})
	$('.manage_progress').click(function(){
		uni_modal("<i class='fa fa-edit'></i> Editar Progreso","manage_progress.php?pid=<?php echo $id ?>&id="+$(this).attr('data-id'),'large')
	})
	$('.delete_progress').click(function(){
	_conf("¿Está seguro que desea eliminar este progreso?","delete_progress",[$(this).attr('data-id')])
	})
	function delete_progress($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_progress',
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