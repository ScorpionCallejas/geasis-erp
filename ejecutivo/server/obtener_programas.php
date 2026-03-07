<?php
    require('../inc/cabeceras.php');
    require('../inc/funciones.php');

    $id_pla = $_POST['id_pla'];
?>

<div class="controls">
    <button id="export-file" class="btn btn-success btn-sm letraPequena"><i class="fas fa-file-excel"></i> excel</button>
</div>
<button id="btn_bloqueo" class="btn letraPequena btn-sm btn-light waves-effect" style="display: none;">
    <i id="icono_candado" class="fa fa-lock"></i> Bloqueo/Desbloqueo
</button>

<div id="data-sheet" class="hot" data-theme="dark"></div>

<script type="text/javascript">
    moment.locale('es');

    // Función de renderizado personalizada
    function genRenderer(instance, td, row, col, prop, value, cellProperties) {
        // Limpiamos el contenido de la celda
        td.innerHTML = '';

        // Obtenemos el id_ram de la columna 0 (primera columna)
        const id_ram = instance.getDataAtCell(row, 0);

        // Si hay un valor y un id_ram, creamos el enlace
        if (value && id_ram) {
            const link = document.createElement('a');
            link.href = `consulta_programa.php?id_ram=${id_ram}`; // Usamos id_ram para el enlace
            link.textContent = value; // El texto del enlace es el valor de la celda
            link.target = '_blank'; // Abrir en una nueva pestaña
            link.classList.add('text-primary', 'custom-link'); // Añadimos la clase personalizada

            td.appendChild(link); // Añadimos el enlace a la celda
        } else {
            // Si no hay valor o id_ram, mostramos el valor como texto
            td.textContent = value;
        }

        return td; // Devolvemos la celda modificada
    }

    var container = document.querySelector('#data-sheet');
    var colHeaders = ["ID", "PROGRAMA", "CICLOS", "TIPO PERIODICIDAD", "IMP. COLEGIATURA", "PARCIALES", "MODALIDAD", "NIVEL", "ESTATUS", "ID."];
    var data = Array(0).fill(0).map(() => ["", "", "", "", "", "", "", "", "", ""]);

    <?php
    $sql = "
        SELECT *
        FROM rama
        WHERE id_pla1 = '$id_pla' AND est_ram = 'Activo'
        ORDER BY id_ram DESC
    ";

    $total = obtener_datos_consulta($db, $sql)['total'];

    if ($total != 0) {
        $resultado = mysqli_query($db, $sql);

        while ($fila = mysqli_fetch_assoc($resultado)) {
            $id_ram = json_encode($fila['id_ram']);
            $nom_ram = json_encode($fila['nom_ram']);
            $cic_ram = json_encode($fila['cic_ram']);
            $per_ram = json_encode($fila['per_ram']);
            $cos_ram = json_encode($fila['cos_ram']);
            $eva_ram = json_encode($fila['eva_ram']);
            $mod_ram = json_encode($fila['mod_ram']);
            $gra_ram = json_encode($fila['gra_ram']);
            $est_ram = json_encode($fila['est_ram']);
            $id_ram = json_encode($fila['id_ram']);

            echo "data.push([$id_ram, $nom_ram, $cic_ram, $per_ram, $cos_ram, $eva_ram, $mod_ram, $gra_ram, $est_ram, $id_ram]);\n";
        }
    } else {
        echo 'data = Array(15).fill(0).map(() => ["", "", "", "", "", "", "", "", "", ""]);';
    }
    ?>

    var hot;

    if (hot) {
        hot.destroy();
    }

    hot = new Handsontable(container, {
        language: 'es-MX',
        data,
        height: 'auto',
        width: '100%',
        hiddenColumns: {
            columns: [9], // Esconde la columna en el índice 9
            indicators: false
        },
        stretchH: 'all',
        colHeaders: colHeaders,
        rowHeaders: true,
        manualColumnResize: true,
        minRows: 20,
        minSpareRows: 1,
        licenseKey: 'non-commercial-and-evaluation',
        columns: [
            {
                readOnly: true,
            },
            {
                readOnly: true,
                renderer: genRenderer // Usamos la función de renderizado personalizada
            },
            {
                readOnly: true,
            },
            {   
                readOnly: true,
                type: 'dropdown',
                source: ['Semestral', 'Cuatrimestral', 'Trimestral', 'Bimestral']
            },
            {
                readOnly: true,
            },
            {
                readOnly: true,
            },
            {
                readOnly: true,
                type: 'dropdown',
                source: ['Online', 'Presencial']
            },
            {
                readOnly: true,
                type: 'dropdown',
                source: ['Licenciatura', 'Preparatoria']
            },
            {
                readOnly: true,
                type: 'dropdown',
                source: ['Activo', 'Inactivo']
            },
            {
                readOnly: true,
            },
        ],
        afterChange: function(changes, source) {
            if (source === 'loadData' || source === 'populateFromArray') {
                return;
            }
            if (changes) {
                changes.forEach(([row, prop, oldValue, newValue]) => {
                    if (row >= hot.countRows() - hot.getSettings().minSpareRows) {
                        let rowData = hot.getDataAtRow(row);
                        adicionarFila(rowData);
                    } else {
                        guardarCelda(hot, row, prop, newValue);
                    }
                });
            }
        },
        filters: true,
        dropdownMenu: ['filter_by_condition', 'filter_by_value', 'filter_action_bar'],
        columnSorting: true,
        contextMenu: {
            items: {
                "row_above": {
                    name: 'Insertar fila arriba',
                    disabled: function() {
                        return this.getSelectedLast() && this.getSelectedLast()[0] === 0;
                    }
                },
                "row_below": {
                    name: 'Insertar fila debajo'
                }
            }
        }
    });

    container.classList.add('dark-mode');

    var exportPlugin = hot.getPlugin('exportFile');
    var button = document.querySelector('#export-file');

    button.addEventListener('click', () => {
        exportPlugin.downloadFile('csv', {
            bom: false,
            columnDelimiter: ',',
            columnHeaders: false,
            exportHiddenColumns: true,
            exportHiddenRows: true,
            fileExtension: 'csv',
            filename: 'Programas',
            mimeType: 'text/csv',
            rowDelimiter: '\r\n',
            rowHeaders: true
        });
    });

    function adicionarFila(rowData) {
        alert("Datos de la fila: " + JSON.stringify(rowData));
    }

    function guardarCelda(hot, row, column, value) {
        var id = hot.getDataAtCell(row, 0);

        if (id == "" || id == null || id == undefined) {
            var accion = "Alta";
            var campo = obtenerCampoValor(column);
            var valor = value;

            if (valor) {
                $.ajax({
                    url: 'server/controlador_programa.php',
                    type: 'POST',
                    data: { campo, valor, accion },
                    dataType: 'json',
                    success: function (data) {
                        hot.setDataAtCell(row, 0, data.id_ram);
                        hot.setDataAtCell(row, 1, data.nom_ram);
                        hot.setDataAtCell(row, 2, data.cic_ram);
                        hot.setDataAtCell(row, 3, data.per_ram);
                        hot.setDataAtCell(row, 4, data.cos_ram);
                        hot.setDataAtCell(row, 5, data.eva_ram);
                        hot.setDataAtCell(row, 6, data.mod_ram);
                        hot.setDataAtCell(row, 7, data.gra_ram);
                        hot.setDataAtCell(row, 8, data.est_ram);
                        hot.setDataAtCell(row, 9, data.id_ram);
                    }
                });
            }
        } else {
            var accion = "Cambio";
            var campo = obtenerCampoValor(column);
            var valor = value;
            var id_ram = id;

            $.ajax({
                url: 'server/controlador_programa.php',
                type: 'POST',
                data: {
                    campo,
                    valor,
                    accion,
                    id_ram
                },
                dataType: 'json',
                success: function(data) {
                    if (data.resultado == 'false') {
                        hot.setDataAtCell(row, 0, '');
                        hot.setDataAtCell(row, 1, '');
                        hot.setDataAtCell(row, 2, '');
                        hot.setDataAtCell(row, 3, '');
                        hot.setDataAtCell(row, 4, '');
                        hot.setDataAtCell(row, 5, '');
                        hot.setDataAtCell(row, 6, '');
                        hot.setDataAtCell(row, 7, '');
                        hot.setDataAtCell(row, 8, '');
                        hot.setDataAtCell(row, 9, '');
                    }
                }
            });
        }
    }

    function obtenerCampoValor(column) {
        if (column == 1) {
            columnName = "nom_ram";
        } else if (column == 2) {
            columnName = "cic_ram";
        } else if (column == 3) {
            columnName = "per_ram";
        } else if (column == 4) {
            columnName = "cos_ram";
        } else if (column == 5) {
            columnName = "eva_ram";
        } else if (column == 6) {
            columnName = "mod_ram";
        } else if (column == 7) {
            columnName = "gra_ram";
        } else if (column == 8) {
            columnName = "est_ram";
        }
        return columnName;
    }

    function toggleReadOnlyColumns(hotInstance) {
        var columnsToToggle = [1, 2, 3, 4, 5, 6, 7, 8]; // Columnas que queremos habilitar/deshabilitar

        var currentColumnsSettings = hotInstance.getSettings().columns;

        columnsToToggle.forEach(function(colIndex) {
            if (currentColumnsSettings[colIndex]) {
                currentColumnsSettings[colIndex].readOnly = !currentColumnsSettings[colIndex].readOnly;
            }
        });

        hotInstance.updateSettings({
            columns: currentColumnsSettings
        });

        hotInstance.render();
    }

    $('#btn_bloqueo').click(function() {
        toggleReadOnlyColumns(hot);

        console.log('click func bloqueo');
        var icono = $('#icono_candado');
        if (icono.hasClass('fa-lock')) {
            icono.removeClass('fa-lock').addClass('fa-unlock');
            toastr.warning('¡⚠️  Datos desbloqueados!');
        } else {
            icono.removeClass('fa-unlock').addClass('fa-lock');
            toastr.info('¡Datos bloqueados!');
        }
    });
</script>