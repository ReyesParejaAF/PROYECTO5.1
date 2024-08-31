<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cita</title>
    <link rel="stylesheet" href="assets/Andres/styles/admin.css">
    <?php include 'partials/header.php'?>
</head>
<body>
    <div class="citas1">
        <div class="top-buttons">
            <h5>Modifique su cita:</h5>
            <a href="index.php?controller=FormularioController&action=list" class="btn btn-primary">Ver Mis Citas</a>
            <a href="index.php?controller=WelcomeController&action=index" class="btn btn-secondary ms-2">Volver a Bienvenida</a>
        </div>
        
        <form id="citaForm" action="index.php?controller=FormularioController&action=edit" method="POST" onsubmit="return validarFormulario(event);">
            <input type="hidden" name="id_cita" value="<?php echo $cita['id_cita']; ?>">
            <label for="nombre">Nombre del propietario:*</label>
            <input type="text" name="name" id="nombre" value="<?php echo $cita['nombre_propietario']; ?>" required>

            <label for="contacto">Número de contacto:*</label>
            <input type="tel" name="tel" id="contacto" value="<?php echo $cita['numero_contacto']; ?>" required>

            <label for="nmascota">Nombre de la mascota:</label>
            <input type="text" name="mascota" id="nmascota" value="<?php echo $cita['nombre_mascota']; ?>">

            <label for="rmascota">Raza de la mascota:*</label>
            <input type="text" name="razamascota" id="rmascota" value="<?php echo $cita['raza_mascota']; ?>" required>

            <label for="servicio">Servicio:*</label>
            <input type="text" name="servicio" id="servicio" value="<?php echo $cita['servicio']; ?>" required>

            <label for="fecha">Fecha de la cita:*</label>
            <input type="date" name="fecha" id="fecha" value="<?php echo $cita['fecha_cita']; ?>" required><br><br>

            <label for="hora">Hora de la cita:*</label>
            <input type="time" name="hora" id="hora" value="<?php echo $cita['hora_cita']; ?>" required>

            <br><br>

            <input type="submit" value="Actualizar Cita">
        </form>

        <script>
            function validarFormulario(event) {
                var nombre = document.getElementById('nombre').value.trim();
                var contacto = document.getElementById('contacto').value.trim();
                var nmascota = document.getElementById('nmascota').value.trim();
                var rmascota = document.getElementById('rmascota').value.trim();
                var servicio = document.getElementById('servicio').value.trim();
                var fecha = document.getElementById('fecha').value;
                var hora = document.getElementById('hora').value;

                // Validación de campos de texto
                var textoRegex = /^[A-Za-z\s]+$/;

                if (!textoRegex.test(nombre)) {
                    alert('El campo "Nombre del propietario" debe contener solo letras.');
                    event.preventDefault(); // Detiene el envío del formulario
                    return false;
                }

                if (!textoRegex.test(nmascota)) {
                    alert('El campo "Nombre de la mascota" debe contener solo letras.');
                    event.preventDefault();
                    return false;
                }

                if (!textoRegex.test(rmascota)) {
                    alert('El campo "Raza de la mascota" debe contener solo letras.');
                    event.preventDefault();
                    return false;
                }

                if (!textoRegex.test(servicio)) {
                    alert('El campo "Servicio" debe contener solo letras.');
                    event.preventDefault();
                    return false;
                }   

                if (nombre === '' || contacto === '' || nmascota === '' || rmascota === '' || servicio === '' || fecha === '' || hora === '') {
                    alert('Por favor, complete los campos obligatorios (*) para proceder al envío.');
                    event.preventDefault();
                    return false;
                }

                var telRegex = /^3\d{9}$/;

                if (!telRegex.test(contacto)) {
                    alert('Por favor, ingrese un número de contacto válido.');
                    event.preventDefault();
                    return false;
                }

                // Validación de horario de atención
                var selectedDate = new Date(fecha + 'T' + hora);
                var dayOfWeek = selectedDate.getDay();
                var selectedHour = parseInt(hora.split(':')[0], 10);
                var selectedMinutes = parseInt(hora.split(':')[1], 10);

                if (dayOfWeek >= 1 && dayOfWeek <= 5) { // Lunes a Viernes
                    if (selectedHour < 8 || (selectedHour >= 18 && selectedMinutes > 0)) {
                        alert('El horario de atención de lunes a viernes es de 8:00 AM a 6:00 PM. Seleccione una hora dentro de este rango.');
                        event.preventDefault();
                        return false;
                    }
                } else if (dayOfWeek === 0 || dayOfWeek === 6) { // Sábados y Domingos
                    if (selectedHour < 11 || (selectedHour >= 16 && selectedMinutes > 0)) {
                        alert('El horario de atención de sábados y domingos es de 11:00 AM a 4:00 PM. Seleccione una hora dentro de este rango.');
                        event.preventDefault();
                        return false;
                    }
                }

                // Validación de fecha y hora futura
                var today = new Date();
                var selectedDate = new Date(fecha + 'T' + hora);

                if (selectedDate < today) {
                    alert('Seleccione una fecha y hora futura para la cita.');
                    event.preventDefault();
                    return false;
                }

                return true; // Permite el envío del formulario si todo es correcto
            }
        </script>
    </div>
</body>
</html>
