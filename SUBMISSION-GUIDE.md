# 🚀 Guía Rápida de Submisión a WordPress.org

**Plugin**: AI Widget by Workfluz  
**Versión**: 1.0.0  
**Developer**: Josue Ayala  
**Empresa**: Workfluz (Medellín, Colombia)  
**Contacto**: support@workfluz.com | WhatsApp +57 333 430 8871

---

## 📦 Paso 1: Crear el ZIP del Plugin

### Opción A: PowerShell (Windows) ⭐ RECOMENDADO
```powershell
cd "c:\Users\USUARIO\Studio\mi-web-de-wordpress\wp-content\plugins\ai-voice-text-widget"
.\create-wporg-zip.ps1
```

### Opción B: Git Bash (Windows/Mac/Linux)
```bash
cd /c/Users/USUARIO/Studio/mi-web-de-wordpress/wp-content/plugins/ai-voice-text-widget
bash create-wporg-zip.sh
```

### Opción C: Manual
1. Comprimir la carpeta `ai-voice-text-widget/`
2. **EXCLUIR** estos archivos:
   - `*.md` (README.md, CONTRIBUTORS.md, etc.)
   - `*.sh` y `*.ps1` (scripts)
   - `diagnose-stats.php`
   - `reset-widget-limit.php`
   - Archivos de planificación
   - `.git/`, `node_modules/`, etc.

**Resultado**: `ai-voice-text-widget-1.0.0.zip` en el Escritorio

---

## 👤 Paso 2: Crear Cuenta en WordPress.org

1. Ir a: https://login.wordpress.org/register
2. **Username**: `josueayala` (¡IMPORTANTE: Debe coincidir con `readme.txt`!)
3. **Email**: support@workfluz.com
4. Completar registro y verificar email

---

## 📝 Paso 3: Validar readme.txt

1. Ir a: https://wordpress.org/plugins/developers/readme-validator/
2. Copiar contenido de `readme.txt`
3. Pegar y verificar
4. Corregir errores/warnings si los hay

---

## 📤 Paso 4: Enviar Plugin para Revisión

1. **Login** en WordPress.org con tu cuenta `josueayala`
2. Ir a: https://wordpress.org/plugins/developers/add/
3. **Subir** el ZIP: `ai-voice-text-widget-1.0.0.zip`
4. **Llenar formulario**:
   - Plugin Name: AI Widget by Workfluz
   - Plugin Slug: ai-voice-text-widget (se auto-genera)
   - Short Description: (copiar del readme.txt)
5. **Aceptar** términos y condiciones
6. **Submit** para revisión

---

## ⏳ Paso 5: Esperar Aprobación

**Tiempo estimado**: 2-14 días (usualmente 3-5 días)

Recibirás un email en `support@workfluz.com` con:
- ✅ **Aprobado**: Acceso SVN al repositorio
- ❌ **Rechazado**: Razones y correcciones necesarias

### Si es Rechazado:
1. Leer cuidadosamente el email
2. Hacer correcciones solicitadas
3. Volver a subir ZIP actualizado
4. Responder al ticket de soporte

### Si es Aprobado:
¡Continuar al Paso 6! 🎉

---

## 📊 Paso 6: Preparar Assets (Screenshots, Banners, Icons)

**IMPORTANTE**: Los assets NO van en el ZIP del plugin. Se suben después a SVN.

### Screenshots Necesarios:

1. **screenshot-1.png** - Widget flotante en el sitio (cerrado)
   - Captura de pantalla del orb en el sitio
   - Tamaño: 1280x720

2. **screenshot-2.png** - Chat abierto con conversación
   - Widget expandido mostrando chat
   - Tamaño: 1280x720

3. **screenshot-3.png** - Modo de voz activo
   - Widget con animación de voz
   - Tamaño: 1280x720

4. **screenshot-4.png** - Panel de Admin - General Settings
   - Configuración de API keys
   - Tamaño: 1280x720

5. **screenshot-5.png** - Panel de Freemium
   - Uso de mensajes y minutos
   - Tamaño: 1280x720

6. **screenshot-6.png** - Página de Apariencia
   - Customización de colores y logo
   - Tamaño: 1280x720

7. **screenshot-7.png** - Analytics/Estadísticas
   - Dashboard de analytics
   - Tamaño: 1280x720

### Banners:

- **banner-772x250.png** - Banner normal (REQUERIDO)
- **banner-1544x500.png** - Banner retina 2x (OPCIONAL)

**Diseño sugerido**:
- Fondo con gradiente Workfluz (#76b4e3 → #009bf0)
- Logo Workfluz
- Texto: "AI Widget by Workfluz - AI Chat & Voice for WordPress"

### Icons:

- **icon-128x128.png** - Icon normal (REQUERIDO)
- **icon-256x256.png** - Icon retina 2x (OPCIONAL)

**Diseño sugerido**:
- Logo "W" de Workfluz
- Fondo con gradiente o sólido
- Formato cuadrado

---

## 🔧 Paso 7: Instalar SVN y Subir Archivos

### Windows: Instalar TortoiseSVN
1. Descargar: https://tortoisesvn.net/downloads.html
2. Instalar TortoiseSVN
3. Reiniciar PC

### Mac/Linux: Instalar SVN via terminal
```bash
# Mac (con Homebrew)
brew install svn

# Ubuntu/Debian
sudo apt-get install subversion
```

### Hacer Checkout del Repositorio
```bash
# Crear carpeta para SVN
cd ~/Desktop
svn co https://plugins.svn.wordpress.org/ai-voice-text-widget/ ai-voice-text-widget-svn
cd ai-voice-text-widget-svn
```

**Username SVN**: josueayala  
**Password**: (password de WordPress.org)

### Estructura del Repositorio:
```
ai-voice-text-widget-svn/
├── trunk/          ← Versión en desarrollo
├── tags/           ← Versiones publicadas (1.0.0, 1.0.1, etc.)
├── assets/         ← Screenshots, banners, icons
└── branches/       ← Ramas experimentales (opcional)
```

---

## 📤 Paso 8: Subir Archivos del Plugin

```bash
cd ~/Desktop/ai-voice-text-widget-svn

# Copiar archivos del plugin a trunk/
cp -r /ruta/completa/al/plugin/* trunk/

# Agregar archivos nuevos
svn add trunk/* --force

# Commit a trunk
svn ci -m "Initial commit - AI Widget by Workfluz v1.0.0" \
       --username josueayala
```

---

## 🖼️ Paso 9: Subir Assets

```bash
cd ~/Desktop/ai-voice-text-widget-svn

# Copiar screenshots
cp screenshot-1.png assets/
cp screenshot-2.png assets/
cp screenshot-3.png assets/
cp screenshot-4.png assets/
cp screenshot-5.png assets/
cp screenshot-6.png assets/
cp screenshot-7.png assets/

# Copiar banners
cp banner-772x250.png assets/
cp banner-1544x500.png assets/  # Opcional

# Copiar icons
cp icon-128x128.png assets/
cp icon-256x256.png assets/  # Opcional

# Agregar y subir assets
svn add assets/* --force
svn ci -m "Add plugin assets (screenshots, banners, icons)" \
       --username josueayala
```

---

## 🏷️ Paso 10: Crear Tag de Versión 1.0.0

```bash
cd ~/Desktop/ai-voice-text-widget-svn

# Copiar trunk a tags/1.0.0
svn cp trunk tags/1.0.0

# Commit del tag
svn ci -m "Tagging version 1.0.0 for release" \
       --username josueayala
```

**IMPORTANTE**: El tag debe coincidir con `Stable tag:` en readme.txt

---

## ✅ Paso 11: Verificar en WordPress.org

1. Esperar 15-30 minutos para que se procese
2. Ir a: https://wordpress.org/plugins/ai-voice-text-widget/
3. Verificar que todo se vea correcto:
   - ✓ Screenshots visibles
   - ✓ Banner visible
   - ✓ Icon visible
   - ✓ Descripción correcta
   - ✓ FAQ visible
   - ✓ Changelog visible
   - ✓ Botón "Download" funcional

---

## 🧪 Paso 12: Probar Instalación desde WordPress.org

1. Crear instalación limpia de WordPress
2. Ir a **Plugins > Add New**
3. Buscar: "AI Widget by Workfluz"
4. **Instalar** y **Activar**
5. Verificar que funciona correctamente

---

## 📣 Paso 13: Promoción (Opcional)

Una vez publicado:

1. **Anunciar en redes sociales**
   - Twitter/X con #WordPress #AI #Plugin
   - LinkedIn
   - Facebook grupos de WordPress

2. **Product Hunt** (opcional)
   - Subir producto a Product Hunt
   - Link: https://www.producthunt.com/posts/create

3. **WordPress News**
   - Enviar a WPTavern: https://wptavern.com/
   - Comentar en foros de WordPress

4. **Email a clientes** (si tienes lista)

---

## 🆘 Problemas Comunes

### "Plugin slug already exists"
- Cambiar el slug a `ai-widget-workfluz` o similar
- Actualizar en readme.txt y código

### "External services not disclosed"
- Ya está documentado en readme.txt (✓)
- Verificar que estén OpenAI, VAPI, ElevenLabs

### "License issues"
- Ya es GPL-2.0 (✓)
- Verificar que no haya código propietario

### "Username doesn't match contributor"
- Crear cuenta `josueayala` antes de subir
- Usar mismo email (support@workfluz.com)

### SVN commit fails
- Verificar username/password
- Hacer `svn update` antes de `svn ci`
- Resolver conflictos si los hay

---

## 📞 Soporte WordPress.org

**Email del equipo de plugins**: plugins@wordpress.org  
**Foro de ayuda**: https://wordpress.org/support/forum/plugins-and-hacks/  
**Slack de WordPress**: https://make.wordpress.org/chat/

---

## ✅ Checklist Final

Antes de enviar, verificar:

- [ ] Username `josueayala` creado en WordPress.org
- [ ] Email `support@workfluz.com` verificado
- [ ] ZIP creado sin archivos innecesarios
- [ ] readme.txt validado sin errores
- [ ] Versión 1.0.0 consistente en todos lados
- [ ] GPL-2.0 license verificada
- [ ] Servicios externos documentados
- [ ] Screenshots preparados (7 archivos)
- [ ] Banner preparado (772x250)
- [ ] Icon preparado (128x128)
- [ ] Código probado en WordPress limpio
- [ ] No hay PHP errors ni warnings

---

## 🎯 Resumen Ultra-Rápido

```bash
# 1. Crear ZIP
.\create-wporg-zip.ps1

# 2. Crear cuenta WordPress.org
https://login.wordpress.org/register (username: josueayala)

# 3. Validar readme
https://wordpress.org/plugins/developers/readme-validator/

# 4. Enviar plugin
https://wordpress.org/plugins/developers/add/

# 5. Esperar email (2-14 días)

# 6. Si aprobado, checkout SVN
svn co https://plugins.svn.wordpress.org/ai-voice-text-widget/

# 7. Subir archivos
cp -r plugin/* trunk/
svn add trunk/* --force
svn ci -m "Initial commit v1.0.0"

# 8. Subir assets
cp screenshots assets/
svn add assets/* --force
svn ci -m "Add assets"

# 9. Crear tag
svn cp trunk tags/1.0.0
svn ci -m "Tagging v1.0.0"

# 10. Verificar
https://wordpress.org/plugins/ai-voice-text-widget/
```

---

**¡Éxito con tu plugin! 🚀**

Josue Ayala - Workfluz  
Medellín, Colombia  
support@workfluz.com | +57 333 430 8871
