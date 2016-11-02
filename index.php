<?php
session_start();
if(!empty($_SESSION))
{
header('Location: home.php');
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Bienvenido - Reserva+</title>
<link rel="stylesheet" href="css/login.css"/>
<script src="js/jquery.min.js"></script>
<script src="js/jquery.ui.shake.js"></script>
	<script>
			$(document).ready(function() {

			$('#login').click(function() {
  			var username=$("#username").val();
  			var password=$("#password").val();
  		    var dataString = 'username='+username+'&password='+password;
  			if($.trim(username).length>0 && $.trim(password).length>0) {
			

			$.ajax({
            type: "POST",
            url: "server/ajaxLogin.php",
            data: dataString,
            cache: false,
            beforeSend: function(){ $("#login").val('Conectando...');},
            success: function(data){
            	console.log(data);
            if(data)
            {
            	window.location.replace("home.php");
            }
            else
            {
             $('#box').shake();
			 $("#login").val('Entrar')
			 $("#error").html("<span style='color:#cc0000'>Error:</span> Usuario o contraseña incorrectos. ");
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
<h1>Bienvenido a Reserva+</h1>

<div id="box">
<form action="" method="post">
<label>Usuario</label>
<input type="text" name="username" class="input" autocomplete="off" id="username"/>
<label>Contraseña</label>
<input type="password" name="password" class="input" autocomplete="off" id="password"/><br/>
<input type="submit" class="button button-primary" value="Entrar" id="login"/>
<span class='msg'></span>

<div id="error">

</div>

</div>
</form>
</div>

</div>
</body>
</html>
