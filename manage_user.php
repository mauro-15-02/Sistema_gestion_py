<?php 
include('db_connect.php');
session_start();
if(isset($_GET['id'])){
$user = $conn->query("SELECT * FROM usuarios where id =".$_GET['id']);
foreach($user->fetch_array() as $k =>$v){
	$meta[$k] = $v;
}
}
?>
<div class="container-fluid">
	<div id="msg"></div>
	
	<form action="" id="manage-user">	
		<input type="hidden" nombre="id" value="<?php echo isset($meta['id']) ? $meta['id']: '' ?>">
		<div class="form-group">
			<label for="nombre">Nombre</label>
			<input type="text" nombre="primer_nombre" id="primer_nombre" class="form-control" value="<?php echo isset($meta['primer_nombre']) ? $meta['primer_nombre']: '' ?>" required>
		</div>
		<div class="form-group">
			<label for="nombre">Apellido</label>
			<input type="text" nombre="apellido" id="apellido" class="form-control" value="<?php echo isset($meta['apellido']) ? $meta['apellido']: '' ?>" required>
		</div>
		<div class="form-group">
			<label for="email">Email</label>
			<input type="text" nombre="email" id="email" class="form-control" value="<?php echo isset($meta['email']) ? $meta['email']: '' ?>" required  autocomplete="off">
		</div>
		<div class="form-group">
			<label for="password">Contraseña</label>
			<input type="password" nombre="password" id="password" class="form-control" value="" autocomplete="off">
			<small><i>Deja este campo en blanco si no desea cambiar la contraseña.</i></small>
		</div>
		<div class="form-group">
			<label for="" class="control-label">Avatar</label>
			<div class="custom-file">
              <input type="file" class="custom-file-input rounded-circle" id="customFile" nombre="img" onchange="displayImg(this,$(this))">
              <label class="custom-file-label" for="customFile">Elegir Archivo</label>
            </div>
		</div>
		<div class="form-group d-flex justify-content-center">
			<img src="<?php echo isset($meta['avatar']) ? 'assets/uploads/'.$meta['avatar'] :'' ?>" alt="" id="cimg" class="img-fluid img-thumbnail">
		</div>
		

	</form>
</div>
<style>
	img#cimg{
		height: 15vh;
		width: 15vh;
		object-fit: cover;
		border-radius: 100% 100%;
	}
</style>
<script>
	function displayImg(input,_this) {
	    if (input.files && input.files[0]) {
	        var reader = new FileReader();
	        reader.onload = function (e) {
	        	$('#cimg').attr('src', e.target.result);
	        }

	        reader.readAsDataURL(input.files[0]);
	    }
	}
	$('#manage-user').submit(function(e){
		e.preventDefault();
		start_load()
		$.ajax({
			url:'ajax.php?action=upfecha_user',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp ==1){
					alert_toast("Datos guardados correctamente",'success')
					setTimeout(function(){
						location.reload()
					},1500)
				}else{
					$('#msg').html('<div class="alert alert-danger">El usuario ya existe</div>')
					end_load()
				}
			}
		})
	})

</script>