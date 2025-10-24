# 🚀 PROMPT COMPLETO: Plataforma Multi-Aplicación Workfluz License Manager

## 📋 RESUMEN EJECUTIVO

Necesito que desarrolles una **plataforma SaaS multi-aplicación** para gestionar licencias y suscripciones de múltiples productos (plugins, SaaS, software). El sistema debe ser:

### 🎯 Características Principales

1. **Multi-Aplicación** 🔥
   - Gestionar licencias de múltiples productos desde un solo panel
   - Cada aplicación con su propia configuración (tokens, APIs, límites)
   - Dashboard unificado con selector de aplicación
   - Configuración desde frontend (sin editar código)

2. **Gestión de Licencias Híbrida** (Automática + Manual)
   - ✅ Generación automática al pagar suscripción
   - ✅ Creación manual por admin (sin pago, para pruebas/cortesías)
   - ✅ Modificación de estado, duración, límites desde admin panel
   - ✅ Revocación, suspensión, extensión manual

3. **Sistema de Pagos**
   - Múltiples pasarelas (Stripe, Wompi, Bold, MercadoPago)
   - Suscripciones recurrentes automáticas
   - Webhooks para sincronización

4. **API REST Pública**
   - Validar licencias desde cualquier aplicación
   - Reportar estadísticas de uso
   - Rate limiting y seguridad

5. **Seguridad Robusta** 🔐
   - JWT + Refresh Tokens
   - 2FA opcional
   - Validación de webhooks con firmas
   - Rate limiting
   - Encriptación de datos sensibles
   - Audit logs completos
   - CORS configurado
   - SQL injection prevention
   - XSS protection

6. **Deploy en EasyPanel** 🐳
   - Docker Compose listo
   - PostgreSQL + Redis incluidos
   - Variables de entorno configurables
   - Auto-scaling ready

---

## �️ ARQUITECTURA: Open Source vs Custom

### ✅ OPCIÓN 1: Lago + Módulo Custom (Recomendado para rapidez)

**Usar como base**: [Lago](https://github.com/getlago/lago) (billing open source)

**Ventajas**:
- ✅ 80% ya desarrollado (billing, suscripciones, webhooks)
- ✅ Solo desarrollar módulo de licenses (20% del trabajo)
- ✅ Bien mantenido, producción-ready
- ✅ Compatible con Docker/EasyPanel

**Desventajas**:
- ⚠️ Stack en Ruby (menos común)
- ⚠️ Menos flexibilidad que custom

**Tiempo estimado**: 3-4 semanas

---

### ✅ OPCIÓN 2: Stack Custom Minimalista (Recomendado para control total)

**Stack optimizado para EasyPanel**:

```yaml
Backend:
  Framework: FastAPI (Python 3.11) o Express.js + TypeScript
  Base de datos: PostgreSQL 15 (incluido en EasyPanel)
  Cache: Redis 7 (incluido en EasyPanel)
  ORM: SQLAlchemy (Python) o Prisma (Node.js)
  Validación: Pydantic (Python) o Zod (TypeScript)
  Queue: Celery + Redis (Python) o BullMQ (Node.js)
  
Frontend:
  Opción A: Next.js 14 App Router (Vercel gratis)
  Opción B: Astro + Alpine.js (ultra-ligero, EasyPanel)
  Opción C: SvelteKit (rápido, moderno)
  UI: Tailwind CSS + Shadcn/ui o DaisyUI
  
Pagos:
  - Stripe Billing (maneja todo automáticamente)
  - Wompi, Bold, MercadoPago (custom integration)
  
Deploy:
  Backend: EasyPanel (Docker Compose)
  Frontend: Vercel (gratis) o EasyPanel
  Storage: S3/R2 Cloudflare (facturas PDF)
  Email: Resend.com (gratis 100/día) o SendGrid
```

**Ventajas**:
- ✅ Control total del código
- ✅ Stack moderno y común
- ✅ Ultra-ligero (< 100MB imagen Docker)
- ✅ Fácil de extender
- ✅ Gratis en EasyPanel

**Tiempo estimado**: 4-6 semanas

---

### ✅ OPCIÓN 3: Odoo Custom Plan (odoo.sh) ⭐ (SELECCIONADA)

**Usar**: [Odoo.sh Custom Plan](https://www.odoo.com/pricing) - $13.60/user/month

**Stack**:
```yaml
Base: Odoo 17 Enterprise (Custom Plan)
  - ALL APPS incluidas (Subscriptions, Invoicing, CRM, eCommerce, etc.)
  - Odoo Studio (personalización drag & drop, sin código)
  - API REST completa (integrada)
  - Multi-moneda y multi-idioma (nativo)
  - Multi-company (gestión de múltiples empresas)
  - Odoo.sh hosting (PaaS administrado) o On Premise

Módulo Custom: "workfluz_license_manager"
  - Gestión de license keys
  - Validación de licencias vía API
  - Multi-aplicación (tabla applications)
  - Configuración de apps desde frontend con Studio
  - Estadísticas de uso por licencia
  - Creación manual de licencias

Base de datos: PostgreSQL (administrado por Odoo.sh)
Cache: Redis (incluido en Odoo.sh)
Backups: Automáticos diarios (incluidos)

Pasarelas de pago:
  - Stripe (módulo oficial Enterprise)
  - PayPal (módulo oficial Enterprise)
  - Authorize.net (oficial)
  - Wompi (módulo custom)
  - MercadoPago (comunidad)
  - Bold (módulo custom)

Frontend:
  - Odoo Website Builder (CMS drag & drop) - Landing page
  - Odoo Portal (incluido) - Dashboard de clientes
  - Odoo Backend (incluido) - Admin panel
  - Odoo Studio (personalización visual)

Deploy: Odoo.sh (PaaS) o On Premise
```

**Ventajas**:
- ✅ **100% ya desarrollado** (subscriptions, billing, invoicing, users, portal, CRM, analytics)
- ✅ **Odoo Studio incluido** - Personalizar sin código (drag & drop)
- ✅ **Todas las apps Enterprise** (300+ módulos oficiales)
- ✅ **Solo desarrollar módulo de licencias** (1-2 semanas con Studio)
- ✅ **API REST completa** con autenticación OAuth2
- ✅ **Dashboard y analytics enterprise** (MRR, ARR, churn, forecasting)
- ✅ **Multi-company nativo** - Gestionar múltiples negocios
- ✅ **Odoo.sh PaaS**: Hosting, backups, staging, CI/CD automático
- ✅ **Soporte oficial Odoo** (tickets prioritarios)
- ✅ **Actualizaciones automáticas** sin downtime
- ✅ **Certificación SSL gratuita**
- ✅ **Python** (mismo stack que FastAPI)
- ✅ **Portal de clientes avanzado** con subscripciones self-service

**Desventajas**:
- ⚠️ **Costo recurrente**: $13.60/usuario/mes (pero incluye TODO)
- ⚠️ **Vendor lock-in** (dependencia de Odoo.sh, mitigable con On Premise)
- ⚠️ **Curva de aprendizaje** inicial (framework Odoo con ORM propio)

**Comparación de costos**:
| Concepto | Custom Plan | Community |
|----------|-------------|-----------|
| Base | $13.60/user/mes | $0 |
| Hosting | Incluido | $20-$50/mes |
| Backups | Incluido | Manual |
| SSL | Incluido | Manual |
| Soporte | Incluido | Comunidad |
| Apps Enterprise | ✅ Todas | ❌ Solo Community |
| Studio | ✅ Incluido | ❌ No disponible |
| **Total (3 usuarios)** | **$40.80/mes** | **$20-$50/mes + $0** |

**Tiempo estimado**: 1-2 semanas (con Studio es más rápido)
**Costo estimado**: $3,000 - $5,000 USD (desarrollo módulo custom) + $40.80/mes (3 usuarios)

---

### 📦 ESTRUCTURA DEL MÓDULO CUSTOM: `workfluz_license_manager`

```python
workfluz_license_manager/
├── __init__.py
├── __manifest__.py
├── models/
│   ├── __init__.py
│   ├── application.py           # Nuevo: Tabla de aplicaciones
│   ├── license_key.py            # Nuevo: Gestión de license keys
│   ├── license_validation.py     # Nuevo: Log de validaciones
│   ├── subscription.py           # Extend: Hereda de sale.subscription
│   └── usage_stats.py            # Nuevo: Estadísticas de uso
├── controllers/
│   ├── __init__.py
│   └── api.py                    # API REST pública (/api/v1/licenses/validate)
├── views/
│   ├── application_views.xml
│   ├── license_key_views.xml
│   ├── usage_stats_views.xml
│   ├── portal_templates.xml      # Portal de clientes
│   └── menu_views.xml
├── security/
│   ├── ir.model.access.csv       # Permisos
│   └── security.xml
├── data/
│   ├── cron_jobs.xml             # Revalidación diaria
│   └── email_templates.xml       # Emails de licencias
└── static/
    └── src/
        └── js/
            └── license_widget.js # Widget JS para copiar keys
```

---

### 🗄️ MODELOS DEL MÓDULO ODOO

```python
# models/application.py
from odoo import models, fields, api

class Application(models.Model):
    _name = 'workfluz.application'
    _description = 'Aplicaciones que usan el sistema de licencias'

    name = fields.Char('Nombre', required=True)
    slug = fields.Char('Slug', required=True, index=True)
    description = fields.Text('Descripción')
    
    # Configuración
    api_token = fields.Char('API Token', readonly=True)
    webhook_url = fields.Char('Webhook URL')
    
    # Configuración JSON (editable desde frontend)
    config = fields.Json('Configuración', default={})
    # Ejemplo: {"vapi_key": "xxx", "openai_key": "yyy", "max_voice_minutes": 1000}
    
    # Estadísticas
    total_licenses = fields.Integer('Total Licencias', compute='_compute_stats')
    active_licenses = fields.Integer('Licencias Activas', compute='_compute_stats')
    
    license_ids = fields.One2many('workfluz.license.key', 'application_id', 'Licencias')
    
    _sql_constraints = [
        ('slug_unique', 'UNIQUE(slug)', 'El slug debe ser único')
    ]
    
    @api.model
    def create(self, vals):
        # Generar API token automáticamente
        import secrets
        vals['api_token'] = secrets.token_urlsafe(32)
        return super().create(vals)

# models/license_key.py
class LicenseKey(models.Model):
    _name = 'workfluz.license.key'
    _description = 'License Keys para activación'
    _inherit = ['mail.thread', 'mail.activity.mixin']  # Tracking de cambios

    name = fields.Char('License Key', required=True, index=True, tracking=True)
    
    # Relaciones
    application_id = fields.Many2one('workfluz.application', 'Aplicación', required=True)
    partner_id = fields.Many2one('res.partner', 'Cliente', required=True)
    subscription_id = fields.Many2one('sale.subscription', 'Suscripción')
    
    # Estado
    status = fields.Selection([
        ('active', 'Activa'),
        ('expired', 'Expirada'),
        ('suspended', 'Suspendida'),
        ('revoked', 'Revocada')
    ], default='active', required=True, tracking=True)
    
    license_type = fields.Selection([
        ('subscription', 'Suscripción'),
        ('lifetime', 'Lifetime'),
        ('manual', 'Manual')
    ], default='subscription', required=True)
    
    # Activación
    site_url = fields.Char('URL del Sitio', tracking=True)
    site_name = fields.Char('Nombre del Sitio')
    activated_at = fields.Datetime('Activada el')
    last_validated_at = fields.Datetime('Última Validación')
    validation_count = fields.Integer('Validaciones', default=0)
    
    # Expiración
    expires_at = fields.Datetime('Expira el', tracking=True)
    
    # Límites
    max_activations = fields.Integer('Máx. Activaciones', default=1)
    current_activations = fields.Integer('Activaciones Actuales', default=0)
    
    # Metadata
    plugin_version = fields.Char('Versión Plugin')
    wordpress_version = fields.Char('Versión WordPress')
    php_version = fields.Char('Versión PHP')
    last_ip = fields.Char('Última IP')
    
    # Notas admin
    admin_notes = fields.Text('Notas del Admin')
    
    # Plan (desde suscripción o manual)
    plan_name = fields.Char('Plan', compute='_compute_plan')
    
    @api.model
    def create(self, vals):
        # Generar license key automáticamente
        if not vals.get('name'):
            vals['name'] = self._generate_license_key(vals.get('license_type', 'subscription'))
        return super().create(vals)
    
    def _generate_license_key(self, license_type):
        import random, string
        type_prefix = {
            'subscription': 'SUBS',
            'lifetime': 'LIFE',
            'manual': 'MANU'
        }
        prefix = type_prefix.get(license_type, 'CUST')
        year = fields.Date.today().year
        random_part = ''.join(random.choices(string.ascii_uppercase + string.digits, k=8))
        return f"WF-{prefix}-{year}-{random_part[:4]}-{random_part[4:]}"
    
    def action_revoke(self):
        self.write({
            'status': 'revoked',
            'revoked_at': fields.Datetime.now()
        })
        # Enviar email de notificación
        template = self.env.ref('workfluz_license_manager.email_license_revoked')
        template.send_mail(self.id, force_send=True)
    
    def action_extend(self, new_expiry_date):
        self.write({'expires_at': new_expiry_date})

# models/usage_stats.py
class UsageStats(models.Model):
    _name = 'workfluz.usage.stats'
    _description = 'Estadísticas de uso por licencia'

    license_id = fields.Many2one('workfluz.license.key', 'Licencia', required=True, ondelete='cascade')
    date = fields.Date('Fecha', required=True, default=fields.Date.today)
    
    text_messages_count = fields.Integer('Mensajes de Texto', default=0)
    voice_minutes = fields.Float('Minutos de Voz', default=0.0)
    total_end_users = fields.Integer('Total Usuarios', default=0)
    active_end_users = fields.Integer('Usuarios Activos', default=0)
    total_conversations = fields.Integer('Conversaciones', default=0)
    
    _sql_constraints = [
        ('license_date_unique', 'UNIQUE(license_id, date)', 'Solo una entrada por licencia por día')
    ]
```

---

### 🌐 CONTROLADOR API REST

```python
# controllers/api.py
from odoo import http
from odoo.http import request
import logging

_logger = logging.getLogger(__name__)

class LicenseAPIController(http.Controller):
    
    @http.route('/api/v1/licenses/validate', type='json', auth='public', methods=['POST'], csrf=False)
    def validate_license(self, **kwargs):
        """
        Endpoint para validar license keys desde WordPress
        
        Request: {
            "license_key": "WF-SUBS-2025-ABCD-1234",
            "site_url": "https://misitio.com",
            "plugin_version": "1.0.0",
            "wordpress_version": "6.4.2",
            "php_version": "8.2.0"
        }
        """
        try:
            license_key = kwargs.get('license_key')
            site_url = kwargs.get('site_url')
            
            if not license_key or not site_url:
                return {
                    'valid': False,
                    'message': 'Faltan parámetros requeridos'
                }
            
            # Buscar licencia
            License = request.env['workfluz.license.key'].sudo()
            license_obj = License.search([('name', '=', license_key)], limit=1)
            
            if not license_obj:
                return {
                    'valid': False,
                    'message': 'Licencia no encontrada'
                }
            
            # Verificar estado
            if license_obj.status == 'revoked':
                return {
                    'valid': False,
                    'message': 'Licencia revocada por el administrador'
                }
            
            if license_obj.status == 'suspended':
                return {
                    'valid': False,
                    'message': 'Licencia suspendida'
                }
            
            # Verificar expiración
            from datetime import datetime
            if license_obj.expires_at and license_obj.expires_at < datetime.now():
                license_obj.status = 'expired'
                return {
                    'valid': False,
                    'message': 'Licencia expirada',
                    'expires_at': license_obj.expires_at.isoformat()
                }
            
            # Verificar activaciones
            if license_obj.site_url and license_obj.site_url != site_url:
                if license_obj.current_activations >= license_obj.max_activations:
                    return {
                        'valid': False,
                        'message': f'Límite de activaciones excedido ({license_obj.current_activations}/{license_obj.max_activations})'
                    }
            
            # Actualizar metadata
            update_vals = {
                'last_validated_at': fields.Datetime.now(),
                'validation_count': license_obj.validation_count + 1,
                'plugin_version': kwargs.get('plugin_version'),
                'wordpress_version': kwargs.get('wordpress_version'),
                'php_version': kwargs.get('php_version'),
                'last_ip': request.httprequest.remote_addr
            }
            
            # Si es nueva activación
            if not license_obj.site_url:
                update_vals.update({
                    'site_url': site_url,
                    'activated_at': fields.Datetime.now(),
                    'current_activations': 1
                })
            
            license_obj.write(update_vals)
            
            # Obtener plan desde suscripción
            plan_features = {}
            if license_obj.subscription_id:
                plan = license_obj.subscription_id.plan_id
                plan_features = {
                    'unlimited_messages': True,  # Según el plan
                    'unlimited_voice': True,
                    'branding_removal': True,
                    'priority_support': True
                }
            
            return {
                'valid': True,
                'plan': license_obj.plan_name or 'premium',
                'status': license_obj.status,
                'expires_at': license_obj.expires_at.isoformat() if license_obj.expires_at else None,
                'features': plan_features,
                'license': {
                    'type': license_obj.license_type,
                    'current_activations': license_obj.current_activations,
                    'max_activations': license_obj.max_activations
                },
                'message': 'Licencia válida'
            }
            
        except Exception as e:
            _logger.error(f"Error validando licencia: {str(e)}")
            return {
                'valid': False,
                'message': 'Error interno del servidor'
            }
```

---

### ⚙️ CONFIGURACIÓN DOCKER PARA EASYPANEL

```yaml
# docker-compose.yml
version: '3.8'

services:
  web:
    image: odoo:17.0
    depends_on:
      - db
      - redis
    ports:
      - "8069:8069"
    environment:
      - HOST=db
      - USER=odoo
      - PASSWORD=${ODOO_DB_PASSWORD}
    volumes:
      - odoo-web-data:/var/lib/odoo
      - ./addons:/mnt/extra-addons  # Módulo custom aquí
      - ./config:/etc/odoo
    command: -- --dev=reload

  db:
    image: postgres:15
    environment:
      - POSTGRES_DB=postgres
      - POSTGRES_USER=odoo
      - POSTGRES_PASSWORD=${ODOO_DB_PASSWORD}
    volumes:
      - odoo-db-data:/var/lib/postgresql/data

  redis:
    image: redis:7-alpine
    volumes:
      - odoo-redis-data:/data

volumes:
  odoo-web-data:
  odoo-db-data:
  odoo-redis-data:
```

---

### 📋 INSTALACIÓN PASO A PASO (ODOO.SH CUSTOM PLAN)

#### 1️⃣ Crear Cuenta en Odoo.sh

```bash
# 1. Ir a https://www.odoo.sh/
# 2. Click en "Start now" o "Get started"
# 3. Seleccionar plan "Custom" ($13.60/user/mes)
# 4. Crear proyecto nuevo:
#    - Nombre: workfluz-license-manager
#    - Versión: Odoo 17
#    - Región: US East (más cercana a Colombia)
```

#### 2️⃣ Configurar Proyecto en Odoo.sh

```bash
# Odoo.sh crea automáticamente:
✅ Base de datos PostgreSQL
✅ Entorno de producción (production)
✅ Entorno de staging (staging)
✅ Repositorio Git privado
✅ CI/CD automático
✅ Backups diarios
✅ SSL certificado

# Acceder al panel:
https://workfluz-license-manager.odoo.com
```

#### 3️⃣ Instalar Módulos Base (desde Odoo.sh)

```bash
# Login en tu instancia Odoo
# Apps → Actualizar lista de apps
# Instalar módulos Enterprise:
✅ Sales
✅ Subscriptions (sale_subscription) - Enterprise
✅ Invoicing (account)
✅ eCommerce (website_sale)
✅ Portal
✅ Contacts (CRM)
✅ Studio (personalización drag & drop)
✅ Payment Providers (Stripe, PayPal, Authorize.net)
```

#### 4️⃣ Desarrollar Módulo Custom con Git

```bash
# Clonar repositorio de Odoo.sh
git clone git@github.com:odoo/workfluz-license-manager.git
cd workfluz-license-manager

# Crear módulo custom
mkdir -p workfluz_license_manager
cd workfluz_license_manager

# Copiar estructura del módulo (del PROMPT)
# Ver sección: ESTRUCTURA DEL MÓDULO CUSTOM

# Commit y push
git add .
git commit -m "feat: Add workfluz_license_manager module"
git push origin main

# Odoo.sh detecta automáticamente y rebuilds
# Esperar ~5 minutos para deploy automático
```

#### 5️⃣ Instalar Módulo Custom desde Odoo

```bash
# Después del deploy automático:
# Apps → Actualizar lista de apps
# Buscar "Workfluz License Manager"
# Click en "Instalar"

# O usar Odoo.sh CLI:
odoo-bin -d workfluz --install=workfluz_license_manager
```

#### 6️⃣ Configurar Pasarelas de Pago (Enterprise)

**Stripe (Oficial Enterprise)**:
```bash
# Apps → Payment Providers → Stripe
# Ya viene instalado en Enterprise
# Configuración → Proveedores de Pago → Stripe
#   - Estado: Habilitado
#   - Publishable Key: pk_live_xxx
#   - Secret Key: sk_live_xxx
#   - Webhook Secret: whsec_xxx
#   - Suscripciones: ✅ Activado
#   - Webhook URL: https://workfluz-license-manager.odoo.com/payment/stripe/webhook
```

**PayPal (Oficial Enterprise)**:
```bash
# Apps → Payment Providers → PayPal
# Configuración similar a Stripe
```

**Wompi (Custom) - Opcional**:
```bash
# Desarrollar módulo payment_wompi
# Seguir estructura del PROMPT (sección Wompi)
# Push al repositorio Git
# Odoo.sh lo instala automáticamente
```

#### 7️⃣ Configurar Planes de Suscripción con Studio

```bash
# Ventas → Configuración → Planes de Suscripción → Crear

# Opción A: Crear manualmente
Plan: Free
  - Precio: $0/mes
  - Recurrencia: Mensual
  - Productos: [Free Plan - AI Widget]
  - Features: 100 mensajes, 30 min voz, con branding

Plan: Premium
  - Precio: $29/mes o $290/año (descuento)
  - Recurrencia: Mensual/Anual
  - Productos: [Premium Plan - AI Widget]
  - Auto-renovable: Sí
  - Features: Mensajes ilimitados, voz ilimitada, sin branding
  
Plan: Pro
  - Precio: $49/mes o $490/año
  - Features: + White label
  
Plan: Enterprise
  - Precio: $199/mes o $1990/año
  - Features: + Soporte dedicado, integraciones custom

# Opción B: Usar Odoo Studio (recomendado)
# Apps → Studio → Subscriptions
# Drag & drop para crear campos custom
# Agregar lógica de negocio sin código
```

#### 8️⃣ Crear Aplicaciones con Studio

```bash
# Workfluz License Manager → Aplicaciones → Crear

# Usar Studio para personalizar campos:
Aplicación: AI Voice Text Widget
  - Slug: ai-voice-text-widget (único)
  - Descripción: Plugin de WordPress para chat con voz
  - Logo: [subir imagen]
  
  # Configuración JSON (campo Studio):
  - Config Editor: [campo JSON visual]
    {
      "vapi_private_key": "",
      "vapi_public_key": "",
      "openai_api_key": "",
      "max_text_messages": 100,
      "max_voice_minutes": 30
    }
  
  # Studio genera automáticamente:
  - API Token: [campo computed]
  - Total Licencias: [campo computed con gráfico]
  - Licencias Activas: [campo computed]
```

#### 9️⃣ Configurar Webhooks en Pasarelas

```bash
# En cada pasarela de pago, configurar webhook URL:
Stripe: https://workfluz-license-manager.odoo.com/payment/stripe/webhook
PayPal: https://workfluz-license-manager.odoo.com/payment/paypal/webhook
Wompi: https://workfluz-license-manager.odoo.com/payment/wompi/webhook
MercadoPago: https://workfluz-license-manager.odoo.com/payment/mercadopago/webhook

# Odoo.sh maneja automáticamente:
✅ Verificación de firmas
✅ Reintentos en caso de error
✅ Logs de webhooks (Configuración → Technical → Webhooks)
```

#### 🔟 Probar Creación Manual de Licencias

```bash
# Workfluz License Manager → Licencias → Crear

Licencia Manual:
  - Aplicación: AI Voice Text Widget [selector]
  - Cliente: Juan Pérez [buscar o crear]
  - Tipo: Manual [dropdown]
  - Expira el: 2026-12-31 [date picker]
  - Máx. activaciones: 1 [número]
  - Notas: Licencia de prueba para cliente VIP [textarea]
  
# Click "Guardar" → License key generada automáticamente:
# WF-MANU-2025-ABCD-1234

# Enviar email automático al cliente con:
✅ License key
✅ Instrucciones de activación
✅ Link al portal de clientes
```

---

### 🎨 USAR ODOO STUDIO PARA PERSONALIZACIÓN SIN CÓDIGO

#### Ejemplo: Agregar campo custom "Plan Features" visual

```bash
# 1. Activar modo Studio
# Apps → Studio → Subscriptions → Planes

# 2. Agregar campo "Features Matrix"
# Drag & drop: Campo "Many2many tags"
# Opciones:
  - Mensajes ilimitados
  - Voz ilimitada
  - Sin branding
  - Soporte prioritario
  - White label
  - Integraciones custom

# 3. Crear vista kanban personalizada
# Studio → Views → Kanban
# Drag & drop campos:
  - Imagen del plan
  - Precio destacado
  - Lista de features con checkmarks
  - Botón CTA "Suscribirse"

# 4. Publicar en Website
# Website → Pricing Page (drag & drop)
# Studio genera código automáticamente
```

#### Ejemplo: Dashboard de Analytics con Studio

```bash
# Studio → Workfluz License Manager → Dashboard

# Agregar widgets drag & drop:
1. KPI Tile: Total Licencias Activas
2. Line Chart: Licencias creadas por mes
3. Pie Chart: Distribución por plan (Free, Premium, Pro)
4. Bar Chart: MRR por aplicación
5. List View: Top 10 clientes por revenue
6. Gauge: Tasa de renovación (churn rate)

# Studio genera queries SQL automáticamente
# Sin escribir código Python
```

---

### ⚙️ CONFIGURACIÓN AVANZADA EN ODOO.SH

#### Entornos de Desarrollo

```bash
# Odoo.sh crea 3 entornos automáticamente:

1. Production (main branch)
   - URL: https://workfluz-license-manager.odoo.com
   - Base de datos: producción real
   - Backups: diarios automáticos

2. Staging (staging branch)
   - URL: https://workfluz-license-manager-staging.odoo.com
   - Base de datos: copia de producción
   - Testing antes de deploy

3. Development (ramas feature/*)
   - URL: https://workfluz-license-manager-dev-123.odoo.com
   - Base de datos: datos de prueba
   - Para desarrollo activo
```

#### CI/CD Automático

```bash
# Workflow automático al hacer push:

git push origin main
↓
Odoo.sh detecta cambio
↓
Ejecuta tests automáticos
↓
Build nuevo contenedor
↓
Deploy sin downtime (blue-green)
↓
Notificación email/Slack
↓
Logs disponibles en dashboard

# Si tests fallan: rollback automático
```

#### Backups y Restauración

```bash
# Panel Odoo.sh → Backups

# Backups automáticos:
- Diarios: últimos 30 días
- Semanales: últimos 3 meses
- Mensuales: último año

# Restaurar backup:
# Click en backup → "Restore" → Seleccionar entorno
# Toma ~5 minutos

# Download backup manual:
# Click → "Download" → archivo .dump (PostgreSQL)
```

---

### 🔌 INTEGRACIÓN CON PASARELAS DE PAGO (ENTERPRISE)

#### Stripe Enterprise (Recomendado)

**Ventajas del módulo Enterprise vs Community**:
- ✅ Soporte oficial de Odoo
- ✅ Actualizaciones automáticas
- ✅ Suscripciones con Stripe Billing integrado
- ✅ Webhook automático configurado
- ✅ 3D Secure 2.0 (SCA compliance)
- ✅ Apple Pay / Google Pay
- ✅ Manejo de disputes/chargebacks
- ✅ Reportes de revenue en dashboard

**Configuración**:
```python
# Configuración → Proveedores de Pago → Stripe
{
  "name": "Stripe",
  "state": "enabled",
  "stripe_publishable_key": "pk_live_xxx",
  "stripe_secret_key": "sk_live_xxx",
  "stripe_webhook_secret": "whsec_xxx",
  "payment_flow": "redirect",  # Redirige a Stripe Checkout
  "capture_manually": False,
  "journal_id": "Banco"  # Diario contable
}
```

**3. Webhook automático**:
- Odoo maneja automáticamente: `checkout.session.completed`, `invoice.payment_succeeded`
- Crear license key en el evento `invoice.payment_succeeded`

**4. Extender con hook**:
```python
# En workfluz_license_manager/models/payment_transaction.py
from odoo import models

class PaymentTransaction(models.Model):
    _inherit = 'payment.transaction'
    
    def _reconcile_after_done(self):
        """Hook: Crear license key después de pago exitoso"""
        res = super()._reconcile_after_done()
        
        for tx in self:
            if tx.provider_code == 'stripe' and tx.state == 'done':
                # Buscar suscripción asociada
                subscription = self.env['sale.subscription'].search([
                    ('partner_id', '=', tx.partner_id.id),
                    ('state', '=', 'open')
                ], limit=1)
                
                if subscription:
                    # Crear license key automáticamente
                    self.env['workfluz.license.key'].create({
                        'partner_id': tx.partner_id.id,
                        'subscription_id': subscription.id,
                        'application_id': 1,  # ID de la app
                        'license_type': 'subscription',
                        'expires_at': subscription.date,
                        'status': 'active'
                    })
        
        return res
```

---

#### Wompi (Colombia)

**Crear módulo custom** `payment_wompi`:

```python
# models/payment_provider.py
from odoo import fields, models

class PaymentProvider(models.Model):
    _inherit = 'payment.provider'
    
    code = fields.Selection(
        selection_add=[('wompi', 'Wompi')],
        ondelete={'wompi': 'set default'}
    )
    wompi_public_key = fields.Char('Wompi Public Key')
    wompi_private_key = fields.Char('Wompi Private Key')

# controllers/main.py
from odoo import http
from odoo.http import request
import requests
import hmac
import hashlib

class WompiController(http.Controller):
    
    @http.route('/payment/wompi/webhook', type='json', auth='public', csrf=False)
    def wompi_webhook(self, **kwargs):
        """Recibir webhooks de Wompi"""
        
        # Verificar firma
        signature = request.httprequest.headers.get('X-Wompi-Signature')
        payload = request.httprequest.get_data()
        
        provider = request.env['payment.provider'].sudo().search([('code', '=', 'wompi')], limit=1)
        calculated_signature = hmac.new(
            provider.wompi_private_key.encode(),
            payload,
            hashlib.sha256
        ).hexdigest()
        
        if signature != calculated_signature:
            return {'error': 'Invalid signature'}
        
        # Procesar evento
        event = kwargs.get('event')
        data = kwargs.get('data', {})
        
        if event == 'transaction.updated' and data.get('status') == 'APPROVED':
            # Buscar transacción
            tx = request.env['payment.transaction'].sudo().search([
                ('reference', '=', data.get('reference'))
            ], limit=1)
            
            if tx:
                tx._set_done()
                
        return {'status': 'ok'}
```

---

### 🎨 PERSONALIZAR PORTAL DE CLIENTES

```xml
<!-- views/portal_templates.xml -->
<odoo>
    <template id="portal_my_licenses" name="My Licenses">
        <t t-call="portal.portal_layout">
            <div class="container mt-4">
                <h1>Mis License Keys</h1>
                
                <t t-foreach="licenses" t-as="license">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">
                                <span t-field="license.name"/>
                                <span t-att-class="'badge badge-' + ('success' if license.status == 'active' else 'danger')">
                                    <t t-esc="license.status"/>
                                </span>
                            </h5>
                            
                            <p><strong>Aplicación:</strong> <span t-field="license.application_id.name"/></p>
                            <p><strong>Plan:</strong> <span t-field="license.plan_name"/></p>
                            <p><strong>Sitio:</strong> <span t-field="license.site_url"/></p>
                            <p><strong>Expira:</strong> <span t-field="license.expires_at"/></p>
                            
                            <button class="btn btn-primary btn-sm" onclick="copyLicenseKey(this)" 
                                    t-att-data-key="license.name">
                                Copiar Key
                            </button>
                        </div>
                    </div>
                </t>
            </div>
        </t>
    </template>
</odoo>
```

---

## 🎯 STACK TECNOLÓGICO RECOMENDADO (OPCIÓN 2 - CUSTOM)

### Backend: FastAPI (Python) - RECOMENDADO

**¿Por qué FastAPI?**
- 🚀 Ultra-rápido (comparable a Node.js)
- 📝 Auto-documentación (Swagger UI integrado)
- ✅ Type hints nativos (menos bugs)
- 🔐 Seguridad integrada (OAuth2, JWT)
- 📦 Pequeño (imagen Docker ~80MB)
- 🐍 Python (fácil de mantener)

```python
# Ejemplo de endpoint
from fastapi import FastAPI, Depends
from pydantic import BaseModel

app = FastAPI()

class LicenseValidation(BaseModel):
    license_key: str
    site_url: str

@app.post("/api/v1/licenses/validate")
async def validate_license(data: LicenseValidation):
    # Auto-validación de tipos
    license = await get_license(data.license_key)
    return {"valid": license.is_active}
```

### Frontend: Next.js 14 o Astro

**Next.js 14** (si necesitas dashboard complejo):
- Server Components
- App Router
- React Server Actions
- Deploy gratis en Vercel

**Astro** (si prefieres ligereza):
- Genera HTML estático
- Hidratación parcial
- Más rápido que Next.js
- Componentes de cualquier framework

---

## �🎯 STACK TECNOLÓGICO RECOMENDADO

### Opción 1: Laravel + Next.js (Recomendado)
```yaml
Backend:
  Framework: Laravel 11
  Base de datos: PostgreSQL 15
  Cache: Redis
  Queue: Laravel Horizon
  
Frontend:
  Framework: Next.js 14 (App Router)
  UI Library: Tailwind CSS + Shadcn/ui
  Estado: Zustand / React Query
  
Hosting:
  Backend: Railway / AWS / DigitalOcean
  Frontend: Vercel
  Base de datos: Supabase / Railway PostgreSQL
  
Pagos:
  - Wompi (Colombia)
  - Bold (Colombia/LATAM)
  - MercadoPago (LATAM)
  - Stripe (Internacional)
```

### Opción 2: Node.js Full Stack
```yaml
Backend:
  Framework: Express.js + TypeScript
  ORM: Prisma
  Validación: Zod
  
Frontend:
  Framework: Next.js 14
  
Todo lo demás igual a Opción 1
```

---

## 📊 DISEÑO DE BASE DE DATOS

### Diagrama ER (Tablas principales)

```sql
-- ============================================
-- TABLA: users
-- Almacena los clientes de Workfluz
-- ============================================
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    country VARCHAR(2) DEFAULT 'CO', -- ISO 3166-1 alpha-2
    phone VARCHAR(20),
    company_name VARCHAR(200),
    tax_id VARCHAR(50), -- NIT, RUT, RFC, etc.
    email_verified_at TIMESTAMP,
    two_factor_enabled BOOLEAN DEFAULT FALSE,
    two_factor_secret TEXT,
    status VARCHAR(20) DEFAULT 'active', -- active, suspended, deleted
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW(),
    deleted_at TIMESTAMP
);

CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_status ON users(status);

-- ============================================
-- TABLA: subscriptions
-- Suscripciones activas de los usuarios
-- ============================================
CREATE TABLE subscriptions (
    id SERIAL PRIMARY KEY,
    user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    plan_id INTEGER NOT NULL REFERENCES plans(id),
    
    -- Estado de la suscripción
    status VARCHAR(20) DEFAULT 'active', 
    -- Valores: trial, active, past_due, canceled, expired, paused
    
    -- Ciclo de facturación
    billing_cycle VARCHAR(20) NOT NULL, -- monthly, yearly
    amount DECIMAL(10,2) NOT NULL, -- Precio en moneda local
    currency VARCHAR(3) DEFAULT 'USD', -- USD, COP, MXN, etc.
    
    -- IDs de pasarelas de pago
    payment_gateway VARCHAR(50), -- wompi, bold, mercadopago, stripe, manual
    gateway_customer_id VARCHAR(255), -- ID del cliente en la pasarela
    gateway_subscription_id VARCHAR(255), -- ID de la suscripción en la pasarela
    
    -- Fechas importantes
    trial_ends_at TIMESTAMP,
    current_period_start TIMESTAMP NOT NULL,
    current_period_end TIMESTAMP NOT NULL,
    canceled_at TIMESTAMP,
    ends_at TIMESTAMP, -- Fecha de terminación definitiva
    
    -- Metadata
    next_billing_date TIMESTAMP,
    last_billing_date TIMESTAMP,
    failed_payment_attempts INTEGER DEFAULT 0,
    
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW()
);

CREATE INDEX idx_subscriptions_user ON subscriptions(user_id);
CREATE INDEX idx_subscriptions_status ON subscriptions(status);
CREATE INDEX idx_subscriptions_gateway_sub ON subscriptions(gateway_subscription_id);

-- ============================================
-- TABLA: plans
-- Planes disponibles (Free, Premium, Pro, Enterprise)
-- ============================================
CREATE TABLE plans (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL, -- Free, Premium, Pro, Enterprise
    slug VARCHAR(100) UNIQUE NOT NULL, -- free, premium, pro, enterprise
    description TEXT,
    
    -- Precios
    monthly_price DECIMAL(10,2) DEFAULT 0,
    yearly_price DECIMAL(10,2) DEFAULT 0,
    currency VARCHAR(3) DEFAULT 'USD',
    
    -- Límites del plan
    features JSONB, -- {"unlimited_messages": true, "unlimited_voice": true, "branding_removal": true}
    
    -- Control
    is_active BOOLEAN DEFAULT TRUE,
    display_order INTEGER DEFAULT 0,
    
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW()
);

CREATE INDEX idx_plans_slug ON plans(slug);

-- Insertar planes por defecto
INSERT INTO plans (name, slug, monthly_price, yearly_price, features) VALUES
('Free', 'free', 0, 0, '{"text_messages_limit": 100, "voice_minutes_limit": 30, "branding": true}'),
('Premium', 'premium', 29, 290, '{"unlimited_messages": true, "unlimited_voice": true, "branding_removal": true, "priority_support": true}'),
('Pro', 'pro', 49, 490, '{"unlimited_messages": true, "unlimited_voice": true, "branding_removal": true, "priority_support": true, "white_label": true}'),
('Enterprise', 'enterprise', 199, 1990, '{"unlimited_messages": true, "unlimited_voice": true, "branding_removal": true, "priority_support": true, "white_label": true, "dedicated_support": true, "custom_integrations": true}');

-- ============================================
-- TABLA: licenses
-- License Keys para activar en sitios WordPress
-- ============================================
CREATE TABLE licenses (
    id SERIAL PRIMARY KEY,
    subscription_id INTEGER REFERENCES subscriptions(id) ON DELETE SET NULL,
    user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    
    -- License Key
    license_key VARCHAR(255) UNIQUE NOT NULL, -- WF-XXXX-XXXX-XXXX-XXXX
    status VARCHAR(20) DEFAULT 'active', -- active, expired, suspended, revoked
    
    -- Tipo de licencia
    type VARCHAR(20) DEFAULT 'subscription', -- subscription, lifetime, manual
    
    -- Activación
    site_url VARCHAR(500), -- URL del sitio WordPress donde está activada
    site_name VARCHAR(200), -- Nombre del sitio
    activated_at TIMESTAMP,
    last_validated_at TIMESTAMP, -- Última vez que el plugin validó la licencia
    validation_count INTEGER DEFAULT 0, -- Número de validaciones
    
    -- Expiración
    expires_at TIMESTAMP, -- NULL = lifetime
    
    -- Límites de activación
    max_activations INTEGER DEFAULT 1, -- Cuántos sitios pueden usar esta licencia
    current_activations INTEGER DEFAULT 0,
    
    -- Metadata
    plugin_version VARCHAR(20), -- Última versión del plugin detectada
    wordpress_version VARCHAR(20), -- Versión de WordPress del sitio
    php_version VARCHAR(20),
    last_ip VARCHAR(45), -- IPv4 o IPv6
    
    -- Notas del admin (para licencias manuales)
    admin_notes TEXT,
    
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW(),
    revoked_at TIMESTAMP,
    
    CONSTRAINT check_activations CHECK (current_activations <= max_activations)
);

CREATE INDEX idx_licenses_key ON licenses(license_key);
CREATE INDEX idx_licenses_user ON licenses(user_id);
CREATE INDEX idx_licenses_subscription ON licenses(subscription_id);
CREATE INDEX idx_licenses_status ON licenses(status);
CREATE INDEX idx_licenses_site ON licenses(site_url);

-- ============================================
-- TABLA: payments
-- Historial de pagos
-- ============================================
CREATE TABLE payments (
    id SERIAL PRIMARY KEY,
    user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    subscription_id INTEGER REFERENCES subscriptions(id) ON DELETE SET NULL,
    
    -- Monto
    amount DECIMAL(10,2) NOT NULL,
    currency VARCHAR(3) DEFAULT 'USD',
    
    -- Pasarela de pago
    payment_gateway VARCHAR(50) NOT NULL, -- wompi, bold, mercadopago, stripe, manual
    gateway_transaction_id VARCHAR(255), -- ID de la transacción en la pasarela
    gateway_customer_id VARCHAR(255),
    
    -- Estado
    status VARCHAR(20) DEFAULT 'pending', -- pending, completed, failed, refunded, canceled
    
    -- Método de pago
    payment_method VARCHAR(50), -- card, pse, nequi, bancolombia, efecty, etc.
    card_last4 VARCHAR(4),
    card_brand VARCHAR(20),
    
    -- Facturación
    invoice_number VARCHAR(50) UNIQUE,
    invoice_pdf_url TEXT,
    
    -- Metadata
    description TEXT,
    metadata JSONB, -- Información adicional de la pasarela
    
    -- Fechas
    paid_at TIMESTAMP,
    refunded_at TIMESTAMP,
    
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW()
);

CREATE INDEX idx_payments_user ON payments(user_id);
CREATE INDEX idx_payments_subscription ON payments(subscription_id);
CREATE INDEX idx_payments_status ON payments(status);
CREATE INDEX idx_payments_gateway_tx ON payments(gateway_transaction_id);

-- ============================================
-- TABLA: usage_stats
-- Estadísticas de uso de cada sitio WordPress
-- ============================================
CREATE TABLE usage_stats (
    id SERIAL PRIMARY KEY,
    license_id INTEGER NOT NULL REFERENCES licenses(id) ON DELETE CASCADE,
    date DATE NOT NULL,
    
    -- Contadores
    text_messages_count INTEGER DEFAULT 0,
    voice_minutes DECIMAL(10,2) DEFAULT 0,
    total_end_users INTEGER DEFAULT 0,
    active_end_users INTEGER DEFAULT 0,
    
    -- Métricas
    avg_response_time DECIMAL(10,2), -- En segundos
    total_conversations INTEGER DEFAULT 0,
    
    created_at TIMESTAMP DEFAULT NOW(),
    
    UNIQUE(license_id, date)
);

CREATE INDEX idx_usage_license ON usage_stats(license_id);
CREATE INDEX idx_usage_date ON usage_stats(date);

-- ============================================
-- TABLA: webhook_events
-- Log de webhooks recibidos de pasarelas de pago
-- ============================================
CREATE TABLE webhook_events (
    id SERIAL PRIMARY KEY,
    payment_gateway VARCHAR(50) NOT NULL,
    event_type VARCHAR(100) NOT NULL, -- subscription.created, payment.succeeded, etc.
    
    -- Payload
    payload JSONB NOT NULL,
    
    -- Procesamiento
    status VARCHAR(20) DEFAULT 'pending', -- pending, processed, failed
    processed_at TIMESTAMP,
    error_message TEXT,
    
    -- Metadata
    signature VARCHAR(500), -- Firma del webhook para verificación
    ip_address VARCHAR(45),
    
    created_at TIMESTAMP DEFAULT NOW()
);

CREATE INDEX idx_webhooks_gateway ON webhook_events(payment_gateway);
CREATE INDEX idx_webhooks_status ON webhook_events(status);
CREATE INDEX idx_webhooks_created ON webhook_events(created_at);

-- ============================================
-- TABLA: admin_users
-- Administradores de la plataforma
-- ============================================
CREATE TABLE admin_users (
    id SERIAL PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    name VARCHAR(200) NOT NULL,
    
    -- Permisos
    role VARCHAR(50) DEFAULT 'admin', -- super_admin, admin, support
    permissions JSONB, -- {"manage_users": true, "manage_licenses": true, "view_payments": true}
    
    -- Seguridad
    last_login_at TIMESTAMP,
    last_login_ip VARCHAR(45),
    
    status VARCHAR(20) DEFAULT 'active',
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW()
);

CREATE INDEX idx_admin_users_email ON admin_users(email);

-- ============================================
-- TABLA: audit_logs
-- Registro de acciones importantes
-- ============================================
CREATE TABLE audit_logs (
    id SERIAL PRIMARY KEY,
    
    -- Actor
    actor_type VARCHAR(50) NOT NULL, -- user, admin, system, api
    actor_id INTEGER,
    
    -- Acción
    action VARCHAR(100) NOT NULL, -- license.created, subscription.canceled, payment.refunded
    entity_type VARCHAR(50), -- license, subscription, payment
    entity_id INTEGER,
    
    -- Detalles
    description TEXT,
    metadata JSONB,
    
    -- Contexto
    ip_address VARCHAR(45),
    user_agent TEXT,
    
    created_at TIMESTAMP DEFAULT NOW()
);

CREATE INDEX idx_audit_actor ON audit_logs(actor_type, actor_id);
CREATE INDEX idx_audit_action ON audit_logs(action);
CREATE INDEX idx_audit_created ON audit_logs(created_at);

-- ============================================
-- TABLA: notifications
-- Notificaciones para usuarios
-- ============================================
CREATE TABLE notifications (
    id SERIAL PRIMARY KEY,
    user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    
    -- Contenido
    type VARCHAR(50) NOT NULL, -- payment_failed, license_expiring, subscription_renewed
    title VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    
    -- Estado
    read_at TIMESTAMP,
    
    -- Metadata
    action_url TEXT,
    metadata JSONB,
    
    created_at TIMESTAMP DEFAULT NOW()
);

CREATE INDEX idx_notifications_user ON notifications(user_id);
CREATE INDEX idx_notifications_read ON notifications(read_at);
```

---

## 🔌 API REST ENDPOINTS

### Base URL
```
https://app.workfluz.com/api/v1
```

### Autenticación
- **Usuarios**: JWT Bearer Token
- **Plugin WordPress**: License Key en header o body

---

### 📍 ENDPOINTS PÚBLICOS (Sin autenticación)

#### 1. POST /api/v1/auth/register
**Descripción**: Registro de nuevos usuarios

**Request Body**:
```json
{
  "email": "juan@ejemplo.com",
  "password": "SecurePass123!",
  "first_name": "Juan",
  "last_name": "Pérez",
  "country": "CO",
  "phone": "+57 300 123 4567",
  "company_name": "Mi Empresa SAS",
  "tax_id": "900123456-1"
}
```

**Response 201**:
```json
{
  "success": true,
  "data": {
    "user": {
      "id": 123,
      "email": "juan@ejemplo.com",
      "first_name": "Juan",
      "last_name": "Pérez",
      "status": "active"
    },
    "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
    "expires_in": 86400
  },
  "message": "Usuario registrado exitosamente. Por favor verifica tu email."
}
```

**Response 422** (Validación):
```json
{
  "success": false,
  "errors": {
    "email": ["El email ya está registrado"],
    "password": ["La contraseña debe tener al menos 8 caracteres"]
  }
}
```

---

#### 2. POST /api/v1/auth/login
**Descripción**: Iniciar sesión

**Request Body**:
```json
{
  "email": "juan@ejemplo.com",
  "password": "SecurePass123!"
}
```

**Response 200**:
```json
{
  "success": true,
  "data": {
    "user": {
      "id": 123,
      "email": "juan@ejemplo.com",
      "first_name": "Juan",
      "last_name": "Pérez"
    },
    "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
    "expires_in": 86400
  }
}
```

**Response 401**:
```json
{
  "success": false,
  "message": "Credenciales inválidas"
}
```

---

#### 3. POST /api/v1/auth/forgot-password
**Request**:
```json
{
  "email": "juan@ejemplo.com"
}
```

**Response 200**:
```json
{
  "success": true,
  "message": "Hemos enviado un enlace de recuperación a tu email"
}
```

---

#### 4. POST /api/v1/licenses/validate ⭐ (USADO POR PLUGIN)
**Descripción**: Validar una license key desde WordPress

**Headers**:
```
Content-Type: application/x-www-form-urlencoded
User-Agent: AI-Widget-Plugin/1.0.0
```

**Request Body**:
```
license_key=WF-PREM-2025-ABCD-1234
site_url=https://misitio.com
plugin_version=1.0.0
wordpress_version=6.4.2
php_version=8.2.0
```

**Response 200** (Licencia válida):
```json
{
  "valid": true,
  "plan": "premium",
  "status": "active",
  "expires_at": "2026-10-23T23:59:59Z",
  "features": {
    "unlimited_messages": true,
    "unlimited_voice": true,
    "branding_removal": true,
    "priority_support": true
  },
  "license": {
    "type": "subscription",
    "current_activations": 1,
    "max_activations": 1
  },
  "message": "Licencia válida"
}
```

**Response 200** (Licencia inválida):
```json
{
  "valid": false,
  "message": "Licencia expirada. Por favor renueva tu suscripción.",
  "expires_at": "2025-09-15T23:59:59Z"
}
```

**Casos de error**:
- `"Licencia no encontrada"`
- `"Licencia revocada por el administrador"`
- `"Límite de activaciones excedido (1/1 sitios)"`
- `"Sitio no autorizado. Esta licencia está activada en otro dominio"`
- `"La suscripción asociada está cancelada"`

**Lógica del endpoint**:
```php
1. Buscar license en DB por license_key
2. Si no existe → return {valid: false, message: "Licencia no encontrada"}
3. Verificar status:
   - Si status = "revoked" → return {valid: false, message: "Licencia revocada"}
   - Si status = "suspended" → return {valid: false, message: "Licencia suspendida"}
4. Verificar expiración:
   - Si expires_at < now() → return {valid: false, message: "Licencia expirada"}
5. Verificar activaciones:
   - Si site_url != license.site_url Y current_activations >= max_activations → error
   - Si site_url es nuevo → incrementar current_activations
6. Actualizar metadata:
   - last_validated_at = now()
   - validation_count++
   - plugin_version, wordpress_version, php_version
7. Registrar en usage_stats si viene con data
8. Return {valid: true, plan: ..., features: ...}
```

---

### 📍 ENDPOINTS AUTENTICADOS (Requieren Bearer Token)

#### 5. GET /api/v1/user/profile
**Descripción**: Obtener perfil del usuario

**Headers**:
```
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
```

**Response 200**:
```json
{
  "success": true,
  "data": {
    "id": 123,
    "email": "juan@ejemplo.com",
    "first_name": "Juan",
    "last_name": "Pérez",
    "country": "CO",
    "phone": "+57 300 123 4567",
    "company_name": "Mi Empresa SAS",
    "tax_id": "900123456-1",
    "email_verified_at": "2025-10-20T10:30:00Z",
    "created_at": "2025-10-15T08:00:00Z"
  }
}
```

---

#### 6. PUT /api/v1/user/profile
**Descripción**: Actualizar perfil

**Request**:
```json
{
  "first_name": "Juan Carlos",
  "phone": "+57 300 999 8888"
}
```

**Response 200**:
```json
{
  "success": true,
  "data": {
    "id": 123,
    "first_name": "Juan Carlos",
    "phone": "+57 300 999 8888"
  },
  "message": "Perfil actualizado"
}
```

---

#### 7. GET /api/v1/subscriptions
**Descripción**: Listar suscripciones del usuario

**Response 200**:
```json
{
  "success": true,
  "data": [
    {
      "id": 456,
      "plan": {
        "id": 2,
        "name": "Premium",
        "slug": "premium"
      },
      "status": "active",
      "billing_cycle": "monthly",
      "amount": 29.00,
      "currency": "USD",
      "current_period_start": "2025-10-01T00:00:00Z",
      "current_period_end": "2025-11-01T00:00:00Z",
      "next_billing_date": "2025-11-01T00:00:00Z",
      "payment_gateway": "stripe",
      "created_at": "2025-09-15T12:00:00Z"
    }
  ]
}
```

---

#### 8. POST /api/v1/subscriptions/create
**Descripción**: Crear nueva suscripción

**Request**:
```json
{
  "plan_id": 2,
  "billing_cycle": "yearly",
  "payment_gateway": "wompi",
  "return_url": "https://app.workfluz.com/dashboard/subscription/success",
  "cancel_url": "https://app.workfluz.com/pricing"
}
```

**Response 200** (Redirigir a checkout):
```json
{
  "success": true,
  "data": {
    "subscription_id": 789,
    "checkout_url": "https://checkout.wompi.co/p/ABC123XYZ",
    "expires_at": "2025-10-23T15:00:00Z"
  },
  "message": "Redirigiendo a pasarela de pago..."
}
```

**Flujo**:
1. Validar plan_id existe y está activo
2. Crear subscription con status = "pending"
3. Generar session de checkout en la pasarela seleccionada
4. Retornar checkout_url
5. Usuario paga en la pasarela
6. Webhook actualiza subscription a "active"
7. Generar license key automáticamente

---

#### 9. POST /api/v1/subscriptions/{id}/cancel
**Descripción**: Cancelar suscripción

**Response 200**:
```json
{
  "success": true,
  "data": {
    "id": 456,
    "status": "canceled",
    "ends_at": "2025-11-01T00:00:00Z"
  },
  "message": "Tu suscripción se cancelará el 01/11/2025. Seguirás teniendo acceso hasta esa fecha."
}
```

---

#### 10. GET /api/v1/licenses
**Descripción**: Listar license keys del usuario

**Response 200**:
```json
{
  "success": true,
  "data": [
    {
      "id": 101,
      "license_key": "WF-PREM-2025-ABCD-1234",
      "status": "active",
      "type": "subscription",
      "plan": "premium",
      "site_url": "https://misitio.com",
      "site_name": "Mi Sitio WordPress",
      "activated_at": "2025-10-01T14:30:00Z",
      "last_validated_at": "2025-10-23T08:00:00Z",
      "expires_at": "2025-11-01T23:59:59Z",
      "current_activations": 1,
      "max_activations": 1,
      "created_at": "2025-10-01T12:00:00Z"
    }
  ]
}
```

---

#### 11. POST /api/v1/licenses/{id}/deactivate
**Descripción**: Desactivar licencia de un sitio (liberar activación)

**Response 200**:
```json
{
  "success": true,
  "data": {
    "id": 101,
    "site_url": null,
    "current_activations": 0
  },
  "message": "Licencia desactivada. Ahora puedes activarla en otro sitio."
}
```

---

#### 12. GET /api/v1/payments
**Descripción**: Historial de pagos

**Query Params**:
- `page` (int): Número de página
- `limit` (int): Resultados por página (default: 20)

**Response 200**:
```json
{
  "success": true,
  "data": [
    {
      "id": 301,
      "amount": 29.00,
      "currency": "USD",
      "status": "completed",
      "payment_method": "card",
      "card_last4": "4242",
      "card_brand": "visa",
      "invoice_number": "INV-2025-000301",
      "invoice_pdf_url": "https://storage.workfluz.com/invoices/INV-2025-000301.pdf",
      "paid_at": "2025-10-01T12:05:32Z",
      "created_at": "2025-10-01T12:00:15Z"
    }
  ],
  "pagination": {
    "current_page": 1,
    "total_pages": 3,
    "total_items": 45,
    "per_page": 20
  }
}
```

---

#### 13. GET /api/v1/usage/{license_id}
**Descripción**: Estadísticas de uso de una licencia

**Query Params**:
- `from` (date): Fecha inicio (YYYY-MM-DD)
- `to` (date): Fecha fin (YYYY-MM-DD)

**Response 200**:
```json
{
  "success": true,
  "data": {
    "license_id": 101,
    "period": {
      "from": "2025-10-01",
      "to": "2025-10-23"
    },
    "totals": {
      "text_messages": 3420,
      "voice_minutes": 142.5,
      "total_end_users": 856,
      "total_conversations": 1234
    },
    "daily_stats": [
      {
        "date": "2025-10-23",
        "text_messages_count": 145,
        "voice_minutes": 6.2,
        "active_end_users": 42
      },
      {
        "date": "2025-10-22",
        "text_messages_count": 167,
        "voice_minutes": 8.1,
        "active_end_users": 51
      }
    ]
  }
}
```

---

### 📍 ENDPOINTS DE ADMINISTRADOR

**Headers**:
```
Authorization: Bearer {admin_token}
X-Admin-Role: super_admin
```

---

#### 14. GET /api/v1/admin/users
**Descripción**: Listar todos los usuarios

**Query Params**:
- `search` (string): Buscar por email o nombre
- `status` (string): active, suspended, deleted
- `page`, `limit`

**Response 200**:
```json
{
  "success": true,
  "data": [
    {
      "id": 123,
      "email": "juan@ejemplo.com",
      "first_name": "Juan",
      "last_name": "Pérez",
      "country": "CO",
      "status": "active",
      "total_subscriptions": 2,
      "total_payments": 450.00,
      "created_at": "2025-09-15T08:00:00Z"
    }
  ],
  "pagination": {...}
}
```

---

#### 15. POST /api/v1/admin/licenses/create-manual
**Descripción**: Crear license key manualmente (SIN PAGO)

**Request**:
```json
{
  "user_id": 123,
  "plan_id": 2,
  "type": "manual",
  "expires_at": "2026-12-31T23:59:59Z",
  "max_activations": 1,
  "admin_notes": "Licencia de cortesía para cliente VIP"
}
```

**Response 201**:
```json
{
  "success": true,
  "data": {
    "id": 999,
    "license_key": "WF-MANU-2025-VIPX-9876",
    "user_id": 123,
    "status": "active",
    "type": "manual",
    "expires_at": "2026-12-31T23:59:59Z",
    "max_activations": 1,
    "admin_notes": "Licencia de cortesía para cliente VIP",
    "created_at": "2025-10-23T10:30:00Z"
  },
  "message": "License key creada manualmente"
}
```

**Lógica**:
1. Validar que user_id existe
2. Validar que plan_id existe
3. Generar license_key con formato `WF-{TYPE}-{YEAR}-{RANDOM}-{RANDOM}`
4. Insertar en tabla licenses con type = "manual"
5. NO crear subscription ni payment
6. Enviar email al usuario con la license key
7. Registrar en audit_logs

---

#### 16. PUT /api/v1/admin/licenses/{id}/revoke
**Descripción**: Revocar license key

**Request**:
```json
{
  "reason": "Violación de términos de servicio"
}
```

**Response 200**:
```json
{
  "success": true,
  "data": {
    "id": 999,
    "status": "revoked",
    "revoked_at": "2025-10-23T11:00:00Z"
  },
  "message": "Licencia revocada"
}
```

---

#### 17. PUT /api/v1/admin/licenses/{id}/extend
**Descripción**: Extender fecha de expiración

**Request**:
```json
{
  "expires_at": "2027-12-31T23:59:59Z",
  "reason": "Extensión por solicitud del cliente"
}
```

**Response 200**:
```json
{
  "success": true,
  "data": {
    "id": 999,
    "expires_at": "2027-12-31T23:59:59Z"
  },
  "message": "Fecha de expiración actualizada"
}
```

---

#### 18. GET /api/v1/admin/dashboard/stats
**Descripción**: Estadísticas generales del negocio

**Response 200**:
```json
{
  "success": true,
  "data": {
    "users": {
      "total": 1245,
      "active": 1180,
      "new_this_month": 87
    },
    "subscriptions": {
      "total": 945,
      "active": 823,
      "trial": 45,
      "canceled": 77
    },
    "revenue": {
      "mrr": 23865.00,
      "arr": 286380.00,
      "this_month": 25420.00,
      "last_month": 24130.00,
      "growth_percentage": 5.35
    },
    "licenses": {
      "total": 1203,
      "active": 1089,
      "expired": 67,
      "revoked": 12
    },
    "plans_distribution": {
      "free": 300,
      "premium": 645,
      "pro": 200,
      "enterprise": 45
    }
  }
}
```

---

### 📍 WEBHOOKS (Recibidos de pasarelas)

#### 19. POST /api/v1/webhooks/wompi
**Descripción**: Recibir eventos de Wompi

**Headers**:
```
X-Wompi-Signature: sha256=abc123...
Content-Type: application/json
```

**Eventos soportados**:
- `transaction.updated` → Pago completado/fallido
- `subscription.created` → Suscripción creada
- `subscription.updated` → Cambio de estado
- `subscription.canceled` → Suscripción cancelada

**Lógica**:
1. Verificar firma del webhook
2. Registrar en tabla webhook_events
3. Procesar según event_type:
   - Si pago completado → actualizar subscription a "active", crear license
   - Si pago fallido → incrementar failed_payment_attempts
   - Si suscripción cancelada → actualizar status, calcular ends_at
4. Marcar webhook como "processed"
5. Enviar email de notificación al usuario

---

#### 20. POST /api/v1/webhooks/stripe
**Similar a Wompi**

#### 21. POST /api/v1/webhooks/mercadopago
**Similar a Wompi**

#### 22. POST /api/v1/webhooks/bold
**Similar a Wompi**

---

## 🎨 FRONTEND - PÁGINAS Y COMPONENTES

### Landing Page (/)
```
- Hero con CTA "Comienza Gratis"
- Comparación de planes (Free, Premium, Pro, Enterprise)
- Características destacadas
- Testimonios
- FAQ
- Footer con enlaces legales
```

### Página de Precios (/pricing)
```tsx
// Componente: PricingCard
<PricingCard
  plan="Premium"
  price={29}
  billingCycle="monthly"
  features={[
    "Mensajes ilimitados",
    "Voz ilimitada",
    "Sin branding",
    "Soporte prioritario"
  ]}
  cta="Comenzar ahora"
  onSelect={() => handleSelectPlan('premium', 'monthly')}
/>

// Switch: Mensual / Anual (con badge "Ahorra 2 meses")
// Comparación detallada de planes
// Monedas soportadas: USD, COP, MXN, ARS
```

### Login (/login)
```
- Formulario email + password
- "¿Olvidaste tu contraseña?"
- "Crear cuenta"
- Login con Google (opcional)
```

### Registro (/register)
```
- Formulario completo de registro
- Validación en tiempo real
- Términos y condiciones
- Envío de email de verificación
```

### Dashboard (/dashboard)
```tsx
// Sidebar:
- Inicio
- Mis Suscripciones
- Mis Licencias
- Pagos e Facturas
- Uso y Estadísticas
- Configuración
- Soporte

// Vista Inicio:
- Resumen de suscripción actual
- License keys activas
- Gráfico de uso (últimos 30 días)
- Últimos pagos
- Notificaciones
```

### Suscripciones (/dashboard/subscriptions)
```tsx
// Tarjeta de suscripción activa:
<SubscriptionCard
  plan="Premium"
  status="active"
  billingCycle="monthly"
  amount={29}
  nextBillingDate="2025-11-01"
  actions={[
    "Cambiar plan",
    "Actualizar método de pago",
    "Cancelar suscripción"
  ]}
/>

// Historial de suscripciones
// Botón "Crear nueva suscripción"
```

### Licencias (/dashboard/licenses)
```tsx
// Lista de license keys:
<LicenseKeyCard
  licenseKey="WF-PREM-2025-ABCD-1234"
  status="active"
  plan="Premium"
  siteUrl="https://misitio.com"
  expiresAt="2025-11-01"
  activations="1/1"
  actions={[
    "Copiar key",
    "Ver detalles",
    "Desactivar sitio",
    "Renovar"
  ]}
/>

// Botón "Activar nueva licencia" (si tiene suscripción activa)
// Instrucciones de activación en WordPress
```

### Pagos (/dashboard/payments)
```tsx
// Tabla de pagos:
| Fecha | Monto | Estado | Método | Factura |
|-------|-------|--------|--------|---------|
| 01/10/25 | $29.00 | Completado | Visa •••• 4242 | [PDF] |

// Filtros: Fecha, Estado, Método
// Descarga masiva de facturas
```

### Uso y Estadísticas (/dashboard/usage)
```tsx
// Selector de licencia (si tiene múltiples)
// Rango de fechas

// Métricas principales:
- Mensajes de texto enviados
- Minutos de voz usados
- Total de usuarios finales
- Conversaciones totales

// Gráficos:
- Línea temporal de uso
- Distribución texto vs voz
- Horas pico de uso
- Top días con más actividad
```

### Configuración (/dashboard/settings)
```tsx
// Tabs:
- Perfil (nombre, email, teléfono, empresa)
- Seguridad (cambiar contraseña, 2FA)
- Facturación (dirección fiscal, método de pago)
- Notificaciones (email, SMS)
```

### Admin Panel (/admin)
```tsx
// Solo accesible para admin_users

// Sidebar:
- Dashboard
- Usuarios
- Suscripciones
- Licencias
- Pagos
- Webhooks
- Configuración

// Dashboard Admin:
- KPIs principales (MRR, ARR, usuarios activos)
- Gráficos de crecimiento
- Últimas transacciones
- Alertas (pagos fallidos, licencias por expirar)

// Gestión de Usuarios:
- Tabla con búsqueda y filtros
- Acciones: Ver detalles, Suspender, Eliminar
- Crear usuario manualmente

// Gestión de Licencias:
- Tabla de todas las licencias
- Búsqueda por license_key, user, site_url
- Acciones: Revocar, Extender, Ver uso
- **Botón destacado: "Crear Licencia Manual"**

// Crear Licencia Manual (Modal):
<CreateManualLicenseForm
  fields={[
    "Usuario (selector o crear nuevo)",
    "Plan (Free, Premium, Pro, Enterprise)",
    "Tipo (manual, lifetime)",
    "Fecha de expiración",
    "Máximo de activaciones",
    "Notas del admin"
  ]}
  onSubmit={(data) => createManualLicense(data)}
/>

// Gestión de Pagos:
- Tabla de todos los pagos
- Filtros por estado, pasarela, fecha
- Acción: Reembolsar pago

// Webhooks:
- Log de todos los webhooks recibidos
- Filtros por pasarela, estado
- Ver payload completo
- Reprocesar webhook fallido
```

---

## 💳 INTEGRACIÓN DE PASARELAS DE PAGO

### 1. Wompi (Colombia)

**Documentación**: https://docs.wompi.co

**Flujo de suscripción**:
```javascript
// 1. Crear sesión de checkout
const checkout = await wompi.createCheckout({
  amount_in_cents: 29000, // $290 COP
  currency: "COP",
  customer_email: "juan@ejemplo.com",
  reference: "SUB-456",
  redirect_url: "https://app.workfluz.com/dashboard/subscription/success",
  payment_methods: ["CARD", "PSE", "NEQUI", "BANCOLOMBIA"]
});

// 2. Redirigir a checkout_url
window.location.href = checkout.permalink;

// 3. Usuario paga

// 4. Wompi envía webhook a /api/v1/webhooks/wompi
{
  "event": "transaction.updated",
  "data": {
    "transaction": {
      "id": "12345-67890",
      "status": "APPROVED",
      "reference": "SUB-456",
      "amount_in_cents": 29000,
      "customer_email": "juan@ejemplo.com"
    }
  },
  "signature": "..."
}

// 5. Backend procesa webhook:
- Verificar firma
- Buscar subscription por reference
- Actualizar status a "active"
- Generar license_key
- Enviar email con license_key
```

**Métodos de pago soportados**:
- Tarjetas (Visa, Mastercard, Amex)
- PSE (débito bancario)
- Nequi
- Bancolombia
- Efecty
- Baloto

---

### 2. Stripe (Internacional)

**Documentación**: https://stripe.com/docs

**Flujo de suscripción recurrente**:
```javascript
// 1. Crear Stripe Checkout Session
const session = await stripe.checkout.sessions.create({
  mode: 'subscription',
  customer_email: 'juan@ejemplo.com',
  line_items: [{
    price: 'price_premium_monthly', // ID del precio en Stripe
    quantity: 1,
  }],
  success_url: 'https://app.workfluz.com/dashboard/subscription/success',
  cancel_url: 'https://app.workfluz.com/pricing',
  metadata: {
    user_id: '123',
    plan_id: '2'
  }
});

// 2. Redirigir
window.location.href = session.url;

// 3. Webhooks de Stripe:
- checkout.session.completed → Crear subscription
- invoice.paid → Renovación exitosa
- invoice.payment_failed → Pago fallido
- customer.subscription.deleted → Cancelación
```

---

### 3. MercadoPago (LATAM)

**Similar a Wompi**, adaptado para México, Argentina, Chile, etc.

---

### 4. Bold (Colombia/LATAM)

**Similar a Wompi**, con soporte para múltiples países.

---

## 📧 EMAILS TRANSACCIONALES

Usar servicio de email: **SendGrid**, **Postmark**, o **Amazon SES**

### Templates necesarios:

1. **Bienvenida** (al registrarse)
   - Subject: "¡Bienvenido a Workfluz! 🎉"
   - Contenido: Verificar email, próximos pasos

2. **Verificación de email**
   - Subject: "Verifica tu email"
   - Contenido: Link de verificación

3. **License Key creada** (después de pago o creación manual)
   - Subject: "Tu License Key de Workfluz está lista 🔑"
   - Contenido: 
     ```
     Tu License Key: WF-PREM-2025-ABCD-1234
     
     Cómo activarla en WordPress:
     1. Ve a AI Widget → Freemium
     2. Pega tu License Key
     3. Click en "Activar License Key"
     
     Tu plan: Premium
     Expira: 01/11/2025
     ```

4. **Pago exitoso**
   - Subject: "Pago recibido - Factura #INV-2025-000301"
   - Contenido: Resumen del pago, adjuntar PDF

5. **Pago fallido**
   - Subject: "⚠️ Problema con tu pago"
   - Contenido: Actualizar método de pago, link al dashboard

6. **Suscripción por expirar** (7 días antes)
   - Subject: "Tu suscripción expira en 7 días"
   - Contenido: Renovar ahora, beneficios del plan

7. **Suscripción expirada**
   - Subject: "Tu suscripción ha expirado"
   - Contenido: Reactivar, cambios en funcionalidad

8. **Licencia revocada** (por admin)
   - Subject: "Tu licencia ha sido revocada"
   - Contenido: Razón, contactar soporte

9. **Recuperación de contraseña**
   - Subject: "Recupera tu contraseña"
   - Contenido: Link temporal (expira en 1 hora)

---

## 🔐 SEGURIDAD

### Autenticación
- **JWT** con expiración de 24 horas
- **Refresh tokens** para renovar sesión
- **2FA opcional** con TOTP (Google Authenticator)
- **Rate limiting**: 5 intentos de login por IP/hora

### Validación de Webhooks
```php
// Wompi
$signature = $_SERVER['HTTP_X_WOMPI_SIGNATURE'];
$payload = file_get_contents('php://input');
$calculated = hash_hmac('sha256', $payload, WOMPI_SECRET);

if (!hash_equals($signature, $calculated)) {
    http_response_code(401);
    die('Invalid signature');
}

// Stripe
$stripe_signature = $_SERVER['HTTP_STRIPE_SIGNATURE'];
$event = \Stripe\Webhook::constructEvent(
    $payload, $stripe_signature, STRIPE_WEBHOOK_SECRET
);
```

### Encriptación
- **Datos sensibles** en DB: AES-256
- **License keys**: Puede ser plain text (ya es un token público)
- **Passwords**: bcrypt con salt

### Prevención de Fraude
- **Máximo de activaciones** por license
- **Detección de cambios frecuentes de sitio_url**
- **Bloqueo automático** si >5 validaciones fallidas en 1 hora
- **Alerts** para admins: pagos sospechosos, cambios de país en cuenta

---

## 📊 MÉTRICAS Y MONITOREO

### KPIs principales
- **MRR** (Monthly Recurring Revenue)
- **ARR** (Annual Recurring Revenue)
- **Churn Rate** (% de cancelaciones)
- **LTV** (Lifetime Value por usuario)
- **CAC** (Costo de Adquisición)
- **Active Licenses**
- **Conversión Free → Premium**

### Herramientas
- **Analytics**: Google Analytics 4, Mixpanel
- **Monitoreo**: Sentry (errores), Datadog (performance)
- **Uptime**: Pingdom, UptimeRobot

---

## 🚀 DEPLOYMENT

### Backend (Laravel)
```bash
# Railway / DigitalOcean / AWS
php artisan migrate
php artisan db:seed
php artisan queue:work --daemon
php artisan schedule:run (cron)
```

### Frontend (Next.js)
```bash
# Vercel
vercel --prod
```

### Variables de entorno (.env)
```bash
# App
APP_URL=https://app.workfluz.com
APP_ENV=production

# Database
DB_HOST=postgres.railway.app
DB_DATABASE=workfluz
DB_USERNAME=postgres
DB_PASSWORD=***

# Redis
REDIS_HOST=redis.railway.app
REDIS_PASSWORD=***

# JWT
JWT_SECRET=***
JWT_EXPIRATION=86400

# Payment Gateways
WOMPI_PUBLIC_KEY=pub_test_***
WOMPI_PRIVATE_KEY=prv_test_***
WOMPI_WEBHOOK_SECRET=***

STRIPE_PUBLIC_KEY=pk_live_***
STRIPE_SECRET_KEY=sk_live_***
STRIPE_WEBHOOK_SECRET=whsec_***

MERCADOPAGO_ACCESS_TOKEN=***
BOLD_API_KEY=***

# Email
MAIL_MAILER=sendgrid
SENDGRID_API_KEY=SG.***

# Storage
AWS_S3_BUCKET=workfluz-invoices
AWS_ACCESS_KEY_ID=***
AWS_SECRET_ACCESS_KEY=***
```

---

## 📝 TAREAS ADICIONALES

### 1. Facturación Electrónica (Colombia - DIAN)
- Integrar con proveedor de facturación (ej: Facturador, Alegra, Siigo)
- Generar PDFs con formato legal
- Enviar a DIAN
- Almacenar XMLs firmados

### 2. Soporte al Cliente
- **Intercom** o **Crisp** para chat en vivo
- Sistema de tickets (Zendesk, Freshdesk)
- Base de conocimientos (FAQ, tutoriales)

### 3. Afiliados (Opcional)
- Programa de referidos
- 20% de comisión recurrente
- Dashboard de afiliados
- Códigos de descuento

### 4. Multi-idioma
- Español (primario)
- Inglés
- Portugués (Brasil)

### 5. Multi-moneda
- USD (Internacional)
- COP (Colombia)
- MXN (México)
- ARS (Argentina)
- Conversión automática según country del usuario

---

## ✅ CHECKLIST DE IMPLEMENTACIÓN

### ⭐ OPCIÓN SELECCIONADA: Odoo Custom Plan (Odoo.sh)

### Fase 1: Setup Inicial (Día 1)
- [ ] Crear cuenta en Odoo.sh
- [ ] Seleccionar plan Custom ($13.60/user/mes)
- [ ] Crear proyecto: workfluz-license-manager
- [ ] Configurar 3 usuarios iniciales
- [ ] Acceder a instancia y cambiar contraseña admin

### Fase 2: Instalación Apps Base (Día 1)
- [ ] Apps → Actualizar lista
- [ ] Instalar Sales
- [ ] Instalar Subscriptions (Enterprise)
- [ ] Instalar Invoicing
- [ ] Instalar Portal
- [ ] Instalar Studio
- [ ] Instalar Payment Providers (Stripe, PayPal)
- [ ] Instalar Website Builder

### Fase 3: Desarrollo Módulo Custom (Semana 1)
- [ ] Clonar repositorio Git de Odoo.sh
- [ ] Crear estructura `workfluz_license_manager`
- [ ] Implementar modelos: Application, LicenseKey, UsageStats
- [ ] Crear controlador API `/api/v1/licenses/validate`
- [ ] Configurar vistas XML (admin + portal)
- [ ] Implementar emails transaccionales
- [ ] Push a Git → Deploy automático
- [ ] Instalar módulo en Odoo

### Fase 4: Personalización con Studio (Semana 1)
- [ ] Studio → Personalizar modelo Application
- [ ] Agregar campo JSON visual para configuración
- [ ] Crear dashboard de licencias con widgets
- [ ] Personalizar portal de clientes (drag & drop)
- [ ] Crear vistas kanban para planes
- [ ] Configurar reportes de MRR/ARR

### Fase 5: Integración Pasarelas (Semana 2)
- [ ] Configurar Stripe Enterprise
- [ ] Obtener API keys (producción)
- [ ] Configurar webhook en Stripe dashboard
- [ ] Implementar hook para auto-crear licencias
- [ ] Desarrollar módulo Wompi custom (opcional)
- [ ] Configurar MercadoPago (opcional)
- [ ] Testing de pagos en staging

### Fase 6: Configuración Planes (Semana 2)
- [ ] Crear 4 planes: Free, Premium, Pro, Enterprise
- [ ] Definir precios mensuales/anuales
- [ ] Configurar productos asociados
- [ ] Crear aplicación: AI Voice Text Widget
- [ ] Definir límites por plan en JSON config
- [ ] Publicar planes en Website

### Fase 7: Testing Completo (Semana 2)
- [ ] Testing: Registro de usuario
- [ ] Testing: Compra de suscripción con Stripe
- [ ] Testing: Generación automática de license key
- [ ] Testing: Validación API desde WordPress
- [ ] Testing: Creación manual de licencias
- [ ] Testing: Portal de clientes
- [ ] Testing: Webhooks de renovación
- [ ] Testing: Emails transaccionales

### Fase 8: Deploy a Producción (Día 1)
- [ ] Merge staging → main (deploy automático)
- [ ] Configurar dominio custom (opcional)
- [ ] Configurar DNS
- [ ] Verificar SSL activo
- [ ] Testing final en producción
- [ ] Documentación de uso para clientes

### Fase 9: Optimización (Opcional)
- [ ] Personalizar branding (logo, colores)
- [ ] A/B testing de precios
- [ ] Integración con soporte (Helpdesk)
- [ ] Programa de afiliados
- [ ] Multi-idioma (ES, EN, PT)
- [ ] Analytics avanzado con Google Analytics

---

## 🎬 CONCLUSIÓN

### 🎯 Decisión Final: **Odoo Custom Plan (Odoo.sh)**

**¿Por qué esta opción?**
1. ✅ **Hosting incluido en $13.60/user** - No necesitas EasyPanel ni Docker
2. ✅ **100% Enterprise listo** - Todas las apps, Studio, API completa
3. ✅ **CI/CD automático** - Push a Git y deploy sin configuración
4. ✅ **Solo 1-2 semanas de desarrollo** - Studio acelera todo
5. ✅ **Backups automáticos** - Diarios, semanales, mensuales incluidos
6. ✅ **Soporte oficial Odoo** - Tickets prioritarios
7. ✅ **Escalable** - De un plugin a múltiples productos sin refactoring
8. ✅ **SSL gratis** - Let's Encrypt incluido
9. ✅ **Multi-company** - Si decides gestionar varios negocios

### 📦 Lo que obtienes con Odoo Custom Plan:

✅ **Sistema de suscripciones recurrentes** (Enterprise - incluido)  
✅ **Facturación automática con PDFs** (incluido)  
✅ **Portal de clientes self-service** (incluido)  
✅ **Odoo Studio** - Personalización drag & drop (incluido)  
✅ **Multi-moneda y multi-idioma** (incluido)  
✅ **Dashboard con KPIs enterprise** (MRR, ARR, churn, forecasting) (incluido)  
✅ **Sistema de permisos avanzado** (usuarios, roles, ACL) (incluido)  
✅ **API REST OAuth2** para validación de licencias (**desarrollar**)  
✅ **Gestión de license keys** multi-app (**desarrollar**)  
✅ **Creación manual de licencias** (**desarrollar**)  
✅ **Estadísticas de uso** por licencia (**desarrollar**)  
✅ **Hosting, SSL, Backups** (incluido en $13.60/user/mes)  

### 🔨 Lo que debes desarrollar:

```
Módulo Custom (1-2 semanas con Studio):
├── 3 modelos Python (~400 líneas con Studio helpers)
├── 1 controlador API REST (~150 líneas)
├── 3-4 vistas XML simples (~200 líneas, Studio genera 80%)
├── 1 portal personalizado (~50 líneas, Studio drag & drop)
├── Webhooks para auto-generar keys (~100 líneas)
└── Templates de email (Studio visual editor)

Total: ~900 líneas vs 1,350 Community vs 15,000+ desde cero
```

### 💰 Comparación de Costos REAL (36 meses):

| Concepto | Odoo Custom | Community | FastAPI Custom |
|----------|-------------|-----------|----------------|
| **Desarrollo** | $3,000-$5,000 | $5,000-$8,000 | $15,000-$25,000 |
| **Hosting (3 años)** | Incluido | $720-$1,800 | $3,600-$10,800 |
| **Suscripción (3 años)** | $1,469 | $0 | $0 |
| **SSL (3 años)** | Incluido | $0 (Let's Encrypt) | $0 |
| **Backups** | Incluido | Manual | $360-$1,080 |
| **Soporte** | Incluido | $0 | $2,000-$5,000 |
| **Actualizaciones** | Automáticas | Manual | Manual |
| **Total 3 años** | **$4,469-$6,469** | **$5,720-$9,800** | **$20,960-$41,880** |

**ROI**: Odoo Custom es más barato a largo plazo si consideras hosting + soporte + actualizaciones

### 🚀 Próximos Pasos:

1. **Crear cuenta Odoo.sh**: https://www.odoo.sh/ (plan Custom, 3 usuarios = $40.80/mes)
2. **Crear proyecto**: workfluz-license-manager, Odoo 17, región US East
3. **Instalar apps base**: Subscriptions, Portal, Studio, Payment Providers
4. **Desarrollar modelos**: Application, LicenseKey, UsageStats (1 semana)
5. **Crear API endpoint**: `/api/v1/licenses/validate` (2 días)
6. **Integrar Stripe**: Configurar webhooks, auto-generar licencias (2 días)
7. **Personalizar con Studio**: Dashboard, portal, vistas (3 días)
8. **Probar desde WordPress**: Validar integración con plugin (1 día)
9. **Deploy a producción**: Merge a main branch (automático)

### 📚 Recursos Útiles:

- **Odoo.sh Docs**: https://www.odoo.sh/documentation/user/en/16.0/
- **Odoo Studio Docs**: https://www.odoo.com/documentation/17.0/applications/studio.html
- **API REST Odoo**: https://www.odoo.com/documentation/17.0/developer/reference/external_api.html
- **Stripe + Odoo**: https://www.odoo.com/documentation/17.0/applications/finance/payment_providers/stripe.html
- **Módulos Enterprise**: https://github.com/odoo/enterprise/tree/17.0
- **Soporte Odoo**: https://www.odoo.com/help

---

**Tiempo estimado**: 1-2 semanas  
**Costo desarrollo**: $3,000 - $5,000 USD  
**Costo mensual**: $40.80/mes (3 usuarios) - Todo incluido  
**Total primer año**: $3,490 - $5,490 USD (desarrollo + 12 meses hosting)  

**Stack final**: Odoo 17 Enterprise (Custom Plan) + Odoo.sh + Studio + PostgreSQL + Redis

---

## 🎁 BONUS: Snippet de Inicio Rápido

### Crear el módulo base en 5 minutos:

```bash
# 1. Crear estructura
mkdir -p workfluz_license_manager/{models,controllers,views,security,data,static/src/js}
cd workfluz_license_manager

# 2. __manifest__.py
cat > __manifest__.py << 'EOF'
{
    'name': 'Workfluz License Manager',
    'version': '17.0.1.0.0',
    'category': 'Sales',
    'summary': 'Gestión de licencias multi-aplicación',
    'description': """
        Sistema de gestión de license keys para múltiples aplicaciones.
        - Soporte multi-aplicación
        - Validación vía API REST
        - Creación automática y manual de licencias
        - Portal de clientes
        - Estadísticas de uso
    """,
    'author': 'Workfluz',
    'website': 'https://workfluz.com',
    'license': 'LGPL-3',
    'depends': ['base', 'sale_subscription', 'portal', 'website'],
    'data': [
        'security/ir.model.access.csv',
        'views/application_views.xml',
        'views/license_key_views.xml',
        'views/menu_views.xml',
        'views/portal_templates.xml',
        'data/cron_jobs.xml',
        'data/email_templates.xml',
    ],
    'installable': True,
    'application': True,
    'auto_install': False,
}
EOF

# 3. __init__.py
echo "from . import models, controllers" > __init__.py

# 4. Ahora puedes copiar los archivos Python del prompt
# models/application.py, models/license_key.py, etc.
```

### Probar API desde WordPress:

```php
// En tu plugin de WordPress:
$response = wp_remote_post('https://tu-odoo.com/api/v1/licenses/validate', [
    'body' => [
        'license_key' => 'WF-SUBS-2025-ABCD-1234',
        'site_url' => home_url(),
        'plugin_version' => '1.0.0',
        'wordpress_version' => get_bloginfo('version'),
        'php_version' => phpversion()
    ]
]);

$result = json_decode(wp_remote_retrieve_body($response), true);

if ($result['valid']) {
    // Licencia válida
    update_option('ai_widget_plan', $result['plan']);
    update_option('ai_widget_features', $result['features']);
} else {
    // Licencia inválida
    error_log('License error: ' . $result['message']);
}
```

---

**¿Listo para comenzar?** 🚀

Con Odoo Community ya instalado, puedes tener el sistema funcionando en 2-3 semanas vs 3-4 meses con desarrollo desde cero.
