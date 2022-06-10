<?php if(!isset($conn)){ include 'db_connect.php'; } ?>

<div class="col-lg-12">
	<div class="card card-outline card-primary">
		<div class="card-body">
			<form action="" id="manage-project">

        <input type="hidden" nombre="id" value="<?php echo isset($id) ? $id : '' ?>">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label for="" class="control-label">Nombre</label>
					<input type="text" class="form-control form-control-sm" nombre="nombre" value="<?php echo isset($nombre) ? $nombre : '' ?>">
				</div>
			</div>
          	<div class="col-md-6">
				<div class="form-group">
					<label for="">Estado</label>
					<select nombre="status" id="status" class="custom-select custom-select-sm">
						<option value="0" <?php echo isset($status) && $status == 0 ? 'selected' : '' ?>>Pendiente</option>
						<option value="3" <?php echo isset($status) && $status == 3 ? 'selected' : '' ?>>En espera</option>
						<option value="5" <?php echo isset($status) && $status == 5 ? 'selected' : '' ?>>Terminado</option>
					</select>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
            <div class="form-group">
              <label for="" class="control-label">Fecha de Comienzo</label>
              <input type="fecha" class="form-control form-control-sm" autocomplete="off" nombre="fecha_de_inicio" value="<?php echo isset($fecha_de_inicio) ? fecha("Y-m-d",strtotime($fecha_de_inicio)) : '' ?>">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="" class="control-label">Fecha de finalización</label>
              <input type="fecha" class="form-control form-control-sm" autocomplete="off" nombre="fin_fecha" value="<?php echo isset($fin_fecha) ? fecha("Y-m-d",strtotime($fin_fecha)) : '' ?>">
            </div>
          </div>
		</div>
        <div class="row">
        	<?php if($_SESSION['login_type'] == 1 ): ?>
           <div class="col-md-6">
            <div class="form-group">
              <label for="" class="control-label">Manager del Proyecto</label>
              <select class="form-control form-control-sm select2" nombre="manager_id">
              	<option></option>
              	<?php 
              	$managers = $conn->query("SELECT *,concat(primer_nombre,' ',apellido) as nombre FROM usuarios where type = 2 order by concat(primer_nombre,' ',apellido) asc ");
              	while($row= $managers->fetch_assoc()):
              	?>
              	<option value="<?php echo $row['id'] ?>" <?php echo isset($manager_id) && $manager_id == $row['id'] ? "selected" : '' ?>><?php echo ucwords($row['nombre']) ?></option>
              	<?php endwhile; ?>
              </select>
            </div>
          </div>
      <?php else: ?>
      	<input type="hidden" nombre="manager_id" value="<?php echo $_SESSION['login_id'] ?>">
      <?php endif; ?>
          <div class="col-md-6">
            <div class="form-group">
              <label for="" class="control-label">Miembros del Proyecto</label>
              <select class="form-control form-control-sm select2" multiple="multiple" nombre="usuario_ids[]">
              	<option></option>
              	<?php 
              	$employees = $conn->query("SELECT *,concat(primer_nombre,' ',apellido) as nombre FROM usuarios where type = 3 order by concat(primer_nombre,' ',apellido) asc ");
              	while($row= $employees->fetch_assoc()):
              	?>
              	<option value="<?php echo $row['id'] ?>" <?php echo isset($usuario_ids) && in_array($row['id'],explode(',',$usuario_ids)) ? "selected" : '' ?>><?php echo ucwords($row['nombre']) ?></option>
              	<?php endwhile; ?>
              </select>
            </div>
          </div>
        </div>
		<div class="row">
			<div class="col-md-10">
				<div class="form-group">
					<label for="" class="control-label">Descripción</label>
					<textarea nombre="descripcion" id="" cols="30" rows="10" class="summernote form-control">
						<?php echo isset($descripcion) ? $descripcion : '' ?>
					</textarea>
				</div>
			</div>
		</div>
        </form>
    	</div>
    	<div class="card-footer border-top border-info">
    		<div class="d-flex w-100 justify-content-center align-items-center">
    			<button class="btn btn-flat  bg-gradient-primary mx-2" form="manage-project">Guardar</button>
    			<button class="btn btn-flat bg-gradient-secondary mx-2" type="button" onclick="location.href='index.php?page=proyecto_list'">Cancelar</button>
    		</div>
    	</div>
	</div>
</div>
<script>
	$('#manage-project').submit(function(e){
		e.preventDefault()
		start_load()
		$.ajax({
			url:'ajax.php?action=save_project',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp == 1){
					alert_toast('Datos guardados correctamente',"success");
					setTimeout(function(){
						location.href = 'index.php?page=proyecto_list'
					},2000)
				}
			}
		})
	})
</script>