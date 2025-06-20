# OhSanSi Backend

Aplicación desarrollada en **Laravel 11** para gestionar la inscripción y administración de las Olimpiadas "Oh! SanSi". Provee un API REST para registro de participantes, manejo de pagos y operaciones de importación desde Excel.

## Requisitos

- PHP >= 8.2 con extensiones `mbstring`, `curl`, `xml`, `gd`
- Composer
- Base de datos PostgreSQL

## Instalación

```bash
# Clona el repositorio
git clone https://github.com/andyortz/OhSanSi-Backend.git
cd OhSanSi-Backend

# Instala dependencias
composer install

# Copia la configuración de entorno y genera la clave
cp .env.example .env
php artisan key:generate

# Configura las credenciales de la base de datos en .env

# Ejecuta migraciones y seeders
php artisan migrate --seed

# Crea el enlace a la carpeta de almacenamiento
php artisan storage:link

# Inicia el servidor local
php artisan serve
```

La aplicación estará disponible en `http://localhost:8000`.

## Estructura de carpetas

```
app/
├── Http/Controllers    # Controladores de la API
├── Models              # Modelos Eloquent
├── Services            # Lógica de negocio (importación Excel, OCR, registros)
├── Repositories        # Consultas y operaciones complejas
├── Imports             # Clases de importación
└── Providers           # Proveedores de servicios

routes/
├── api.php             # Endpoints públicos del API
├── web.php             # Rutas web
└── console.php         # Comandos Artisan

database/
├── migrations          # Definición de tablas
└── seeders             # Carga de datos iniciales
```

## Explicación técnica

El proyecto sigue el patrón **MVC** de Laravel y amplía la arquitectura con servicios y repositorios:

- **Controladores**: orquestan las solicitudes entrantes y utilizan los servicios para procesar la lógica. Se encuentran en `app/Http/Controllers`.
- **Servicios**: encapsulan la lógica de negocio como la importación de archivos Excel (`app/Services/Excel`) o el procesamiento de boletas mediante OCR.
- **Repositorios**: clases en `app/Repositories` para consultas a nivel de base de datos que requieren personalización.
- **Migraciones y Seeders**: permiten construir la estructura de tablas y poblar datos de referencia (ubicadas en `database/migrations` y `database/seeders`).
- **Autenticación**: se implementa con Laravel Sanctum para emitir y validar tokens.

Para conocer todas las rutas disponibles consulta `routes/api.php`.

---

¡Listo! Con estos pasos tendrás el backend ejecutándose de manera local.
