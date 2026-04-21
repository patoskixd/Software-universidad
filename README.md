# UniGest — Plataforma de Gestión de Solicitudes Administrativas

Sistema web para el registro, consulta y gestión de solicitudes administrativas universitarias, desarrollado como prueba técnica para la Dirección de Informática.

---

## Requisitos

- PHP 8.1 o superior
- MySQL (incluido en XAMPP)
- Apache (incluido en XAMPP)

---

## Instrucciones de ejecución

### 1. Clonar el repositorio

```bash
git clone https://github.com/patoskixd/Software-universidad
```

Colocar la carpeta en `C:/xampp/htdocs/` (o el directorio raíz del servidor).

### 2. Crear la base de datos

Ejecutar los scripts en orden desde phpMyAdmin o la consola MySQL:

```sql
source database/migrations/001_initial_schema.sql
source database/migrations/002_administrativos.sql
source database/migrations/003_login_attempts.sql
source database/migrations/004_add_observaciones.sql
source database/migrations/005_add_ip_solicitante.sql
```

### 3. Configurar conexión

Copiar el archivo de ejemplo y ajustar credenciales:

```bash
cp config/app.example.php config/app.php
```

Editar `config/app.php`:

```php
return [
    'db' => [
        'host'    => 'localhost',
        'name'    => 'sistema_universidad',
        'user'    => 'root',
        'pass'    => '',
        'charset' => 'utf8mb4',
    ],
];
```

### 4. Acceder al sistema

| URL | Descripción |
|---|---|
| `http://localhost/software-universidad/` | Formulario público |
| `http://localhost/software-universidad/login.php` | Acceso administrativo |

**Credenciales de administrador por defecto (solo entorno local):**

```
Correo:    admin@universidad.cl
Contraseña: admin123
```

> Estas credenciales son únicamente para desarrollo y evaluación local. Deben cambiarse antes de cualquier despliegue en un entorno real.

---

## Estructura del proyecto

```
software-universidad/
├── api/
│   └── solicitudes.php          # Punto de entrada de la API REST
├── app/
│   ├── controllers/
│   │   └── SolicitudController.php
│   ├── core/
│   │   ├── Auth.php             # Manejo de sesión y autenticación
│   │   ├── Csrf.php             # Protección CSRF
│   │   ├── Database.php         # Singleton de conexión PDO
│   │   ├── Middleware.php       # Ejecución de capas de seguridad
│   │   ├── RateLimit.php        # Límite de intentos (login y solicitudes públicas)
│   │   └── Response.php         # Respuestas JSON estandarizadas
│   └── models/
│       ├── Administrativo.php   # Autenticación de administradores
│       └── Solicitud.php        # CRUD y validación de solicitudes
├── assets/
│   ├── css/style.css            # Estilos globales y paleta de colores
│   └── js/
│       ├── main.js              # Lógica del panel admin
│       ├── public.js            # Lógica del formulario público
│       ├── templates.js         # Generación de HTML dinámico
│       └── utils.js             # Utilidades compartidas (escapeHtml, toasts)
├── config/
│   ├── app.example.php
│   └── app.php                  # Configuración local (no versionado)
├── database/
│   └── migrations/
│       ├── 001_initial_schema.sql
│       ├── 002_administrativos.sql
│       ├── 003_login_attempts.sql
│       ├── 004_add_observaciones.sql
│       └── 005_add_ip_solicitante.sql
├── views/
│   ├── auth/login.php           # Vista del formulario de autenticación
│   ├── layouts/main.php         # Layout compartido
│   ├── public/index.php         # Vista del formulario público
│   └── request/index.php        # Vista del panel administrativo
├── .htaccess                    # Cabeceras de seguridad HTTP
├── favicon.svg                  # Ícono de la aplicación
├── admin.php                    # Redirige al panel administrativo
├── index.php                    # Redirige al formulario público
├── login.php                    # Maneja autenticación y sesión del administrador
└── logout.php                   # Cierra sesión y redirige al login
```

---

## Funcionalidades implementadas

### Públicas (sin autenticación)

- **Registrar solicitud**: formulario con nombre, correo, tipo y descripción. Validación en cliente y servidor.
- **Consultar solicitudes por correo**: el solicitante puede buscar sus propias solicitudes ingresando su correo y ver el estado actual y observaciones del administrador.

### Administrativas (requieren login)

- **Listar solicitudes**: tabla paginada con ordenamiento por columna.
- **Filtrar**: por estado, tipo de solicitud y búsqueda de texto libre (nombre o correo).
- **Ver detalle**: modal con toda la información de la solicitud.
- **Actualizar estado**: cambiar entre Pendiente, En Revisión, Aprobada o Rechazada, con campo de observaciones opcional.
- **Exportar a Excel**: descarga un archivo `.csv` UTF-16LE compatible con Excel, respetando los filtros activos.
- **Estadísticas**: tarjetas con conteo en tiempo real por estado.

---

## Decisiones técnicas

### Arquitectura MVC simplificada

Se optó por una arquitectura MVC sin framework para cumplir el requerimiento de PHP puro, manteniendo separación clara entre modelos, controladores y vistas sin añadir dependencias externas.

### API REST con un único endpoint

Toda la comunicación del frontend se realiza a través de `api/solicitudes.php`, que despacha según el método HTTP (`GET`, `POST`, `PATCH`). Esto centraliza la lógica de negocio y simplifica el enrutamiento.

### PDO con prepared statements

Todas las consultas usan sentencias preparadas con PDO, eliminando la posibilidad de inyección SQL. Los parámetros nunca se interpolan directamente en el SQL.

### Protección CSRF

Los formularios HTML incluyen un token oculto y las peticiones AJAX lo envían en el header `X-CSRF-Token`. La validación usa `hash_equals()` para evitar timing attacks.

### Rate limiting en login

Se registra cada intento fallido en la tabla `login_attempts`. Si se superan 5 intentos en 10 minutos por IP, se bloquea el acceso temporalmente. Al hacer login exitoso se ejecuta un `cleanup()` que elimina registros vencidos, evitando crecimiento indefinido de la tabla.

### Rate limiting en solicitudes públicas

El endpoint de creación de solicitudes limita a 10 solicitudes por minuto por IP. La IP se almacena en la columna `ip_solicitante` de la tabla `solicitudes` y se consulta directamente para verificar el límite. Si se supera, el servidor responde con HTTP 429.

### Contraseñas con bcrypt

Las contraseñas de administradores se almacenan con `password_hash()` usando bcrypt, verificadas con `password_verify()`.

### Paginación del lado del servidor

El listado usa `LIMIT` y `OFFSET` en SQL con un valor por defecto de 10 registros por página y un máximo de 100 (`min(100, $limit)`), evitando consultas que devuelvan volúmenes arbitrarios de datos.

### Exportación Excel con UTF-16LE

Se genera un archivo `.csv` con separador de tabulación (TSV) y BOM UTF-16LE que Excel reconoce y abre correctamente con caracteres especiales del español.

### Cabeceras de seguridad HTTP

El archivo `.htaccess` define:

```
X-Frame-Options: DENY              → Previene clickjacking
X-Content-Type-Options: nosniff   → Previene MIME sniffing
Referrer-Policy: same-origin      → Limita información del referer
Permissions-Policy                 → Deshabilita cámara, micrófono y geolocalización
```

### Máquina de estados para solicitudes

Las transiciones de estado siguen reglas estrictas: `pendiente` y `en_revision` permiten avanzar a cualquier estado, mientras que `aprobada` y `rechazada` son **estados finales** que no admiten modificaciones posteriores. Si se intenta una transición inválida.

### Validación en dos capas

Toda entrada se valida en el frontend  y en el servidor. La validación del servidor incluye longitud máxima, formato de correo con `FILTER_VALIDATE_EMAIL`, regex Unicode para nombres y whitelist de valores permitidos para enumeraciones.

---

## Supuestos realizados

- Un mismo correo puede registrar múltiples solicitudes, no se requiere autenticación del solicitante.
- Las observaciones del administrador son opcionales y solo se persisten cuando el estado final es "Aprobada" o "Rechazada".
- El correo electrónico se normaliza a minúsculas al crear una solicitud y al consultar por correo, permitiendo búsquedas insensibles a mayúsculas/minúsculas.
- Las solicitudes no pueden eliminarse, no existe operación de borrado y el historial es permanente.
- El sistema opera en red interna, no se implementó HTTPS.
- No se requirió un sistema de roles múltiples, existe un único perfil de administrador.

---

## Limitaciones conocidas

- **Consulta pública sin verificación de identidad**: cualquier persona que conozca el correo electrónico de un solicitante puede ver sus solicitudes y su estado. Podría mitigarse con un token de consulta enviado al correo al momento de registrar la solicitud.
- **Sin notificaciones por correo**: el sistema no envía emails al solicitante cuando su solicitud cambia de estado. Podría implementarse con PHPMailer.
- **Sin gestión de administradores desde la interfaz**: agregar o eliminar administradores requiere acceso directo a la base de datos. Podría resolverse con un panel CRUD protegido por el mismo sistema de autenticación.
- **Rate limiting por IP con NAT**: en redes donde varios usuarios comparten la misma IP pública, un bloqueo por IP afectaría a todos simultáneamente. Podría complementarse con rate limiting por sesión o usuario autenticado.
- **Sin HTTPS forzado**: se asume que el servidor de producción maneja el certificado SSL a nivel de infraestructura.
- **CORS abierto**: el header `Access-Control-Allow-Origin: *` permite peticiones desde cualquier dominio. En producción debería restringirse al dominio institucional.
- **Sin recuperación de contraseña**: el administrador no puede restablecer su contraseña desde la interfaz. Requiere acceso directo a la base de datos para actualizarla manualmente. Podría implementarse con un flujo de recuperación vía correo electrónico.

---

## Mejoras posibles

- Notificación por correo al actualizar estado (PHPMailer + plantilla HTML)
- Envío de token de consulta al correo del solicitante al registrar una solicitud, reemplazando la consulta pública abierta por una verificada
- Recuperación de contraseña para administradores mediante enlace enviado al correo
- Panel de gestión de administradores desde la interfaz
- Adjuntar archivos a las solicitudes
- Historial de cambios de estado por solicitud
- Autenticación de solicitantes para seguimiento personalizado
- Restricción de CORS al dominio institucional en producción
