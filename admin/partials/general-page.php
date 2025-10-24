<?php
/**
 * Página de configuración general del plugin.
 *
 * @package AI_Voice_Text_Widget
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Guardar configuración
if ( isset( $_POST['ai_widget_general_submit'] ) && check_admin_referer( 'ai_widget_general' ) ) {
    $options = array(
        'ai_widget_enabled',
        'ai_widget_assistant_name',
        'ai_widget_welcome_message',
        'ai_widget_placeholder',
        'ai_widget_voice_enabled',
        'ai_widget_text_enabled'
    );

    foreach ( $options as $option ) {
        if ( isset( $_POST[ $option ] ) ) {
            update_option( $option, sanitize_text_field( $_POST[ $option ] ) );
        } elseif ( strpos( $option, '_enabled' ) !== false ) {
            update_option( $option, false );
        }
    }

    echo '<div class="notice notice-success"><p>✅ Configuración guardada correctamente</p></div>';
}

// Obtener valores actuales
$enabled = get_option( 'ai_widget_enabled', true );
$assistant_name = get_option( 'ai_widget_assistant_name', 'Workfluz Assistant' );
$welcome_message = get_option( 'ai_widget_welcome_message', '¡Hola! 👋 ¿Cómo le gustaría interactuar?' );
$placeholder = get_option( 'ai_widget_placeholder', 'Escribe tu mensaje...' );
$voice_enabled = get_option( 'ai_widget_voice_enabled', true );
$text_enabled = get_option( 'ai_widget_text_enabled', true );
?>

<div class="wrap">
    <h1>⚙️ Configuración General</h1>
    <p class="description">Configuración básica del widget de inteligencia artificial.</p>
    
    <form method="post" action="">
        <?php wp_nonce_field( 'ai_widget_general' ); ?>
        
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="ai_widget_enabled">Habilitar Widget</label>
                </th>
                <td>
                    <label>
                        <input type="checkbox" name="ai_widget_enabled" id="ai_widget_enabled" value="1" <?php checked( $enabled ); ?>>
                        Mostrar el widget en el sitio
                    </label>
                    <p class="description">Activa o desactiva la visualización del widget en tu sitio web.</p>
                </td>
            </tr>
            
            <tr>
                <th scope="row">
                    <label for="ai_widget_assistant_name">Nombre del Asistente</label>
                </th>
                <td>
                    <input type="text" name="ai_widget_assistant_name" id="ai_widget_assistant_name" value="<?php echo esc_attr( $assistant_name ); ?>" class="regular-text">
                    <p class="description">Nombre que aparecerá en el widget.</p>
                </td>
            </tr>
            
            <tr>
                <th scope="row">
                    <label for="ai_widget_welcome_message">Mensaje de Bienvenida</label>
                </th>
                <td>
                    <input type="text" name="ai_widget_welcome_message" id="ai_widget_welcome_message" value="<?php echo esc_attr( $welcome_message ); ?>" class="large-text">
                    <p class="description">Mensaje inicial que verán los usuarios al abrir el widget.</p>
                </td>
            </tr>
            
            <tr>
                <th scope="row">
                    <label for="ai_widget_placeholder">Placeholder del Chat</label>
                </th>
                <td>
                    <input type="text" name="ai_widget_placeholder" id="ai_widget_placeholder" value="<?php echo esc_attr( $placeholder ); ?>" class="large-text">
                    <p class="description">Texto que aparece en el campo de entrada de mensajes.</p>
                </td>
            </tr>
            
            <tr>
                <th scope="row">Modos Habilitados</th>
                <td>
                    <fieldset>
                        <label>
                            <input type="checkbox" name="ai_widget_voice_enabled" value="1" <?php checked( $voice_enabled ); ?>>
                            <strong>Modo Voz</strong> (VAPI/ElevenLabs)
                        </label>
                        <br>
                        <label style="margin-top: 10px; display: inline-block;">
                            <input type="checkbox" name="ai_widget_text_enabled" value="1" <?php checked( $text_enabled ); ?>>
                            <strong>Modo Chat de Texto</strong> (OpenAI/n8n)
                        </label>
                        <p class="description">Selecciona los modos de interacción que quieres ofrecer a tus usuarios.</p>
                    </fieldset>
                </td>
            </tr>
        </table>

        <p class="submit">
            <input type="submit" name="ai_widget_general_submit" class="button button-primary" value="Guardar Cambios">
        </p>
    </form>

    <hr style="margin: 40px 0;">

    <div class="ai-widget-info-box" style="background: #f0f6fc; border-left: 4px solid #2271b1; padding: 15px; border-radius: 4px;">
        <h3 style="margin-top: 0;">💡 Próximos pasos</h3>
        <p>Una vez configurado lo básico, continúa con:</p>
        <ol>
            <li><strong>Proveedores IA:</strong> Configura las API keys de VAPI, OpenAI, ElevenLabs o n8n</li>
            <li><strong>System Prompt:</strong> Personaliza la personalidad y comportamiento del asistente</li>
            <li><strong>Apariencia:</strong> Ajusta colores, posición y diseño del widget</li>
            <li><strong>Freemium:</strong> Configura límites de uso y planes de pago</li>
        </ol>
    </div>
</div>
