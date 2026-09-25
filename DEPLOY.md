# Deploy — https://nailsbydelphina.okto.ie

Corre en el VPS de OVH de okto (acceso: ver `okto-hub/ACCESOS.md`, alias SSH `okto-vps`),
igual que el resto de los sitios: un contenedor Docker que solo escucha en `127.0.0.1:8081`
y Cloudflare Tunnel publica `nailsbydelphina.okto.ie` → `http://localhost:8081`.

## Actualizar el sitio

```bash
./deploy.sh
```

Compila el frontend (`npm run build`), sube el código a `~/sites/delphina`, reconstruye el
contenedor y chequea `/up`. Al arrancar, el contenedor corre las migraciones y cachea
config/rutas/vistas solo. Nunca sube `.env`, la base de datos, `CV/` ni `resources/data`.

## Qué vive solo en el servidor (`~/sites/delphina`)

| Qué | Dónde |
|---|---|
| `.env` de producción (APP_KEY, SMTP, etc.) | `~/sites/delphina/.env` (permisos 600) |
| Base de datos SQLite | `~/sites/delphina/data/database.sqlite` |
| Logs de Laravel | `~/sites/delphina/logs/` |

Si cambiás el `.env`: `docker compose up -d --force-recreate` en `~/sites/delphina`.

## Mail

- Envía `no-reply@okto.ie` (casilla técnica en el `okto-mail` del mismo VPS; su contraseña
  está solo en el `.env` del servidor). Nombre visible: "Nails by Delphina".
- Las respuestas van al Gmail de Delfi (`MAIL_REPLY_TO_ADDRESS`).
- SPF/DKIM de okto.ie autorizan al VPS, así que no cae en spam.

## Comandos útiles (en el VPS)

```bash
cd ~/sites/delphina
docker logs delphina --tail 50                  # arranque / Apache
tail -50 logs/laravel.log                       # errores de la app
docker exec -u www-data delphina php artisan studio:admin EMAIL --only   # crear/resetear admin (pide contraseña con -it)
cp data/database.sqlite ~/backups/delphina-$(date +%F).sqlite           # backup manual
```

## Si el mail falla y Delfi no puede entrar

En `.env` poner `ADMIN_TWO_FACTOR=false`, `docker compose up -d --force-recreate`, arreglar el
SMTP y volver a `true`.

## Cloudflare Tunnel

La regla está en `/etc/cloudflared/config.yml` (antes del `http_status:404`). Backup previo
al cambio: `/etc/cloudflared/config.yml.bak-20260925-000506`. El DNS se creó con
`cloudflared tunnel route dns okto-hub nailsbydelphina.okto.ie`.

## SEO después del lanzamiento

- Google Search Console: propiedad `https://nailsbydelphina.okto.ie/` verificada (etiqueta HTML vía `GOOGLE_SITE_VERIFICATION` en el `.env` del VPS — no borrarla) y `sitemap.xml` enviado el 2026-09-25.
- Perfil de Empresa de Google (Maps): link a la web y a `/book`.
- Bio de Instagram: `https://nailsbydelphina.okto.ie/?book=1` (abre la reserva directo).
