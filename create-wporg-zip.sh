#!/bin/bash

# Script para crear ZIP del plugin para WordPress.org
# Autor: Josue Ayala - Workfluz
# Fecha: 24 de octubre de 2025

echo "🚀 Creando ZIP de AI Widget by Workfluz para WordPress.org..."

# Configuración
PLUGIN_SLUG="ai-voice-text-widget"
VERSION="1.0.0"
OUTPUT_DIR="$HOME/Desktop"
OUTPUT_FILE="$OUTPUT_DIR/${PLUGIN_SLUG}-${VERSION}.zip"

# Cambiar al directorio del plugin
cd "$(dirname "$0")"

echo "📁 Directorio actual: $(pwd)"

# Limpiar ZIP anterior si existe
if [ -f "$OUTPUT_FILE" ]; then
    echo "🗑️  Eliminando ZIP anterior..."
    rm "$OUTPUT_FILE"
fi

# Crear ZIP excluyendo archivos innecesarios
echo "📦 Creando archivo ZIP..."
zip -r "$OUTPUT_FILE" . \
    -x "*.git*" \
    -x "*node_modules*" \
    -x "*.DS_Store*" \
    -x "*Thumbs.db" \
    -x "*.md" \
    -x "*.sh" \
    -x "diagnose-stats.php" \
    -x "PLUGIN_AI_WIDGET_PLAN.md" \
    -x "RESUMEN_PLUGIN_AI_WIDGET.md" \
    -x ".wporg-submission.md" \
    -x "WORDPRESS-ORG-CHECKLIST.md" \
    -x "CONTRIBUTORS.md" \
    -x "reset-widget-limit.php" \
    -x "*.log" \
    -x "*.bak" \
    -x "*~"

# Verificar que se creó correctamente
if [ -f "$OUTPUT_FILE" ]; then
    echo "✅ ZIP creado exitosamente:"
    echo "   📍 Ubicación: $OUTPUT_FILE"
    echo "   📊 Tamaño: $(du -h "$OUTPUT_FILE" | cut -f1)"
    echo ""
    echo "📋 Contenido del ZIP:"
    unzip -l "$OUTPUT_FILE" | head -n 20
    echo "   ..."
    echo ""
    echo "🎯 Siguiente paso:"
    echo "   1. Ir a https://wordpress.org/plugins/developers/add/"
    echo "   2. Subir: $OUTPUT_FILE"
    echo "   3. Esperar aprobación (2-14 días)"
    echo ""
    echo "💡 Tip: Valida tu readme.txt en:"
    echo "   https://wordpress.org/plugins/developers/readme-validator/"
else
    echo "❌ Error al crear el ZIP"
    exit 1
fi
