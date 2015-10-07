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
	            	console.log(data);
		            if(data)
		            {
		            	location.reload(true);
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
		            	console.log(data);
			            if(data)
			            {
//			            	location.reload(true);
							window.location.href = "home.php";
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
	$consulta = "SELECT * FROM reservaciones where negocioid = $idnegocio and estatus = 'A'";
	$result=mysqli_query($db,$consulta);
	$resultado = Array();
    while ($tsArray =  mysqli_fetch_assoc($result))
    {
                $resultado[] = $tsArray;
    }

     $html .= "<table>";
	 $html .= "<tr>
	              <th>Nombre</th>
	              <th>Telefono</th>
	              <th>Correo</th>
	              <th width=10>No. Personas</th>
	              <th>Hora de Reservacion</th>
	              <th>Confirmar Reservacion</th>
	              <th>Cancelar Reservacion</th>
	            </tr>";
	foreach ($resultado as $value){
		$html .= "<tr>
		<td>".$value['nombre']." ".$value['apellidos']."</td>
		<td>".$value['telefono']."</td>
		<td>".$value['correo']."</td>
		<td>".$value['numPersonas']."</td>
		<td>".$value['hora_llegada']."</td>
		<td><input type='submit' class='button button-primary' value='Confirmar' id='confirma' name='".$value['reservacionID']."'/></td>
		<td><input type='button' class='button button-cancela' value='Cancelar' id='cancela' name='".$value['reservacionID']."'/></td>
		</tr>";
	}
    $html .= "</table>";
    /* liberar el conjunto de resultados */
    mysqli_free_result($result);
	/* cerrar la conexión */
	mysqli_close($db);
    echo $html;
?>
</div>
</body>
</html>