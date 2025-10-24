# WordPress.org Submission Checklist

Use esta lista antes de enviar tu plugin a WordPress.org.

## ✅ Archivos Requeridos

- [x] `readme.txt` - Formato WordPress.org estándar
- [x] `LICENSE.txt` - GPL-2.0 completo
- [x] `ai-voice-text-widget.php` - Archivo principal con headers correctos
- [ ] `assets/` - Carpeta con screenshots y banners
  - [ ] `screenshot-1.png` (1280x720 o similar)
  - [ ] `screenshot-2.png`
  - [ ] `screenshot-3.png`
  - [ ] `banner-772x250.png`
  - [ ] `banner-1544x500.png` (opcional, alta resolución)
  - [ ] `icon-128x128.png`
  - [ ] `icon-256x256.png`

## ✅ Información de Contacto

- [x] WordPress.org username: `josueayala` (debe existir y estar verificado)
- [x] Email de soporte: support@workfluz.com
- [x] WhatsApp: +57 333 430 8871
- [x] Ubicación: Medellín, Colombia

## ✅ Código & Licencia

- [x] Todo el código es GPL-2.0-or-later
- [x] No hay código ofuscado o encriptado
- [x] Headers del plugin correctos
- [x] Version numbers consistentes (1.0.0)
- [x] Text domain correcto: `ai-voice-text-widget`
- [x] No hay llamadas a servicios externos de licencias
- [x] Validación de licencia es local

## ✅ Divulgación de Servicios Externos

- [x] OpenAI API documentado en readme.txt
- [x] VAPI SDK documentado en readme.txt
- [x] ElevenLabs API documentado en readme.txt
- [x] Links a Terms of Service de cada servicio
- [x] Links a Privacy Policy de cada servicio
- [x] Explicación clara de qué datos se envían
- [x] Sección "Privacy Policy" en readme.txt

## ✅ Seguridad

- [x] WordPress nonces en formularios AJAX
- [x] Capability checks (`current_user_can()`)
- [x] Data sanitization (`sanitize_text_field()`, etc.)
- [x] Prepared statements en queries SQL
- [x] No hay `eval()` o código peligroso
- [x] No hay SQL injection vulnerabilities
- [x] No hay XSS vulnerabilities

## ✅ Freemium Compliance

- [x] Plugin funciona completamente en modo gratuito
- [x] Límites claros: 100 mensajes, 30 minutos voz/mes
- [x] No hay "nag screens" molestos
- [x] Premium solo quita límites y branding
- [x] No hay forced upgrades

## ✅ Documentación

- [x] readme.txt completo con:
  - [x] Descripción detallada
  - [x] Installation instructions
  - [x] FAQ section
  - [x] Screenshots descriptions
  - [x] Changelog
  - [x] Upgrade Notice
  - [x] External Services disclosure
- [x] README.md para GitHub
- [x] CONTRIBUTORS.md con información del equipo
- [x] Comentarios en código (PHPDoc)

## ✅ Testing

- [ ] Probado en WordPress 6.0+
- [ ] Probado en PHP 7.4, 8.0, 8.1, 8.2
- [ ] Probado en diferentes themes
- [ ] Probado en multisite
- [ ] No hay PHP errors/warnings
- [ ] No hay JavaScript console errors
- [ ] Widget funciona en mobile
- [ ] Funciona con caching plugins

## ✅ Database

- [x] Usa `dbDelta()` para crear tablas
- [x] Tables tienen prefijo `$wpdb->prefix`
- [x] Charset correcto en CREATE TABLE
- [x] Cleanup en desactivación (opcional)

## ✅ Assets para WordPress.org

**IMPORTANTE**: Los assets van en un directorio SVN separado, NO en el plugin.

Crear carpeta `assets/` con:

### Screenshots (REQUERIDO)
- `screenshot-1.png` - Widget flotante en el sitio
- `screenshot-2.png` - Interfaz de chat
- `screenshot-3.png` - Modo de voz activo
- `screenshot-4.png` - Panel de admin (General Settings)
- `screenshot-5.png` - Panel de Freemium
- `screenshot-6.png` - Página de Apariencia
- `screenshot-7.png` - Analytics/Estadísticas

**Tamaño recomendado**: 1280x720 o similar ratio

### Banner (RECOMENDADO)
- `banner-772x250.png` - Banner normal
- `banner-1544x500.png` - Banner retina (2x)

### Icon (RECOMENDADO)
- `icon-128x128.png` - Icono normal
- `icon-256x256.png` - Icono retina (2x)

**Colores sugeridos**: Usar los colores de Workfluz (#76b4e3 → #009bf0)

## ✅ Pasos para Subir a WordPress.org

### 1. Crear cuenta en WordPress.org
- [ ] Ir a https://login.wordpress.org/register
- [ ] Registrar username: `josueayala`
- [ ] Verificar email

### 2. Enviar Plugin para Revisión
- [ ] Ir a https://wordpress.org/plugins/developers/add/
- [ ] Subir ZIP del plugin (sin carpeta `assets/`)
- [ ] Esperar email de aprobación (puede tardar 2-14 días)

### 3. Una vez Aprobado
- [ ] Recibirás acceso SVN al repositorio
- [ ] El repositorio será: `https://plugins.svn.wordpress.org/ai-voice-text-widget/`

### 4. Configurar SVN
```bash
# Checkout del repositorio
svn co https://plugins.svn.wordpress.org/ai-voice-text-widget/ ai-voice-text-widget-svn
cd ai-voice-text-widget-svn

# Estructura:
# /trunk/        - Versión en desarrollo
# /tags/         - Versiones publicadas (1.0.0, 1.0.1, etc.)
# /assets/       - Screenshots, banners, icons
# /branches/     - Ramas experimentales (opcional)
```

### 5. Subir Archivos
```bash
# Copiar archivos del plugin a /trunk/
cp -r /ruta/al/plugin/* trunk/

# Copiar assets a /assets/
cp screenshot-*.png assets/
cp banner-*.png assets/
cp icon-*.png assets/

# Agregar archivos
svn add trunk/*
svn add assets/*

# Commit
svn ci -m "Initial commit of AI Widget by Workfluz v1.0.0"
```

### 6. Crear Tag de Versión
```bash
# Copiar trunk a tags/1.0.0
svn cp trunk tags/1.0.0

# Commit
svn ci -m "Tagging version 1.0.0"
```

### 7. Verificar en WordPress.org
- [ ] Ir a https://wordpress.org/plugins/ai-voice-text-widget/
- [ ] Verificar que aparezca correctamente
- [ ] Probar instalación desde WordPress

## 📋 Antes de Enviar - Verificación Final

1. **Crear ZIP del plugin**:
```bash
cd wp-content/plugins
zip -r ai-voice-text-widget.zip ai-voice-text-widget/ \
  -x "*.git*" \
  -x "*node_modules*" \
  -x "*.DS_Store" \
  -x "*diagnose-stats.php" \
  -x "*.md" \
  -x "PLUGIN_AI_WIDGET_PLAN.md" \
  -x "RESUMEN_PLUGIN_AI_WIDGET.md"
```

2. **Probar el ZIP**:
   - Descomprimir en instalación limpia de WordPress
   - Activar plugin
   - Verificar que funcione correctamente
   - Revisar PHP errors en debug mode

3. **Revisar readme.txt**:
   - Ir a https://wordpress.org/plugins/developers/readme-validator/
   - Pegar contenido de readme.txt
   - Corregir warnings/errores

4. **Screenshots preparados**:
   - Tener listos los 7 screenshots
   - Formato PNG o JPG
   - Tamaño consistente (1280x720 recomendado)

5. **Banner e Icon**:
   - Diseñar banner con logo de Workfluz
   - Crear icon cuadrado con "W" de Workfluz
   - Usar colores corporativos

## 🚨 Errores Comunes a Evitar

- ❌ No incluir `assets/` en el ZIP del plugin (va separado en SVN)
- ❌ Hardcodear license keys
- ❌ Llamadas a servidores de licencias externos sin divulgar
- ❌ Código ofuscado o encriptado
- ❌ No documentar servicios externos
- ❌ Versiones inconsistentes (plugin header vs readme.txt)
- ❌ No tener username de WordPress.org antes de enviar
- ❌ Usar Tags prohibidos (premium, pro, etc.)

## 📞 Contacto Revisión WordPress.org

Si hay problemas o preguntas durante la revisión:
- Email: plugins@wordpress.org
- Foro: https://wordpress.org/support/forum/plugins-and-hacks/

## ✅ Status Actual

- [x] Código listo y GPL-compliant
- [x] readme.txt formateado correctamente
- [x] Información de contacto actualizada
- [x] Servicios externos documentados
- [ ] Assets creados (screenshots, banners, icons)
- [ ] ZIP del plugin preparado
- [ ] Username WordPress.org creado
- [ ] Plugin enviado para revisión

---

**Última actualización**: 24 de octubre de 2025
**Versión del plugin**: 1.0.0
**Developer**: Josue Ayala - Workfluz
