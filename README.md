*NOTA 1*
--Este proyecto muy basico en el cual eh querido trabajar poco a poco, le falta muchas mejoras y ajuste pero vamos dandole forma en otras versiones. *fue creado con HTML, CSS, JS, PHP Y usando como servidor local Xampp, en este proyecto esta la base de dato sql llamada odontologia.slq--*

*NOTA 2*
--Este pequeño proyecto esta en su version beta 0.1, funciona, realiza sus proceso, hay que corregir ciertos puntos, Hacerle algunas mejoras. etc. ---


1. Inicio del Proyecto: Módulo de Pacientes
  •	El modulo paciente cuenta con 2 opciones:
  o	Crear pacientes: La opción crear paciente cuenta con un formulario que tiene como Nombre completo, Fecha Nacimiento, Edad, Sexo,  Nacionalidad, Dirección, Contacto, Correo Electrónico, Aseguradora, No seguros.
  o	Listado de Paciente: Muestra una lista con todo los datos del usuario y tiene la función de Ver, Editar, Eliminar
  o	Exportación de datos: Se añadieron funcionalidades para exportar detalles de Pacientes a archivos CSV.
2. Inicio del Proyecto: Módulo de Citas
    •	Estructura básica de la aplicación: Se comenzó con la estructura inicial de la aplicación web utilizando HTML, PHP y una base de datos MySQL.
    •	Módulo de citas: Se implementaron las funcionalidades para crear, ver, editar y eliminar citas.
    •	Exportación de datos: Se añadieron funcionalidades para exportar detalles de citas y listas de especialistas a archivos CSV.
    •	Interfaz de usuario: Se hicieron mejoras en la interfaz de usuario utilizando CSS para que sea más amigable y fácil de usar.

3. Desarrollo del Módulo de Finanzas
  •	Formulario de registro de pagos: Se creó un formulario para registrar pagos, con un select dinámico para seleccionar citas y pacientes.
  •	Generación de facturas en PDF: Se implementó la generación de facturas en formato PDF, incluyendo detalles como el nombre del paciente y del especialista.
  •	Listado de facturas: Se modificó el listado de facturas para mostrar el número de cita y se incluyeron opciones de impresión y exportación para las facturas.
4. Funcionalidades de Resumen y Reportes
  •	Resumen general: Se agregó una sección de resumen en la página de inicio (index.php) que muestra un resumen general del sistema.
  •	Gráficos y estadísticas: Se añadieron gráficos de barras y líneas para mostrar los ingresos mensuales, la cantidad de citas por semana o mes, y la distribución de pagos por método (efectivo, tarjeta, etc.).
  •	Citas próximas: Se implementó una sección para mostrar una lista de citas programadas para el día o la semana.
  •	Pagos pendientes: Se agregó una lista de pagos pendientes o retrasados.
5. Funcionalidades de Alertas y Recordatorios
  •	Alertas de recordatorio: Se implementaron alertas de recordatorio para vencimientos de citas y pagos. Se mostraron estas alertas en la página de inicio de manera similar a las secciones de pagos pendientes y citas próximas.
6. Mejoras en la Experiencia de Usuario
  •	Mensajes de éxito: Se cambió el manejo de mensajes de confirmación, como "Pago registrado exitosamente" y "Pago actualizado exitosamente", para que se muestren en ventanas emergentes utilizando modales de Bootstrap y AJAX, evitando redirecciones no deseadas.
  •	Interfaz dinámica: Se mejoró la interacción del usuario con la aplicación, mostrando mensajes de confirmación y utilizando ventanas emergentes para notificar el éxito de ciertas acciones.
7. Funcionalidades de Historial y Filtrado
  •	Eliminar pagos facturados del listado principal: Se modificó la lógica para que los pagos que ya han sido facturados se eliminen del listado de pagos actual y se muevan a un historial de pagos.
  •	Página de historial de pagos: Se creó una página (historial_pagos.php) para listar los pagos que han sido facturados y ya no están en el listado principal de pagos.
8. Gestión de Facturación
  •	Procesar factura de manera dinámica: Se modificó procesar_factura.php para ser más dinámico y permitir una mejor gestión de facturas.
  •	Redirección tras generar factura: Se implementó una lógica para redirigir a la página de creación de facturas una vez que se haya generado una factura, proporcionando un flujo de trabajo más continuo.


*NOTA 1*
Este proyecto muy basico en el cual eh querido trabajar poco a poco, le falta muchas mejoras y ajuste pero vamos dandole forma en otras versiones.
