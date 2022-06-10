<?php include('db_connect.php') ?>
<?php
$twhere = "";
if ($_SESSION['login_type'] != 1)
  $twhere = "  ";
?>
<!-- Info boxes -->
<div class="col-12">
  <div class="card">
    <div class="card-body">
      <h1>¡Bienvenido <?php echo $_SESSION['login_name']?>!</h1>
    </div>
  </div>
</div>
<hr>
<?php

$where = "";
if ($_SESSION['login_type'] == 2) {
  $where = " where manager_id = '{$_SESSION['login_id']}' ";
} elseif ($_SESSION['login_type'] == 3) {
  $where = " where concat('[',REPLACE(usuario_ids,',','],['),']') LIKE '%[{$_SESSION['login_id']}]%' ";
}
$where2 = "";
if ($_SESSION['login_type'] == 2) {
  $where2 = " where p.manager_id = '{$_SESSION['login_id']}' ";
} elseif ($_SESSION['login_type'] == 3) {
  $where2 = " where concat('[',REPLACE(p.usuario_ids,',','],['),']') LIKE '%[{$_SESSION['login_id']}]%' ";
}
?>

<div class="row">
  <div class="col-md-8">
    <div class="card card-outline card-success">
      <div class="card-header">
        <b>Progreso de Proyectos</b>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table m-0 table-hover">
            <colgroup>
              <col width="5%">
              <col width="30%">
              <col width="35%">
              <col width="15%">
              <col width="15%">
            </colgroup>
            <thead>
              <th>N°</th>
              <th>Proyecto</th>
              <th>Progreso</th>
              <th>Estado</th>
              <th></th>
            </thead>
            <tbody>
              <?php
              $i = 1;
              $stat = array("Pendiente", "Empezado", "En progreso", "En espera", "Vencido", "Terminado");
              $where = "";
              if ($_SESSION['login_type'] == 2) {
                $where = " where manager_id = '{$_SESSION['login_id']}' ";
              } elseif ($_SESSION['login_type'] == 3) {
                $where = " where concat('[',REPLACE(usuario_ids,',','],['),']') LIKE '%[{$_SESSION['login_id']}]%' ";
              }
              $qry = $conn->query("SELECT * FROM proyecto_list $where order by nombre asc");
              while ($row = $qry->fetch_assoc()) :
                $prog = 0;
                $tprog = $conn->query("SELECT * FROM tarea_list where proyecto_id = {$row['id']}")->num_rows;
                $cprog = $conn->query("SELECT * FROM tarea_list where proyecto_id = {$row['id']} and status = 3")->num_rows;
                $prog = $tprog > 0 ? ($cprog / $tprog) * 100 : 0;
                $prog = $prog > 0 ?  number_format($prog, 2) : $prog;
                $prod = $conn->query("SELECT * FROM productividad_usuario where proyecto_id = {$row['id']}")->num_rows;
                if ($row['status'] == 0 && strtotime(date('Y-m-d')) >= strtotime($row['fecha_de_inicio'])) :
                  if ($prod  > 0  || $cprog > 0)
                    $row['status'] = 2;
                  else
                    $row['status'] = 1;
                elseif ($row['status'] == 0 && strtotime(date('Y-m-d')) > strtotime($row['fin_fecha'])) :
                  $row['status'] = 4;
                endif;
              ?>
                <tr>
                  <td>
                    <?php echo $i++ ?>
                  </td>
                  <td>
                    <a>
                      <?php echo ucwords($row['nombre']) ?>
                    </a>
                    <br>
                    <small>
                      Hasta: <?php echo date("Y-m-d", strtotime($row['fin_fecha'])) ?>
                    </small>
                  </td>
                  <td class="project_progress">
                    <div class="progress progress-sm">
                      <div class="progress-bar bg-green" role="progressbar" aria-valuenow="57" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $prog ?>%">
                      </div>
                    </div>
                    <small>
                      <?php echo $prog ?>% Completado
                    </small>
                  </td>
                  <td class="project-state">
                    <?php
                    if ($stat[$row['status']] == 'Pendiente') {
                      echo "<span class='badge badge-secondary'>{$stat[$row['status']]}</span>";
                    } elseif ($stat[$row['status']] == 'Empezado') {
                      echo "<span class='badge badge-primary'>{$stat[$row['status']]}</span>";
                    } elseif ($stat[$row['status']] == 'En progreso') {
                      echo "<span class='badge badge-info'>{$stat[$row['status']]}</span>";
                    } elseif ($stat[$row['status']] == 'En espera') {
                      echo "<span class='badge badge-warning'>{$stat[$row['status']]}</span>";
                    } elseif ($stat[$row['status']] == 'Vencido') {
                      echo "<span class='badge badge-danger'>{$stat[$row['status']]}</span>";
                    } elseif ($stat[$row['status']] == 'Terminado') {
                      echo "<span class='badge badge-success'>{$stat[$row['status']]}</span>";
                    }
                    ?>
                  </td>
                  <td>
                    <a class="btn btn-primary btn-sm" href="./index.php?page=view_project&id=<?php echo $row['id'] ?>">
                      <i class="fas fa-eye">
                      </i>
                      Visualizar
                    </a>
                  </td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="row">
      <div class="col-12 col-sm-6 col-md-12">
        <div class="small-box bg-light shadow-sm border">
          <div class="inner">
            <h3><?php echo $conn->query("SELECT * FROM proyecto_list $where")->num_rows; ?></h3>

            <p>Proyectos Totales</p>
          </div>
          <div class="icon">
            <i class="fa fa-layer-group"></i>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-12">
        <div class="small-box bg-light shadow-sm border">
          <div class="inner">
            <h3><?php echo $conn->query("SELECT t.*,p.nombre as pnombre,p.fecha_de_inicio,p.status as pstatus, p.fin_fecha,p.id as pid FROM tarea_list t inner join proyecto_list p on p.id = t.proyecto_id $where2")->num_rows; ?></h3>
            <p>Tareas Totales</p>
          </div>
          <div class="icon">
            <i class="fa fa-tareas"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>