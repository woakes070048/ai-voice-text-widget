# AI Voice Text Widget - TODO

## 🚀 FASE 1: PREPARAR PLUGIN PARA SISTEMA DE LICENCIAS (EN PROGRESO)

### 1. ✅ Modelo Freemium Implementado
- [x] Reestructurar base de datos (per-installation limits)
- [x] Crear tabla `wp_ai_widget_installation`
- [x] Crear tabla `wp_ai_widget_end_users`
- [x] Actualizar tabla `wp_ai_widget_conversations` (interaction_type, duration_seconds)
- [x] Crear clase `AI_Widget_Freemium` con métodos de límites
- [x] Implementar cron jobs (reseteo mensual, cleanup, analytics)
- [x] Crear panel visual de Freemium con barras de progreso
- [x] Crear script de migración `migrate-freemium.php`

### 2. ✅ Integrar Límites en API REST (COMPLETADO)
- [x] Modificar endpoint `/wp-json/ai-widget/v1/chat`
  - [x] Verificar límites con `AI_Widget_Freemium::can_send_text_message()`
  - [x] Retornar error si límite excedido
  - [x] Registrar mensaje con `AI_Widget_Freemium::log_text_message()`
- [x] Crear endpoint `/wp-json/ai-widget/v1/check-limits`
  - [x] Retornar remaining messages y voice minutes
  - [x] Usar en frontend para mostrar alertas preventivas
- [x] Crear endpoint `/wp-json/ai-widget/v1/log-voice`
  - [x] Recibir session_id y duration_seconds
  - [x] Validar límites con `can_use_voice_minutes()`
  - [x] Registrar con `log_voice_usage()`

### 3. ✅ Agregar Branding en Plan Gratuito (COMPLETADO)
- [x] Modificar `public/class-public.php`
  - [x] Agregar `showBranding` a `wp_localize_script()`
  - [x] Llamar `AI_Widget_Freemium::should_show_branding()`
- [x] Modificar `public/js/widget-vapi.js`
  - [x] Detectar `aiWidgetData.showBranding`
  - [x] Agregar watermark "Workfluz Chat Systems" en footer del chat
  - [x] Estilo: texto pequeño, gris claro, esquina inferior derecha
- [x] Agregar CSS para branding
  - [x] `.ai-widget-branding` con estilos responsive

### 4. ✅ Tracking de Duración de Voz con VAPI (COMPLETADO)
- [x] Modificar `public/js/widget-vapi.js`
  - [x] Capturar evento `call-started` para guardar timestamp
  - [x] Capturar evento `call-ended` para calcular duración
  - [x] Enviar duración a endpoint `/log-voice`
  - [x] Mostrar alerta si límite de minutos cercano
- [x] Validar límites ANTES de iniciar llamada
  - [x] Consultar `/check-limits` antes de `vapiClient.start()`
  - [x] Mostrar mensaje si no hay minutos disponibles

### 5. ✅ Sistema de License Keys (COMPLETADO)
- [x] Agregar campo `license_key` a tabla `wp_ai_widget_installation`
- [x] Agregar campos de validación:
  - [x] `license_status` (none, active, invalid, expired)
  - [x] `license_validated_at` (última verificación exitosa)
  - [x] `license_expires_at` (fecha de expiración)
  - [x] `license_last_check` (última consulta a API)
  - [x] `license_plan` (plan asociado a la licencia)
- [x] Crear sección en `admin/partials/freemium-page.php`
  - [x] Input para ingresar License Key
  - [x] Botón "Validar Licencia" con AJAX
  - [x] Mostrar estado de licencia (activa/inválida)
  - [x] Mostrar fecha de expiración
  - [x] Botones: Revalidar, Desactivar, Cambiar
- [x] Crear método `AI_Widget_Freemium::validate_license($license_key)`
  - [x] Consultar API externa (app.workfluz.com o configurable)
  - [x] Caché local por 24 horas
  - [x] Modo offline (usar última validación si API no responde)
  - [x] Actualizar plan a Premium si válida
  - [x] Guardar metadata: status, plan, expires_at
- [x] Crear método `AI_Widget_Freemium::revalidate_current_license()`
- [x] Crear método `AI_Widget_Freemium::deactivate_license()`
- [x] Crear cron job para revalidar licencias diariamente
- [x] Crear endpoints AJAX en `class-admin.php`:
  - [x] `wp_ajax_validate_license`
  - [x] `wp_ajax_revalidate_license`
  - [x] `wp_ajax_deactivate_license`
- [x] Crear script de migración `migrate-license-keys.php`
- [x] Agregar índices de base de datos para license_key y license_status
- [x] UI completa con mensajes de éxito/error
- [x] Información sobre cómo obtener license key

### 6. 🌐 Preparar API Externa (Mock) (PRÓXIMO)
- [ ] Crear endpoint mock en plugin para simular API externa
  - [ ] POST `/wp-json/ai-widget/v1/mock/validate-license`
  - [ ] Retornar JSON: `{ valid: true, plan: 'premium', expires_at: '2025-11-23' }`
- [ ] Documentar estructura de API para desarrollo futuro
- [ ] Crear constante `AI_WIDGET_LICENSE_API_URL` configurable

---

## 🎯 FASE 2: SITIO EXTERNO app.workfluz.com (FUTURO)

### 7. 🏗️ Arquitectura del SaaS
- [ ] Diseñar base de datos
  - [ ] Tabla `users` (email, password, nombre, país)
  - [ ] Tabla `subscriptions` (user_id, plan, status, payment_method)
  - [ ] Tabla `licenses` (license_key, subscription_id, site_url, activated_at)
  - [ ] Tabla `payments` (subscription_id, amount, currency, gateway, status)
- [ ] Stack tecnológico
  - [ ] Frontend: Next.js 14 / React
  - [ ] Backend: Laravel / Node.js + Express
  - [ ] Base de datos: PostgreSQL / MySQL
  - [ ] Cache: Redis
  - [ ] Hosting: Vercel / Railway / AWS


### 8. 🔌 API REST del SaaS
- [ ] `POST /api/v1/auth/register` - Registro de usuarios
- [ ] `POST /api/v1/auth/login` - Login
- [ ] `GET /api/v1/subscriptions` - Listar suscripciones del usuario
- [ ] `POST /api/v1/subscriptions/create` - Crear suscripción
- [ ] `POST /api/v1/subscriptions/cancel` - Cancelar suscripción
- [ ] `POST /api/v1/licenses/validate` - Validar License Key (usado por plugin)
- [ ] `POST /api/v1/licenses/activate` - Activar licencia en sitio
- [ ] `POST /api/v1/licenses/deactivate` - Desactivar licencia
- [ ] `GET /api/v1/analytics` - Estadísticas de uso por sitio
- [ ] Webhooks para notificar cambios al plugin

### 9. 💳 Integración de Pagos
- [ ] Integrar Wompi (Colombia)
  - [ ] Webhooks de eventos de pago
  - [ ] Manejo de suscripciones recurrentes
- [ ] Integrar Bold (alternativa)
- [ ] Integrar MercadoPago (LATAM)
- [ ] Manejo de diferentes monedas (COP, USD, MXN, etc.)
- [ ] Sistema de facturación electrónica

### 10. 📊 Dashboard de Usuario
- [ ] Panel de suscripciones activas
- [ ] Lista de sitios WordPress conectados
- [ ] Estadísticas de uso (mensajes, minutos de voz)
- [ ] Historial de pagos y facturas
- [ ] Gestión de License Keys
- [ ] Soporte / tickets

---

## 🔧 MEJORAS ADICIONALES

### 11. 📈 Analytics Avanzados
- [ ] Actualizar queries en `analytics-page.php` para usar nuevo schema
- [ ] Gráficos de texto vs voz
- [ ] Distribución de uso por hora del día
- [ ] Métricas de conversión (visitantes → interacciones)

### 12. 🎨 UI/UX
- [ ] Preview en vivo del widget en página de Apariencia
- [ ] Modo oscuro para el chat
- [ ] Animaciones de transición
- [ ] Notificaciones toast para límites alcanzados

### 13. 🧪 Testing
- [ ] Tests unitarios para `AI_Widget_Freemium`
- [ ] Tests de integración para API endpoints
- [ ] Tests E2E con Playwright
- [ ] Pruebas de carga (100+ usuarios simultáneos)

### 14. 📚 Documentación
- [ ] README completo con instalación
- [ ] Guía de migración de modelo antiguo
- [ ] Documentación de API externa
- [ ] Video tutorial de configuración
- [ ] FAQ para usuarios

### 15. 🔒 Seguridad
- [ ] Sanitización de inputs en todos los endpoints
- [ ] Rate limiting en API REST
- [ ] Validación de nonces en AJAX
- [ ] Encriptación de License Keys en DB
- [ ] Logs de auditoría para cambios de plan

---

## 📝 NOTAS TÉCNICAS

### Límites Plan Gratuito
- **Mensajes de texto**: 100/mes
- **Minutos de voz**: 30/mes
- **Usuarios finales**: Ilimitados
- **Branding**: Obligatorio
- **Reseteo**: Automático mensual (cron)

### Límites Plan Premium
- **Todo ilimitado**
- **Sin branding**
- **Soporte prioritario**
- **Precio**: $29/mes o $290/año

### API Externa (Futuro)
```bash
# Validar licencia
curl -X POST https://app.workfluz.com/api/v1/licenses/validate \
  -H "Content-Type: application/json" \
  -d '{
    "license_key": "WF-ABC123-XYZ789",
    "site_url": "https://misitio.com"
  }'

# Respuesta
{
  "valid": true,
  "plan": "premium",
  "status": "active",
  "expires_at": "2025-11-23T23:59:59Z",
  "features": {
    "unlimited_messages": true,
    "unlimited_voice": true,
    "branding_removal": true,
    "priority_support": true
  }
}
```

---

**Última actualización**: 23 de octubre de 2025  
**Estado actual**: Fase 1 - Puntos 1-5 completados ✅  
**Completado**: Modelo Freemium + API REST + Branding + Tracking de voz + Sistema de License Keys  
**Próximo objetivo**: Mock de API Externa (punto 6)