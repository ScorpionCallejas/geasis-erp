<?php  
	//ARCHIVO VIA AJAX PARA OTENER TODAS LAS ACTIVIDADES
	//materias_horario.php
	require('../inc/cabeceras.php');

	header('Content-type: application/json');


	$id_alu_ram = $_GET['id_alu_ram'];

	$sql = "
		SELECT id_for_cop AS id, nom_for AS actividad, nom_mat AS materia, pun_for AS puntaje, ini_for_cop AS inicio, fin_for_cop AS fin, tip_for AS tipo, pun_cal_act AS calificacion, ret_cal_act AS retroalimentacion, fec_cal_act AS fecha
		FROM alu_ram
		INNER JOIN alu_hor ON alu_hor.id_alu_ram1 = alu_ram.id_alu_ram
		INNER JOIN sub_hor ON sub_hor.id_sub_hor = alu_hor.id_sub_hor5
		INNER JOIN bloque ON bloque.id_mat6 = sub_hor.id_mat1
		INNER JOIN materia ON materia.id_mat = bloque.id_mat6
		INNER JOIN foro ON foro.id_blo4 = bloque.id_blo
		INNER JOIN foro_copia ON foro_copia.id_for1 = foro.id_for
		INNER JOIN cal_act ON cal_act.id_for_cop2 = foro_copia.id_for_cop
		WHERE id_alu_ram4 = '$id_alu_ram'
		UNION
		SELECT id_ent_cop AS id, nom_ent AS actividad, nom_mat AS materia, pun_ent AS puntaje, ini_ent_cop AS inicio, fin_ent_cop AS fin, tip_ent AS tipo, pun_cal_act AS calificacion, ret_cal_act AS retroalimentacion, fec_cal_act AS fecha
		FROM alu_ram
		INNER JOIN alu_hor ON alu_hor.id_alu_ram1 = alu_ram.id_alu_ram
		INNER JOIN sub_hor ON sub_hor.id_sub_hor = alu_hor.id_sub_hor5
		INNER JOIN bloque ON bloque.id_mat6 = sub_hor.id_mat1
		INNER JOIN materia ON materia.id_mat = bloque.id_mat6
		INNER JOIN entregable ON entregable.id_blo5 = bloque.id_blo
		INNER JOIN entregable_copia ON entregable_copia.id_ent1 = entregable.id_ent
		INNER JOIN cal_act ON cal_act.id_ent_cop2 = entregable_copia.id_ent_cop
		WHERE id_alu_ram4 = '$id_alu_ram'
		UNION
		SELECT id_exa_cop AS id, nom_exa AS actividad, nom_mat AS materia, pun_exa AS puntaje, ini_exa_cop AS inicio, fin_exa_cop AS fin, tip_exa AS tipo, pun_cal_act AS calificacion, ret_cal_act AS retroalimentacion, fec_cal_act AS fecha
		FROM alu_ram
		INNER JOIN alu_hor ON alu_hor.id_alu_ram1 = alu_ram.id_alu_ram
		INNER JOIN sub_hor ON sub_hor.id_sub_hor = alu_hor.id_sub_hor5
		INNER JOIN bloque ON bloque.id_mat6 = sub_hor.id_mat1
		INNER JOIN materia ON materia.id_mat = bloque.id_mat6
		INNER JOIN examen ON examen.id_blo6 = bloque.id_blo
		INNER JOIN examen_copia ON examen_copia.id_exa1 = examen.id_exa
		INNER JOIN cal_act ON cal_act.id_exa_cop2 = examen_copia.id_exa_cop
		WHERE id_alu_ram4 = '$id_alu_ram'
		ORDER BY inicio ASC

	";
	$resultado = mysqli_query($db, $sql);


	$arreglo = array();


	while ($fila = mysqli_fetch_assoc($resultado)) {
		array_push($arreglo, [
		  'id'   => $fila['id'],
		  'custom_class' => 'yellow',
	      'name'   => $fila['actividad'],
	      'start'   => $fila['inicio'],
	      'end'   => $fila['fin']

	      
	    ]);
	}

	echo json_encode($arreglo);

	//echo '[{"id":"45","title":null, "start":"2019-05-03 00:00:00","color":"#f44336"}]';


?>