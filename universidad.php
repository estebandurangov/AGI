#!/usr/bin/php -q
<?php
require('phpagi.php');
$agi=new AGI();
$agi->answer();
$agi->exec("AGI","googletts.agi,\"Bienvenido al sistema de matrícula de la universidad de Antioquia\",\"es\"");

while(true){
  $agi->exec("AGI","googletts.agi,\"por favor ingrese su número de documento y espere a que lo procesemos\",\"es\"");
  // Captura el número de documento ingresado por el usuario
  $numero_documento = $agi->get_data('silence/1', 5000, 10); // 30 segundos de espera, max 10 dígitos
  $numero_documento = trim($numero_documento['result']); // Se asegura de que no haya espacios
  if(empty($numero_documento)){
    continue;
  }
  // Repite el número de documento para confirmación
  $agi->exec("AGI", "googletts.agi,\"Usted ingresó el número de documento: $numero_documento\",\"es\"");
  $agi->exec("AGI", "googletts.agi,\"Por favor confirme oprimiendo la tecla uno para decir sí o cero para decir no\",\"es\"");
  
  $respuesta = $agi->get_data('silence/1', 1000, 1); // 5 segundos de espera, max 1 dígito
  $respuesta = strtolower(trim($respuesta['result'])); // Se asegura de que no haya espacios y lo convierte a minúsculas
  if ($respuesta == '1') {
    $agi->exec("AGI", "googletts.agi,\"Gracias. su número de documento ha sido confirmado. \",\"es\"");
    break;
  
  } else {
      $agi->exec("AGI", "googletts.agi,\"Lo siento. no ha sido posible procesar su numero\",\"es\"");
      // Aquí podrías reiniciar el proceso o volver a pedir el número de documento
  }
}
require('definiciones.inc');
$link=mysqli_connect(MAQUINA,USUARIO,CLAVE);
mysqli_select_db($link,DB);
$result=mysqli_query($link, "SELECT * FROM materias");
while(true){
    $agi->exec("AGI", "googletts.agi,\"Las materias disponibles para matrícula son las      siguientes\",\"es\"");
    while($row = mysqli_fetch_array($result)){
        $agi->exec("AGI", "googletts.agi,\"" . $row['nombre_materia'] ."codigo unico". $row['id']."\",\"es\"");
    }
    $result=mysqli_query($link, "SELECT * FROM materias");
    $agi->exec("AGI", "googletts.agi,\"Marque 1 para repetir este menú. 2 para pasar a matricular o 3 para colgar\",\"es\"");
    $respuesta = $agi->get_data('silence/1', 1000, 1); // 5 segundos de espera, max 1 dígito
    $respuesta = strtolower(trim($respuesta['result'])); // Se asegura de que no haya espacios y lo convierte a minúsculas
    if ($respuesta == '1') {
      continue;
    
    } 
    if($respuesta == '2') {
      break;
    }
    if($respuesta=='3'){
      $agi->hangup();
    }
}
$agi->exec("AGI", "googletts.agi,\"Iniciará el proceso de matrícula.\",\"es\"");
while (true) {
    // Pedimos el código de la materia o asterisco para finalizar
    $agi->exec("AGI", "googletts.agi,\"Ingrese el código de la materia a matricular u oprima asterisco para finalizar\",\"es\"");
    
    // Captura la respuesta del usuario
    $respuesta = $agi->get_data('silence/1', 1500, 4); // 5 segundos de espera, max 4 dígitos
    $respuesta = strtolower(trim($respuesta['result'])); // Asegura que no haya espacios y lo convierte a minúsculas
    
    // Verificamos si el usuario ingresó '*', lo que significa que quiere salir
    if ($respuesta == '*') {
        $agi->exec("AGI", "googletts.agi,\"Ha finalizado el proceso de matrícula\",\"es\"");
        break;
    }
    
    // Verificamos si la respuesta está vacía
    if (empty($respuesta)) {
        $agi->exec("AGI", "googletts.agi,\"No ingresó un código válido. Inténtelo nuevamente.\",\"es\"");
        continue; // Vuelve a pedir la entrada del usuario
    }

    // Realizamos la consulta SQL para insertar la materia
    $query = "INSERT INTO materia_estudiante(id_materia, id_estudiante) VALUES('$respuesta', '$numero_documento')";
    $result = mysqli_query($link, $query); // Ejecuta la consulta SQL

    // Verificamos si la consulta fue exitosa
    if ($result) {
        $agi->exec("AGI", "googletts.agi,\"La materia con código $respuesta ha sido matriculada exitosamente.\",\"es\"");
    } else {
        $agi->exec("AGI", "googletts.agi,\"Hubo un error al matricular la materia. Inténtelo nuevamente.\",\"es\"");
        error_log("Error en la consulta SQL: " . mysqli_error($link)); // Loguea el error
    }
}
$query_materias = "
    SELECT m.nombre_materia 
    FROM materia_estudiante me
    JOIN materias m ON me.id_materia = m.id
    WHERE me.id_estudiante = '$numero_documento'";

$result_materias = mysqli_query($link, $query_materias);

if ($result_materias) {
    // Si hay resultados, leemos en voz alta los nombres de las materias
    $agi->exec("AGI", "googletts.agi,\"Tiene matriculadas las materias:\",\"es\"");
    while ($row = mysqli_fetch_assoc($result_materias)) {
        $nombre_materia = $row['nombre_materia'];
        $agi->exec("AGI", "googletts.agi,\"$nombre_materia.\",\"es\"");
    }
} else {
    // En caso de que no haya materias o haya un error
    $agi->exec("AGI", "googletts.agi,\"No se encontraron materias matriculadas o hubo un error.\",\"es\"");
    error_log("Error en la consulta SQL: " . mysqli_error($link)); // Loguea el error
}
$agi->exec("AGI", "googletts.agi,\"Gracias por utilizar el AGI de la gloriosa Universidad de antioquia. Gobernador careverga financianos.\",\"es\"");
$agi->hangup();
?>