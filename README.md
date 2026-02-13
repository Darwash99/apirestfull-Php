🚀 Instalación y ejecución – Backend (API RESTful PHP)
📌 Requisitos

El proyecto fue desarrollado usando:

PHP: 8.3.16

Composer: 2.8.4

Servidor local: Laragon

Base de datos: MySQL

Instalación
1️⃣ Clonar el repositorio
    git clone URL_DEL_REPOSITORIO
    cd NOMBRE_DEL_PROYECTO

2️⃣ Instalar dependencias
    composer install

⚙️ Configuración
3️⃣ Configurar la base de datos

Editar el archivo:
    config/database.php
    Ajustar las credenciales según tu entorno local:
    return [
        'host' => 'localhost',
        'database' => 'nombre_base_datos',
        'username' => 'root',
        'password' => '',
    ];

4️⃣ Configuración de CORS
En la raíz del proyecto, editar el archivo:
    index.php

Buscar la línea:
header("Access-Control-Allow-Origin: *");

Si es necesario, reemplazar * por la URL del frontend, por ejemplo:
    header("Access-Control-Allow-Origin: http://localhost:5173");

Ejecutar el proyecto

Si usas Laragon, simplemente:

Iniciar Laragon.

Verificar que Apache y MySQL estén activos.

Acceder desde el navegador a:
    http://localhost/NOMBRE_DEL_PROYECTO