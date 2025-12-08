# Proyecto Estado de Situación Financiera

Este proyecto es una aplicación web desarrollada en **Laravel 10** para visualizar el **Estado de Situación Financiera** de clientes o de manera general, con filtros por cliente y período, utilizando **Tailwind CSS** para la interfaz.

---

## Tabla de Contenidos

- [Características](#características)
- [Requisitos](#requisitos)
- [Instalación](#instalación)
- [Estructura del Proyecto](#estructura-del-proyecto)
- [Rutas Principales](#rutas-principales)
- [Uso](#uso)
- [Tecnologías](#tecnologías)
- [Contribuciones](#contribuciones)
- [Licencia](#licencia)

---

## Características

- Visualización de **Activo, Pasivo y Patrimonio Neto**.
- Totales destacados y verificación de descuadre contable.
- Filtro por **Cliente** y **Rango de Fechas** en una sola fila profesional.
- Estética moderna con **modo oscuro** compatible.
- Compatible con diferentes tamaños de pantalla (responsive).

---

## Requisitos

- PHP >= 8.2
- Laravel 10
- Composer
- Base de datos MySQL o MariaDB
- Node.js y npm (para compilar assets con Tailwind CSS)

---

## Instalación

1. Clonar el repositorio:

   ```bash
   git clone https://github.com/tiagoraminelli/estado-financiero.git
   cd estado-financiero
   ```
2. Instalar dependencias de PHP:

   ```bash
   composer install
   ```
3. Instalar dependencias de Node.js:

   ```bash
   npm install
   ```
4. Copiar el archivo de entorno y configurar la base de datos:

   ```bash
   cp .env.example .env
   ```

   Editar `.env` con los datos de tu base de datos.
5. Generar la clave de aplicación:

   ```bash
   php artisan key:generate
   ```
6. Ejecutar migraciones y seeds si existen:

   ```bash
   php artisan migrate --seed
   ```
7. Compilar los assets:

   ```bash
   npm run dev
   ```
8. Ejecutar el servidor de desarrollo:

   ```bash
   php artisan serve
   ```

---
