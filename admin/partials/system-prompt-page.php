<?php
/**
 * Página de configuración de System Prompt.
 *
 * @package AI_Voice_Text_Widget
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Guardar configuración
if ( isset( $_POST['ai_widget_system_prompt_submit'] ) && check_admin_referer( 'ai_widget_system_prompt' ) ) {
    update_option( 'ai_widget_use_openai_assistant', sanitize_text_field( $_POST['ai_widget_use_openai_assistant'] ) );
    update_option( 'ai_widget_system_prompt', sanitize_textarea_field( $_POST['ai_widget_system_prompt'] ) );
    update_option( 'ai_widget_openai_assistant_id', sanitize_text_field( $_POST['ai_widget_openai_assistant_id'] ) );

    echo '<div class="notice notice-success"><p>✅ System Prompt guardado correctamente</p></div>';
}

// Obtener valores actuales
$use_openai_assistant = get_option( 'ai_widget_use_openai_assistant', '0' );
$system_prompt = get_option( 'ai_widget_system_prompt', '' );
$openai_assistant_id = get_option( 'ai_widget_openai_assistant_id', '' );
$openai_api_key = get_option( 'ai_widget_openai_api_key', '' );
?>

<div class="wrap">
    <h1>🤖 System Prompt</h1>
    <p class="description">Configura cómo debe comportarse el asistente en el modo de chat de texto.</p>
    
    <?php if ( empty( $openai_api_key ) ) : ?>
        <div class="notice notice-warning">
            <p>⚠️ <strong>API Key no configurada:</strong> Primero configura tu OpenAI API Key en <a href="<?php echo admin_url( 'admin.php?page=ai-widget-providers' ); ?>">Proveedores IA</a>.</p>
        </div>
    <?php endif; ?>
    
    <form method="post" action="">
        <?php wp_nonce_field( 'ai_widget_system_prompt' ); ?>
        
        <table class="form-table">
            <tr>
                <th scope="row">Método de Configuración</th>
                <td>
                    <fieldset>
                        <label>
                            <input type="radio" name="ai_widget_use_openai_assistant" value="0" <?php checked( $use_openai_assistant, '0' ); ?>>
                            <strong>System Prompt Personalizado</strong>
                            <p class="description">Escribe tu propio prompt para definir el comportamiento del asistente.</p>
                        </label>
                        <br><br>
                        <label>
                            <input type="radio" name="ai_widget_use_openai_assistant" value="1" <?php checked( $use_openai_assistant, '1' ); ?>>
                            <strong>Asistente de OpenAI</strong>
                            <p class="description">Usa un asistente previamente configurado en OpenAI Platform con acceso a archivos y herramientas.</p>
                        </label>
                    </fieldset>
                </td>
            </tr>
        </table>

        <!-- System Prompt Section -->
        <div id="system-prompt-section" style="<?php echo $use_openai_assistant === '1' ? 'display:none;' : ''; ?>">
            <hr>
            <h2 class="title">✍️ System Prompt Personalizado</h2>
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="ai_widget_system_prompt">Prompt del Sistema</label>
                    </th>
                    <td>
                        <textarea name="ai_widget_system_prompt" id="ai_widget_system_prompt" rows="15" class="large-text code" style="font-family: 'Courier New', monospace;"><?php echo esc_textarea( $system_prompt ); ?></textarea>
                        
                        <div style="background: #e7f3ff; border-left: 4px solid #2271b1; padding: 15px; margin-top: 15px; border-radius: 4px;">
                            <h4 style="margin-top: 0;">💡 Tips para un buen System Prompt:</h4>
                            <ul style="margin: 0;">
                                <li><strong>Define el rol:</strong> "Eres un asistente de ventas experto en..."</li>
                                <li><strong>Establece el tono:</strong> Amigable, profesional, técnico, casual, etc.</li>
                                <li><strong>Marca límites:</strong> "No respondas preguntas sobre política o religión"</li>
                                <li><strong>Formato de respuesta:</strong> "Responde en párrafos cortos", "Usa viñetas"</li>
                                <li><strong>Contexto:</strong> Información sobre tu empresa, productos, servicios</li>
                            </ul>
                        </div>

                        <details style="margin-top: 15px; background: #f9f9f9; padding: 15px; border-radius: 4px;">
                            <summary style="cursor: pointer; font-weight: 600;">📝 Ver ejemplo de System Prompt</summary>
                            <pre style="background: #fff; padding: 15px; margin-top: 10px; border-radius: 4px; overflow-x: auto; font-size: 13px; line-height: 1.6;">Eres un asistente virtual amigable y profesional llamado WorkfluzBot.

Tu rol es ayudar a los visitantes del sitio web con información sobre nuestros servicios de desarrollo web y marketing digital.

PERSONALIDAD:
- Amigable pero profesional
- Claro y conciso en tus respuestas
- Empático con las necesidades del usuario
- Entusiasta sobre nuestros servicios

REGLAS:
1. Responde siempre en español
2. Sé breve: máximo 3 párrafos por respuesta
3. Usa emojis moderadamente para ser más expresivo
4. Si no sabes algo, sé honesto y ofrece conectar con un humano
5. No inventes información sobre precios o servicios

INFORMACIÓN DE LA EMPRESA:
- Nombre: Workfluz
- Servicios: Desarrollo web, apps móviles, marketing digital
- Ubicación: España
- Horario de atención: Lunes a Viernes, 9:00 - 18:00 CET

EJEMPLOS DE RESPUESTA:
Usuario: "¿Qué servicios ofrecen?"
Tú: "¡Hola! 👋 En Workfluz nos especializamos en tres áreas principales:

1. 🌐 Desarrollo Web - Sitios profesionales y e-commerce
2. 📱 Apps Móviles - iOS y Android
3. 📊 Marketing Digital - SEO, SEM y redes sociales

¿Te interesa algún servicio en particular?"</pre>
                        </details>
                    </td>
                </tr>
            </table>
        </div>

        <!-- OpenAI Assistant Section -->
        <div id="assistant-section" style="<?php echo $use_openai_assistant === '1' ? '' : 'display:none;'; ?>">
            <hr>
            <h2 class="title">🎓 Asistente de OpenAI</h2>
            
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="ai_widget_openai_assistant_id">Seleccionar Asistente</label>
                    </th>
                    <td>
                        <select name="ai_widget_openai_assistant_id" id="ai_widget_openai_assistant_id" class="large-text">
                            <option value="">-- Cargando asistentes... --</option>
                        </select>
                        <button type="button" id="refresh-assistants-btn" class="button" style="margin-left: 10px;">
                            <span class="dashicons dashicons-update" style="margin-top: 3px;"></span> Recargar
                        </button>
                        
                        <p class="description">
                            Los asistentes se configuran en <a href="https://platform.openai.com/assistants" target="_blank">OpenAI Platform</a>
                        </p>
                        
                        <div id="assistant-loading" style="display:none; margin-top: 10px;">
                            <span class="spinner is-active" style="float: none; margin: 0 5px 0 0;"></span>
                            Cargando asistentes de OpenAI...
                        </div>
                        
                        <div id="assistant-error" style="display:none; margin-top: 10px;" class="notice notice-error inline">
                            <p></p>
                        </div>

                        <div id="assistant-info" style="display:none; margin-top: 15px; padding: 15px; background: #f0f6fc; border-left: 3px solid #2196F3; border-radius: 4px;">
                            <h4 style="margin-top: 0;">📋 Información del Asistente</h4>
                            <p><strong>Nombre:</strong> <span id="assistant-info-name"></span></p>
                            <p><strong>Modelo:</strong> <span id="assistant-info-model"></span></p>
                            <p><strong>Instrucciones:</strong><br><span id="assistant-info-instructions" style="font-size: 12px; color: #666;"></span></p>
                        </div>
                    </td>
                </tr>
            </table>

            <div class="notice notice-info inline" style="margin-top: 20px;">
                <p>
                    <strong>💡 ¿Cuándo usar un Asistente de OpenAI?</strong><br>
                    • Necesitas que consulte documentos (PDFs, catálogos, manuales)<br>
                    • Quieres usar Code Interpreter para análisis de datos<br>
                    • Requieres integración con APIs externas (Function Calling)<br>
                    • Prefieres gestionar todo desde OpenAI Platform<br>
                    <br>
                    <a href="https://platform.openai.com/docs/assistants/overview" target="_blank">📚 Documentación de OpenAI Assistants</a>
                </p>
            </div>
        </div>

        <p class="submit">
            <input type="submit" name="ai_widget_system_prompt_submit" class="button button-primary" value="Guardar Configuración">
        </p>
    </form>
</div>

<script>
jQuery(document).ready(function($) {
    // Toggle entre System Prompt y Assistant
    $('input[name="ai_widget_use_openai_assistant"]').on('change', function() {
        if ($(this).val() === '1') {
            $('#system-prompt-section').slideUp();
            $('#assistant-section').slideDown();
            if ($('#ai_widget_openai_assistant_id option').length <= 1) {
                loadOpenAIAssistants();
            }
        } else {
            $('#system-prompt-section').slideDown();
            $('#assistant-section').slideUp();
        }
    });

    // Cargar asistentes de OpenAI
    function loadOpenAIAssistants() {
        var apiKey = '<?php echo esc_js( $openai_api_key ); ?>';
        if (!apiKey) {
            $('#assistant-error p').text('Configura primero tu OpenAI API Key en la sección Proveedores IA.');
            $('#assistant-error').show();
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

    // Mostrar info del asistente
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

    // Botón recargar
    $('#refresh-assistants-btn').on('click', function() {
        loadOpenAIAssistants();
    });

    // Cargar al inicio si está seleccionado
    if ($('input[name="ai_widget_use_openai_assistant"]:checked').val() === '1') {
        loadOpenAIAssistants();
    }
});
</script>
