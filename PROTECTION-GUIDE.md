# PROTECCIÓN LEGAL DEL PLUGIN AI WIDGET - GUÍA PARA EL DESARROLLADOR

## ✅ Protecciones Implementadas

### 1. Licencia Propietaria
- ✅ Archivo `LICENSE.txt` con términos legales completos
- ✅ Archivo `README-LICENSE.md` con información clara para usuarios
- ✅ Headers de copyright en archivo principal del plugin
- ✅ Headers de copyright en archivos PHP críticos
- ✅ Headers de copyright en archivos JavaScript

### 2. Protección de Archivos
- ✅ Archivo `.htaccess` para bloquear acceso directo a PHP
- ✅ Prevención de listado de directorios
- ✅ Bloqueo de archivos sensibles (.json, .bak, etc.)
- ✅ Cabeceras HTTP de copyright

### 3. Avisos Legales en Código
- ✅ Avisos de copyright en todos los archivos principales
- ✅ Advertencias sobre uso no autorizado
- ✅ Información de contacto para licencias

---

## 🔒 PROTECCIONES ADICIONALES RECOMENDADAS

### A. Antes de Entregar el Plugin a un Cliente

#### 1. **Ofuscación de Código JavaScript**
```bash
# Usar herramientas como:
npm install -g javascript-obfuscator
javascript-obfuscator widget-vapi.js --output widget-vapi.min.js
```

#### 2. **Encriptación de Código PHP** (Opcional pero recomendado)
- **ionCube Encoder**: https://www.ioncube.com/
- **Zend Guard**: https://www.zend.com/products/zend-guard
- **SourceGuardian**: https://www.sourceguardian.com/

#### 3. **Firma Digital del Plugin**
```bash
# Crear un hash SHA256 del plugin completo
cd wp-content/plugins/ai-voice-text-widget
find . -type f -exec sha256sum {} \; > CHECKSUMS.txt
```

### B. Sistema de Verificación de Licencias (Implementar)

#### 1. **Crear un Servidor de Licencias Workfluz**
```php
// Agregar a class-freemium.php
public static function verify_license_remote() {
    $license_key = get_option('ai_widget_license_key');
    $domain = parse_url(home_url(), PHP_URL_HOST);
    
    $response = wp_remote_post('https://api.workfluz.com/v1/verify-license', array(
        'body' => array(
            'license_key' => $license_key,
            'domain' => $domain,
            'plugin_version' => AI_VOICE_TEXT_WIDGET_VERSION
        )
    ));
    
    if (is_wp_error($response)) {
        return false;
    }
    
    $data = json_decode(wp_remote_retrieve_body($response), true);
    return $data['valid'] ?? false;
}
```

#### 2. **Verificación Diaria Automática**
```php
// Ya está implementado en cron-jobs.php pero asegúrate de tener el endpoint
add_action('ai_widget_revalidate_license', array('AI_Widget_Freemium', 'verify_license_remote'));
```

### C. Marcas de Agua y Telemetría

#### 1. **Agregar Marca de Agua en Plan Gratuito**
```javascript
// En widget-vapi.js - ya implementado con showBranding
if (aiWidgetData.showBranding) {
    // Mostrar "Powered by Workfluz"
}
```

#### 2. **Telemetría de Uso** (Opcional)
```php
// Enviar estadísticas anónimas a Workfluz
function send_usage_stats() {
    wp_remote_post('https://api.workfluz.com/v1/stats', array(
        'body' => array(
            'site_id' => get_option('ai_widget_site_id'),
            'version' => AI_VOICE_TEXT_WIDGET_VERSION,
            'active_plan' => AI_Widget_Freemium::get_current_plan()
        )
    ));
}
```

---

## 📋 CHECKLIST ANTES DE ENTREGAR EL PLUGIN

### Entrega a Cliente con Licencia Válida
- [ ] Verificar que el cliente tiene licencia comprada
- [ ] Documentar el dominio autorizado
- [ ] Activar la license key en el servidor de Workfluz
- [ ] Proporcionar copia de LICENSE.txt al cliente
- [ ] Firmar contrato de licencia con el cliente

### Entrega a Cliente de Prueba (Demo/Staging)
- [ ] Activar marca de agua "DEMO - Not Licensed"
- [ ] Limitar funcionalidad (ej: máximo 50 mensajes)
- [ ] Establecer fecha de expiración (ej: 30 días)
- [ ] Incluir aviso de licencia requerida

### Protección del Código
- [ ] Ofuscar JavaScript en producción
- [ ] Considerar encriptar PHP con ionCube (para versión premium)
- [ ] Generar CHECKSUMS.txt
- [ ] Firmar digitalmente el plugin

---

## ⚖️ ACCIONES LEGALES EN CASO DE USO NO AUTORIZADO

### 1. **Detección**
Si detectas que alguien usa tu plugin sin licencia:

### 2. **Notificación Formal**
Envía un email desde legal@workfluz.com:

```
Asunto: Uso No Autorizado de Software Propietario - AI Widget by Workfluz

Estimados Señores,

Hemos detectado que el software "AI Widget by Workfluz" está siendo 
utilizado en su sitio web [DOMINIO] sin una licencia válida.

Este software es propiedad exclusiva de Workfluz y está protegido por 
derechos de autor bajo las leyes de España y tratados internacionales.

REQUERIMIENTO:
1. Cese inmediato del uso del software
2. Eliminación completa del código de su sitio web
3. Destrucción de todas las copias
4. Adquisición de licencia válida si desea continuar usándolo

Plazo: 7 días naturales desde la recepción de esta notificación.

De no cumplir, iniciaremos acciones legales correspondientes.

Atentamente,
Departamento Legal - Workfluz
legal@workfluz.com
```

### 3. **Acciones Legales**
Si no responden:
- Contactar con abogado especialista en propiedad intelectual
- Presentar denuncia ante las autoridades competentes
- Solicitar medidas cautelares
- Reclamar daños y perjuicios

---

## 📞 RECURSOS Y CONTACTOS

### Registro de Propiedad Intelectual (España)
- Web: https://www.culturaydeporte.gob.es/cultura/propiedadintelectual.html
- Registro: Puede registrar el código como obra literaria

### Asociaciones Profesionales
- APDIF: Asociación para la Promoción y Defensa de la Propiedad Intelectual
- BSA: Business Software Alliance

### Abogados Especializados
- Buscar "abogado propiedad intelectual software España"
- Bufetes especializados en derecho tecnológico

---

## 💡 RECOMENDACIONES FINALES

### 1. **Siempre Usa Contratos**
Antes de entregar el plugin, firma un contrato que incluya:
- Límites de uso del software
- Prohibición de copia/redistribución
- Cláusulas de confidencialidad
- Penalizaciones por incumplimiento

### 2. **Documenta Todo**
- Guarda emails con clientes
- Registra entregas de software
- Mantén logs de instalaciones

### 3. **Sistema de Licencias Online**
Considera crear un sistema en Workfluz donde:
- Clientes registren sus dominios
- Verifiques licencias automáticamente
- Puedas revocar licencias remotamente

### 4. **Versiones Diferentes**
Mantén versiones distintas:
- **Desarrollo**: Sin protecciones, para tu uso
- **Demo**: Con limitaciones y marcas de agua
- **Cliente**: Con ofuscación y verificación de licencia
- **Premium**: Totalmente encriptada con ionCube

---

## ✅ ESTADO ACTUAL DE PROTECCIÓN

### Protecciones Básicas (Implementadas)
- ✅ Licencia propietaria clara
- ✅ Copyright notices en todo el código
- ✅ .htaccess de protección
- ✅ Documentación legal

### Protecciones Intermedias (Recomendadas)
- ⚠️ Ofuscación de JavaScript
- ⚠️ Sistema de verificación de licencias remoto
- ⚠️ Firma digital del plugin

### Protecciones Avanzadas (Opcional)
- ❌ Encriptación PHP con ionCube
- ❌ Sistema de telemetría
- ❌ Desactivación remota

---

**IMPORTANTE**: La mejor protección es la legal. Asegúrate de:
1. Tener contratos firmados con tus clientes
2. Registrar tu software ante la propiedad intelectual
3. Actuar rápidamente ante usos no autorizados

---

© 2024-2025 Workfluz. Documento confidencial.
