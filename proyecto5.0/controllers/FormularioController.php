<?php

require_once __DIR__ . '\..\models\Formulario.php';

class FormularioController {
    
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }

            if (!isset($_SESSION['user_id'])) {
                $_SESSION['user_id'] = 1; // Esto es solo un ejemplo.
            }

            $user_id = $_SESSION['user_id'];
            $formulario = new Formulario();
            
            // Obtener el id_mascota correspondiente al user_id
            $id_mascota = $formulario->getMascotaIdByUserId($user_id);

            if (!$id_mascota) {
                echo "<script>alert('No se encontró una mascota asociada a este usuario. Por favor, registre una mascota primero.'); window.location.href='index.php?controller=WelcomeController&action=index';</script>";
                return;
            }

            $formulario->nombre_propietario = isset($_POST['name']) ? $_POST['name'] : '';
            $formulario->numero_contacto = isset($_POST['tel']) ? $_POST['tel'] : '';
            $formulario->nombre_mascota = isset($_POST['mascota']) ? $_POST['mascota'] : '';
            $formulario->raza_mascota = isset($_POST['razamascota']) ? $_POST['razamascota'] : '';
            $formulario->servicio = isset($_POST['servicio']) ? $_POST['servicio'] : ''; 
            $formulario->fecha_cita = isset($_POST['fecha']) ? $_POST['fecha'] : '';
            $formulario->hora_cita = isset($_POST['hora']) ? $_POST['hora'] : '';
            $formulario->id_mascota = $id_mascota;

            if ($formulario->create()) {
                echo "<script>alert('Cita agendada exitosamente.'); window.location.href='index.php?controller=FormularioController&action=create';</script>";
            } else {
                echo "<script>alert('Error al agendar la cita.'); window.location.href='index.php?controller=FormularioController&action=create';</script>";
            }
        } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }

            if (!isset($_SESSION['user_id'])) {
                $_SESSION['user_id'] = 1; // Esto es solo un ejemplo.
            }

            $user_id = $_SESSION['user_id'];
            $formulario = new Formulario();
            $mascota_data = $formulario->getMascotaDataByUserId($user_id);

            require_once 'views/create.php';
        } else {
            echo "Método de solicitud no permitido.";
        }
    }

    public function list() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['user_id'] = 1; // Esto es solo un ejemplo.
        }
    
        $user_id = $_SESSION['user_id'];
        $formulario = new Formulario();
        
        // Verifica el id_mascota
        $id_mascota = $formulario->getMascotaIdByUserId($user_id);
        // Esto debería mostrar el id_mascota para depuración
    
        $citas = $formulario->getAllByMascota($id_mascota);
    
        if (empty($citas)) {
            echo "<script>alert('No se encontraron citas para este usuario.');</script>";
        }
    
        require_once 'views/lista_citas.php';
    }
    

    public function delete() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['user_id'] = 1; // Esto es solo un ejemplo.
        }

        $user_id = $_SESSION['user_id'];
        $id_cita = $_GET['id'] ?? null;

        if ($id_cita) {
            $formulario = new Formulario();
            if ($formulario->deleteById($id_cita, $user_id)) {
                echo "<script>alert('Cita borrada exitosamente.'); window.location.href='index.php?controller=FormularioController&action=list';</script>";
            } else {
                echo "<script>alert('Error al borrar la cita.'); window.location.href='index.php?controller=FormularioController&action=list';</script>";
            }
        }
    }

    public function edit() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            if (!isset($_SESSION['user_id'])) {
                $_SESSION['user_id'] = 1; // Esto es solo un ejemplo.
            }
    
            $user_id = $_SESSION['user_id'];
            $id_cita = $_POST['id_cita'] ?? null;
            $formulario = new Formulario();
    
            // Obtener el id_mascota correspondiente al user_id
            $id_mascota = $formulario->getMascotaIdByUserId($user_id);
    
            if (!$id_mascota) {
                echo "<script>alert('No se encontró una mascota asociada a este usuario.');</script>";
                return;
            }
    
            if ($id_cita) {
                $formulario->id_cita = $id_cita;
                $formulario->nombre_propietario = $_POST['name'] ?? '';
                $formulario->numero_contacto = $_POST['tel'] ?? '';
                $formulario->nombre_mascota = $_POST['mascota'] ?? '';
                $formulario->raza_mascota = $_POST['razamascota'] ?? '';
                $formulario->servicio = $_POST['servicio'] ?? '';
                $formulario->fecha_cita = $_POST['fecha'] ?? '';
                $formulario->hora_cita = $_POST['hora'] ?? '';
                $formulario->id_mascota = $id_mascota;
    
                if ($formulario->update()) {
                    echo "<script>alert('Cita actualizada exitosamente.'); window.location.href='index.php?controller=FormularioController&action=list';</script>";
                } else {
                    echo "<script>alert('Error al actualizar la cita.'); window.location.href='index.php?controller=FormularioController&action=list';</script>";
                }
            }
        } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            if (!isset($_SESSION['user_id'])) {
                $_SESSION['user_id'] = 1; // Esto es solo un ejemplo.
            }
    
            $user_id = $_SESSION['user_id'];
            $id_cita = $_GET['id'] ?? null;
            $formulario = new Formulario();
    
            // Obtener el id_mascota correspondiente al user_id
            $id_mascota = $formulario->getMascotaIdByUserId($user_id);
    
            if (!$id_mascota) {
                echo "<script>alert('No se encontró una mascota asociada a este usuario.');</script>";
                return;
            }
    
            if ($id_cita) {
                $cita = $formulario->getById($id_cita, $id_mascota);
    
                if ($cita) {
                    require_once 'views/editar_cita.php';
                } else {
                    echo "<script>alert('Cita no encontrada.'); window.location.href='index.php?controller=FormularioController&action=list';</script>";
                }
            }
        } else {
            echo "Método de solicitud no permitido.";
        }
    }
}    