# Script PowerShell para crear ZIP del plugin para WordPress.org
# Autor: Josue Ayala - Workfluz
# Fecha: 24 de octubre de 2025

Write-Host "🚀 Creando ZIP de AI Widget by Workfluz para WordPress.org..." -ForegroundColor Green

# Configuración
$PluginSlug = "ai-voice-text-widget"
$Version = "1.0.0"
$OutputDir = [Environment]::GetFolderPath("Desktop")
$OutputFile = Join-Path $OutputDir "$PluginSlug-$Version.zip"
$SourceDir = $PSScriptRoot

Write-Host "📁 Directorio del plugin: $SourceDir" -ForegroundColor Cyan

# Limpiar ZIP anterior si existe
if (Test-Path $OutputFile) {
    Write-Host "🗑️  Eliminando ZIP anterior..." -ForegroundColor Yellow
    Remove-Item $OutputFile -Force
}

# Archivos y carpetas a excluir
$ExcludePatterns = @(
    "*.git*",
    "*node_modules*",
    "*.DS_Store",
    "*Thumbs.db",
    "*.md",
    "*.sh",
    "*.ps1",
    "diagnose-stats.php",
    "dev-helper.php",
    "migrate-*.php",
    "reset-widget-limit.php",
    "PLUGIN_AI_WIDGET_PLAN.md",
    "RESUMEN_PLUGIN_AI_WIDGET.md",
    ".wporg-submission.md",
    "WORDPRESS-ORG-CHECKLIST.md",
    "CONTRIBUTORS.md",
    "SUBMISSION-GUIDE.md",
    "LICENSE-PROPRIETARY-OLD.txt",
    "*.log",
    "*.bak",
    "*~"
)

# Función para verificar si un archivo debe ser excluido
function Should-Exclude {
    param($Path)
    
    foreach ($Pattern in $ExcludePatterns) {
        if ($Path -like $Pattern) {
            return $true
        }
    }
    return $false
}

# Crear ZIP
Write-Host "📦 Creando archivo ZIP..." -ForegroundColor Cyan

try {
    # Obtener todos los archivos recursivamente
    $Files = Get-ChildItem -Path $SourceDir -Recurse -File | Where-Object {
        $RelativePath = $_.FullName.Substring($SourceDir.Length)
        -not (Should-Exclude $RelativePath)
    }
    
    # Crear ZIP usando .NET
    Add-Type -AssemblyName System.IO.Compression.FileSystem
    
    if (Test-Path $OutputFile) {
        Remove-Item $OutputFile -Force
    }
    
    $Zip = [System.IO.Compression.ZipFile]::Open($OutputFile, 'Create')
    
    foreach ($File in $Files) {
        $RelativePath = $File.FullName.Substring($SourceDir.Length + 1)
        $ZipEntry = $Zip.CreateEntry($PluginSlug + "\" + $RelativePath)
        $EntryStream = $ZipEntry.Open()
        $FileStream = [System.IO.File]::OpenRead($File.FullName)
        $FileStream.CopyTo($EntryStream)
        $FileStream.Close()
        $EntryStream.Close()
    }
    
    $Zip.Dispose()
    
    # Verificar que se creó correctamente
    if (Test-Path $OutputFile) {
        $FileSize = (Get-Item $OutputFile).Length
        $FileSizeMB = [math]::Round($FileSize / 1MB, 2)
        
        Write-Host ""
        Write-Host "✅ ZIP creado exitosamente:" -ForegroundColor Green
        Write-Host "   📍 Ubicación: $OutputFile" -ForegroundColor White
        Write-Host "   📊 Tamaño: $FileSizeMB MB" -ForegroundColor White
        Write-Host ""
        Write-Host "📋 Archivos incluidos:" -ForegroundColor Cyan
        
        # Mostrar primeros archivos
        $ZipRead = [System.IO.Compression.ZipFile]::OpenRead($OutputFile)
        $EntryCount = $ZipRead.Entries.Count
        $ZipRead.Entries | Select-Object -First 15 | ForEach-Object {
            Write-Host "   - $($_.FullName)" -ForegroundColor Gray
        }
        if ($EntryCount -gt 15) {
            Write-Host "   ... y $($EntryCount - 15) archivos más" -ForegroundColor Gray
        }
        $ZipRead.Dispose()
        
        Write-Host ""
        Write-Host "🎯 Siguientes pasos:" -ForegroundColor Yellow
        Write-Host "   1. Ir a https://wordpress.org/plugins/developers/add/" -ForegroundColor White
        Write-Host "   2. Subir: $OutputFile" -ForegroundColor White
        Write-Host "   3. Esperar aprobación (2-14 días)" -ForegroundColor White
        Write-Host ""
        Write-Host "💡 Tips importantes:" -ForegroundColor Yellow
        Write-Host "   - Valida readme.txt en: https://wordpress.org/plugins/developers/readme-validator/" -ForegroundColor White
        Write-Host "   - Crea cuenta WordPress.org con username: josueayala" -ForegroundColor White
        Write-Host "   - Prepara screenshots para después de la aprobación" -ForegroundColor White
        Write-Host ""
        Write-Host "📧 Contacto revisión: plugins@wordpress.org" -ForegroundColor Cyan
        
        # Abrir carpeta con el ZIP
        Start-Process "explorer.exe" -ArgumentList "/select,`"$OutputFile`""
        
    } else {
        Write-Host "❌ Error: El archivo ZIP no fue creado" -ForegroundColor Red
        exit 1
    }
    
} catch {
    Write-Host "❌ Error al crear el ZIP: $_" -ForegroundColor Red
    exit 1
}
