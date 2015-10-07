<?php
session_start();
if(empty($_SESSION['negocio']))
{
header('Location: index.php');
}

?>

<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Inicio</title>
<link rel="stylesheet" href="css/login.css"/>
<script src="js/jquery.min.js"></script>
<script src="js/jquery.ui.shake.js"></script>
  <script>
      $(document).ready(function() {

      $('input:submit').click(function()
      {
      var name = $(this).attr("name");
      var result = confirm("Esta seguro de confirmar la Reservación");
      var dataString = "confirma="+name;      
      if(result == true)
      {
        $.ajax({
              type: "POST",
              url: "server/procesaReservacion.php",
              data: dataString,
              cache: false,
              success: function(data){
                if(data)
                {
                  document.getElementById(name).style.display = "none";
                  //location.reload();
                }
                else
                {
                  alert("No llego nada");
                }
              }
              });
      }
      return false;
      });

      $('input:button').click(function()
      {
        var name = $(this).attr("name");
        var result = confirm("Esta seguro de cancelar la Reservación");
        var dataString = "cancela="+name;     
        if(result == true)
        {
          $.ajax({
                type: "POST",
                url: "server/procesaReservacion.php",
                data: dataString,
                cache: false,
                success: function(data){
                  if(data)
                  {
                    document.getElementById(name).style.display = "none";
                  }
                  else
                  {
                    alert("No llego nada");
                  }
                }
                });
        }
        return false;
      });
      });
    </script>
</head>
<body>
<div id="main">
  <h1>Reservacioes de Hoy</h1>
  <a href="logout.php">Logout</a>
  <?php
  include("server/db.php");
  $idnegocio = $_SESSION['negocio'];
  $horaentra=mysqli_query($db ,"SELECT hora_entrada FROM negocios where negocioID=$idnegocio");
  $row = mysqli_fetch_array($horaentra);
  $horaentra=$row['hora_entrada'];
  $horasale=mysqli_query($db ,"SELECT hora_salida FROM negocios where negocioID=$idnegocio");
  $row = mysqli_fetch_array($horasale);
  $horasale=$row['hora_salida'];
  $result = substr($horasale, 0, 2);
  $resultado = Array();

  if($result<="06")
  {
    $consulta = "SELECT * FROM reservaciones where negocioid = $idnegocio and fecha_reserva between CURDATE() and DATE_ADD(CURDATE(),INTERVAL 01 DAY) and hora_llegada >= '$horaentra' and estatus = 'A';";
    $consulta .= "SELECT * FROM reservaciones where negocioid = $idnegocio and fecha_reserva between CURDATE() and DATE_ADD(CURDATE(),INTERVAL 01 DAY) and hora_llegada <= '$horasale' and estatus = 'A'";
    $multi = mysqli_multi_query($db,$consulta);
    $resulta = mysqli_store_result($db);
    if($resulta == null and $multi == false)
    {
      echo "<p>No hay datos que Mostrar</p>";
    }else
    {
      echo "<p>Hay algo que mostrar</p>";
      muestraTablas($resulta,$db);
    }
      
  }
  else
  {
    echo"un dia perro";
    $resultCo = mysqli_query($db,"SELECT * FROM reservaciones where negocioid=$idnegocio AND (fecha_reserva = CURDATE()) AND(hora_llegada BETWEEN '$horaentra' AND '$horasale')");
    if( mysqli_num_rows($resultCo) == 0)
    echo "No hay reservaciones que mostrar";
    else{muestraTablas($resultCo);}
  }

  function muestraTablas($resulta,$db) 
  {
    $html = "<table>";
    $html .= "<tr>
                <th>Nombre</th>
                <th>Telefono</th>
                <th>Correo</th>
                <th>No. Personas</th>
                <th>Dia</th>
                <th>Hora</th>
                <th>Confirmar</th>
                <th>Cancelar</th>
              </tr>";
    do{
        $resulta = mysqli_store_result($db);
        while ($row=mysqli_fetch_assoc($resulta))
        {
          $html .= "<tr id='".$row['reservacionID']."'>
          <td>".$row['nombre']." ".$row['apellidos']."</td>
          <td>".$row['telefono']."</td>
          <td>".$row['correo']."</td>
          <td>".$row['numPersonas']."</td>
          <td>".$row['fecha_reserva']."</td>
          <td>".$row['hora_llegada']."</td>
          <td><input type='submit' class='button button-primary' value='Confirmar' id='confirma' name='".$row['reservacionID']."'/></td>
          <td><input type='button' class='button button-cancela' value='Cancelar' id='cancela' name='".$row['reservacionID']."'/></td>
          </tr>";
        }
        mysqli_free_result($db);
    }while (mysqli_next_result($db));
    $html .= "</table>";
    mysqli_close($db);
    echo $html;
  }
  ?>
</div>
</body>
</html>