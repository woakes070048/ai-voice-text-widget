<?php
/**
 * Página de configuración de Proveedores de IA.
 *
 * @package AI_Voice_Text_Widget
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Guardar configuración
if ( isset( $_POST['ai_widget_providers_submit'] ) && check_admin_referer( 'ai_widget_providers' ) ) {
    $options = array(
        'ai_widget_vapi_public_key',
        'ai_widget_vapi_assistant_id',
        'ai_widget_elevenlabs_api_key',
        'ai_widget_elevenlabs_voice_id',
        'ai_widget_chat_provider',
        'ai_widget_openai_api_key',
        'ai_widget_n8n_webhook_url'
    );

    foreach ( $options as $option ) {
        if ( isset( $_POST[ $option ] ) ) {
            update_option( $option, sanitize_text_field( $_POST[ $option ] ) );
        }
    }

    echo '<div class="notice notice-success"><p>✅ Proveedores configurados correctamente</p></div>';
}

// Obtener valores actuales
$vapi_public_key = get_option( 'ai_widget_vapi_public_key', '' );
$vapi_assistant_id = get_option( 'ai_widget_vapi_assistant_id', '' );
$elevenlabs_api_key = get_option( 'ai_widget_elevenlabs_api_key', '' );
$elevenlabs_voice_id = get_option( 'ai_widget_elevenlabs_voice_id', '' );
$chat_provider = get_option( 'ai_widget_chat_provider', 'openai' );
$openai_api_key = get_option( 'ai_widget_openai_api_key', '' );
$n8n_webhook_url = get_option( 'ai_widget_n8n_webhook_url', '' );
?>

<div class="wrap">
    <h1>🔌 Proveedores de IA</h1>
    <p class="description">Configura las API keys y credenciales de los servicios de inteligencia artificial.</p>
    
    <form method="post" action="">
        <?php wp_nonce_field( 'ai_widget_providers' ); ?>
        
        <!-- VAPI -->
        <h2 class="title">🎤 VAPI (Voice AI Platform)</h2>
        <p class="description">Configuración para llamadas de voz con IA.</p>
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="ai_widget_vapi_public_key">Public Key</label>
                </th>
                <td>
                    <input type="text" name="ai_widget_vapi_public_key" id="ai_widget_vapi_public_key" value="<?php echo esc_attr( $vapi_public_key ); ?>" class="large-text code">
                    <p class="description">
                        Obtenla en <a href="https://vapi.ai/dashboard" target="_blank">VAPI Dashboard</a> → Settings → API Keys
                    </p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="ai_widget_vapi_assistant_id">Assistant ID</label>
                </th>
                <td>
                    <input type="text" name="ai_widget_vapi_assistant_id" id="ai_widget_vapi_assistant_id" value="<?php echo esc_attr( $vapi_assistant_id ); ?>" class="large-text code">
                    <p class="description">ID del asistente creado en VAPI Dashboard.</p>
                </td>
            </tr>
        </table>

        <hr>

        <!-- ElevenLabs -->
        <h2 class="title">🔊 ElevenLabs (Text-to-Speech)</h2>
        <p class="description">Configuración para síntesis de voz de alta calidad.</p>
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="ai_widget_elevenlabs_api_key">API Key</label>
                </th>
                <td>
                    <input type="text" name="ai_widget_elevenlabs_api_key" id="ai_widget_elevenlabs_api_key" value="<?php echo esc_attr( $elevenlabs_api_key ); ?>" class="large-text code">
                    <p class="description">
                        Obtenla en <a href="https://elevenlabs.io/" target="_blank">ElevenLabs</a> → Profile → API Keys
                    </p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="ai_widget_elevenlabs_voice_id">Voice ID</label>
                </th>
                <td>
                    <input type="text" name="ai_widget_elevenlabs_voice_id" id="ai_widget_elevenlabs_voice_id" value="<?php echo esc_attr( $elevenlabs_voice_id ); ?>" class="large-text code">
                    <p class="description">ID de la voz que quieres usar (ej: 21m00Tcm4TlvDq8ikWAM).</p>
                </td>
            </tr>
        </table>

        <hr>

        <!-- Proveedor de Chat -->
        <h2 class="title">💬 Proveedor de Chat de Texto</h2>
        <p class="description">Selecciona qué servicio procesará las conversaciones de texto.</p>
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="ai_widget_chat_provider">Proveedor</label>
                </th>
                <td>
                    <select name="ai_widget_chat_provider" id="ai_widget_chat_provider" class="regular-text">
                        <option value="openai" <?php selected( $chat_provider, 'openai' ); ?>>OpenAI (ChatGPT)</option>
                        <option value="n8n" <?php selected( $chat_provider, 'n8n' ); ?>>n8n Webhook</option>
                    </select>
                    <p class="description">Elige entre OpenAI o tu propio webhook de n8n.</p>
                </td>
            </tr>
        </table>

        <!-- OpenAI Section -->
        <div id="openai-section" style="<?php echo $chat_provider === 'openai' ? '' : 'display:none;'; ?>">
            <h3>🤖 OpenAI Configuration</h3>
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="ai_widget_openai_api_key">OpenAI API Key</label>
                    </th>
                    <td>
                        <input type="text" name="ai_widget_openai_api_key" id="ai_widget_openai_api_key" value="<?php echo esc_attr( $openai_api_key ); ?>" class="large-text code">
                        <p class="description">
                            Obtenla en <a href="https://platform.openai.com/api-keys" target="_blank">OpenAI Platform</a> → API Keys
                        </p>
                    </td>
                </tr>
            </table>
        </div>

        <!-- n8n Section -->
        <div id="n8n-section" style="<?php echo $chat_provider === 'n8n' ? '' : 'display:none;'; ?>">
            <h3>🔗 n8n Webhook Configuration</h3>
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="ai_widget_n8n_webhook_url">Webhook URL</label>
                    </th>
                    <td>
                        <input type="url" name="ai_widget_n8n_webhook_url" id="ai_widget_n8n_webhook_url" value="<?php echo esc_attr( $n8n_webhook_url ); ?>" class="large-text code" placeholder="https://tu-instancia.n8n.cloud/webhook/ai-chat">
                        <p class="description">URL completa del webhook de n8n que procesará los mensajes.</p>
                        
                        <div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 12px; margin-top: 10px; border-radius: 4px;">
                            <strong>📤 Formato del Request POST:</strong>
                            <pre style="background: #f5f5f5; padding: 10px; border-radius: 4px; overflow-x: auto; font-size: 12px;">{
  "message": "texto del usuario",
  "session_id": "session_xxx",
  "conversation_history": [...],
  "isVoiceMessage": false
}</pre>
                            <strong>📥 Respuesta esperada:</strong>
                            <pre style="background: #f5f5f5; padding: 10px; border-radius: 4px; overflow-x: auto; font-size: 12px;">{
  "response": "respuesta del asistente"
}</pre>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <p class="submit">
            <input type="submit" name="ai_widget_providers_submit" class="button button-primary" value="Guardar Configuración">
        </p>
    </form>
</div>

<script>
jQuery(document).ready(function($) {
    $('#ai_widget_chat_provider').on('change', function() {
        if ($(this).val() === 'openai') {
            $('#openai-section').slideDown();
            $('#n8n-section').slideUp();
        } else {
            $('#openai-section').slideUp();
            $('#n8n-section').slideDown();
        }
    });
});
</script>
