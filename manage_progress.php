<?php 
include 'db_connect.php';
if(isset($_GET['id'])){
	$qry = $conn->query("SELECT * FROM productividad_usuario where id = ".$_GET['id'])->fetch_array();
	foreach($qry as $k => $v){
		$$k = $v;
	}
}
?>
<div class="container-fluid">
	<form action="" id="manage-progress">
		<input type="hidden" nombre="id" value="<?php echo isset($id) ? $id : '' ?>">
		<input type="hidden" nombre="proyecto_id" value="<?php echo isset($_GET['pid']) ? $_GET['pid'] : '' ?>">
		<div class="col-lg-12">
			<div class="row">
				<div class="col-md-5">
					<?php if(!isset($_GET['tid'])): ?>
					 <div class="form-group">
		              <label for="" class="control-label">Tarea</label>
		              <select class="form-control form-control-sm select2" nombre="tarea_id">
		              	<option></option>
		              	<?php 
		              	$tareas = $conn->query("SELECT * FROM tarea_list where proyecto_id = {$_GET['pid']} order by tarea asc ");
		              	while($row= $tareas->fetch_assoc()):
		              	?>
		              	<option value="<?php echo $row['id'] ?>" <?php echo isset($tarea_id) && $tarea_id == $row['id'] ? "selected" : '' ?>><?php echo ucwords($row['tarea']) ?></option>
		              	<?php endwhile; ?>
		              </select>
		            </div>
		            <?php else: ?>
					<input type="hidden" nombre="tarea_id" value="<?php echo isset($_GET['tid']) ? $_GET['tid'] : '' ?>">
		            <?php endif; ?>
					<div class="form-group">
						<label for="">Tema</label>
						<input type="text" class="form-control form-control-sm" nombre="tema" value="<?php echo isset($tema) ? $tema : '' ?>" required>
					</div>
					<div class="form-group">
						<label for="">Fecha</label>
						<input type="fecha" class="form-control form-control-sm" nombre="fecha" value="<?php echo isset($fecha) ? fecha("d-m-Y",strtotime($fecha)) : '' ?>" required>
					</div>
					<div class="form-group">
						<label for="">Tiempo de comienzo</label>
						<input type="time" class="form-control form-control-sm" nombre="hora_de_inicio" value="<?php echo isset($hora_de_inicio) ? fecha("H:i",strtotime("2022-01-01 ".$hora_de_inicio)) : '' ?>" required>
					</div>
					<div class="form-group">
						<label for="">Tiempo de finalización</label>
						<input type="time" class="form-control form-control-sm" nombre="fin_tiempo" value="<?php echo isset($fin_tiempo) ? fecha("H:i",strtotime("2022-01-01 ".$fin_tiempo)) : '' ?>" required>
					</div>
				</div>
				<div class="col-md-7">
					<div class="form-group">
						<label for="">Comentarios/Descripción del Progreso</label>
						<textarea nombre="comentario" id="" cols="30" rows="10" class="summernote form-control" required="">
							<?php echo isset($comentario) ? $comentario : '' ?>
						</textarea>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>

<script>
	$(document).ready(function(){
	$('.summernote').summernote({
        height: 200,
        toolbar: [
            [ 'style', [ 'style' ] ],
            [ 'font', [ 'bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear'] ],
            [ 'fontnombre', [ 'fontnombre' ] ],
            [ 'fontsize', [ 'fontsize' ] ],
            [ 'color', [ 'color' ] ],
            [ 'para', [ 'ol', 'ul', 'paragraph', 'height' ] ],
            [ 'table', [ 'table' ] ],
            [ 'view', [ 'undo', 'redo', 'fullscreen', 'codeview', 'help' ] ]
        ]
    })
     $('.select2').select2({
	    placeholder:"Please select here",
	    width: "100%"
	  });
     })
    $('#manage-progress').submit(function(e){
    	e.preventDefault()
    	start_load()
    	$.ajax({
    		url:'ajax.php?action=save_progress',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp == 1){
					alert_toast('Data successfully saved',"success");
					setTimeout(function(){
						location.reload()
					},1500)
				}
			}
    	})
    })
</script>