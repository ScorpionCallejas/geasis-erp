<?php  
	//ARCHIVO VIA AJAX PARA OTENER TODAS LAS ACTIVIDADES
	//materias_horario.php
	require('../inc/cabeceras.php');

	header('Content-type: application/json');

	$sql = "
		SELECT id_for AS id, nom_for AS actividad, pun_for AS puntaje, ini_for_cop AS inicio, fin_for_cop AS fin, tip_for AS tipo, nom_pro AS profesor, nom_gru AS grupo
		FROM sub_hor
		INNER JOIN profesor ON profesor.id_pro = sub_hor.id_pro1
		INNER JOIN foro_copia ON foro_copia.id_sub_hor2 = sub_hor.id_sub_hor
		INNER JOIN foro ON foro.id_for = foro_copia.id_for1
		INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
		WHERE id_pro1 = '$id'
		UNION
		SELECT id_ent AS id, nom_ent AS actividad, pun_ent AS puntaje, ini_ent_cop AS inicio, fin_ent_cop AS fin, tip_ent AS tipo, nom_pro AS profesor, nom_gru AS grupo
		FROM sub_hor
		INNER JOIN profesor ON profesor.id_pro = sub_hor.id_pro1
		INNER JOIN entregable_copia ON entregable_copia.id_sub_hor3 = sub_hor.id_sub_hor
		INNER JOIN entregable ON entregable.id_ent = entregable_copia.id_ent1
		INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
		WHERE id_pro1 = '$id'
		UNION
		SELECT id_exa AS id, nom_exa AS actividad, pun_exa AS puntaje, ini_exa_cop AS inicio, fin_exa_cop AS fin, tip_exa AS tipo, nom_pro AS profesor, nom_gru AS grupo
		FROM sub_hor
		INNER JOIN profesor ON profesor.id_pro = sub_hor.id_pro1
		INNER JOIN examen_copia ON examen_copia.id_sub_hor4 = sub_hor.id_sub_hor
		INNER JOIN examen ON examen.id_exa = examen_copia.id_exa1
		INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
		WHERE id_pro1 = '$id'
		ORDER BY inicio ASC

	";

	//echo $sql;
	$resultado = mysqli_query($db, $sql);


	$arreglo = array();


	while ($fila = mysqli_fetch_assoc($resultado)) {
		array_push($arreglo, [
		  'id'   => $fila['id'],
		  'custom_class' => 'yellow',
	      'name'   => $fila['actividad']." - ".$fila['grupo'],
	      'start'   => $fila['inicio'],
	      'end'   => $fila['fin']

	      
	    ]);
	}

	echo json_encode($arreglo);

	//echo '[{"id":"45","title":null, "start":"2019-05-03 00:00:00","color":"#f44336"}]';


?>