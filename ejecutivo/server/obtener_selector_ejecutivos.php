<?php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$id_pla = $_POST['id_pla'];
    $sqlEjecutivos = "
        SELECT * 
        FROM ejecutivo 
        INNER JOIN plantel ON plantel.id_pla = ejecutivo.id_pla
        WHERE plantel.id_cad1 = 1 AND est_eje = 'Activo' AND eli_eje = 'Activo' 
        ORDER BY nom_pla ASC, nom_eje ASC
    "; 
?>
<select id="selectorEjecutivos" class="form-control"><?php 
    
    $resultadoEjecutivos = mysqli_query($db, $sqlEjecutivos); 
    while ($filaEjecutivos = mysqli_fetch_assoc($resultadoEjecutivos)) { 
        ?><option value="<?php echo $filaEjecutivos['id_eje']; ?>"<?php echo ($filaEjecutivos['id_eje'] == $id) ? ' selected="selected"' : ''; ?>><?php 
            echo obtener_permisos_ejecutivo_para_select(
                $filaEjecutivos['usu_eje'],
                $filaEjecutivos['est_eje'],
                $filaEjecutivos['ran_eje']
            ) . ' - ' . $filaEjecutivos['nom_eje'].' - '.$filaEjecutivos['nom_pla']; 
        ?></option><?php 
    } 
?></select>