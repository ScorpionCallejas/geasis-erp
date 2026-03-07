<?php 
  //ARCHIVO VIA AJAX PARA LISTAR MENSAJES DE CONTACTO SELECCIONADO Y ASIGNARLOS DER/IZQ
  //mensajes.php
  require('../inc/cabeceras.php');
  require('../inc/funciones.php');

  //***CODIGO NO REUTILIZABLE POR LA CONDICION DE LA LINEA 35 DONDE SE DEBE ESPECIFICAR LA EXTRACCION DE DATOS DEL USUARIO CON SESION ACTIVA HAY QUE DEFINIR EN LA LINEA 40-41 QUE TIPO DE USUARIO SOY EN LAS DOS TABLAS SEMEJANTES, Y HASTA ABAJO EL USUARIO QUE FALTA ESO ES TODO

  
  
  $id_sal1 = $_POST['id_sal'];


            $sqlSalas = "
            SELECT men_con1 as mensaje, hor_con1 AS hor, use1_con1 AS usuario, tip1_con1 AS tipoUsuario
            FROM con1
            WHERE id_sal2 = '$id_sal1'
            UNION
            SELECT men_con2 as mensaje, hor_con2 AS hor, use2_con2 AS usuario, tip2_con2 AS tipoUsuario
            FROM con2
            WHERE id_sal3 = '$id_sal1'
            ORDER BY hor
            ";

            //echo $sqlSalas;

            $resultadoSalas = mysqli_query($db, $sqlSalas);





            while ($fila = mysqli_fetch_assoc($resultadoSalas)) {

              //$fila['usuario']." ".$fila['tipoUsuario'];
              $usuario = $fila['usuario'];
               //echo "$tipo"."<br>";

              $hor = $fila['hor'];
              $hor = fechaHoraFormateada($hor);

              if ($fila['tipoUsuario'] == $tipo && $fila['usuario'] == $id) {

                //echo "este soy yo"."<br>";
                $sqlDatosSala = " 
                  SELECT * 
                  FROM alumno
                  WHERE id_alu = '$usuario'";
                $resultadoDatosSala = mysqli_query($db, $sqlDatosSala);
                $datosSala = mysqli_fetch_assoc($resultadoDatosSala);

                //echo $sqlDatosSala;
                echo '
                  <div class="card bg-info rounded w-75 float-right z-depth-0 mb-1">
                    <div class="card-body p-2">
                      <p class="card-text text-white">
                        '.$fila['mensaje'].'
                      </p>
                    </div>
                  </div>

                ';
                    

                   
              }else if($fila['tipoUsuario'] == $tipo && $fila['usuario'] != $id){
                //echo "es mi tipo pero no mi id"."<br>";
                $sqlDatosSala = "
                  SELECT * 
                  FROM alumno  
                  WHERE id_alu = '$usuario'";
                $resultadoDatosSala = mysqli_query($db, $sqlDatosSala);
                $datosSala = mysqli_fetch_assoc($resultadoDatosSala);


                echo '  
                  <div class="card bg-light rounded w-75 z-depth-0 mb-1 message-text">
                    <div class="card-body p-2">
                      <p class="card-text black-text">
                        '.$fila['mensaje'].'
                      </p>
                    </div>
                  </div>

                ';

                

              }else if($fila['tipoUsuario'] == 'Ejecutivo'){
                //echo "Es de tipo usuario"."<br>";

                $sqlDatosSala = " 
                  SELECT * 
                  FROM ejecutivo
                  INNER JOIN empleado ON empleado.id_emp = ejecutivo.id_emp4  
                  WHERE id_eje = '$usuario'";
                $resultadoDatosSala = mysqli_query($db, $sqlDatosSala);
                $datosSala = mysqli_fetch_assoc($resultadoDatosSala);

                echo '

                  <div class="card bg-light rounded w-75 z-depth-0 mb-1 message-text">
                    <div class="card-body p-2">
                      <p class="card-text black-text">
                        '.$fila['mensaje'].'
                      </p>
                    </div>
                  </div>

                ';

              }else if($fila['tipoUsuario'] == 'Adminge'){
                //echo "Es de tipo usuario"."<br>";

                $sqlDatosSala = "
                  SELECT * 
                  FROM adminge 
                  INNER JOIN empleado ON empleado.id_emp = adminge.id_emp6 
                  WHERE id_adg = '$usuario'";
                $resultadoDatosSala = mysqli_query($db, $sqlDatosSala);
                $datosSala = mysqli_fetch_assoc($resultadoDatosSala);

                echo '

                  <div class="card bg-light rounded w-75 z-depth-0 mb-1 message-text">
                    <div class="card-body p-2">
                      <p class="card-text black-text">
                        '.$fila['mensaje'].'
                      </p>
                    </div>
                  </div>

                ';

              }else if($fila['tipoUsuario'] == 'Adminco'){
                //echo "Es de tipo usuario"."<br>";

                $sqlDatosSala = "
                  SELECT * 
                  FROM adminco 
                  INNER JOIN empleado ON empleado.id_emp = adminco.id_emp5 
                  WHERE id_adc = '$usuario'";
                $resultadoDatosSala = mysqli_query($db, $sqlDatosSala);
                $datosSala = mysqli_fetch_assoc($resultadoDatosSala);

                echo '

                  <div class="card bg-light rounded w-75 z-depth-0 mb-1 message-text">
                    <div class="card-body p-2">
                      <p class="card-text black-text">
                        '.$fila['mensaje'].'
                      </p>
                    </div>
                  </div>

                ';

              }else if($fila['tipoUsuario'] == 'Profesor'){
                //echo "Es de tipo usuario"."<br>";

                $sqlDatosSala = "
                  SELECT * 
                  FROM profesor 
                  INNER JOIN empleado ON empleado.id_emp = profesor.id_emp3 
                  WHERE id_pro = '$usuario'";
                $resultadoDatosSala = mysqli_query($db, $sqlDatosSala);
                $datosSala = mysqli_fetch_assoc($resultadoDatosSala);

                echo '

                  <div class="card bg-light rounded w-75 z-depth-0 mb-1 message-text">
                    <div class="card-body p-2">
                      <p class="card-text black-text">
                        '.$fila['mensaje'].'
                      </p>
                    </div>
                  </div>

                ';

              }else if($fila['tipoUsuario'] == 'Admin'){
                //echo "Es de tipo usuario"."<br>";

                $sqlDatosSala = "
                  SELECT * 
                  FROM admin 
                  INNER JOIN empleado ON empleado.id_emp = admin.id_emp7 
                  WHERE id_adm = '$usuario'
                ";
                $resultadoDatosSala = mysqli_query($db, $sqlDatosSala);
                $datosSala = mysqli_fetch_assoc($resultadoDatosSala);

                echo '

                  <div class="card bg-light rounded w-75 z-depth-0 mb-1 message-text">
                    <div class="card-body p-2">
                      <p class="card-text black-text">
                        '.$fila['mensaje'].'
                      </p>
                    </div>
                  </div>

                ';

              }

            } 



            $mensajes = mysqli_num_rows($resultadoSalas);

            echo '<span id="aux" value="'.$mensajes.'"></span>';

          ?>