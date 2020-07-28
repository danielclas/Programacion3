<?php

// autoload_classmap.php @generated by Composer

$vendorDir = dirname(dirname(__FILE__));
$baseDir = dirname($vendorDir);

return array(
    'App\\Controllers\\MascotasController' => $baseDir . '/src/controllers/MascotasController.php',
    'App\\Controllers\\TurnosController' => $baseDir . '/src/controllers/TurnosController.php',
    'App\\Controllers\\UsuariosController' => $baseDir . '/src/controllers/UsuariosController.php',
    'App\\Middleware\\AllCapsMiddleware' => $baseDir . '/src/middlewares/AllCapsMiddleware.php',
    'App\\Middleware\\TokenValidateMiddleware' => $baseDir . '/src/middlewares/TokenValidateMiddleware.php',
    'App\\Middlewares\\JsonContentType' => $baseDir . '/src/middlewares/JsonConvertMiddleware.php',
    'App\\Models\\Mascota' => $baseDir . '/src/models/Mascota.php',
    'App\\Models\\TipoMascota' => $baseDir . '/src/models/TipoMascota.php',
    'App\\Models\\Turno' => $baseDir . '/src/models/Turnos.php',
    'App\\Models\\Usuario' => $baseDir . '/src/models/Usuario.php',
    'App\\Utils\\Authenticator' => $baseDir . '/src/utils/Authenticator.php',
    'App\\Utils\\RequestValidator' => $baseDir . '/src/utils/RequestValidator.php',
    'App\\Utils\\ResponseParser' => $baseDir . '/src/utils/ResponseParser.php',
    'App\\Utils\\ValidarPost' => $baseDir . '/src/utils/validarPost.php',
    'Config\\Database' => $baseDir . '/config/database.php',
    'JsonException' => $vendorDir . '/symfony/polyfill-php73/Resources/stubs/JsonException.php',
    'Normalizer' => $vendorDir . '/symfony/polyfill-intl-normalizer/Resources/stubs/Normalizer.php',
    'Stringable' => $vendorDir . '/symfony/polyfill-php80/Resources/stubs/Stringable.php',
    'ValueError' => $vendorDir . '/symfony/polyfill-php80/Resources/stubs/ValueError.php',
);