<?php
	include("db.php");
	if(isSet($_POST['confirma']))
	{

		$idReservacion=mysqli_real_escape_string($db,$_POST['confirma']); 
		$consulta = "update reservaciones set estatus = 'C' where reservacionID = $idReservacion";
		$result=mysqli_query($db,$consulta);
		if (!$result) {
			die("Fallo en la insercion de registro en la Base de Datos: ". mysqli_error());
		}
		else
		{
			echo $idReservacion;
		}
	}

	if(isSet($_POST['cancela']))
	{

		$idReservacion=mysqli_real_escape_string($db,$_POST['cancela']); 
		$consulta = "update reservaciones set estatus = 'X' where reservacionID = $idReservacion";
		$result=mysqli_query($db,$consulta);
		if (!$result) {
			die("Fallo en la insercion de registro en la Base de Datos: ". mysqli_error());
		}
		else
		{
			echo $idReservacion;	
		}
	}

    /* liberar el conjunto de resultados */
    mysqli_free_result($result);
	/* cerrar la conexión */
	mysqli_close($db);
?>