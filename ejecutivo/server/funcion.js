// Función para adicionar pagos de colegiatura a un alumno recién inscrito
export const generarPagos = async (matricula, grupo_id, responsable) => {
  const data = await getDatosGrupo(grupo_id);
 // let inicio = "2023-07-31T06:00:00.000Z";
 let inicio = data.fechaInicio;
 let fin = data.fechaFin;
 // let periodicidad = data.planCalendario;
 let periodicidad = "Semestral";
 let duracionMensualidades = data.planDuracion;
 let precioColegiatura = data.planMensual;
 let precioCertificacion = data.precioCertificacion;


 let cde = data.cde;


 const fechaInicioAux = new Date(inicio);
 const dia = fechaInicioAux.getDate();


 let fechaInicio;
 let fechaFin;


 let fechaInicio2;
 let fechaFin2;


 // Validar si el día está entre 1 y 15
 if (dia >= 1 && dia <= 15) {
   // console.log("La fecha de inicio está entre el 1 y el 15 del mes.");
  
   // Obtener el año y el mes de la fecha
   const year = fechaInicioAux.getFullYear();
   const month = fechaInicioAux.getMonth();


   // Obtener el día 1 y 5 del mes
   fechaInicio = new Date(year, month, 1);
   fechaFin = new Date(year, month, 5);


   fechaInicio2 = sumarUnMes(fechaInicio);
   fechaFin2 = sumarUnMes(fechaFin);


 } else {


   const fechaInicioObj = '26-02-2023';
   const fechaInicioAux = '26-02-2023';

   const diaSemana = fechaInicioObj.getDay();
   // diaSemana = '7';

   // Calcula la cantidad de días para llegar al próximo viernes (5 es el índice del viernes en JavaScript)
   const diferenciaDias = (5 - diaSemana + 7) % 7;
   // diferenciaDias = ( 5 - 7 + 7 ) % 7;


   // Clonamos la fecha de inicio para no modificarla directamente
   const proximoViernesObj = new Date(fechaInicioObj);
   proximoViernesObj.setDate(fechaInicioObj.getDate() + diferenciaDias);


   const proximoViernes = proximoViernesObj.toISOString(); // Retorna el próximo viernes en formato ISO 8601 (cad


   fechaInicio = fechaInicioAux;
   fechaFin = sumarDias( proximoViernes, 7 );


   fechaInicio2 = cambiarDiaFecha(sumarUnMes(fechaInicio), 10);
   fechaFin2 = cambiarDiaFecha(fechaInicio2, 15);


 }


 //VARIABLES COLEGIATURA 1
 let monto_adeudo = precioColegiatura;
 let monto_original = precioColegiatura;
 let folio = cde;
 let concepto = "Colegiatura 1";
 // let responsable = responsable;
 let fecha_inicio = fechaInicio;
 let fecha_fin = fechaFin;
 let tipo_id = 2;
 let factura = "001";
 let visible = 1;
 // let matricula = matricula;
 // VARIABLES COLEGIATURA 1


 //INSERCION COLAGIATURA 1
 insertarPago(monto_adeudo, monto_original, folio, concepto, responsable, fecha_inicio, fecha_fin, tipo_id, factura, visible, matricula);
 // FIN INSERCION COLEGIATURA 1


 //VARIABLES COLEGIATURA 2
 monto_adeudo = precioColegiatura;
 monto_original = precioColegiatura;
 folio = cde;
 concepto = "Colegiatura 2";
 // let responsable = responsable;
 fecha_inicio = fechaInicio2;
 fecha_fin = fechaFin2;
 tipo_id = 2;
 factura = "002";
 visible = 0;
 // let matricula = matricula;
 // VARIABLES COLEGIATURA 2


 // INSERCION COLEGIATURA 2
 insertarPago(monto_adeudo, monto_original, folio, concepto, responsable, fecha_inicio, fecha_fin, tipo_id, factura, visible, matricula);
 // FIN INSERCION COLEGIATURA 2


 //VARIABLES TRAMITES
 monto_adeudo = precioCertificacion;
 monto_original = precioCertificacion;
 folio = cde;
 concepto = "Trámites";
 // let responsable = responsable;
 fecha_inicio = fechaInicio2;
 fecha_fin = fechaFin2;
 tipo_id = 3;
 factura = "Trámites";
 visible = 0;
 // let matricula = matricula;
 // VARIABLES TRAMITES


 // INSERCION TRAMITES
 insertarPago(monto_adeudo, monto_original, folio, concepto, responsable, fecha_inicio, fecha_fin, tipo_id, factura, visible, matricula);
 // FIN INSERCION TRAMITES


 // console.log( "1- cole1--- inicio: ",fechaInicio,"-- fin: ",fechaFin );
 // console.log( "2- cole2--- inicio: ",fechaInicio2,"-- fin: ",fechaFin2 );
  let fechaInicioColegiaturas;
 let fechaFinColegiaturas;
  for (var i = 0, numero = 3; i < (duracionMensualidades - 2); i++, numero++) {
   if (i == 0) {
     fechaInicioColegiaturas = cambiarDiaFecha(sumarUnMes(fechaInicio2), 1);
     fechaFinColegiaturas = cambiarDiaFecha(fechaInicioColegiaturas, 5);
   } else {
     fechaInicioColegiaturas = sumarUnMes(fechaInicioColegiaturas);
     fechaFinColegiaturas = cambiarDiaFecha(fechaInicioColegiaturas, 5);
   }
    // VARIABLES COLEGIATURA N
   monto_adeudo = precioColegiatura;
   monto_original = precioColegiatura;
   folio = cde;
   concepto = `Colegiatura ${numero}`;
   // let responsable = responsable;
   fecha_inicio = fechaInicioColegiaturas;
   fecha_fin = fechaFinColegiaturas;
   tipo_id = 1;
   factura = `00${numero}`;
   visible = 0;
   // let matricula = matricula;
   // VARIABLES COLEGIATURA N
    // INSERCION COLEGIATURA N
   insertarPago(monto_adeudo, monto_original, folio, concepto, responsable, fecha_inicio, fecha_fin, tipo_id, factura, visible, matricula);
   // FIN INSERCION COLEGIATURA N
    // console.log( (i+3),"-cole",(i+3),"--- inicio: ",fechaInicioColegiaturas,"-- fin: ",fechaFinColegiaturas );
 }
 }


const insertarPago = async (monto_adeudo, monto_original, folio, concepto, responsable, fecha_inicio, fecha_fin, tipo_id, factura, visible, matricula) => {
 try {
   const [rows] = await pool.query(
     "INSERT INTO pagos(monto_adeudo, monto_original, folio, concepto, responsable, fecha_inicio, fecha_fin, tipo_id, factura, visible, matricula) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
     [monto_adeudo, monto_original, folio, concepto, responsable, formatearFecha(fecha_inicio), formatearFecha(fecha_fin), tipo_id, factura, visible, matricula]
   );
  
 } catch (error) {
   // Manejo del error
   console.error("Error al insertar el pago:", error);
 }
};


export const fechaMesAnnio = (dateString) => {
 const date = new Date(dateString);


 // JavaScript cuenta los meses del 0 al 11, por eso se suma 1
 const month = String(date.getMonth() + 1).padStart(2, '0');
 const year = String(date.getFullYear()).slice(-2);


 return `${month}/${year}`;
};


export const esFechaVigente = (dateString) => {
 const fechaObjetivo = new Date(dateString);
 const fechaActual = new Date();


 // Poner la hora, minuto, segundo y milisegundo a 0 para comparar solo la fecha
 fechaActual.setHours(0, 0, 0, 0);
 fechaObjetivo.setHours(0, 0, 0, 0);


 return fechaObjetivo >= fechaActual;
};
