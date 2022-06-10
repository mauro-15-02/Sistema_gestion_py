<?php 
include 'db_connect.php';
if(isset($_GET['id'])){
	$qry = $conn->query("SELECT * FROM tarea_list where id = ".$_GET['id'])->fetch_array();
	foreach($qry as $k => $v){
		$$k = $v;
	}
}
?>
<div class="container-fluid">
	<form action="" id="manage-tarea">
		<input type="hidden" nombre="id" value="<?php echo isset($id) ? $id : '' ?>">
		<input type="hidden" nombre="proyecto_id" value="<?php echo isset($_GET['pid']) ? $_GET['pid'] : '' ?>">
		<div class="form-group">
			<label for="">Tarea</label>
			<input type="text" class="form-control form-control-sm" nombre="tarea" value="<?php echo isset($tarea) ? $tarea : '' ?>" required>
		</div>
		<div class="form-group">
			<label for="">Descripción</label>
			<textarea nombre="descripcion" id="" cols="30" rows="10" class="summernote form-control">
				<?php echo isset($descripcion) ? $descripcion : '' ?>
			</textarea>
		</div>
		<div class="form-group">
			<label for="">Estado</label>
			<select nombre="status" id="status" class="custom-select custom-select-sm">
				<option value="1" <?php echo isset($status) && $status == 1 ? 'selected' : '' ?>>Pendiente</option>
				<option value="2" <?php echo isset($status) && $status == 2 ? 'selected' : '' ?>>En Progreso</option>
				<option value="3" <?php echo isset($status) && $status == 3 ? 'selected' : '' ?>>Terminado</option>
			</select>
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
     })
    
    $('#manage-tarea').submit(function(e){
    	e.preventDefault()
    	start_load()
    	$.ajax({
    		url:'ajax.php?action=save_tarea',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp == 1){
					alert_toast('Datos guardados correctamente.',"success");
					setTimeout(function(){
						location.reload()
					},1500)
				}
			}
    	})
    })
</script>