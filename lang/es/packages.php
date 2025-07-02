<?php

return [
    'label' => 'Paquete',
    'plural' => 'Paquetes',
    'navigation' => [
        'label' => 'Paquetes',
        'group' => 'Paquete',
    ],
    'form' => [
        'url' => [
            'composer' => 'URL del Repositorio de Composer',
            'github' => 'Lanzamientos de Paquetes',
        ],
        'username' => [
            'composer' => 'Nombre de Usuario de Composer',
            'github' => 'Nombre de Usuario u Organización de GitHub',
        ],
        'password' => [
            'composer' => 'Contraseña de Composer',
            'github' => 'Token de Acceso Personal (PAT)',
        ],
    ],
    'instructions' => [
        'composer' => [
            'label' => 'Configuración de Composer',
            'content' => 'Para agregar un paquete de Composer, debe proporcionar las credenciales de acceso para el repositorio privado. El producto será sublicenciado usando Satis. Cada miembro del equipo recibirá credenciales de acceso individuales. No comparta estas credenciales con nadie.',
        ],
        'github' => [
            'label' => 'Configuración de GitHub',
            'content' => 'Para agregar un paquete de GitHub, debe proporcionar las credenciales de acceso para el repositorio privado. El producto será sublicenciado usando GitHub Packages. Cada miembro del equipo recibirá un Token de Acceso Personal (PAT). No comparta estas credenciales con nadie.',
        ],
    ],
    'validations' => [
        'name' => [
            'composer' => 'El nombre del paquete debe seguir el formato "proveedor/paquete".',
            'github' => 'El nombre del paquete debe seguir el formato "usuario/repositorio".',
        ],
        'url' => [
            'github' => 'Use una URL SSH válida para el repositorio de GitHub.',
        ],
        'password' => [
            'github' => 'El Token de Acceso Personal (PAT) debe seguir el formato "github_pat_".',
        ],
    ],
    'infolist' => [
        'name' => 'Nombre',
        'type' => 'Tipo',
        'url' => 'Url',
        'composer_command' => 'Comando do composer',
        'section' => [
            'package_releases' => 'Lanzamientos de Paquetes',
            'package_release' => 'Lanzamiento de Paquete',
            'dependencies' => 'Dependencias',
        ],
    ],
    'table' => [
        'name' => 'Nombre',
        'package_releases_count' => 'Lanzamientos de Paquetes',
        'type' => 'Tipo',
        'url' => 'Url',
        'created_at' => 'Creado El',
        'updated_at' => 'Actualizado El',
    ],
];
