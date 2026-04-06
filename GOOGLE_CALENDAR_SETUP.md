# Google Calendar Integration Setup Guide

## Paso 1: Crear Proyecto en Google Cloud Console

1. Ve a [Google Cloud Console](https://console.cloud.google.com/)
2. Crea un nuevo proyecto o selecciona uno existente
3. Habilita la Google Calendar API:
   - Ve a "APIs & Services" > "Library"
   - Busca "Google Calendar API"
   - Haz clic en "Enable"

## Paso 2: Crear Credenciales OAuth 2.0

1. Ve a "APIs & Services" > "Credentials"
2. Haz clic en "Create Credentials" > "OAuth 2.0 Client IDs"
3. Configura la pantalla de consentimiento:
   - User Type: External
   - App name: Delfi Nail Technician
   - User support email: tu-email@ejemplo.com
   - Developer contact: tu-email@ejemplo.com
4. Agrega scopes:
   - `https://www.googleapis.com/auth/calendar.events`
5. Crea las credenciales:
   - Application type: Web application
   - Authorized redirect URIs: `http://localhost:8000` (para desarrollo)
   - Authorized JavaScript origins: `http://localhost:8000`

## Paso 3: Descargar y Configurar Credenciales

1. Descarga el archivo JSON de credenciales
2. Crea el directorio: `storage/app/google-calendar/`
3. Renombra el archivo a `credentials.json` y colócalo en `storage/app/google-calendar/`

## Paso 4: Configurar Variables de Entorno

Agrega estas variables a tu archivo `.env`:

```env
GOOGLE_CALENDAR_ADMIN_ID=primary
```

Si quieres usar un calendario específico, obtén el ID del calendario desde Google Calendar settings.

## Paso 5: Primera Autenticación

Cuando ejecutes el código por primera vez, Google te pedirá autorización. Asegúrate de:

1. Estar logueado con la cuenta de Google que tendrá el calendario admin
2. Autorizar los permisos solicitados
3. El token se guardará automáticamente en `storage/app/google-calendar/token.json`

## Paso 6: Configurar Sincronización Automática

### Opción A: Programar Comando con Cron

Agrega esta línea a tu crontab:

```bash
*/15 * * * * cd /path-to-your-project && php artisan calendar:sync
```

### Opción B: Usar Laravel Scheduler

En `app/Console/Kernel.php`, agrega:

```php
protected function schedule(Schedule $schedule)
{
    $schedule->command('calendar:sync')
             ->everyFifteenMinutes();
}
```

## Paso 7: Probar la Integración

1. Crea una cita de prueba
2. Completa el pago
3. Verifica que se creen eventos en:
   - Tu calendario admin
   - El calendario del cliente (si proporcionó email)

## Scopes Requeridos

Asegúrate de que las credenciales tengan estos scopes:

- `https://www.googleapis.com/auth/calendar.events` - Para crear/modificar/eliminar eventos

## Manejo de Errores

### Errores Comunes:

1. **"Invalid Credentials"**: Verifica que `credentials.json` sea correcto
2. **"Access denied"**: Asegúrate de autorizar la aplicación
3. **"Calendar not found"**: Verifica el `GOOGLE_CALENDAR_ADMIN_ID`

### Logging:

Todos los errores se registran en `storage/logs/laravel.log`. Revisa este archivo para debugging.

## Estructura de Archivos

```
storage/
  app/
    google-calendar/
      credentials.json
      token.json (se crea automáticamente)
```

## Comandos Útiles

```bash
# Sincronizar manualmente
php artisan calendar:sync

# Sincronizar para más días
php artisan calendar:sync --days=60

# Limpiar cache
php artisan config:clear
php artisan cache:clear
```

## Notas de Producción

1. **Redirect URIs**: Actualiza con tu dominio real en producción
2. **Token Storage**: Asegúrate de que `storage/app/google-calendar/` tenga permisos de escritura
3. **Rate Limits**: Google Calendar API tiene límites de cuota. Monitorea el uso
4. **Timezone**: La aplicación usa 'Europe/Dublin' como timezone por defecto

## Soporte

Si encuentras problemas:

1. Revisa los logs de Laravel
2. Verifica la configuración de Google Cloud Console
3. Asegúrate de que las credenciales sean válidas
4. Prueba con una cuenta de Google diferente si es necesario