<?php  

    include 'inc/cabeceras.php';
    include 'inc/funciones.php';

    $id_pag = 10000;
    // $id_pag = 298;

    enviarCorreoPagoAlumnoServer( $id_pag, $nombrePlantel, $correo2Plantel, $ligaPlantel, $esloganPlantel, $nomResponsable, $direccionPlantel );

    function enviarCorreoPagoAlumnoServer( $id_pag, $nombrePlantel, $correo2Plantel, $ligaPlantel, $esloganPlantel, $nomResponsable, $direccionPlantel ){
    require( '../includes/conexion.php' );

    $sql = "
      SELECT *
      FROM vista_pagos
      WHERE id_pag = '$id_pag'
    ";


    $resultado = mysqli_query( $db, $sql );

    $fila = mysqli_fetch_assoc( $resultado );

    $abonado = $fila['mon_ori_pag'] - $fila['mon_pag'];
    $id_alu_ram = $fila['id_alu_ram'];

    $datosAlumno = obtenerDatosAlumnoPrograma( $id_alu_ram );
    $correoAlumno = 'ericorps@hotmail.com';
    $bol_alu = $datosAlumno['bol_alu'];

    $para ="";
    $para .= $correoAlumno;

    $titulo = 'Recibo de pago de '.$nombrePlantel;

      
    $mensaje = '
      <!DOCTYPE html>

      <html lang="en" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:v="urn:schemas-microsoft-com:vml">
      <head>
      <title></title>
      <meta charset="utf-8"/>
      <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
      <!--[if mso]><xml><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch><o:AllowPNG/></o:OfficeDocumentSettings></xml><![endif]-->
      <!--[if !mso]><!-->
      <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css"/>
      <!--<![endif]-->
      <style>
          * {
            box-sizing: border-box;
          }

          body {
            margin: 0;
            padding: 0;
          }

          th.column {
            padding: 0
          }

          a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: inherit !important;
          }

          #MessageViewBody a {
            color: inherit;
            text-decoration: none;
          }

          p {
            line-height: inherit
          }

          @media (max-width:700px) {
            .icons-inner {
              text-align: center;
            }

            .icons-inner td {
              margin: 0 auto;
            }

            .fullMobileWidth,
            .row-content {
              width: 100% !important;
            }

            .image_block img.big {
              width: auto !important;
            }

            .mobile_hide {
              display: none;
            }

            .stack .column {
              width: 100%;
              display: block;
            }

            .mobile_hide {
              min-height: 0;
              max-height: 0;
              max-width: 0;
              overflow: hidden;
              font-size: 0px;
            }
          }
        </style>
      </head>
      <body style="background-color: #4f4fef; margin: 0; padding: 0; -webkit-text-size-adjust: none; text-size-adjust: none;">



      <br>
      <br>
      <br>

      <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-4" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #4f4fef;" width="100%">
      <tbody>
      <tr>
      <td>
      <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #3939ad;" width="680">
      <tbody>
      <tr>
      <th class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 5px;" width="100%">
      <table border="0" cellpadding="0" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
      <tr>
      <td style="padding-bottom:10px;padding-left:10px;padding-right:10px;padding-top:30px;">
      <div style="font-family: sans-serif; ">
      <div style="font-size: 12px; color: #ffffff; line-height: 1.5; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;position: relative; ">
      
      <p style="margin: 0; font-size: 14px; text-align: center; mso-line-height-alt: 24px;"><span style="font-size:16px;">Servicios empresariales de '.$nombrePlantel.'</span></p>
      <p style="margin: 0; font-size: 14px; text-align: center; mso-line-height-alt: 24px;"><span style="font-size:16px;">Gracias por tu confianza ❤️</span>
      </p>

      <p style="margin: 0; font-size: 12px; text-align: center; mso-line-height-alt: 24px;"><span style="font-size:12px; color: lightblue;">'.$esloganPlantel.'</span>
      </p>

      <p style="margin: 0; font-size: 10px; text-align: center; mso-line-height-alt: 24px;"><span style="font-size:10px; color: white;">Responsable: '.$nomResponsable.'</span></p>

      </div>

      <!-- <div style="text-align: center;">
        
        
      </div> -->

      </div>
      </td>
      </tr>
      </table>

      <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff;" width="680">
      <tbody>
      <tr>
      <th class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top;" width="50%">
      <table border="0" cellpadding="0" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
      <tr>
      <td style="padding-bottom:5px;padding-left:35px;padding-right:10px;padding-top:15px;">
      <div style="font-family: sans-serif">
      <div style="font-size: 12px; color: #626262; line-height: 1.2; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;">
      <p style="margin: 0; font-size: 14px;">Recibo de pago</p>
      </div>
      </div>
      </td>
      </tr>
      </table>
      <table border="0" cellpadding="0" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
      <tr>
      <td style="padding-bottom:10px;padding-left:35px;padding-right:10px;padding-top:15px;">
      <div style="font-family: sans-serif">
      <div style="font-size: 12px; color: #030303; line-height: 1.2; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;">


      <p style="margin: 0; font-size: 14px;"><span style="font-size:18px;"><strong><span style="">'.$fila['con_pag'].'</span></strong></span></p>


      </div>
      </div>
      </td>
      </tr>
      </table>
      <table border="0" cellpadding="0" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
      <tr>
      <td style="padding-left:35px;padding-right:10px;padding-top:10px;">
      <div style="font-family: sans-serif">
      <div style="font-size: 12px; color: #626262; line-height: 1.2; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;">
      <p style="margin: 0; font-size: 14px;">'.$direccionPlantel.'</p>
      </div>
      </div>
      </td>
      </tr>
      </table>
      <table border="0" cellpadding="0" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
      <tr>
      <td style="padding-left:35px;padding-right:10px;padding-top:10px;padding-bottom:5px;">
      <div style="font-family: sans-serif">
      <div style="font-size: 12px; color: #626262; line-height: 1.2; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;">
      <p style="margin: 0; font-size: 14px;">'.$nombrePlantel.'</p>
      </div>
      </div>
      </td>
      </tr>
      </table>


      <table border="0" cellpadding="0" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
      <tr>
      <td style="padding-left:35px;padding-right:10px;padding-top:10px;padding-bottom:5px;">
      <div style="font-family: sans-serif">
      <div style="font-size: 12px; color: #626262; line-height: 1.2; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;">
      <p style="margin: 0; font-size: 14px;">Alumno: '.$fila['nom_alu'].' - Matrícula: '.$bol_alu.'</p>
      </div>
      </div>
      </td>
      </tr>
      </table>


      <table border="0" cellpadding="0" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
      <tr>
      <td style="padding-left:35px;padding-right:10px;padding-top:10px;padding-bottom:5px;">
      <div style="font-family: sans-serif">
      <div style="font-size: 12px; color: #626262; line-height: 1.2; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;">
      <p style="margin: 0; font-size: 14px;">'.$fila['nom_gen'].' - '.$fila['nom_ram'].'</p>
      </div>
      </div>
      </td>
      </tr>
      </table>


      <table border="0" cellpadding="0" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
      <tr>
      <td style="padding-left:35px;padding-right:10px;padding-top:10px;padding-bottom:5px;">
      <div style="font-family: sans-serif">
      <div style="font-size: 12px; color: #626262; line-height: 1.2; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;">
      <p style="margin: 0; font-size: 14px;">'.fechaHoraFormateadaCompacta2( date('Y-m-d H:i:s') ).'</p>
      </div>
      </div>
      </td>
      </tr>
      </table>


      </th>
      <th class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top;" width="50%">
      <table border="0" cellpadding="0" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
      <tr>



      <td style="padding-bottom:5px;padding-left:35px;padding-right:10px;padding-top:15px;">
      <div style="font-family: sans-serif">
      <div style="font-size: 12px; color: #626262; line-height: 1.2; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;">
      <p style="margin: 0; font-size: 14px;">Cantidad</p>
      </div>
      </div>
      </td>
      </tr>
      </table>
      <table border="0" cellpadding="0" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
      <tr>
      <td style="padding-bottom:10px;padding-left:35px;padding-right:10px;padding-top:15px;">
      <div style="font-family: sans-serif">
      <div style="font-size: 12px; color: #030303; line-height: 1.2; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;">
      <p style="margin: 0; font-size: 14px;"><strong><span style="font-size:20px;"><span style="font-size: 30px;">'.formatearDinero( $fila['mon_ori_pag'] ).'</span></span></strong></p>

      <p style="margin: 0; font-size: 14px;"><strong><span style="font-size:20px;"><span style="font-size: 16px; color: grey;">'.$fila['est_pag'].'</span></span></strong></p>


      </div>
      </div>
      </td>
      </tr>
      </table>
      <table border="0" cellpadding="0" cellspacing="0" class="button_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
      <tr>
      <td style="padding-bottom:15px;padding-left:35px;padding-right:10px;padding-top:10px;text-align:left;">

        <!-- LOGO -->
      </td>
      </tr>
      </table>
      </th>
      </tr>
      </tbody>
      </table>
      </td>
      </tr>
      </tbody>
      </table>
      <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-5" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #4f4fef;" width="100%">
      <tbody>
      <tr>
      <td>
      <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff;" width="680">
      <tbody>
      <tr>
      <th class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 0px;" width="100%">
      <table border="0" cellpadding="10" cellspacing="0" class="divider_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
      <tr>
      <td>
      <div align="center">
      <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
      <tr>
      <td class="divider_inner" style="font-size: 1px; line-height: 1px; border-top: 1px solid #D6D3D3;"><span></span></td>
      </tr>
      </table>
      </div>
      </td>
      </tr>
      </table>
      <table border="0" cellpadding="0" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
      <tr>
      <td style="padding-bottom:5px;padding-left:35px;padding-right:10px;padding-top:20px;">
      <div style="font-family: sans-serif">
      <div style="font-size: 12px; color: #626262; line-height: 1.2; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;">
      <p style="margin: 0; font-size: 14px;">Folio de pago</p>
      </div>
      </div>
      </td>
      </tr>
      </table>
      <table border="0" cellpadding="0" cellspacing="0" class="button_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
      <tr>
      <td style="padding-bottom:20px;padding-left:35px;padding-right:10px;padding-top:10px;text-align:left;">
      <!--[if mso]><v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" style="height:32px;width:48px;v-text-anchor:middle;" arcsize="13%" stroke="false" fillcolor="#f9d5d5"><w:anchorlock/><v:textbox inset="0px,0px,0px,0px"><center style="color:#e17370; font-family:Tahoma, sans-serif; font-size:16px"><![endif]-->
      <div style="text-decoration:none;display:inline-block;color:#e17370;background-color:#f9d5d5;border-radius:4px;width:auto;border-top:0px solid #8a3b8f;border-right:0px solid #8a3b8f;border-bottom:0px solid #8a3b8f;border-left:0px solid #8a3b8f;padding-top:0px;padding-bottom:0px;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;text-align:center;mso-border-alt:none;word-break:keep-all;"><span style="padding-left:20px;padding-right:20px;font-size:16px;display:inline-block;letter-spacing:normal;"><span style="font-size: 16px; line-height: 2; word-break: break-word; mso-line-height-alt: 32px;">'.$fila['fol_pag'].'</span></span></div>
      <!--[if mso]></center></v:textbox></v:roundrect><![endif]-->
      </td>
      </tr>
      </table>


      <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-10" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
      <tbody>
      <tr>
      <td>
      <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff;" width="680">
      <tbody>
      <tr>
      <th class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top;" width="25%">
      <table border="0" cellpadding="0" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
      <tr>
      <td style="padding-left:35px;padding-right:10px;padding-top:15px;padding-bottom:5px;">
      <div style="font-family: sans-serif">
      <div style="font-size: 12px; color: #848484; line-height: 1.5; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;">
      <p style="margin: 0; font-size: 14px; text-align: left; mso-line-height-alt: 21px;"><span style="font-size:14px;">Pagado</span></p>
      </div>
      </div>
      </td>
      </tr>
      </table>
      </th>
      <th class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top;" width="50%">
      <div class="spacer_block" style="height:5px;line-height:5px;"> </div>
      <div class="spacer_block mobile_hide" style="height:25px;line-height:25px;"> </div>
      <div class="spacer_block" style="height:5px;line-height:5px;"> </div>
      </th>
      <th class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top;" width="25%">
      <table border="0" cellpadding="0" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
      <tr>
      <td style="padding-left:35px;padding-right:10px;padding-top:15px;padding-bottom:5px;">
      <div style="font-family: sans-serif">
      <div style="font-size: 12px; color: #666666; line-height: 1.5; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;">
      <p style="margin: 0; font-size: 14px; text-align: left; mso-line-height-alt: 21px;"><span style="font-size:14px;">'.formatearDinero( $abonado ).'</span></p>

      </div>


      </div>
      </td>
      </tr>
      </table>
      </th>
      </tr>
      </tbody>
      </table>
      </td>
      </tr>
      </tbody>
      </table>



      <!-- ABONOS -->
      ';


        $sqlAbonos = "
          SELECT *
          FROM abono_pago
          WHERE id_pag1 = '$id_pag'
        ";

        $resultadoAbonos = mysqli_query( $db, $sqlAbonos );

        if ( !$resultadoAbonos ) {
          
          echo $sqlAbonos;

        }

        while( $filaAbonos = mysqli_fetch_assoc( $resultadoAbonos ) ){
      
          
          $mensaje.= '<table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-10" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
            <tbody>
            <tr>
            <td>
            <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff;" width="680">
            <tbody>
            <tr>
            <th class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top;" width="25%">
            <table border="0" cellpadding="0" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
            <tr>
            <td style="padding-left:35px;padding-right:10px;padding-top:15px;padding-bottom:5px;">
            <div style="font-family: sans-serif">
            <div style="font-size: 12px; color: #848484; line-height: 1.5; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;">
            <p style="margin: 0; font-size: 14px; text-align: left; mso-line-height-alt: 21px;"><span style="font-size:12px;">'.$filaAbonos['tip_abo_pag'].' <br>'.fechaFormateadaCompacta2( $filaAbonos['fec_abo_pag'] ).'</span></p>
            </div>
            </div>
            </td>
            </tr>
            </table>
            </th>
            <th class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top;" width="50%">
            <div class="spacer_block" style="height:5px;line-height:5px;"> </div>
            <div class="spacer_block mobile_hide" style="height:25px;line-height:25px;"> </div>
            <div class="spacer_block" style="height:5px;line-height:5px;"> </div>
            </th>
            <th class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top;" width="25%">
            <table border="0" cellpadding="0" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
            <tr>
            <td style="padding-left:35px;padding-right:10px;padding-top:15px;padding-bottom:5px;">
            <div style="font-family: sans-serif">
            <div style="font-size: 12px; color: #666666; line-height: 1.5; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;">
            <p style="margin: 0; font-size: 14px; text-align: left; mso-line-height-alt: 21px;"><span style="font-size:14px;">'.formatearDinero( $filaAbonos['mon_abo_pag'] ).'</span></p>

            </div>


            </div>
            </td>
            </tr>
            </table>
            </th>
            </tr>
            </tbody>
            </table>
            </td>
            </tr>
            </tbody>
            </table>
          ';
      
        }
      


      $mensaje.= '
        <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-11" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
        <tbody>
        <tr>
        <td>
        <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff;" width="680">
        <tbody>
        <tr>
        <th class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top;" width="25%">
        <table border="0" cellpadding="0" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
        <tr>
        <td style="padding-left:35px;padding-right:10px;padding-top:15px;padding-bottom:5px;">
        <div style="font-family: sans-serif">
        <div style="font-size: 12px; color: #848484; line-height: 1.5; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;">
        <p style="margin: 0; font-size: 14px; text-align: left; mso-line-height-alt: 21px;"><span style="font-size:14px;">Adeudo</span></p>
        </div>
        </div>
        </td>
        </tr>
        </table>
        </th>
        <th class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top;" width="50%">
        <div class="spacer_block" style="height:5px;line-height:5px;"> </div>
        <div class="spacer_block mobile_hide" style="height:25px;line-height:25px;"> </div>
        <div class="spacer_block" style="height:5px;line-height:5px;"> </div>
        </th>
        <th class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top;" width="25%">
        <table border="0" cellpadding="0" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
        <tr>
        <td style="padding-left:35px;padding-right:10px;padding-top:15px;padding-bottom:5px;">
        <div style="font-family: sans-serif">
        <div style="font-size: 12px; color: #666666; line-height: 1.5; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;">
        <p style="margin: 0; font-size: 14px; text-align: left; mso-line-height-alt: 21px;"><span style="font-size:14px;">'.formatearDinero( $fila['mon_pag'] ).'</span></p>
        </div>
        </div>
        </td>
        </tr>
        </table>
        </th>
        </tr>
        </tbody>
        </table>
        </td>
        </tr>
        </tbody>
        </table>
        <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-12" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
        <tbody>
        <tr>
        <td>
        <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff;" width="680">
        <tbody>
        <tr>
        <th class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top;" width="25%">
        <table border="0" cellpadding="0" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
        <tr>
        <td style="padding-bottom:15px;padding-left:35px;padding-right:10px;padding-top:20px;">
        <div style="font-family: sans-serif">
        <div style="font-size: 12px; color: #030303; line-height: 1.2; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;">
        <p style="margin: 0; font-size: 14px;"><span style="font-size:18px;"><strong><span style="">Total</span></strong></span></p>
        </div>
        </div>
        </td>
        </tr>
        </table>
        </th>
        <th class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top;" width="50%">
        <div class="spacer_block" style="height:5px;line-height:5px;"> </div>
        <div class="spacer_block mobile_hide" style="height:25px;line-height:25px;"> </div>
        <div class="spacer_block" style="height:5px;line-height:5px;"> </div>
        </th>
        <th class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top;" width="25%">
        <table border="0" cellpadding="0" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
        <tr>
        <td style="padding-bottom:15px;padding-left:35px;padding-right:10px;padding-top:20px;">
        <div style="font-family: sans-serif">
        <div style="font-size: 12px; color: #030303; line-height: 1.2; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;">
        <p style="margin: 0; font-size: 14px;"><strong><span style="font-size:20px;"><span style="">'.formatearDinero( $fila['mon_ori_pag'] ).'</span></span></strong></p>
        </div>
        </div>
        </td>
        </tr>
        </table>
        </th>
        </tr>
        </tbody>
        </table>
        </td>
        </tr>
        </tbody>
        </table>
        <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-13" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
        <tbody>
        <tr>
        <td>
        <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff;" width="680">
        <tbody>
        <tr>
        <th class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 5px;" width="100%">
        <div class="spacer_block" style="height:20px;line-height:20px;"> </div>
        </th>
        </tr>
        </tbody>
        </table>
        </td>
        </tr>
        </tbody>
        </table>
        <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-14" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #4f4fef;" width="100%">
        <tbody>
        <tr>
        <td>
        <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff;" width="680">
        <tbody>
        <tr>
        <th class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 0px;" width="100%">
        <table border="0" cellpadding="10" cellspacing="0" class="divider_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
        <tr>
        <td>
        <div align="center">
        <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
        <tr>
        <td class="divider_inner" style="font-size: 1px; line-height: 1px; border-top: 1px solid #D6D3D3;"><span></span></td>
        </tr>
        </table>
        </div>
        </td>
        </tr>
        </table>
        <table border="0" cellpadding="0" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
        <tr>
        <td style="padding-bottom:10px;padding-left:35px;padding-right:10px;padding-top:25px;">
        <div style="font-family: sans-serif">
        <div style="font-size: 12px; color: #030303; line-height: 1.2; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;">
        <p style="margin: 0; font-size: 14px;"><span style="font-size:18px;"><strong><span style="">Pago seguro</span></strong></span></p>
        </div>
        </div>
        </td>
        </tr>
        </table>
        <table border="0" cellpadding="0" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
        <tr>
        <td style="padding-bottom:10px;padding-left:35px;padding-right:35px;padding-top:10px;">
        <div style="font-family: sans-serif">
        <div style="font-size: 12px; color: #393d47; line-height: 1.8; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;">
        <p style="margin: 0; font-size: 14px;">Recuerda presentar este comprobante en caso de tener cualquier problema eventualmente.</p>
        </div>
        </div>
        </td>
        </tr>
        </table>

        <hr>
      ';


          
          $sqlPagos = "
            SELECT *
            FROM pago
            WHERE id_alu_ram10 = '$id_alu_ram' AND est_pag = 'Pendiente'
            ORDER BY ini_pag ASC
          ";

          $resultadoPagos = mysqli_query( $db, $sqlPagos );

          $resultadoTotalPagos = mysqli_query( $db, $sqlPagos );

          $totalPagos = mysqli_num_rows( $resultadoTotalPagos );

          if ( $totalPagos > 0 ) {
            
            $mensaje.= '
              <table border="0" cellpadding="0" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
              <tr>
              <td style="padding-bottom:10px;padding-left:35px;padding-right:10px;padding-top:25px;">
              <div style="font-family: sans-serif">
              <div style="font-size: 12px; color: #030303; line-height: 1.2; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;">
              <p style="margin: 0; font-size: 14px;"><span style="font-size:18px;"><strong><span style="">¡RECUERDA! <br>Calendario de próximos pagos</span></strong></span></p>
              </div>
              </div>
              </td>
              </tr>
              </table>
            ';

            
              while( $filaPagos = mysqli_fetch_assoc( $resultadoPagos ) ){
            
                $mensaje.= '
                  <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-10" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                  <tbody>
                  <tr>
                  <td>
                  <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff;" width="680">
                  <tbody>
                  <tr>
                  <th class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top;" width="25%">
                  <table border="0" cellpadding="0" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
                  <tr>
                  <td style="padding-left:35px;padding-right:10px;padding-top:15px;padding-bottom:5px;">
                  <div style="font-family: sans-serif">
                  <div style="font-size: 12px; color: #848484; line-height: 1.5; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;">
                  <p style="margin: 0; font-size: 14px; text-align: left; mso-line-height-alt: 21px;"><span style="font-size:12px;">'.$filaPagos['con_pag'].' <br>'.fechaFormateadaCompacta2( $filaPagos['fin_pag'] ).'</span></p>
                  </div>
                  </div>
                  </td>
                  </tr>
                  </table>
                  </th>
                  <th class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top;" width="50%">
                  <div class="spacer_block" style="height:5px;line-height:5px;"> </div>
                  <div class="spacer_block mobile_hide" style="height:25px;line-height:25px;"> </div>
                  <div class="spacer_block" style="height:5px;line-height:5px;"> </div>
                  </th>
                  <th class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top;" width="25%">
                  <table border="0" cellpadding="0" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
                  <tr>
                  <td style="padding-left:35px;padding-right:10px;padding-top:15px;padding-bottom:5px;">
                  <div style="font-family: sans-serif">
                  <div style="font-size: 12px; color: #666666; line-height: 1.5; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;">
                  <p style="margin: 0; font-size: 14px; text-align: left; mso-line-height-alt: 21px;"><span style="font-size:14px;">'.formatearDinero( $filaPagos['mon_pag'] ).'</span></p>

                  </div>


                  </div>
                  </td>
                  </tr>
                  </table>
                  </th>
                  </tr>
                  </tbody>
                  </table>
                  </td>
                  </tr>
                  </tbody>
                  </table>
                ';


            
              }
            
          
          } else {
            
            $mensaje.= '
              <table border="0" cellpadding="0" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
              <tr>
              <td style="padding-bottom:10px;padding-left:35px;padding-right:10px;padding-top:25px;">
              <div style="font-family: sans-serif">
              <div style="font-size: 12px; color: #030303; line-height: 1.2; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;">
              <p style="margin: 0; font-size: 14px;"><span style="font-size:18px;"><strong><span style="">No tienes más pagos de momento.</span></strong></span></p>
              </div>
              </div>
              </td>
              </tr>
              </table>
            ';
        
          }
        
      
      $mensaje.= '     
        <hr>
        <table border="0" cellpadding="0" cellspacing="0" class="button_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
        <tr>
        <td style="padding-bottom:30px;padding-left:35px;padding-right:10px;padding-top:10px;text-align:center;">
        <div align="center">
        <!--[if mso]><v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="http://www.example.com" style="height:44px;width:122px;v-text-anchor:middle;" arcsize="10%" strokeweight="0.75pt" strokecolor="#E17370" fillcolor="#e17370"><w:anchorlock/><v:textbox inset="0px,0px,0px,0px"><center style="color:#ffffff; font-family:Tahoma, sans-serif; font-size:16px"><![endif]--><a href="'.$ligaPlantel.'" style="text-decoration:none;display:inline-block;color:#ffffff;background-color:#e17370;border-radius:4px;width:auto;border-top:1px solid #E17370;border-right:1px solid #E17370;border-bottom:1px solid #E17370;border-left:1px solid #E17370;padding-top:5px;padding-bottom:5px;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;text-align:center;mso-border-alt:none;word-break:keep-all;" target="_blank"><span style="padding-left:30px;padding-right:30px;font-size:16px;display:inline-block;letter-spacing:normal;"><span style="font-size: 16px; line-height: 2; word-break: break-word; mso-line-height-alt: 32px;">Ir a la plataforma</span></span></a>
        <!--[if mso]></center></v:textbox></v:roundrect><![endif]-->
        </div>
        </td>
        </tr>
        </table>
        </th>
        </tr>
        </tbody>
        </table>
        </td>
        </tr>
        </tbody>
        </table>
        <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-15" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
        <tbody>
        <tr>
        <td>
        <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffb0af;" width="680">
        <tbody>
        <tr>
        <th class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 5px;" width="100%">
        <table border="0" cellpadding="10" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
        <tr>
        <td>
        <div style="font-family: sans-serif">
        <div style="font-size: 12px; color: #393d47; line-height: 1.2; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;">
        <p style="margin: 0; font-size: 14px; text-align: center;">FOLIO : <strong>'.$fila['fol_pag'].'</strong></p>
        </div>
        </div>
        </td>
        </tr>
        </table>
        </th>
        </tr>
        </tbody>
        </table>
        </td>
        </tr>
        </tbody>
        </table>
        <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-16" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
        <tbody>
        <tr>
        <td>



        <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-20" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
        <tbody>
        <tr>
        <td>
        <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #3939ad;" width="680">
        <tbody>
        <tr>
        <th class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top;" width="50%">
        <table border="0" cellpadding="0" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
        <tr>
        <td style="padding-bottom:10px;padding-left:25px;padding-right:10px;padding-top:10px;">
        <div style="font-family: sans-serif">
        <div style="font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #ffffff; line-height: 1.2;">
        <p style="margin: 0; font-size: 18px; text-align: left;"><strong><span style="color:#ffffff;">Redes sociales</span></strong></p>
        </div>
        </div>
        </td>
        </tr>
        </table>
        <table border="0" cellpadding="0" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
        <tr>
        <td style="padding-bottom:20px;padding-left:25px;padding-right:10px;padding-top:10px;">
        <div style="font-family: sans-serif">
        <div style="font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #C0C0C0; line-height: 1.8;">
        <p style="margin: 0;"><span style=""></span></p>
        <p style="margin: 0; font-size: 14px; text-align: left; mso-line-height-alt: 21.6px;"><span style="color:#C0C0C0;font-size:12px;">Recuerdo darle like y seguir nuestras fanpages en facebook para estar al pendiente de los eventos, promociones, ofertas y más...</span></p>
        <p style="margin: 0; mso-line-height-alt: 21.6px;"></p>
        </div>
        </div>
        </td>
        </tr>
        </table>


        </th>
        <th class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top;" width="50%">
        <table border="0" cellpadding="0" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
        <tr>
        <td style="padding-bottom:10px;padding-left:25px;padding-right:10px;padding-top:10px;">
        <div style="font-family: sans-serif">
        <div style="font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #ffffff; line-height: 1.2;">
        <p style="margin: 0; font-size: 18px; text-align: left;"><strong><span style="color:#ffffff;">Contacto</span></strong></p>
        </div>
        </div>
        </td>
        </tr>
        </table>
        <table border="0" cellpadding="0" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
        <tr>
        <td style="padding-bottom:20px;padding-left:25px;padding-right:10px;padding-top:10px;">
        <div style="font-family: sans-serif">
        <div style="font-size: 12px; color: #C0C0C0; line-height: 1.8; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;">

        <p style="margin: 0; mso-line-height-alt: 21.6px;"><span style="color:#C0C0C0;font-size:12px;">Dirección:
          '.$direccionPlantel.' </br> Teléfonos: '.$telefonoPlantel.'</span></p>

        Visita: <a href="'.$urlPlantel.'" style="color: white;" target="_blank">
          '.$urlPlantel.'
        </a>

          
        </div>
        </div>
        </td>
        </tr>
        </table>

        </th>
        </tr>
        </tbody>
        </table>
        </td>
        </tr>
        </tbody>
        </table>
        <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-21" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
        <tbody>
        <tr>
        <td>
        <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #3939ad;" width="680">
        <tbody>
        <tr>
        <th class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 5px;" width="100%">
        <div class="spacer_block" style="height:20px;line-height:20px;"> </div>
        </th>
        </tr>
        </tbody>
        </table>
        </td>
        </tr>
        </tbody>
        </table>
        <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-22" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
        <tbody>



        <tr>
        <td>
        <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="680">
        <tbody>
        <tr>
        <th class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top;" width="100%">
        <div class="spacer_block" style="height:5px;line-height:5px;"> </div>
        <div class="spacer_block mobile_hide" style="height:20px;line-height:20px;"> </div>
        <div class="spacer_block" style="height:5px;line-height:5px;"> </div>
        </th>
        </tr>
        </tbody>
        </table>
        </td>
        </tr>
        </tbody>
        </table>

        </td>
        </tr>
        </tbody>
        </table><!-- End -->
        </body>
        </html>
      ';

      $cabeceras  = 'MIME-Version: 1.0' . "\r\n";
      $cabeceras .= 'Content-type: text/html; charset=UTF-8' . "\r\n";

      // Cabeceras adicionales
      $cabeceras .= 'From: '.$nombrePlantel.' <'.$correo2Plantel.'>' . "\r\n";

      // Enviarlo
      mail( $para, $titulo, $mensaje, $cabeceras );

  }
?>