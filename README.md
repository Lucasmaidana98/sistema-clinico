# Sistema Clínico 🏥

Un sistema completo de gestión clínica desarrollado con Laravel 11, diseñado para la administración de pacientes, citas médicas y historiales clínicos.

## 🌟 Características Principales

- **Sistema de Autenticación Completo**: Login seguro con gestión de sesiones
- **Control de Acceso Basado en Roles**: Super Admin, Admin, Doctor, Enfermera, Recepcionista
- **Gestión de Pacientes**: CRUD completo con validaciones robustas
- **Sistema de Citas Médicas**: Programación y gestión de citas
- **Historiales Médicos**: Registro completo de consultas y tratamientos
- **Reportes en PDF**: Exportación de datos con filtros avanzados
- **Interfaz Moderna**: Diseño responsive con Tailwind CSS
- **Validaciones Centralizadas**: Form Requests con mensajes en español

## 🏗️ Arquitectura del Sistema

### Módulos Principales

1. **Gestión de Pacientes** 👥
   - Registro de pacientes con datos personales y médicos
   - Búsqueda y filtrado avanzado
   - Historial médico completo
   - Gestión de contactos de emergencia

2. **Sistema de Citas** 📅
   - Programación de citas médicas
   - Estados de cita (Programada, Confirmada, Completada, etc.)
   - Asignación de doctores
   - Notas y observaciones

3. **Historiales Médicos** 📋
   - Registro de síntomas y diagnósticos
   - Prescripciones médicas
   - Signos vitales
   - Seguimiento de tratamientos

### Roles y Permisos

- **Super Admin**: Acceso completo al sistema
- **Admin**: Gestión general excepto configuraciones del sistema
- **Doctor**: Enfoque en atención de pacientes y registros médicos
- **Enfermera**: Soporte en atención de pacientes
- **Recepcionista**: Gestión de citas y atención al cliente

## 🚀 Instalación

### Requisitos Previos

- PHP 8.2 o superior
- Composer
- Node.js y NPM
- SQLite (incluido) o MySQL

### Pasos de Instalación

1. **Clonar el repositorio**
```bash
git clone https://github.com/tu-usuario/sistema-clinico.git
cd sistema-clinico
```

2. **Instalar dependencias de PHP**
```bash
composer install
```

3. **Instalar dependencias de Node.js**
```bash
npm install
```

4. **Configurar el entorno**
```bash
cp .env.example .env
php artisan key:generate
```

5. **Ejecutar migraciones y seeders**
```bash
php artisan migrate --seed
```

6. **Compilar assets**
```bash
npm run build
```

7. **Iniciar el servidor de desarrollo**
```bash
php artisan serve
```

## 👥 Usuarios de Prueba

El sistema incluye usuarios pre-configurados para testing:

| Rol | Email | Contraseña | Descripción |
|-----|-------|------------|-------------|
| Super Admin | admin@clinico.com | admin123 | Acceso completo |
| Admin | administrador@clinico.com | admin123 | Gestión general |
| Doctor | doctor@clinico.com | doctor123 | Dr. María González |
| Enfermera | nurse@clinico.com | nurse123 | Enfermera Carmen Pérez |
| Recepcionista | recepcion@clinico.com | recepcion123 | Laura Sánchez |

## 🗄️ Base de Datos

### Tablas Principales

- **users**: Usuarios del sistema con roles
- **patients**: Información de pacientes
- **appointments**: Citas médicas programadas
- **medical_records**: Historiales médicos detallados
- **roles** y **permissions**: Sistema de autorización

### Datos de Prueba

- 9 usuarios con diferentes roles
- 93 pacientes con datos realistas en español
- Permisos configurados por rol

## 🔧 Tecnologías Utilizadas

### Backend
- **Laravel 11**: Framework PHP principal
- **Spatie Laravel Permission**: Gestión de roles y permisos
- **Laravel Breeze**: Sistema de autenticación

### Frontend
- **Tailwind CSS**: Framework de estilos
- **Alpine.js**: Interactividad JavaScript
- **Blade Templates**: Motor de plantillas
- **Font Awesome**: Iconografía médica

### Herramientas de Desarrollo
- **Laravel Pint**: Formateo de código
- **Vite**: Build tool para assets
- **DataTables**: Tablas interactivas

## 📊 Funcionalidades Avanzadas

### Validaciones Robustas
- Validación de nombres con caracteres españoles
- Validación de emails únicos
- Validación de números de teléfono e identificación
- Validación de rangos de signos vitales
- Mensajes de error personalizados en español

### Seguridad
- Autenticación con hash de contraseñas
- Protección CSRF en todos los formularios
- Control de acceso basado en permisos
- Sanitización de entradas de usuario

### Reportes PDF
- Exportación de listas de pacientes
- Reportes de citas por fecha y doctor
- Historiales médicos detallados
- Filtros avanzados para todos los reportes

## 🎨 Interfaz de Usuario

### Características del Diseño
- **Tema Médico Profesional**: Colores azules y blancos
- **Responsive Design**: Adaptable a móviles y tablets
- **Navegación Intuitiva**: Menús contextuales por rol
- **Dashboard Informativo**: Estadísticas y accesos rápidos
- **Formularios Validados**: Retroalimentación inmediata

### Componentes Reutilizables
- Tablas con búsqueda y filtrado
- Modales de confirmación
- Alertas de éxito y error
- Cards informativos con estadísticas

## 🧪 Testing

### Tests Realizados
- ✅ Autenticación y autorización
- ✅ CRUD de pacientes completo
- ✅ Navegación basada en roles
- ✅ Validaciones de formularios
- ✅ Integridad de base de datos

### Comandos de Testing
```bash
# Ejecutar tests
php artisan test

# Verificar rutas
php artisan route:list

# Limpiar caché
php artisan config:clear
php artisan cache:clear
```

## 📝 Estructura del Proyecto

```
sistema-clinico/
├── app/
│   ├── Http/
│   │   ├── Controllers/          # Controladores del sistema
│   │   └── Requests/            # Validaciones centralizadas
│   └── Models/                  # Modelos Eloquent
├── database/
│   ├── migrations/              # Migraciones de BD
│   ├── seeders/                # Datos de prueba
│   └── factories/              # Factories para testing
├── resources/
│   ├── views/                  # Vistas Blade
│   │   ├── layouts/           # Layouts principales
│   │   ├── patients/          # Vistas de pacientes
│   │   ├── appointments/      # Vistas de citas
│   │   └── medical-records/   # Vistas de historiales
│   └── css/                   # Estilos CSS
└── routes/
    ├── web.php                # Rutas web principales
    └── auth.php              # Rutas de autenticación
```

## 🚀 Deployment

### Configuración de Producción

1. **Variables de Entorno**
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tu-dominio.com
DB_CONNECTION=mysql
# Configurar base de datos de producción
```

2. **Optimizaciones**
```bash
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

3. **Configuración del Servidor**
- Configurar HTTPS
- Configurar permisos de archivos
- Configurar cron jobs si es necesario

## 🤝 Contribución

### Guías de Contribución
1. Fork el repositorio
2. Crear una rama para la nueva característica
3. Implementar cambios con tests
4. Enviar Pull Request con descripción detallada

### Estándares de Código
- Seguir PSR-12 para PHP
- Usar Laravel Pint para formateo
- Comentarios en español para funciones principales
- Tests unitarios para nueva funcionalidad

## 📞 Soporte

### Contacto
- **Desarrollador**: Sistema Clínico Team
- **GitHub Issues**: Para reportar bugs y solicitar características

### Documentación Adicional
- [Laravel Documentation](https://laravel.com/docs)
- [Tailwind CSS](https://tailwindcss.com/docs)
- [Spatie Permission](https://spatie.be/docs/laravel-permission)

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo `LICENSE` para más detalles.

---

**Sistema Clínico** - Desarrollado con ❤️ para mejorar la gestión sanitaria
