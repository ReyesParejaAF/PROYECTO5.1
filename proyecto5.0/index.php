<?php
require_once "models/database.php"; // Incluir configuración de la base de datos
require_once "models/User.php"; // Incluir el modelo User
require 'vendor/autoload.php'; // Incluir composer

// Mapeo de nombres de controladores en español a inglés
$controllerMap = [
    'usuario' => 'Users',
    'Users' => 'Users'
    // Agrega otros mapeos si es necesario
];

// Verificar el controlador y la acción solicitada
if (!isset($_REQUEST['controller'])) {
    // Si no se especifica un controlador, cargar el controlador de Landing o el que desees
    require_once "controllers/Landing.php";
    $controller = new Landing();
    $controller->main();
} else {

    session_start();
    // Obtener el nombre del controlador de la solicitud
    $controllerName = $_REQUEST['controller'];

    // Convertir el nombre del controlador al formato esperado (si es necesario)
    if (isset($controllerMap[$controllerName])) {
        $controllerName = $controllerMap[$controllerName];
    }

    // Construir el nombre del archivo del controlador
    $controllerFile = "controllers/" . $controllerName . ".php"; // Ejemplo: "controllers/Users.php"

    // Verificar que el archivo del controlador exista antes de incluirlo
    if (file_exists($controllerFile)) {
        require_once $controllerFile;

        // Verificar que la clase exista en el archivo
        if (class_exists($controllerName)) {
            // Crear una instancia del controlador
            $controller = new $controllerName(); // Asegúrate de que la clase coincida con el nombre del archivo

            // Determinar la acción a realizar
            $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'mostrarFormularioRegistro'; // Acción por defecto

            // Verificar que la acción exista en el controlador antes de llamarla
            if (method_exists($controller, $action)) {
                call_user_func(array($controller, $action));
            } else {
                // Manejar el caso donde la acción no existe
                echo "La acción '$action' no existe en el controlador '$controllerName'.";
            }
        } else {
            // Manejar el caso donde la clase del controlador no existe
            echo "La clase '$controllerName' no existe en el archivo '$controllerFile'.";
        }
    } else {
        // Manejar el caso donde el archivo del controlador no existe
        echo "El archivo del controlador '$controllerFile' no existe.";
    }
}
?>



