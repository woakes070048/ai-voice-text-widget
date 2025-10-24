# Configuración de License Key API

## 📝 Descripción

El sistema de License Keys permite validar licencias con un servidor externo (futuro: `app.workfluz.com`). La URL de la API puede configurarse mediante una constante en `wp-config.php`.

---

## ⚙️ Configuración

### 1. Agregar constante en wp-config.php

Abre el archivo `wp-config.php` en la raíz de tu instalación de WordPress y agrega la siguiente línea **antes de** la línea que dice `/* That's all, stop editing! Happy publishing. */`:

```php
// URL de la API de validación de License Keys para AI Widget
define( 'AI_WIDGET_LICENSE_API_URL', 'https://app.workfluz.com/api/v1/licenses/validate' );
```

### 2. URL por defecto

Si no defines la constante, el plugin usará automáticamente:
```
https://app.workfluz.com/api/v1/licenses/validate
```

### 3. Desarrollo/Testing local

Para pruebas locales, puedes apuntar a un servidor de desarrollo:

```php
define( 'AI_WIDGET_LICENSE_API_URL', 'http://localhost:3000/api/v1/licenses/validate' );
```

O usar el endpoint mock interno del plugin (próximo paso):

```php
define( 'AI_WIDGET_LICENSE_API_URL', home_url( '/wp-json/ai-widget/v1/mock/validate-license' ) );
```

---

## 🔌 Estructura de la API

### Request (POST)

El plugin envía los siguientes datos al endpoint de validación:

```http
POST /api/v1/licenses/validate
Content-Type: application/x-www-form-urlencoded
User-Agent: AI-Widget-Plugin/1.0.0

license_key=XXXX-XXXX-XXXX-XXXX-XXXX
site_url=https://misitio.com
plugin_version=1.0.0
```

### Response exitosa (200 OK)

```json
{
  "valid": true,
  "plan": "premium",
  "expires_at": "2025-11-23T23:59:59Z",
  "message": "Licencia válida"
}
```

**Campos de la respuesta:**
- `valid` (boolean): Si la licencia es válida
- `plan` (string): Tipo de plan ("premium", "pro", "enterprise", etc.)
- `expires_at` (string, opcional): Fecha de expiración en formato ISO 8601
- `message` (string, opcional): Mensaje descriptivo

### Response inválida (200 OK)

```json
{
  "valid": false,
  "message": "Licencia expirada"
}
```

Posibles mensajes de error:
- `"Licencia no encontrada"`
- `"Licencia expirada"`
- `"Licencia cancelada"`
- `"Sitio no autorizado"`
- `"Límite de activaciones excedido"`

### Response de error del servidor (500, 503, etc.)

Si el servidor no responde o retorna un error:
- El plugin **mantiene la última validación exitosa en caché** (modo offline)
- Muestra advertencia al admin: "No se pudo conectar al servidor. Usando licencia en caché."
- Reintenta en la siguiente revalidación automática (24 horas)

---

## 🔄 Flujo de Validación

### 1. Activación manual (Admin ingresa license key)

```
Usuario ingresa license key
    ↓
Click en "Activar License Key"
    ↓
AJAX: wp_ajax_validate_license
    ↓
AI_Widget_Freemium::validate_license($license_key, $force_refresh = false)
    ↓
HTTP POST a AI_WIDGET_LICENSE_API_URL
    ↓
Guardar en DB: license_key, license_status, license_plan, license_expires_at
    ↓
Actualizar plan a "premium" si válida
    ↓
Mostrar mensaje de éxito/error
```

### 2. Revalidación automática (Cron diario)

```
Cron: ai_widget_revalidate_license (cada 24 horas)
    ↓
AI_Widget_Freemium::revalidate_current_license()
    ↓
HTTP POST a AI_WIDGET_LICENSE_API_URL
    ↓
Actualizar DB con nuevo status
    ↓
Si expiró: cambiar plan a "free"
```

### 3. Caché inteligente

- Si la license key **no cambió** y la última validación fue hace **menos de 24 horas**, retorna caché local
- `force_refresh = true` ignora caché y consulta API directamente
- En caso de error de red, mantiene última validación exitosa

---

## 🧪 Testing

### Probar con cURL

```bash
curl -X POST https://app.workfluz.com/api/v1/licenses/validate \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -H "User-Agent: AI-Widget-Plugin/1.0.0" \
  -d "license_key=TEST-1234-5678-ABCD-EFGH" \
  -d "site_url=https://misitio.com" \
  -d "plugin_version=1.0.0"
```

### Simular respuesta con Mock (próximo paso)

Una vez implementado el Mock API (punto 6 del TODO), podrás probar localmente sin necesidad de `app.workfluz.com`.

---

## 🛡️ Seguridad

### Transmisión
- **Siempre usa HTTPS** en producción
- El plugin valida el código de respuesta HTTP (200 OK)
- Sanitiza todos los datos antes de guardar en DB

### Almacenamiento
- Las license keys se guardan en texto plano en la tabla `wp_ai_widget_installation`
- Considera encriptar las keys en una futura actualización (punto 15 del TODO)

### Rate Limiting
- El plugin limita las validaciones a **1 cada 24 horas** (caché)
- Los usuarios admin pueden forzar revalidación manualmente
- No hay límite de intentos fallidos (por ahora)

---

## 🐛 Troubleshooting

### "Error de conexión: Could not resolve host"

**Causa**: El servidor no puede resolver el DNS de la API.

**Solución**:
1. Verifica que la URL en `AI_WIDGET_LICENSE_API_URL` sea correcta
2. Revisa que tu servidor tenga acceso a Internet
3. Prueba con cURL desde el servidor

### "Respuesta inválida del servidor (código: 404)"

**Causa**: El endpoint no existe en la API externa.

**Solución**:
1. Verifica la URL del endpoint
2. Si `app.workfluz.com` aún no está disponible, usa el Mock API local

### "License key desactivada pero sigue siendo Premium"

**Causa**: Caché de objeto de WordPress.

**Solución**:
```bash
wp cache flush
```

O limpia manualmente:
```php
delete_transient( 'ai_widget_license_cache' );
```

---

## 📊 Logs

El cron job de revalidación escribe en el log de WordPress (si `WP_DEBUG` está activo):

```
AI Widget - License revalidation: SUCCESS - Licencia válida (caché)
AI Widget - License revalidation: FAILED - Licencia expirada
```

Ver logs:
```bash
tail -f wp-content/debug.log
```

---

## 🚀 Próximos pasos

1. **Implementar Mock API local** (punto 6 del TODO)
   - Endpoint: `/wp-json/ai-widget/v1/mock/validate-license`
   - Responde con JSON de prueba
   - Útil para desarrollo sin sitio externo

2. **Desarrollar app.workfluz.com** (Fase 2 del TODO)
   - Base de datos de licencias
   - Sistema de pagos (Wompi/Bold/MercadoPago)
   - Dashboard de usuario
   - Gestión de suscripciones

3. **Mejoras de seguridad** (punto 15 del TODO)
   - Encriptar license keys en DB
   - Rate limiting en validación
   - Logs de auditoría
   - Detección de uso fraudulento

---

**Documentación actualizada**: 23 de octubre de 2025
