# NutriChef

Plataforma de gestión nutricional donde pacientes pueden consultar recetas, recibir recomendaciones de nutricionistas, chatear con ellos, y gestionar menús semanales.

## Requisitos

- PHP 8.3+
- Composer
- Node.js 20+
- SQLite (por defecto) o MySQL

## Instalación

```bash
# Clonar el repositorio
git clone <repo-url> nutrichef
cd nutrichef

# Dependencias PHP
composer install

# Dependencias JS
npm install

# Configuración
cp .env.example .env
php artisan key:generate
```

Editar `.env` y configurar:
- **Base de datos**: por defecto usa SQLite (`DB_CONNECTION=sqlite`). Para MySQL cambiar `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`.
- **Correo**: configurado con Mailtrap Testing por defecto. Reemplazar `MAIL_PASSWORD` con tu token de [Mailtrap](https://mailtrap.io).

```bash
# Migraciones
php artisan migrate

# Enlace de almacenamiento
php artisan storage:link

# Compilar assets
npm run build
```

## Desarrollo

```bash
npm run dev
```

## Roles

- **user** — paciente: ve recetas, chatea con nutricionistas, recibe recomendaciones, gestiona menús
- **nutritionist** — nutricionista: atiende consultas, envía recomendaciones, asigna planes
- **admin** — administrador: reportes, gestiona recetas, envía notificaciones

## Credenciales por defecto

Registrarse en `/register`. No hay seeders precargados.

## Tests

```bash
php artisan test
```

## Stack

- Laravel 12
- Alpine.js + Bootstrap 5
- Chart.js (reportes admin)
- DomPDF (exportar PDF)
- PhpSpreadsheet (exportar Excel)
- Mailtrap Testing (correo en desarrollo)
