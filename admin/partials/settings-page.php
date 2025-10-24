<?php
/**
 * Página de configuración del plugin.
 *
 * @package AI_Voice_Text_Widget
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Guardar configuración
if ( isset( $_POST['ai_widget_settings_submit'] ) && check_admin_referer( 'ai_widget_settings' ) ) {
    $options = array(
        'ai_widget_enabled', 'ai_widget_position', 'ai_widget_primary_color', 'ai_widget_secondary_color',
        'ai_widget_welcome_message', 'ai_widget_placeholder', 'ai_widget_assistant_name', 'ai_widget_logo_svg',
        'ai_widget_provider', 'ai_widget_vapi_public_key', 'ai_widget_vapi_assistant_id',
        'ai_widget_elevenlabs_api_key', 'ai_widget_elevenlabs_voice_id',
        'ai_widget_openai_api_key', 'ai_widget_personality', 'ai_widget_custom_prompt',
        'ai_widget_use_openai_assistant', 'ai_widget_openai_assistant_id', 'ai_widget_system_prompt',
        'ai_widget_voice_enabled', 'ai_widget_text_enabled', 'ai_widget_free_limit',
        'ai_widget_chat_provider', 'ai_widget_n8n_webhook_url'
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
$position = get_option( 'ai_widget_position', 'bottom-right' );
$primary_color = get_option( 'ai_widget_primary_color', '#76b4e3' );
$secondary_color = get_option( 'ai_widget_secondary_color', '#009bf0' );
$welcome_message = get_option( 'ai_widget_welcome_message', '¡Hola! 👋 ¿Cómo le gustaría interactuar?' );
$placeholder = get_option( 'ai_widget_placeholder', 'Escribe tu mensaje...' );
$assistant_name = get_option( 'ai_widget_assistant_name', 'Workfluz Assistant' );
$logo_svg = get_option( 'ai_widget_logo_svg', '' );

$provider = get_option( 'ai_widget_provider', 'vapi' );
$vapi_public_key = get_option( 'ai_widget_vapi_public_key', '' );
$vapi_assistant_id = get_option( 'ai_widget_vapi_assistant_id', '' );
$elevenlabs_api_key = get_option( 'ai_widget_elevenlabs_api_key', '' );
$elevenlabs_voice_id = get_option( 'ai_widget_elevenlabs_voice_id', '' );
$openai_api_key = get_option( 'ai_widget_openai_api_key', '' );

$personality = get_option( 'ai_widget_personality', 'friendly' );
$custom_prompt = get_option( 'ai_widget_custom_prompt', '' );

$use_openai_assistant = get_option( 'ai_widget_use_openai_assistant', '0' );
$openai_assistant_id = get_option( 'ai_widget_openai_assistant_id', '' );
$system_prompt = get_option( 'ai_widget_system_prompt', '' );

$voice_enabled = get_option( 'ai_widget_voice_enabled', true );
$text_enabled = get_option( 'ai_widget_text_enabled', true );
$free_limit = get_option( 'ai_widget_free_limit', 100 );

$chat_provider = get_option( 'ai_widget_chat_provider', 'openai' );
$n8n_webhook_url = get_option( 'ai_widget_n8n_webhook_url', '' );
?>

<div class="wrap ai-widget-admin-wrap">
    <h1>⚙️ <?php echo esc_html( get_admin_page_title() ); ?></h1>
    
    <div class="ai-widget-admin-container">
        <!-- Panel Lateral -->
        <div class="ai-widget-sidebar">
            <div class="ai-widget-card">
                <h3>🎯 Configuración Rápida</h3>
                <div class="ai-widget-quick-links">
                    <a href="#tab-general" class="quick-link">
                        <span class="dashicons dashicons-admin-generic"></span>
                        <div>
                            <strong>General</strong>
                            <small>Configuración básica</small>
                        </div>
                    </a>
                    <a href="#tab-provider" class="quick-link">
                        <span class="dashicons dashicons-admin-plugins"></span>
                        <div>
                            <strong>Proveedores IA</strong>
                            <small>VAPI, OpenAI, n8n</small>
                        </div>
                    </a>
                    <a href="#tab-system-prompt" class="quick-link">
                        <span class="dashicons dashicons-admin-customizer"></span>
                        <div>
                            <strong>System Prompt</strong>
                            <small>Personalidad del bot</small>
                        </div>
                    </a>
                    <a href="#tab-appearance" class="quick-link">
                        <span class="dashicons dashicons-admin-appearance"></span>
                        <div>
                            <strong>Apariencia</strong>
                            <small>Colores y estilos</small>
                        </div>
                    </a>
                    <a href="#tab-freemium" class="quick-link">
                        <span class="dashicons dashicons-chart-line"></span>
                        <div>
                            <strong>Freemium</strong>
                            <small>Límites y planes</small>
                        </div>
                    </a>
                </div>
            </div>

            <div class="ai-widget-card">
                <h3>📊 Estado del Sistema</h3>
                <div class="ai-widget-status">
                    <div class="status-item">
                        <span class="status-dot <?php echo $enabled ? 'active' : 'inactive'; ?>"></span>
                        <span>Widget: <?php echo $enabled ? 'Activo' : 'Inactivo'; ?></span>
                    </div>
                    <div class="status-item">
                        <span class="status-dot <?php echo !empty($vapi_public_key) ? 'active' : 'inactive'; ?>"></span>
                        <span>VAPI: <?php echo !empty($vapi_public_key) ? 'Configurado' : 'Pendiente'; ?></span>
                    </div>
                    <div class="status-item">
                        <span class="status-dot <?php echo !empty($openai_api_key) ? 'active' : 'inactive'; ?>"></span>
                        <span>OpenAI: <?php echo !empty($openai_api_key) ? 'Configurado' : 'Pendiente'; ?></span>
                    </div>
                    <div class="status-item">
                        <span class="status-dot <?php echo !empty($n8n_webhook_url) ? 'active' : 'inactive'; ?>"></span>
                        <span>n8n: <?php echo !empty($n8n_webhook_url) ? 'Configurado' : 'Pendiente'; ?></span>
                    </div>
                </div>
            </div>

            <div class="ai-widget-card">
                <h3>💡 Recursos</h3>
                <div class="ai-widget-resources">
                    <a href="https://vapi.ai/dashboard" target="_blank" class="resource-link">
                        <span class="dashicons dashicons-external"></span>
                        VAPI Dashboard
                    </a>
                    <a href="https://platform.openai.com/" target="_blank" class="resource-link">
                        <span class="dashicons dashicons-external"></span>
                        OpenAI Platform
                    </a>
                    <a href="https://elevenlabs.io/" target="_blank" class="resource-link">
                        <span class="dashicons dashicons-external"></span>
                        ElevenLabs
                    </a>
                    <a href="https://n8n.io/" target="_blank" class="resource-link">
                        <span class="dashicons dashicons-external"></span>
                        n8n Documentation
                    </a>
                </div>
            </div>
        </div>

        <!-- Contenido Principal -->
        <div class="ai-widget-main-content">
    <form method="post" action="">
        <?php wp_nonce_field( 'ai_widget_settings' ); ?>
        
        <h2 class="nav-tab-wrapper">
            <a href="#tab-general" class="nav-tab nav-tab-active">General</a>
            <a href="#tab-provider" class="nav-tab">Proveedor de IA</a>
            <a href="#tab-system-prompt" class="nav-tab">System Prompt</a>
            <a href="#tab-appearance" class="nav-tab">Apariencia</a>
            <a href="#tab-freemium" class="nav-tab">Freemium</a>
        </h2>

        <!-- TAB: GENERAL -->
        <div id="tab-general" class="tab-content" style="display:block;">
            <h2>Configuración General</h2>
            <table class="form-table">
                <tr>
                    <th>Habilitar Widget</th>
                    <td>
                        <label>
                            <input type="checkbox" name="ai_widget_enabled" value="1" <?php checked( $enabled ); ?>>
                            Mostrar el widget en el sitio
                        </label>
                    </td>
                </tr>
                <tr>
                    <th>Nombre del Asistente</th>
                    <td>
                        <input type="text" name="ai_widget_assistant_name" value="<?php echo esc_attr( $assistant_name ); ?>" class="regular-text">
                        <p class="description">Nombre que aparecerá en el widget</p>
                    </td>
                </tr>
                <tr>
                    <th>Mensaje de Bienvenida</th>
                    <td>
                        <input type="text" name="ai_widget_welcome_message" value="<?php echo esc_attr( $welcome_message ); ?>" class="large-text">
                    </td>
                </tr>
                <tr>
                    <th>Placeholder</th>
                    <td>
                        <input type="text" name="ai_widget_placeholder" value="<?php echo esc_attr( $placeholder ); ?>" class="large-text">
                    </td>
                </tr>
                <tr>
                    <th>Modos Habilitados</th>
                    <td>
                        <label>
                            <input type="checkbox" name="ai_widget_voice_enabled" value="1" <?php checked( $voice_enabled ); ?>>
                            Modo Voz (VAPI/ElevenLabs)
                        </label><br>
                        <label>
                            <input type="checkbox" name="ai_widget_text_enabled" value="1" <?php checked( $text_enabled ); ?>>
                            Modo Chat de Texto (OpenAI)
                        </label>
                        <p class="description">Selecciona los modos que quieres ofrecer a tus usuarios</p>
                    </td>
                </tr>
            </table>
        </div>

        <!-- TAB: PROVEEDOR DE IA -->
        <div id="tab-provider" class="tab-content">
            <h2>Configuración de Proveedores de IA</h2>
            
            <h3>🎤 VAPI (Voice AI)</h3>
            <table class="form-table">
                <tr>
                    <th>Public Key</th>
                    <td>
                        <input type="text" name="ai_widget_vapi_public_key" value="<?php echo esc_attr( $vapi_public_key ); ?>" class="large-text">
                        <p class="description">Public key de tu cuenta VAPI</p>
                    </td>
                </tr>
                <tr>
                    <th>Assistant ID</th>
                    <td>
                        <input type="text" name="ai_widget_vapi_assistant_id" value="<?php echo esc_attr( $vapi_assistant_id ); ?>" class="large-text">
                        <p class="description">ID del asistente creado en VAPI Dashboard</p>
                    </td>
                </tr>
            </table>

            <hr style="margin: 30px 0;">

            <h3>🔊 ElevenLabs (Text-to-Speech)</h3>
            <table class="form-table">
                <tr>
                    <th>API Key</th>
                    <td>
                        <input type="text" name="ai_widget_elevenlabs_api_key" value="<?php echo esc_attr( $elevenlabs_api_key ); ?>" class="large-text">
                    </td>
                </tr>
                <tr>
                    <th>Voice ID</th>
                    <td>
                        <input type="text" name="ai_widget_elevenlabs_voice_id" value="<?php echo esc_attr( $elevenlabs_voice_id ); ?>" class="large-text">
                    </td>
                </tr>
            </table>

            <hr style="margin: 30px 0;">

            <h3>💬 Proveedor de Chat de Texto</h3>
            <table class="form-table">
                <tr>
                    <th>Seleccionar Proveedor</th>
                    <td>
                        <select name="ai_widget_chat_provider" id="ai_widget_chat_provider">
                            <option value="openai" <?php selected( $chat_provider, 'openai' ); ?>>OpenAI (ChatGPT)</option>
                            <option value="n8n" <?php selected( $chat_provider, 'n8n' ); ?>>n8n Webhook</option>
                        </select>
                        <p class="description">Elige el proveedor que procesará las conversaciones de texto</p>
                    </td>
                </tr>
            </table>

            <!-- OpenAI Chat Section -->
            <div id="openai-chat-section" style="<?php echo $chat_provider === 'openai' ? '' : 'display:none;'; ?>">
                <h3>🤖 OpenAI (Chat de Texto)</h3>
                <table class="form-table">
                    <tr>
                        <th>API Key</th>
                        <td>
                            <input type="text" name="ai_widget_openai_api_key" id="ai_widget_openai_api_key" value="<?php echo esc_attr( $openai_api_key ); ?>" class="large-text">
                            <p class="description">Necesario para el modo de chat de texto con OpenAI</p>
                        </td>
                    </tr>
                    <tr>
                        <th>Personalidad (Legacy)</th>
                        <td>
                            <select name="ai_widget_personality" id="ai_widget_personality">
                                <option value="friendly" <?php selected( $personality, 'friendly' ); ?>>Amigable</option>
                                <option value="professional" <?php selected( $personality, 'professional' ); ?>>Profesional</option>
                                <option value="casual" <?php selected( $personality, 'casual' ); ?>>Casual</option>
                                <option value="technical" <?php selected( $personality, 'technical' ); ?>>Técnico</option>
                                <option value="sales" <?php selected( $personality, 'sales' ); ?>>Ventas</option>
                                <option value="support" <?php selected( $personality, 'support' ); ?>>Soporte</option>
                                <option value="custom" <?php selected( $personality, 'custom' ); ?>>Personalizado</option>
                            </select>
                            <p class="description">⚠️ Legacy: Usa la pestaña "System Prompt" para configuración avanzada</p>
                        </td>
                    </tr>
                    <tr id="custom-prompt-row" style="<?php echo $personality === 'custom' ? '' : 'display:none;'; ?>">
                        <th>Prompt Personalizado (Legacy)</th>
                        <td>
                            <textarea name="ai_widget_custom_prompt" rows="4" class="large-text"><?php echo esc_textarea( $custom_prompt ); ?></textarea>
                            <p class="description">⚠️ Legacy: Usa la pestaña "System Prompt" para la nueva funcionalidad</p>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- n8n Webhook Section -->
            <div id="n8n-chat-section" style="<?php echo $chat_provider === 'n8n' ? '' : 'display:none;'; ?>">
                <h3>🔗 n8n Webhook</h3>
                <table class="form-table">
                    <tr>
                        <th>Webhook URL</th>
                        <td>
                            <input type="url" name="ai_widget_n8n_webhook_url" id="ai_widget_n8n_webhook_url" value="<?php echo esc_attr( $n8n_webhook_url ); ?>" class="large-text" placeholder="https://tu-instancia.n8n.cloud/webhook/tu-webhook-id">
                            <p class="description">
                                URL completa del webhook de n8n que procesará los mensajes<br>
                                <strong>Ejemplo:</strong> https://your-instance.app.n8n.cloud/webhook/ai-chat
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th>Formato de Request</th>
                        <td>
                            <p class="description" style="background:#f0f6fc; padding:12px; border-left:3px solid #2196F3; border-radius:4px; font-family:monospace; font-size:13px;">
                                <strong>POST Request a tu webhook:</strong><br>
                                {<br>
                                &nbsp;&nbsp;"message": "mensaje del usuario",<br>
                                &nbsp;&nbsp;"session_id": "id-de-sesión",<br>
                                &nbsp;&nbsp;"conversation_history": [...]<br>
                                }<br><br>
                                <strong>Respuesta esperada:</strong><br>
                                {<br>
                                &nbsp;&nbsp;"response": "respuesta del asistente"<br>
                                }
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th>Ejemplo de Workflow</th>
                        <td>
                            <p class="description">
                                <strong>Nodos recomendados:</strong><br>
                                1. <strong>Webhook</strong> (trigger) - Recibe el mensaje<br>
                                2. <strong>OpenAI Chat Model</strong> (o cualquier LLM) - Procesa el mensaje<br>
                                3. <strong>Respond to Webhook</strong> - Devuelve la respuesta<br>
                                <br>
                                💡 <strong>Tip:</strong> Puedes usar cualquier lógica en n8n (bases de datos, APIs externas, RAG, etc.)
                            </p>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- TAB: SYSTEM PROMPT -->
        <div id="tab-system-prompt" class="tab-content">
            <h2>🤖 System Prompt</h2>
            <p class="description" style="font-size:14px; margin-bottom:20px;">
                Configura cómo debe comportarse el asistente en el <strong>modo de chat de texto</strong>. 
                Para voz (VAPI/ElevenLabs), configura el asistente directamente en sus plataformas.
            </p>

            <table class="form-table">
                <tr>
                    <th>Método de Configuración</th>
                    <td>
                        <label>
                            <input type="radio" name="ai_widget_use_openai_assistant" value="0" <?php checked( $use_openai_assistant, '0' ); ?>>
                            Usar System Prompt personalizado
                        </label><br>
                        <label>
                            <input type="radio" name="ai_widget_use_openai_assistant" value="1" <?php checked( $use_openai_assistant, '1' ); ?>>
                            Usar Asistente de OpenAI (Assistants API)
                        </label>
                    </td>
                </tr>
            </table>

            <!-- System Prompt Section -->
            <div id="system-prompt-section" style="<?php echo $use_openai_assistant === '1' ? 'display:none;' : ''; ?>">
                <h3>✍️ System Prompt Personalizado</h3>
                <table class="form-table">
                    <tr>
                        <th>System Prompt</th>
                        <td>
                            <textarea name="ai_widget_system_prompt" rows="12" class="large-text" style="font-family:monospace;"><?php echo esc_textarea( $system_prompt ); ?></textarea>
                            <p class="description">
                                <strong>Tips:</strong><br>
                                • Define el rol y personalidad del asistente<br>
                                • Establece reglas claras de comportamiento<br>
                                • Especifica qué NO debe hacer<br>
                                • Usa un lenguaje claro y específico<br>
                                <br>
                                <strong>Ejemplo:</strong> "Eres un asistente amigable y servicial. Respondes de manera clara y concisa. Siempre mantienes un tono profesional pero cercano. Usa emojis moderadamente para ser más expresivo."
                            </p>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- OpenAI Assistant Section -->
            <div id="assistant-section" style="<?php echo $use_openai_assistant === '1' ? '' : 'display:none;'; ?>">
                <h3>🎓 Asistente de OpenAI</h3>
                
                <?php if ( empty( $openai_api_key ) ) : ?>
                    <div class="notice notice-warning inline">
                        <p>⚠️ Configura primero tu API key de OpenAI en la pestaña "Proveedor de IA"</p>
                    </div>
                <?php endif; ?>

                <table class="form-table">
                    <tr>
                        <th>Seleccionar Asistente</th>
                        <td>
                            <select name="ai_widget_openai_assistant_id" id="ai_widget_openai_assistant_id" class="large-text">
                                <option value="">-- Cargando asistentes... --</option>
                            </select>
                            <button type="button" id="refresh-assistants-btn" class="button" style="margin-left:10px;">🔄 Recargar</button>
                            <p class="description">
                                Los asistentes son configurados en <a href="https://platform.openai.com/assistants" target="_blank">OpenAI Platform</a>
                            </p>
                            
                            <div id="assistant-loading" style="display:none; margin-top:10px;">
                                <span class="spinner is-active" style="float:none; margin:0 5px 0 0;"></span>
                                Cargando asistentes de OpenAI...
                            </div>
                            
                            <div id="assistant-error" style="display:none; margin-top:10px;" class="notice notice-error inline">
                                <p></p>
                            </div>

                            <div id="assistant-info" style="display:none; margin-top:15px; padding:15px; background:#f0f6fc; border-left:3px solid #2196F3; border-radius:4px;">
                                <h4 style="margin-top:0;">📋 Información del Asistente</h4>
                                <p><strong>Nombre:</strong> <span id="assistant-info-name"></span></p>
                                <p><strong>Modelo:</strong> <span id="assistant-info-model"></span></p>
                                <p><strong>Instrucciones:</strong><br><span id="assistant-info-instructions" style="font-size:12px; color:#666;"></span></p>
                            </div>
                        </td>
                    </tr>
                </table>

                <div class="notice notice-info inline" style="margin-top:20px;">
                    <p>
                        <strong>💡 ¿Cuándo usar un Asistente de OpenAI?</strong><br>
                        • Necesitas que el asistente consulte documentos (PDFs, manuales, catálogos)<br>
                        • Quieres usar Code Interpreter para análisis de datos<br>
                        • Requieres integración con APIs externas (Function Calling)<br>
                        • Prefieres gestionar todo desde OpenAI Platform<br>
                        <br>
                        <a href="https://platform.openai.com/docs/assistants/overview" target="_blank">📚 Documentación de OpenAI Assistants</a>
                    </p>
                </div>
            </div>
        </div>

        <!-- TAB: APARIENCIA -->
        <div id="tab-appearance" class="tab-content">
            <h2>Apariencia del Widget</h2>
            <table class="form-table">
                <tr>
                    <th>Posición</th>
                    <td>
                        <select name="ai_widget_position">
                            <option value="bottom-right" <?php selected( $position, 'bottom-right' ); ?>>Abajo Derecha</option>
                            <option value="bottom-left" <?php selected( $position, 'bottom-left' ); ?>>Abajo Izquierda</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>Color Primario</th>
                    <td>
                        <input type="text" name="ai_widget_primary_color" value="<?php echo esc_attr( $primary_color ); ?>" class="color-picker">
                    </td>
                </tr>
                <tr>
                    <th>Color Secundario</th>
                    <td>
                        <input type="text" name="ai_widget_secondary_color" value="<?php echo esc_attr( $secondary_color ); ?>" class="color-picker">
                    </td>
                </tr>
                <tr>
                    <th>Logo SVG</th>
                    <td>
                        <textarea name="ai_widget_logo_svg" rows="6" class="large-text" style="font-family:monospace;"><?php echo esc_textarea( $logo_svg ); ?></textarea>
                        <p class="description">Código SVG del logo (opcional)</p>
                    </td>
                </tr>
            </table>
        </div>

        <!-- TAB: FREEMIUM -->
        <div id="tab-freemium" class="tab-content">
            <h2>Configuración Freemium</h2>
            <table class="form-table">
                <tr>
                    <th>Límite Plan Gratuito</th>
                    <td>
                        <input type="number" name="ai_widget_free_limit" value="<?php echo esc_attr( $free_limit ); ?>" min="1" step="1">
                        <p class="description">Número de mensajes gratuitos por usuario</p>
                    </td>
                </tr>
            </table>
            <p class="description">
                <strong>Nota:</strong> La integración de pagos (Stripe) está pendiente de implementación.
            </p>
        </div>

        <p class="submit">
            <input type="submit" name="ai_widget_settings_submit" class="button button-primary" value="Guardar Cambios">
        </p>
    </form>
        </div><!-- .ai-widget-main-content -->
    </div><!-- .ai-widget-admin-container -->
</div><!-- .wrap -->

<style>
.ai-widget-admin-wrap {
    margin-right: 20px;
}

.ai-widget-admin-container {
    display: flex;
    gap: 20px;
    margin-top: 20px;
}

.ai-widget-sidebar {
    flex: 0 0 280px;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.ai-widget-main-content {
    flex: 1;
    min-width: 0;
}

.ai-widget-card {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

.ai-widget-card h3 {
    margin: 0 0 15px 0;
    font-size: 14px;
    font-weight: 600;
    color: #333;
    display: flex;
    align-items: center;
    gap: 8px;
}

.ai-widget-quick-links {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.quick-link {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background: #f8f9fa;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    text-decoration: none;
    color: #374151;
    transition: all 0.2s ease;
}

.quick-link:hover {
    background: #2271b1;
    border-color: #2271b1;
    color: #fff;
    transform: translateX(4px);
}

.quick-link.active {
    background: #2271b1;
    border-color: #2271b1;
    color: #fff;
}

.quick-link.active small {
    opacity: 0.9;
}

.quick-link .dashicons {
    font-size: 20px;
    width: 20px;
    height: 20px;
}

.quick-link div {
    flex: 1;
}

.quick-link strong {
    display: block;
    font-size: 13px;
    font-weight: 600;
}

.quick-link small {
    display: block;
    font-size: 11px;
    opacity: 0.7;
    margin-top: 2px;
}

.ai-widget-status {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.status-item {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 13px;
    color: #555;
}

.status-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    flex-shrink: 0;
}

.status-dot.active {
    background: #10b981;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
}

.status-dot.inactive {
    background: #94a3b8;
}

.ai-widget-resources {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.resource-link {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 10px;
    background: #f8f9fa;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    text-decoration: none;
    color: #2271b1;
    font-size: 13px;
    transition: all 0.2s ease;
}

.resource-link:hover {
    background: #e5e7eb;
    border-color: #cbd5e1;
    color: #135e96;
}

.resource-link .dashicons {
    font-size: 16px;
    width: 16px;
    height: 16px;
}

/* Tabs en el contenido principal */
.tab-content { 
    display: none; 
    padding: 20px 0; 
}

.nav-tab-wrapper { 
    margin-bottom: 0; 
    background: #fff;
    border: 1px solid #ddd;
    border-bottom: none;
    border-radius: 8px 8px 0 0;
    padding: 10px 20px 0;
}

.tab-content {
    background: #fff;
    border: 1px solid #ddd;
    border-top: none;
    border-radius: 0 0 8px 8px;
    padding: 30px;
}

/* Responsive */
@media (max-width: 1280px) {
    .ai-widget-admin-container {
        flex-direction: column;
    }
    
    .ai-widget-sidebar {
        flex: 1;
        flex-direction: row;
        flex-wrap: wrap;
    }
    
    .ai-widget-card {
        flex: 1;
        min-width: 250px;
    }
}

@media (max-width: 768px) {
    .ai-widget-sidebar {
        flex-direction: column;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    // Función para cambiar de tab
    function switchTab(tabId) {
        $('.nav-tab').removeClass('nav-tab-active');
        $('.tab-content').hide();
        $('.quick-link').removeClass('active');
        
        // Activar tab y contenido
        $('.nav-tab[href="' + tabId + '"]').addClass('nav-tab-active');
        $(tabId).show();
        $('.quick-link[href="' + tabId + '"]').addClass('active');
    }
    
    // Tabs del header
    $('.nav-tab').on('click', function(e) {
        e.preventDefault();
        switchTab($(this).attr('href'));
    });
    
    // Enlaces del sidebar
    $('.quick-link').on('click', function(e) {
        e.preventDefault();
        switchTab($(this).attr('href'));
        
        // Scroll suave al contenido
        $('html, body').animate({
            scrollTop: $('.ai-widget-main-content').offset().top - 32
        }, 300);
    });

    // Color Pickers
    $('.color-picker').wpColorPicker();

    // Cambio de proveedor
    $('select[name="ai_widget_provider"]').on('change', function() {
        $('.provider-config').hide();
        $('#provider-' + $(this).val()).show();
    }).trigger('change');

    // Cambio de proveedor de chat
    $('#ai_widget_chat_provider').on('change', function() {
        if ($(this).val() === 'openai') {
            $('#openai-chat-section').show();
            $('#n8n-chat-section').hide();
        } else if ($(this).val() === 'n8n') {
            $('#openai-chat-section').hide();
            $('#n8n-chat-section').show();
        }
    });

    // Mostrar/ocultar prompt personalizado
    $('#ai_widget_personality').on('change', function() {
        if ($(this).val() === 'custom') {
            $('#custom-prompt-row').show();
        } else {
            $('#custom-prompt-row').hide();
        }
    });

    // System Prompt: Cambio entre System Prompt y Asistente
    $('input[name="ai_widget_use_openai_assistant"]').on('change', function() {
        if ($(this).val() === '1') {
            $('#system-prompt-section').hide();
            $('#assistant-section').show();
            // Cargar asistentes si es necesario
            if ($('#ai_widget_openai_assistant_id option').length <= 1) {
                loadOpenAIAssistants();
            }
        } else {
            $('#system-prompt-section').show();
            $('#assistant-section').hide();
        }
    });

    // Cargar asistentes de OpenAI
    function loadOpenAIAssistants() {
        var apiKey = $('#ai_widget_openai_api_key').val();
        if (!apiKey) {
            return;
        }

        $('#assistant-loading').show();
        $('#assistant-error').hide();
        $('#assistant-info').hide();

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'ai_widget_load_assistants',
                nonce: '<?php echo wp_create_nonce( "ai_widget_load_assistants" ); ?>',
                api_key: apiKey
            },
            success: function(response) {
                $('#assistant-loading').hide();
                
                if (response.success) {
                    var assistants = response.data.assistants;
                    var select = $('#ai_widget_openai_assistant_id');
                    var currentValue = '<?php echo esc_js( $openai_assistant_id ); ?>';
                    
                    select.empty();
                    select.append('<option value="">-- Seleccionar asistente --</option>');
                    
                    if (assistants.length === 0) {
                        select.append('<option value="" disabled>No hay asistentes disponibles</option>');
                        $('#assistant-error p').text('No se encontraron asistentes. Crea uno en OpenAI Platform.');
                        $('#assistant-error').show();
                    } else {
                        $.each(assistants, function(i, assistant) {
                            var option = $('<option></option>')
                                .val(assistant.id)
                                .text(assistant.name + ' (' + assistant.model + ')')
                                .data('assistant', assistant);
                            
                            if (assistant.id === currentValue) {
                                option.prop('selected', true);
                            }
                            
                            select.append(option);
                        });
                        
                        // Si hay un asistente seleccionado, mostrar su info
                        if (currentValue) {
                            select.trigger('change');
                        }
                    }
                } else {
                    $('#assistant-error p').text('Error: ' + response.data.message);
                    $('#assistant-error').show();
                }
            },
            error: function(xhr, status, error) {
                $('#assistant-loading').hide();
                $('#assistant-error p').text('Error de conexión: ' + error);
                $('#assistant-error').show();
            }
        });
    }

    // Mostrar información del asistente seleccionado
    $('#ai_widget_openai_assistant_id').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        var assistant = selectedOption.data('assistant');
        
        if (assistant) {
            $('#assistant-info-name').text(assistant.name);
            $('#assistant-info-model').text(assistant.model);
            $('#assistant-info-instructions').text(assistant.instructions || 'Sin instrucciones específicas');
            $('#assistant-info').slideDown();
        } else {
            $('#assistant-info').slideUp();
        }
    });

    // Botón recargar asistentes
    $('#refresh-assistants-btn').on('click', function() {
        loadOpenAIAssistants();
    });

    // Cargar asistentes al cargar la página si está seleccionado ese modo
    if ($('input[name="ai_widget_use_openai_assistant"]:checked').val() === '1') {
        loadOpenAIAssistants();
    }
});
</script>
