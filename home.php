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
<h1>Reservaciones de Hoy</h1>
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
  
  if ($idnegocio == 1) {
    $sql= "SELECT * FROM reservaciones where negocioid = $idnegocio and estatus = 'A' order by hora_llegada";
    $normal = mysqli_query($db,$sql); 
    muestraTablas2($normal);
  }
  else
  {
  	$consulta1 = "SELECT * FROM reservaciones where negocioid = $idnegocio and fecha_reserva = CURDATE() and hora_llegada >= '$horaentra' and estatus = 'A' order by hora_llegada;";
    $consulta2 = "SELECT * FROM reservaciones where negocioid = $idnegocio and fecha_reserva = DATE_ADD(CURDATE(),INTERVAL 01 DAY) and hora_llegada <= '$horasale' and estatus = 'A' order by hora_llegada";
      
  	
  	$query1=mysqli_query($db,$consulta1);
  	$query2=mysqli_query($db,$consulta2);
  	
  	if (mysqli_num_rows($query1) == 0 && mysqli_num_rows($query2) == 0)
  	{
  		echo "<p>No hay reservaciones que mostrar</p>";
    }
  	else
    {  
      if($result<="06")
      {
    		$sql= "SELECT * FROM reservaciones where negocioid = $idnegocio and fecha_reserva = CURDATE() and hora_llegada >= '$horaentra' and estatus = 'A' order by hora_llegada;";
    		$sql .=  "SELECT * FROM reservaciones where negocioid = $idnegocio and fecha_reserva = DATE_ADD(CURDATE(),INTERVAL 01 DAY) and hora_llegada <= '$horasale' and estatus = 'A' order by hora_llegada";
    		$multi = mysqli_multi_query($db,$sql);
    	    
    	 	muestraTablas($db);
    	}
    	
    	else
      {
    		$sql= "SELECT * FROM reservaciones where negocioid = $idnegocio and fecha_reserva = CURDATE() and( hora_llegada >= '$horaentra' and hora_llegada <= '$horasale') and estatus = 'A' order by hora_llegada";		
    		$normal = mysqli_query($db,$sql);	
    		muestraTablas2($normal);
  		}
  	}
  }

  function muestraTablas($db) 
  {
    $html = "<table>";
    $html .= "<tr>
                <th>Nombre</th>
                <th>Telefono</th>
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

  function muestraTablas2($result) 

  {
    while ($tsArray =  mysqli_fetch_assoc($result))
      {
                $resultado[] = $tsArray;
      }

    $html = "<table>";
    $html .= "<tr>
                <th>Nombre</th>
                <th>Telefono</th>
                <th>No. Personas</th>
                <th>Dia</th>
                <th>Hora</th>
                <th>Confirmar</th>
                <th>Cancelar</th>
              </tr>";
    foreach ($resultado as $value)
    {
      $html .= "<tr id='".$value['reservacionID']."'>
      <td>".$value['nombre']." ".$value['apellidos']."</td>
      <td>".$value['telefono']."</td>
      <td>".$value['numPersonas']."</td>
      <td>".$value['fecha_reserva']."</td>
      <td>".$value['hora_llegada']."</td>
      <td><input type='submit' class='button button-primary' value='Confirmar' id='confirma' name='".$value['reservacionID']."'/></td>
      <td><input type='button' class='button button-cancela' value='Cancelar' id='cancela' name='".$value['reservacionID']."'/></td>
      </tr>";
    }
    $html .= "</table>";
    mysqli_free_result($result);
    mysqli_close($db);
    echo $html;
  }
?>
</div>
</body>
</html>