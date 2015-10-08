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
  echo $row['hora_entrada'];
  $horaentra=$row['hora_entrada'];
  $horasale=mysqli_query($db ,"SELECT hora_salida FROM negocios where negocioID=$idnegocio");
  $row = mysqli_fetch_array($horasale);
  echo $row['hora_salida'];
  $horasale=$row['hora_salida'];
  $result = substr($horasale, 0, 2);
  echo $result;
  $resultado = Array();
  if($result<="06")
  {
    echo"dos dias perro";
    $consulta = "SELECT * FROM reservaciones where negocioid = $idnegocio and fecha_reserva = CURDATE() and hora_llegada >= '$horaentra' and estatus = 'A';";
    $consulta .= "SELECT * FROM reservaciones where negocioid = $idnegocio and fecha_reserva = DATE_ADD(CURDATE(),INTERVAL 01 DAY) and hora_llegada <= '$horasale' and estatus = 'A'";
    mysqli_multi_query($db,$consulta);
    $result = mysqli_store_result($db);
    if($result == null || $result == false)
    {
      echo "No hay reservaciones que mostrar";
    }
    else
    {
	
      echo "Por que entre";
	  //while ($row = mysqli_fetch_row($result)){
		//echo $row[0] ."\n".$row[1] ."\n".$row[2] ."\n".$row[3];
		
		
		
		//}
		do{
		while($row2=mysqli_fetch_assoc($result))
		{
		  echo $row2['nombre'] ."\n".$row2['apellidos'] ."\n".$row2['telefono'] ."\n".$row2['correo'];
		  
		 
		}
		mysqli_free_result($result);
		} while( mysqli_next_result($db));
		/*$row2 = mysqli_fetch_assoc($result);
		foreach ($row2 as value)
		{
			echo $value['nombre'] ."\n".$value['apellidos'] ."\n".$value['telefono'] ."\n".$value['correo'];
		
		
		}*/
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

  function muestraTablas($result) 
  {
    while ($tsArray =  mysqli_fetch_assoc($result))
      {
                $resultado[] = $tsArray;
      }
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
    foreach ($resultado as $value)
    {
      $html .= "<tr id='".$value['reservacionID']."'>
      <td>".$value['nombre']." ".$value['apellidos']."</td>
      <td>".$value['telefono']."</td>
      <td>".$value['correo']."</td>
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

  function muestraTablas2() 
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
    while($row2=mysqli_fetch_assoc($result))
    {
      $html .= "<tr id='".$value['reservacionID']."'>
      <td>".$value['nombre']." ".$value['apellidos']."</td>
      <td>".$value['telefono']."</td>
      <td>".$value['correo']."</td>
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
	
}
?>
</div>
</body>
</html>