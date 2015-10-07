<?php     

    include("db.php");

    $persons = $_POST['person'];
    $hour = $_POST['hour'];
    $minute = $_POST['min'];
    $date = $_POST['date'];
    $first_name = $_POST['fname'];
    $last_name = $_POST['lname'];
    $telephone = $_POST['phone'];
    $email_from = $_POST['mail'];
    $negocio = $_POST['negocio'];
    $email_subject = "Reservacion";
    $codeRP = $_POST['cod'];
    $meet = $hour.":".$minute.":00";

    $consulta = "SELECT email from negocios where negocioID = $negocio";
    $result = mysqli_query($db,$consulta);
    $datos = mysqli_fetch_array($result);
    $mailnegocio = $datos["email"];
    $consultaSQL = "INSERT INTO reservaciones VALUES (null,$negocio,'$first_name','$last_name','$telephone','$email_from','$codeRP',$persons,'$date','$meet','A')";
    $inserta = mysqli_query($db,$consultaSQL);
    if (!$inserta){
        die("Fallo en la insercion de registro en la Base de Datos: ". mysql_error());
    }
    else 
    {
      $email_message = file_get_contents("../negocios/".$negocio."/mailer.html");
      $email_message = str_replace('$first_name', $first_name, $email_message);
      $email_message = str_replace('$last_name', $last_name, $email_message);
      $email_message = str_replace('$telephone', $telephone, $email_message);
      $email_message = str_replace('$email_from', $email_from, $email_message);
      $email_message = str_replace('$date', $date, $email_message);
      $email_message = str_replace('$meet', $meet, $email_message);
      $email_message = str_replace('$persons', $persons, $email_message);
      $email_message = str_replace('$codeRP', $codeRP, $email_message);
      $email_message = preg_replace('/\\\\/','', $email_message);
      $headers = "From: ".$mailnegocio."\r\n";
      $headers .= "MIME-Version: 1.0\r\n";
      $headers .= "Content-Type: text/html; charset=utf-8\r\n";
      $headers .= "Content-Transfer-Encoding: 8bit\r\n";
      if(mail($email_from, $email_subject, $email_message, $headers))
      {
        $headers1 = "From: ".$email_from."\r\n";
        $headers1 .= "MIME-Version: 1.0\r\n";
        $headers1 .= "Content-Type: text/html; charset=utf-8\r\n";
        $headers1 .= "Content-Transfer-Encoding: 8bit\r\n";
        $mensaje = "<html lang='en'>
                    <head>
                    <meta charset ='utf-8'>
                    </head>
                    <body>
                    <h2>Datos de Reservación</h2>
                    <table width='100%' cellpadding='5' style='padding:20px; background-color:#ddd'>
                      <tbody>
                        <tr valign='bottom' style='height:40px; text-transform: uppercase;''>
                          <th>Nombre</th>
                          <th>Apellido</th>
                          <th>Telefono</th>
                          <th>Email</th>
                        </tr>
                        <tr>
                          <td>".$first_name."</td>
                          <td>".$last_name."</td>
                          <td>".$telephone."</td>
                          <td>".$email_from."</td>
                        </tr>
                        <tr><br></tr>
                        <tr valign='bottom' style=height:40px; text-transform: uppercase;'>
                          <th>Fecha de reserva</th>
                          <th>Horario</th>
                          <th>Personas</th>
                          <th>Nombre de RP</th>
                        </tr>
                        <tr>
                          <td>".$date."</td>
                          <td>".$meet."</td>
                          <td>".$persons."</td>
                          <td>".$codeRP."</td>
                        </tr>
                      </tbody>
                    </table>
                  </body>
                  </html>";
        if(mail($mailnegocio, $email_subject, $mensaje, $headers1))
        {
          header("Location: ../negocios/".$negocio."/fin.html");
        }
      }else 
      {   
        header("Location: ../negocios/".$negocio."/index.html");
      }
    }



 



 



      /*$email_message = "<html>



                        <head>



                        </head>

                        <body>

                        <table style='width: 100%; height: 100%;'>

                          <tbody>

                            <tr>

                              <td>Encabezado</td>

                            </tr>

                            <tr>

                              <td>

                                <table style='margin: 0 auto;'>

                                  <tbody>

                                    <tr>

                                      <td>Datos de Cliente</td>

                                      <td>&nbsp;</td>

                                    </tr>

                                    <tr>

                                      <td>Nombre: </td>

                                      <td>".$first_name."</td>

                                    </tr>

                                    <tr>

                                      <td>Apellidos: </td>

                                      <td>".$last_name."</td>

                                    </tr>

                                    <tr>

                                      <td>Email: </td>

                                      <td>".$email_from."</td>

                                    </tr>

                                    <tr>

                                      <td>Fecha Entrada: </td>

                                      <td>".$checkIN."</td>

                                    </tr>

                                    <tr>

                                      <td>Fecha Salida: </td>

                                      <td>".$checkOut."</td>

                                    </tr>

                                    <tr>

                                      <td>Adultos: </td>

                                      <td>".$adults."</td>

                                    </tr>

                                    <tr>

                                      <td>Ni&ntilde;os: </td>

                                      <td>".$child."</td>

                                    </tr>

                                  </tbody>

                                </table>

                              </td>

                            </tr>

                            <tr style='background-color: yellow;'>

                              <td>Pie Mail</td>

                            </tr>

                          </tbody>

                        </table>

                        <div style='background-color: yellow; width: 50px; height: 50px;'></div>

                        <div style='background-color: blue; width: 50px; height: 50px;'></div>

                        <div style='background-color: red; width: 50px; height: 50px;'></div>

                        </body>

                        </html>";*/



// create email headers




?>



