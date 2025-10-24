<?php
/**
 * Página de configuración de Apariencia Avanzada.
 *
 * @package AI_Voice_Text_Widget
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Verificar si es premium
$config = AI_Widget_Freemium::get_installation_config();
$is_premium = ( $config && $config->plan === 'premium' );

// Guardar configuración
if ( isset( $_POST['ai_widget_appearance_submit'] ) && check_admin_referer( 'ai_widget_appearance' ) ) {
    // Configuración básica
    update_option( 'ai_widget_position', sanitize_text_field( $_POST['ai_widget_position'] ) );
    update_option( 'ai_widget_primary_color', sanitize_hex_color( $_POST['ai_widget_primary_color'] ) );
    update_option( 'ai_widget_secondary_color', sanitize_hex_color( $_POST['ai_widget_secondary_color'] ) );
    
    // Logo personalizado (solo premium)
    if ( $is_premium ) {
        if ( isset( $_POST['ai_widget_logo_url'] ) ) {
            update_option( 'ai_widget_logo_url', esc_url_raw( $_POST['ai_widget_logo_url'] ) );
        }
        if ( isset( $_POST['ai_widget_logo_svg'] ) ) {
            update_option( 'ai_widget_logo_svg', wp_kses_post( $_POST['ai_widget_logo_svg'] ) );
        }
        
        // Configuración avanzada
        update_option( 'ai_widget_orb_bg_color', sanitize_hex_color( $_POST['ai_widget_orb_bg_color'] ) );
        update_option( 'ai_widget_border_color', sanitize_hex_color( $_POST['ai_widget_border_color'] ) );
        update_option( 'ai_widget_logo_size', absint( $_POST['ai_widget_logo_size'] ) );
        update_option( 'ai_widget_orb_size', absint( $_POST['ai_widget_orb_size'] ) );
        update_option( 'ai_widget_gradient_animation', sanitize_text_field( $_POST['ai_widget_gradient_animation'] ) );
    }

    echo '<div class="notice notice-success is-dismissible"><p>✅ Apariencia guardada correctamente</p></div>';
}

// Obtener valores actuales
$position = get_option( 'ai_widget_position', 'bottom-right' );
$primary_color = get_option( 'ai_widget_primary_color', '#76b4e3' );
$secondary_color = get_option( 'ai_widget_secondary_color', '#009bf0' );
$logo_svg = get_option( 'ai_widget_logo_svg', '' );
$logo_url = get_option( 'ai_widget_logo_url', '' );
$orb_bg_color = get_option( 'ai_widget_orb_bg_color', '#ffffff' );
$border_color = get_option( 'ai_widget_border_color', '#ffffff' );
$logo_size = get_option( 'ai_widget_logo_size', 65 );
$orb_size = get_option( 'ai_widget_orb_size', 70 );
$gradient_animation = get_option( 'ai_widget_gradient_animation', 'slow' );
?>

<div class="wrap">
    <h1>🎨 Apariencia del Widget</h1>
    <p class="description">Personaliza el diseño visual del widget para que coincida con tu marca.</p>
    
    <?php if ( ! $is_premium ) : ?>
        <div class="notice notice-info">
            <p><strong>💎 Desbloquea Personalización Premium:</strong> Actualiza a la versión premium para acceder a opciones avanzadas de personalización, incluyendo logo personalizado, tamaños ajustables y configuración de colores avanzada.</p>
        </div>
    <?php endif; ?>
    
    <div class="ai-widget-appearance-container" style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-top: 20px;">
        
        <!-- Panel de Configuración -->
        <div class="configuration-panel">
            <form method="post" action="" id="appearance-form">
                <?php wp_nonce_field( 'ai_widget_appearance' ); ?>
                
                <div class="postbox">
                    <div class="postbox-header"><h2 class="hndle">Configuración Básica</h2></div>
                    <div class="inside">
                        <table class="form-table">
                            <tr>
                                <th scope="row">
                                    <label for="ai_widget_position">Posición</label>
                                </th>
                                <td>
                                    <select name="ai_widget_position" id="ai_widget_position" class="regular-text">
                                        <option value="bottom-right" <?php selected( $position, 'bottom-right' ); ?>>Abajo Derecha</option>
                                        <option value="bottom-left" <?php selected( $position, 'bottom-left' ); ?>>Abajo Izquierda</option>
                                    </select>
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row">
                                    <label for="ai_widget_primary_color">Color Primario</label>
                                </th>
                                <td>
                                    <input type="text" name="ai_widget_primary_color" id="ai_widget_primary_color" value="<?php echo esc_attr( $primary_color ); ?>" class="color-picker" data-default-color="#76b4e3">
                                    <p class="description">Degradado inicio</p>
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row">
                                    <label for="ai_widget_secondary_color">Color Secundario</label>
                                </th>
                                <td>
                                    <input type="text" name="ai_widget_secondary_color" id="ai_widget_secondary_color" value="<?php echo esc_attr( $secondary_color ); ?>" class="color-picker" data-default-color="#009bf0">
                                    <p class="description">Degradado final</p>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <?php if ( $is_premium ) : ?>
                    <!-- Logo Personalizado (Premium) -->
                    <div class="postbox">
                        <div class="postbox-header"><h2 class="hndle">💎 Logo Personalizado</h2></div>
                        <div class="inside">
                            <table class="form-table">
                                <tr>
                                    <th scope="row">
                                        <label>Logo Actual</label>
                                    </th>
                                    <td>
                                        <div id="current-logo-preview" style="padding: 20px; background: #f9f9f9; border-radius: 8px; text-align: center;">
                                            <?php if ( $logo_url ) : ?>
                                                <img src="<?php echo esc_url( $logo_url ); ?>" alt="Logo actual" style="max-width: 100px; max-height: 100px;">
                                            <?php elseif ( $logo_svg ) : ?>
                                                <div style="width: 100px; height: 100px; margin: 0 auto;"><?php echo wp_kses_post( $logo_svg ); ?></div>
                                            <?php else : ?>
                                                <p style="color: #666;">Logo de Workfluz por defecto</p>
                                            <?php endif; ?>
                                        </div>
                                        <input type="hidden" name="ai_widget_logo_url" id="ai_widget_logo_url" value="<?php echo esc_attr( $logo_url ); ?>">
                                        <p style="margin-top: 10px;">
                                            <button type="button" class="button" id="upload-logo-btn">
                                                🖼️ Cambiar Logo (PNG, JPG, SVG)
                                            </button>
                                            <?php if ( $logo_url || $logo_svg ) : ?>
                                                <button type="button" class="button" id="remove-logo-btn" style="margin-left: 5px;">
                                                    🗑️ Usar Logo por Defecto
                                                </button>
                                            <?php endif; ?>
                                        </p>
                                        <p class="description">Sube tu propio logo para reemplazar el de Workfluz</p>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <th scope="row">
                                        <label for="ai_widget_logo_svg">O pega código SVG</label>
                                    </th>
                                    <td>
                                        <textarea name="ai_widget_logo_svg" id="ai_widget_logo_svg" rows="6" class="large-text code" style="font-family: 'Courier New', monospace;"><?php echo esc_textarea( $logo_svg ); ?></textarea>
                                        <p class="description">Alternativa: pega el código SVG directamente</p>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Configuración Avanzada (Premium) -->
                    <div class="postbox">
                        <div class="postbox-header"><h2 class="hndle">💎 Configuración Avanzada</h2></div>
                        <div class="inside">
                            <table class="form-table">
                                <tr>
                                    <th scope="row">
                                        <label for="ai_widget_orb_size">Tamaño del Orbe</label>
                                    </th>
                                    <td>
                                        <input type="range" name="ai_widget_orb_size" id="ai_widget_orb_size" min="50" max="100" value="<?php echo esc_attr( $orb_size ); ?>" step="5" style="width: 300px;">
                                        <span id="orb-size-value" style="margin-left: 10px; font-weight: bold;"><?php echo esc_html( $orb_size ); ?>px</span>
                                        <p class="description">Tamaño del botón flotante (50-100px)</p>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <th scope="row">
                                        <label for="ai_widget_logo_size">Tamaño del Logo</label>
                                    </th>
                                    <td>
                                        <input type="range" name="ai_widget_logo_size" id="ai_widget_logo_size" min="40" max="90" value="<?php echo esc_attr( $logo_size ); ?>" step="5" style="width: 300px;">
                                        <span id="logo-size-value" style="margin-left: 10px; font-weight: bold;"><?php echo esc_html( $logo_size ); ?>%</span>
                                        <p class="description">Porcentaje del logo dentro del orbe (40-90%)</p>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <th scope="row">
                                        <label for="ai_widget_orb_bg_color">Color de Fondo del Orbe</label>
                                    </th>
                                    <td>
                                        <input type="text" name="ai_widget_orb_bg_color" id="ai_widget_orb_bg_color" value="<?php echo esc_attr( $orb_bg_color ); ?>" class="color-picker" data-default-color="#ffffff">
                                        <p class="description">Color del fondo interno del círculo</p>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <th scope="row">
                                        <label for="ai_widget_border_color">Color del Borde</label>
                                    </th>
                                    <td>
                                        <input type="text" name="ai_widget_border_color" id="ai_widget_border_color" value="<?php echo esc_attr( $border_color ); ?>" class="color-picker" data-default-color="#ffffff">
                                        <p class="description">Color del borde entre el gradiente y el fondo</p>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <th scope="row">
                                        <label for="ai_widget_gradient_animation">Velocidad de Animación</label>
                                    </th>
                                    <td>
                                        <select name="ai_widget_gradient_animation" id="ai_widget_gradient_animation" class="regular-text">
                                            <option value="none" <?php selected( $gradient_animation, 'none' ); ?>>Sin Animación</option>
                                            <option value="slow" <?php selected( $gradient_animation, 'slow' ); ?>>Lenta (10s)</option>
                                            <option value="normal" <?php selected( $gradient_animation, 'normal' ); ?>>Normal (5s)</option>
                                            <option value="fast" <?php selected( $gradient_animation, 'fast' ); ?>>Rápida (2s)</option>
                                        </select>
                                        <p class="description">Velocidad de rotación del gradiente</p>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>

                <p class="submit">
                    <input type="submit" name="ai_widget_appearance_submit" class="button button-primary button-large" value="💾 Guardar Cambios">
                    <button type="button" class="button button-secondary" id="reset-defaults">↺ Restaurar Valores por Defecto</button>
                </p>
            </form>
        </div>

        <!-- Panel de Vista Previa -->
        <div class="preview-panel" style="position: sticky; top: 32px;">
            <div class="postbox">
                <div class="postbox-header"><h2 class="hndle">👁️ Vista Previa en Tiempo Real</h2></div>
                <div class="inside">
                    <p class="description">Widget real del frontend con tu configuración actual</p>
                    
                    <!-- Vista previa REAL del widget -->
                    <div id="widget-preview-container" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 40px; border-radius: 12px; margin-top: 15px; min-height: 600px; position: relative;">
                        <p style="color: white; text-align: center; margin-bottom: 20px; font-size: 12px; opacity: 0.9;">
                            Así se verá el widget en tu sitio web
                        </p>
                        <!-- El widget real se cargará aquí -->
                        <div id="ai-widget-preview-target"></div>
                    </div>
                    
                    <p style="margin-top: 15px; font-size: 12px; color: #666; text-align: center;">
                        💡 Haz clic en el orbe para ver el chat completo
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.ai-widget-appearance-container {
    max-width: 1400px;
}

.configuration-panel .postbox {
    margin-bottom: 20px;
}

#widget-preview-container {
    overflow: hidden;
}

/* Asegurar que el widget aparezca en el preview */
#widget-preview-container #ai-vapi-widget-container {
    position: relative !important;
    bottom: auto !important;
    right: auto !important;
    margin: 20px auto !important;
    display: flex !important;
    justify-content: center;
}
</style>

<?php
// Cargar el widget real del frontend para preview
wp_enqueue_script( 'ai-widget-vapi', plugins_url( 'public/js/widget-vapi.js', dirname( dirname( __FILE__ ) ) ), array( 'jquery' ), '1.0.0', true );
wp_localize_script( 'ai-widget-vapi', 'aiWidgetData', array(
    'apiUrl'              => rest_url( 'ai-widget/v1' ),
    'sessionId'           => 'preview-' . uniqid(),
    'voiceEnabled'        => true,
    'textEnabled'         => true,
    'provider'            => get_option( 'ai_widget_provider', 'vapi' ),
    'vapiPublicKey'       => get_option( 'ai_widget_vapi_public_key', '' ),
    'vapiAssistantId'     => get_option( 'ai_widget_vapi_assistant_id', '' ),
    'assistantName'       => get_option( 'ai_widget_assistant_name', 'Workfluz Assistant' ),
    'welcomeMessage'      => get_option( 'ai_widget_welcome_message', '¡Hola! 👋 ¿Cómo le gustaría interactuar?' ),
    'placeholder'         => get_option( 'ai_widget_placeholder', 'Escribe tu mensaje...' ),
    'primaryColor'        => $primary_color,
    'secondaryColor'      => $secondary_color,
    'logoSVG'             => $logo_svg ? $logo_svg : '',
    'logoURL'             => $logo_url ? $logo_url : '',
    'showBranding'        => (bool) get_option( 'ai_widget_show_branding', true ),
    'plan'                => $is_premium ? 'premium' : 'free',
) );
?>

<script>
jQuery(document).ready(function($) {
    // Color Pickers
    $('.color-picker').wpColorPicker({
        change: function(event, ui) {
            reloadWidget();
        }
    });

    // Range sliders
    $('#ai_widget_orb_size').on('input', function() {
        $('#orb-size-value').text($(this).val() + 'px');
    });

    $('#ai_widget_logo_size').on('input', function() {
        $('#logo-size-value').text($(this).val() + '%');
    });

    // Upload logo button (WordPress Media Library)
    $('#upload-logo-btn').on('click', function(e) {
        e.preventDefault();
        
        var mediaUploader = wp.media({
            title: 'Selecciona o Sube un Logo',
            button: {
                text: 'Usar este Logo'
            },
            multiple: false,
            library: {
                type: ['image']
            }
        });

        mediaUploader.on('select', function() {
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            $('#ai_widget_logo_url').val(attachment.url);
            $('#current-logo-preview').html('<img src="' + attachment.url + '" alt="Logo" style="max-width: 100px; max-height: 100px;">');
            reloadWidget();
        });

        mediaUploader.open();
    });

    // Remove logo button
    $('#remove-logo-btn').on('click', function(e) {
        e.preventDefault();
        if (confirm('¿Usar el logo de Workfluz por defecto?')) {
            $('#ai_widget_logo_url').val('');
            $('#ai_widget_logo_svg').val('');
            $('#current-logo-preview').html('<p style="color: #666;">Logo de Workfluz por defecto</p>');
            reloadWidget();
        }
    });

    // Reset to defaults
    $('#reset-defaults').on('click', function(e) {
        e.preventDefault();
        if (confirm('¿Restaurar todos los valores por defecto?')) {
            $('#ai_widget_primary_color').wpColorPicker('color', '#76b4e3');
            $('#ai_widget_secondary_color').wpColorPicker('color', '#009bf0');
            $('#ai_widget_orb_bg_color').wpColorPicker('color', '#ffffff');
            $('#ai_widget_border_color').wpColorPicker('color', '#ffffff');
            $('#ai_widget_orb_size').val(70).trigger('input');
            $('#ai_widget_logo_size').val(65).trigger('input');
            $('#ai_widget_gradient_animation').val('slow');
            reloadWidget();
        }
    });

    // Función para recargar el widget con nuevos valores
    function reloadWidget() {
        // Actualizar los valores de aiWidgetData
        aiWidgetData.primaryColor = $('#ai_widget_primary_color').val();
        aiWidgetData.secondaryColor = $('#ai_widget_secondary_color').val();
        aiWidgetData.logoURL = $('#ai_widget_logo_url').val();
        aiWidgetData.logoSVG = $('#ai_widget_logo_svg').val();
        
        // Remover widget anterior
        $('#ai-vapi-widget-container').remove();
        
        // Reiniciar el widget
        setTimeout(function() {
            if (typeof AIVapiWidget !== 'undefined') {
                // Mover el widget al contenedor de preview
                new AIVapiWidget();
                
                // Mover el widget generado al contenedor de preview
                setTimeout(function() {
                    var $widget = $('body > #ai-vapi-widget-container').detach();
                    $('#widget-preview-container').append($widget);
                }, 100);
            }
        }, 100);
    }

    // Cargar widget inicial en el preview
    setTimeout(function() {
        if (typeof AIVapiWidget !== 'undefined') {
            new AIVapiWidget();
            
            // Mover el widget al contenedor de preview
            setTimeout(function() {
                var $widget = $('body > #ai-vapi-widget-container').detach();
                $('#widget-preview-container').append($widget);
            }, 100);
        }
    }, 500);

    // Recargar widget al cambiar configuración
    var changeTimeout;
    $('#appearance-form input, #appearance-form select, #appearance-form textarea').on('change input', function() {
        clearTimeout(changeTimeout);
        changeTimeout = setTimeout(function() {
            reloadWidget();
        }, 800);
    });
});
</script>
