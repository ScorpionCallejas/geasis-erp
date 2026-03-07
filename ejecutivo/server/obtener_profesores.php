<?php
    require('../inc/cabeceras.php');
    require('../inc/funciones.php');

    $id_pla = $_POST['id_pla'];
    
?>

<div class="controls">
    <button id="export-file" class="btn btn-success btn-sm letraPequena"><i class="fas fa-file-excel"></i> excel</button>
</div>
<div id="data-sheet" class="hot" data-theme="dark"></div>

<script type="text/javascript">
    moment.locale('es');

    var container = document.querySelector('#data-sheet');
    var colHeaders = ['ID', 'NOMBRE', 'GÉNERO', 'CORREO', 'CONTRASEÑA', 'TELÉFONO', 'INGRESO',  'ESTATUS', 'ESPECIALIDAD', 'ID*'];
    var data = Array(0).fill(0).map(() => ["", "", "", "", "", "", "", "", ""]);;;  // Declaración al inicio del script o función
    
<?php
    $sql = "
      SELECT *
      FROM profesor
      WHERE id_pla2 = '$id_pla' 
      ORDER BY id_pro DESC
    ";

   

    $total = obtener_datos_consulta($db, $sql)['total'];

    //echo $sql;

    if ( $total != 0 ) {
        $resultado = mysqli_query($db, $sql);

        while($fila = mysqli_fetch_assoc($resultado)){
            $id_pro = json_encode($fila['id_pro']);
            $nom_pro = json_encode($fila['nom_pro'].' '.$fila['app_pro'].' '.$fila['apm_pro']);
            $gen_pro = json_encode($fila['gen_pro']);
            $cor_pro = json_encode($fila['cor_pro']);
            $pas_pro = json_encode($fila['pas_pro']);
            $tel_pro = json_encode($fila['tel_pro']);
            $ing_pro = json_encode($fila['ing_pro']);
            $est_pro = json_encode($fila['est_pro']);
            $esp_pro = json_encode($fila['esp_pro']);
            $id_pro = json_encode($fila['id_pro']);
            
            echo "data.push([$id_pro, $nom_pro, $gen_pro, $cor_pro, $pas_pro, $tel_pro, $ing_pro, $est_pro, $esp_pro, $id_pro]);\n";
        }

    } else {
        echo 'data = Array(15).fill(0).map(() => ["", "", "", "", "", "", "", "",""]);';
    }
    
?>
    

    var hot;  // Declaración al inicio del script o función

    if (hot) {
        hot.destroy();
    }

    function firstColumnRenderer(instance, td, row, col, prop, value, cellProperties) {
        Handsontable.renderers.TextRenderer.apply(this, arguments);
        td.style.backgroundColor = '#17202A'; // Cambia el color de fondo
    }

    hot = new Handsontable(container, {
        language: 'es-MX',
        data,
        

        // cells: function deshabilitarFila(row, col) {
        //     var cellProperties = {};

        //     if ( col === 0 || col === 2 ) { // Verifica si es la primera columna
        //       cellProperties.renderer = firstColumnRenderer; // Usa un renderizador personalizado
        //     }

        //     return cellProperties;
        // },
        height: 'auto',
        width: '100%',
        // hiddenColumns: {
        //     columns: [0], // Esconde la columna en el índice 0 (es decir, la primera columna, que sería el ID)
        //     indicators: false // Esto oculta el indicador de columnas ocultas
        // },
        stretchH: 'all',
        colHeaders: colHeaders,
        rowHeaders: true,

        manualColumnResize: true,
        minRows: 20,
        minSpareRows: 1,
        licenseKey: 'non-commercial-and-evaluation',
        afterChange: function(changes, source) {
            if (source === 'loadData' || source === 'populateFromArray') {
                // Si la fuente es loadData o populateFromArray, no hacer nada
                return;
            }
            if (changes) {
                changes.forEach(([row, prop, oldValue, newValue]) => {
                    // Ignora los cambios en las columnas 0 y 2
                    // POR EJEMPLO ID Y FEC CAPTURA QUE SON DATOS FIJOS
                    if (prop === 0 || prop === 2) {
                        return;
                    }

                    if (row >= hot.countRows() - hot.getSettings().minSpareRows) {
                        // Si es una fila nueva
                        let rowData = hot.getDataAtRow(row);
                        adicionarFila(rowData);
                    } else {
                        // Si es una fila existente
                        guardarCelda(hot, row, prop, newValue);
                    }
                });
            }
        },
        afterChange: function(changes, source) {
            if (source === 'loadData' || source === 'populateFromArray') {
                // Si la fuente es loadData o populateFromArray, no hacer nada
                return;
            }
            if (changes) {
                changes.forEach(([row, prop, oldValue, newValue]) => {
                    if (row >= hot.countRows() - hot.getSettings().minSpareRows) {
                        // Si es una fila nueva
                        let rowData = hot.getDataAtRow(row);
                        adicionarFila(rowData);
                    } else {
                        // Si es una fila existente
                        guardarCelda(hot, row, prop, newValue);
                    }
                });
            }
        },

        filters: true,
        dropdownMenu: ['filter_by_condition', 'filter_by_value', 'filter_action_bar'],
        height: 'auto',
        columnSorting: true,

        contextMenu: {
            items: {
                "row_above": {
                    name: 'Insertar fila arriba',
                    disabled: function() {
                        // Deshabilitar cuando la primera fila está seleccionada
                        return this.getSelectedLast() && this.getSelectedLast()[0] === 0;
                    }
                },
                "row_below": {
                    name: 'Insertar fila debajo'
                }
            }
        },


        columns: [
            {
                readOnly: true,
            },  // Configuración por defecto para ID

            {
                
            },  // Configuración por defecto para Nombre
            {
                
            },

            {
                  // Estatus que vi en la imagen
            },
            {
                
            },   // Configuración por defecto para Teléfono
            {
                
            },
            { 
                
            },
            {
                
            },
            {
                
            }   // Configuración por defecto para obs
        ]
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
        filename: 'Asesores',
        mimeType: 'text/csv',
        rowDelimiter: '\r\n',
        rowHeaders: true
      });
    });



    function adicionarFila(rowData) {

        alert("Datos de la fila: " + JSON.stringify(rowData));

        // $.ajax({
        //     type: "POST",
        //     url: "adicionar_fila.php",
        //     data: { data: rowData },
        //     success: function(response) {
        //         console.log("Fila añadida con éxito!");
        //     },
        //     error: function(error) {
        //         console.log("Hubo un problema al añadir la fila.");
        //     }
        // });
    }

    function guardarCelda(hot, row, column, value) {

        var id = hot.getDataAtCell(row, 0); // Obtener el valor de la primera columna (ID) para la fila específica
        //alert("ID: " + id + "\nFila: " + row + "\nColumna: " + column + "\nValor: " + value);
        if ( id == "" || id == null || id == undefined ) {
            // ALTA
            //alert("no hay datos en esta fila");

            var accion = "Alta";
            var campo = obtenerCampoValor( column );
            var valor = value;

            // alert( valor );

            // ADD

            if(valor){

                 var id_pla = $('#selectorPlantel option:selected').attr('id_pla');

                 $.ajax({
                     url: 'server/controlador_profesores.php',
                     type: 'POST',
                     data: { campo, valor, accion, id_pla },
                    dataType: 'json',
                    success: function( data ){
                     //success
                         console.log(data);
                        
                         hot.setDataAtCell(row, 0, data.id_pro);
                         hot.setDataAtCell(row, 1, data.nom_pro);
                         hot.setDataAtCell(row, 2, data.gen_pro);
                         hot.setDataAtCell(row, 3, data.cor_pro);
                         hot.setDataAtCell(row, 4, data.pas_pro);
                         hot.setDataAtCell(row, 5, data.tel_pro);
                         hot.setDataAtCell(row, 6, data.ing_pro);
                         hot.setDataAtCell(row, 7, data.est_pro);
                         hot.setDataAtCell(row, 8, data.esp_pro);
                         
                         //hot.setDataAtCell(row, 7, data.obs_eje);

                    }

                });

             }

        } else {

           // UPDATE
            var accion = "Cambio";
            var campo = obtenerCampoValor( column );
            var valor = value;
            var id_pro = id;

            $.ajax({
                url: 'server/controlador_profesores.php',
                type: 'POST',
                data: { campo, valor, accion, id_pro },
                dataType: 'json',
                success: function( data ){
                //success

                    console.log(data);
                    if ( data.resultado == 'false' ) {
                        hot.setDataAtCell(row, 0, '');
                        hot.setDataAtCell(row, 6, '');
                    }

                    // console.log(data.resultado);
                }

            });

        }

        function obtenerCampoValor( column ){

//          alert("obtenerCampoValor");
            if (column == 1) {
                columnName = "nom_pro";
            } else if (column == 2) {
                columnName = "gen_pro";
            } else if (column == 3) {
                columnName = "cor_pro";
            } else if (column == 4) {
                columnName = "pas_pro";
            } else if (column == 5) {
                columnName = "tel_pro";
            } else if (column == 6) {
                columnName = "ing_pro";
            } else if (column == 7) {
                columnName = "est_pro";
            } else if (column == 8) {
                columnName = "esp_pro";
            }

            return columnName;

        }
    }


    
</script>