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
git clone <url-repositorio> software-universidad
```

Colocar la carpeta en `C:/xampp/htdocs/` (o el directorio raíz del servidor).

### 2. Crear la base de datos

Ejecutar los scripts en orden desde phpMyAdmin o la consola MySQL:

```sql
source database/migrations/001_initial_schema.sql
source database/migrations/002_administrativos.sql
source database/migrations/003_login_attempts.sql
source database/migrations/004_add_observaciones.sql
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

**Credenciales de administrador por defecto:**

```
Correo:    admin@universidad.cl
Contraseña: admin123
```

> Se recomienda cambiar la contraseña en producción.

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
│   │   ├── RateLimit.php        # Límite de intentos de login
│   │   └── Response.php         # Respuestas JSON estandarizadas
│   └── models/
│       ├── Administrativo.php   # Autenticación de administradores
│       └── Solicitud.php        # CRUD y validación de solicitudes
├── assets/
│   ├── css/style.css
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
│       └── 004_add_observaciones.sql
├── views/
│   ├── auth/login.php
│   ├── layouts/main.php         # Layout compartido
│   ├── public/index.php         # Vista del formulario público
│   └── request/index.php        # Vista del panel administrativo
├── .htaccess                    # Cabeceras de seguridad HTTP
├── admin.php
├── index.php
├── login.php
└── logout.php
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

Se optó por una arquitectura MVC sin framework para cumplir el requerimiento de PHP puro, manteniendo separación clara entre modelos, controladores y vistas. Esto facilita la legibilidad y el mantenimiento sin añadir dependencias externas.

### API REST con un único endpoint

Toda la comunicación del frontend se realiza a través de `api/solicitudes.php`, que despacha según el método HTTP (`GET`, `POST`, `PATCH`). Esto centraliza la lógica de negocio y simplifica el enrutamiento.

### PDO con prepared statements

Todas las consultas usan sentencias preparadas con PDO, eliminando la posibilidad de inyección SQL. Los parámetros nunca se interpolan directamente en el SQL.

### Protección CSRF

Los formularios HTML incluyen un token oculto y las peticiones AJAX lo envían en el header `X-CSRF-Token`. La validación usa `hash_equals()` para evitar timing attacks (comparación de strings que se detiene al primer caracter diferente, lo que puede revelar información).

### Rate limiting en login

Se registra cada intento fallido en la tabla `login_attempts` con IP y timestamp. Si se superan 5 intentos en 10 minutos, se bloquea el acceso temporalmente. Esto mitiga ataques de fuerza bruta sin necesidad de dependencias externas.

### Contraseñas con bcrypt

Las contraseñas de administradores se almacenan con `password_hash()` usando el algoritmo bcrypt, verificadas con `password_verify()`.

### Paginación del lado del servidor

El listado de solicitudes usa `LIMIT` y `OFFSET` en SQL. El total se obtiene con `COUNT(*)`. Esto evita cargar registros innecesarios en memoria cuando el volumen crece.

### Exportación Excel con UTF-16LE

Se genera un archivo TSV (separado por tabulaciones) con BOM UTF-16LE, que Excel reconoce y abre correctamente con todos los caracteres especiales del español.

### Cabeceras de seguridad HTTP

El archivo `.htaccess` define:

```
X-Frame-Options: DENY              → Previene clickjacking
X-Content-Type-Options: nosniff   → Previene MIME sniffing
Referrer-Policy: same-origin      → Limita información del referer
Permissions-Policy                 → Deshabilita cámara, micrófono y geolocalización
```

### Validación en dos capas

Toda entrada se valida en el frontend (UX inmediato) y en el servidor (seguridad real). La validación del servidor incluye: longitud máxima, formato de correo con `FILTER_VALIDATE_EMAIL`, regex Unicode para nombres (`/^[\p{L}\p{M}\s\'\-\.]+$/u`) y whitelist de valores permitidos para enumeraciones.

---

## Supuestos realizados

- Un mismo correo puede registrar múltiples solicitudes (no hay autenticación de solicitantes).
- Las observaciones del administrador solo se registran al aprobar o rechazar una solicitud.
- El sistema opera en una red interna; no se implementó HTTPS (se delega al servidor web institucional).
- No se requirió un sistema de roles múltiples; existe un único perfil de administrador.

---

## Limitaciones conocidas

- **Sin notificaciones por correo**: el sistema no envía emails al solicitante cuando su solicitud cambia de estado. La funcionalidad estaba contemplada y podría implementarse con PHPMailer disparando el envío en `SolicitudController::updateEstado()`, enviando una plantilla HTML con el nuevo estado y las observaciones del administrador.
- **Sin gestión de administradores desde la interfaz**: agregar o eliminar administradores requiere acceso directo a la base de datos.
- **Rate limiting por IP**: en redes con NAT, un bloqueo por IP afectaría a todos los usuarios detrás de la misma IP pública.
- **Sin HTTPS forzado**: se asume que el servidor de producción maneja SSL a nivel de infraestructura.

---

## Mejoras posibles

- Notificación por correo al actualizar estado (PHPMailer + plantilla HTML)
- Panel de gestión de administradores desde la interfaz
- Adjuntar archivos a las solicitudes
- Historial de cambios de estado por solicitud
