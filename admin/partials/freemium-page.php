<?php
/**
 * Página de configuración Freemium (Nuevo Modelo).
 *
 * @package AI_Voice_Text_Widget
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Cargar clase Freemium
require_once AI_VOICE_TEXT_WIDGET_PLUGIN_DIR . 'includes/class-freemium.php';

// Obtener estadísticas
$stats = AI_Widget_Freemium::get_stats();
$config = AI_Widget_Freemium::get_installation_config();

// Calcular porcentajes
$text_percentage = $stats['plan'] === 'premium' ? 100 : ( $stats['text_messages']['limit'] > 0 ? ( $stats['text_messages']['used'] / $stats['text_messages']['limit'] ) * 100 : 0 );
$voice_percentage = $stats['plan'] === 'premium' ? 100 : ( $stats['voice_minutes']['limit'] > 0 ? ( $stats['voice_minutes']['used'] / $stats['voice_minutes']['limit'] ) * 100 : 0 );

// Calcular días restantes del período
$days_remaining = 0;
if ( $stats['period']['end'] ) {
    $now = new DateTime();
    $end = new DateTime( $stats['period']['end'] );
    $diff = $now->diff( $end );
    $days_remaining = $diff->days;
}
?>

<div class="wrap">
    <h1>💎 Plan y Facturación</h1>
    <p class="description">Gestiona tu plan y controla el uso mensual de tu instalación.</p>
    
    <!-- Estado del Plan -->
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; border-radius: 12px; margin: 30px 0; box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px;">Plan Actual</div>
                <div style="font-size: 42px; font-weight: 700; margin-bottom: 10px;">
                    <?php echo $stats['plan'] === 'premium' ? '✨ PREMIUM' : '🆓 GRATIS'; ?>
                </div>
                <?php if ( $stats['plan'] === 'free' ) : ?>
                    <div style="font-size: 14px; opacity: 0.9;">
                        Se renueva en <strong><?php echo $days_remaining; ?> días</strong> (<?php echo date( 'd/m/Y', strtotime( $stats['period']['end'] ) ); ?>)
                    </div>
                <?php else : ?>
                    <div style="font-size: 14px; opacity: 0.9;">
                        ✅ Uso ilimitado • Sin branding • Soporte prioritario
                    </div>
                <?php endif; ?>
            </div>
            <div style="text-align: right;">
                <?php if ( $stats['plan'] === 'free' ) : ?>
                    <a href="#upgrade" class="button button-hero" style="background: white; color: #667eea; border: none; font-weight: 600; padding: 12px 30px; font-size: 16px;">
                        🚀 Actualizar a Premium
                    </a>
                <?php else : ?>
                    <div style="background: rgba(255,255,255,0.2); padding: 15px 25px; border-radius: 8px;">
                        <div style="font-size: 13px; opacity: 0.9; margin-bottom: 5px;">Estado</div>
                        <div style="font-size: 18px; font-weight: 600;">✅ Activo</div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Gestión de License Key -->
    <div style="background: white; padding: 30px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin: 30px 0;">
        <h2 style="margin-top: 0;">🔑 License Key Premium</h2>
        <p class="description">Activa tu plan Premium usando una license key válida de Workfluz.</p>
        
        <?php
        $has_license = ! empty( $stats['license_key'] );
        $license_active = $stats['license_status'] === 'active';
        ?>
        
        <?php if ( $has_license ) : ?>
            <!-- Licencia Existente -->
            <div style="background: <?php echo $license_active ? '#d1fae5' : '#fee2e2'; ?>; border-left: 4px solid <?php echo $license_active ? '#059669' : '#dc2626'; ?>; padding: 20px; border-radius: 4px; margin-bottom: 20px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-size: 14px; font-weight: 600; color: <?php echo $license_active ? '#059669' : '#dc2626'; ?>; margin-bottom: 8px;">
                            <?php echo $license_active ? '✅ Licencia Activa' : '❌ Licencia Inválida'; ?>
                        </div>
                        <div style="font-family: monospace; font-size: 13px; color: #374151; margin-bottom: 5px;">
                            <strong>Key:</strong> <?php echo esc_html( substr( $stats['license_key'], 0, 20 ) . '...' . substr( $stats['license_key'], -10 ) ); ?>
                        </div>
                        <?php if ( $license_active && $stats['license_expires_at'] ) : ?>
                            <div style="font-size: 13px; color: #6b7280;">
                                <strong>Expira:</strong> <?php echo date( 'd/m/Y', strtotime( $stats['license_expires_at'] ) ); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div>
                        <button type="button" id="revalidate-license-btn" class="button" style="margin-right: 10px;">
                            🔄 Revalidar
                        </button>
                        <button type="button" id="deactivate-license-btn" class="button button-secondary">
                            🗑️ Desactivar
                        </button>
                    </div>
                </div>
            </div>
        <?php else : ?>
            <!-- Sin Licencia -->
            <div style="background: #f9fafb; border: 2px dashed #d1d5db; padding: 30px; border-radius: 8px; margin-bottom: 20px; text-align: center;">
                <div style="font-size: 48px; margin-bottom: 15px;">🔓</div>
                <p style="color: #6b7280; font-size: 16px; margin-bottom: 20px;">
                    No tienes una license key activada.<br>
                    Ingresa tu license key para desbloquear el plan Premium.
                </p>
            </div>
        <?php endif; ?>
        
        <!-- Formulario de Activación -->
        <div id="license-form-container" style="<?php echo $has_license ? 'display: none;' : ''; ?>">
            <form id="license-activation-form">
                <table class="form-table" role="presentation">
                    <tbody>
                        <tr>
                            <th scope="row">
                                <label for="license_key">License Key</label>
                            </th>
                            <td>
                                <input 
                                    type="text" 
                                    id="license_key" 
                                    name="license_key" 
                                    class="regular-text code" 
                                    placeholder="XXXX-XXXX-XXXX-XXXX-XXXX"
                                    style="width: 100%; max-width: 500px; font-family: monospace;"
                                    required
                                />
                                <p class="description">
                                    Ingresa la license key que recibiste de Workfluz al comprar el plan Premium.
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>
                
                <p class="submit">
                    <button type="submit" class="button button-primary">
                        🔑 Activar License Key
                    </button>
                    <?php if ( $has_license ) : ?>
                        <button type="button" id="cancel-license-edit-btn" class="button button-secondary" style="margin-left: 10px;">
                            Cancelar
                        </button>
                    <?php endif; ?>
                </p>
                
                <div id="license-activation-message" style="display: none; margin-top: 20px;"></div>
            </form>
        </div>
        
        <?php if ( $has_license ) : ?>
            <button type="button" id="change-license-btn" class="button">
                ✏️ Cambiar License Key
            </button>
        <?php endif; ?>
        
        <div style="background: #f0f6fc; border-left: 4px solid #2271b1; padding: 15px; border-radius: 4px; margin-top: 30px;">
            <h4 style="margin-top: 0;">ℹ️ ¿Cómo obtener una License Key?</h4>
            <ol style="margin: 10px 0; padding-left: 20px;">
                <li>Visita <a href="https://app.workfluz.com" target="_blank">app.workfluz.com</a></li>
                <li>Registra una cuenta y compra el plan Premium</li>
                <li>Recibirás tu license key por email</li>
                <li>Copia y pega la license key aquí para activar tu plan</li>
            </ol>
            <p style="margin-bottom: 0; font-size: 13px; color: #6b7280;">
                <strong>Nota:</strong> La license key se valida automáticamente cada 24 horas con nuestros servidores.
            </p>
        </div>
    </div>

    <!-- JavaScript para manejo de licencias -->
    <script>
    jQuery(document).ready(function($) {
        // Mostrar formulario de cambio de licencia
        $('#change-license-btn').on('click', function() {
            $('#license-form-container').slideDown();
            $(this).hide();
        });
        
        // Cancelar edición
        $('#cancel-license-edit-btn').on('click', function() {
            $('#license-form-container').slideUp();
            $('#change-license-btn').show();
            $('#license-activation-message').hide();
        });
        
        // Activar licencia
        $('#license-activation-form').on('submit', function(e) {
            e.preventDefault();
            
            var $form = $(this);
            var $submitBtn = $form.find('button[type="submit"]');
            var $message = $('#license-activation-message');
            var licenseKey = $('#license_key').val().trim();
            
            if (!licenseKey) {
                showMessage('error', '❌ Por favor ingresa una license key válida');
                return;
            }
            
            $submitBtn.prop('disabled', true).text('🔄 Validando...');
            $message.hide();
            
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'validate_license',
                    license_key: licenseKey,
                    _wpnonce: '<?php echo wp_create_nonce( 'validate_license' ); ?>'
                },
                success: function(response) {
                    if (response.success) {
                        showMessage('success', '✅ ' + response.data.message);
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    } else {
                        showMessage('error', '❌ ' + response.data.message);
                        $submitBtn.prop('disabled', false).text('🔑 Activar License Key');
                    }
                },
                error: function() {
                    showMessage('error', '❌ Error de conexión. Inténtalo de nuevo.');
                    $submitBtn.prop('disabled', false).text('🔑 Activar License Key');
                }
            });
        });
        
        // Revalidar licencia
        $('#revalidate-license-btn').on('click', function() {
            var $btn = $(this);
            var originalText = $btn.text();
            
            $btn.prop('disabled', true).text('🔄 Validando...');
            
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'revalidate_license',
                    _wpnonce: '<?php echo wp_create_nonce( 'revalidate_license' ); ?>'
                },
                success: function(response) {
                    if (response.success) {
                        alert('✅ ' + response.data.message);
                        location.reload();
                    } else {
                        alert('❌ ' + response.data.message);
                        $btn.prop('disabled', false).text(originalText);
                    }
                },
                error: function() {
                    alert('❌ Error de conexión. Inténtalo de nuevo.');
                    $btn.prop('disabled', false).text(originalText);
                }
            });
        });
        
        // Desactivar licencia
        $('#deactivate-license-btn').on('click', function() {
            if (!confirm('¿Estás seguro de que deseas desactivar tu license key?\n\nTu plan volverá a FREE con límites mensuales.')) {
                return;
            }
            
            var $btn = $(this);
            $btn.prop('disabled', true).text('🔄 Procesando...');
            
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'deactivate_license',
                    _wpnonce: '<?php echo wp_create_nonce( 'deactivate_license' ); ?>'
                },
                success: function(response) {
                    if (response.success) {
                        alert('✅ License key desactivada correctamente');
                        location.reload();
                    } else {
                        alert('❌ ' + response.data.message);
                        $btn.prop('disabled', false).text('🗑️ Desactivar');
                    }
                },
                error: function() {
                    alert('❌ Error de conexión. Inténtalo de nuevo.');
                    $btn.prop('disabled', false).text('🗑️ Desactivar');
                }
            });
        });
        
        function showMessage(type, message) {
            var $message = $('#license-activation-message');
            var bgColor = type === 'success' ? '#d1fae5' : '#fee2e2';
            var borderColor = type === 'success' ? '#059669' : '#dc2626';
            
            $message
                .html(message)
                .css({
                    'background': bgColor,
                    'border-left': '4px solid ' + borderColor,
                    'padding': '15px',
                    'border-radius': '4px'
                })
                .slideDown();
        }
    });
    </script>

    <!-- Uso Mensual -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin: 30px 0;">
        <!-- Mensajes de Texto -->
        <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h3 style="margin: 0; font-size: 16px; font-weight: 600; color: #374151;">💬 Mensajes de Texto</h3>
                <?php if ( $stats['plan'] === 'premium' ) : ?>
                    <span style="background: #d1fae5; color: #059669; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">ILIMITADO</span>
                <?php else : ?>
                    <span style="color: #6b7280; font-size: 14px;">
                        <?php echo number_format( $stats['text_messages']['used'] ); ?> / <?php echo number_format( $stats['text_messages']['limit'] ); ?>
                    </span>
                <?php endif; ?>
            </div>
            
            <?php if ( $stats['plan'] === 'free' ) : ?>
                <div style="background: #f3f4f6; height: 12px; border-radius: 6px; overflow: hidden; margin-bottom: 15px;">
                    <div style="
                        background: <?php echo $text_percentage >= 90 ? '#ef4444' : ( $text_percentage >= 70 ? '#f59e0b' : '#10b981' ); ?>;
                        height: 100%;
                        width: <?php echo min( 100, $text_percentage ); ?>%;
                        transition: width 0.5s ease;
                    "></div>
                </div>
                
                <div style="display: flex; justify-content: space-between; font-size: 13px; color: #6b7280;">
                    <span>Usado: <?php echo number_format( $stats['text_messages']['used'] ); ?></span>
                    <span>Restante: <?php echo number_format( $stats['text_messages']['remaining'] ); ?></span>
                </div>
                
                <?php if ( $text_percentage >= 80 ) : ?>
                    <div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 10px; margin-top: 15px; border-radius: 4px; font-size: 13px;">
                        <strong>⚠️ Atención:</strong> Has usado el <?php echo round( $text_percentage ); ?>% de tus mensajes mensuales.
                        <?php if ( $text_percentage >= 100 ) : ?>
                            <a href="#upgrade" style="color: #667eea; text-decoration: underline;">Actualiza a Premium</a> para continuar.
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php else : ?>
                <div style="text-align: center; padding: 30px; background: #f9fafb; border-radius: 8px;">
                    <div style="font-size: 36px; margin-bottom: 10px;">∞</div>
                    <div style="color: #6b7280; font-size: 14px;">
                        Mensajes ilimitados este mes<br>
                        <strong><?php echo number_format( $stats['text_messages']['used'] ); ?></strong> mensajes enviados
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Minutos de Voz -->
        <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h3 style="margin: 0; font-size: 16px; font-weight: 600; color: #374151;">🎤 Minutos de Voz</h3>
                <?php if ( $stats['plan'] === 'premium' ) : ?>
                    <span style="background: #d1fae5; color: #059669; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">ILIMITADO</span>
                <?php else : ?>
                    <span style="color: #6b7280; font-size: 14px;">
                        <?php echo number_format( $stats['voice_minutes']['used'], 1 ); ?> / <?php echo number_format( $stats['voice_minutes']['limit'] ); ?> min
                    </span>
                <?php endif; ?>
            </div>
            
            <?php if ( $stats['plan'] === 'free' ) : ?>
                <div style="background: #f3f4f6; height: 12px; border-radius: 6px; overflow: hidden; margin-bottom: 15px;">
                    <div style="
                        background: <?php echo $voice_percentage >= 90 ? '#ef4444' : ( $voice_percentage >= 70 ? '#f59e0b' : '#3b82f6' ); ?>;
                        height: 100%;
                        width: <?php echo min( 100, $voice_percentage ); ?>%;
                        transition: width 0.5s ease;
                    "></div>
                </div>
                
                <div style="display: flex; justify-content: space-between; font-size: 13px; color: #6b7280;">
                    <span>Usado: <?php echo number_format( $stats['voice_minutes']['used'], 1 ); ?> min</span>
                    <span>Restante: <?php echo number_format( $stats['voice_minutes']['remaining'], 1 ); ?> min</span>
                </div>
                
                <?php if ( $voice_percentage >= 80 ) : ?>
                    <div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 10px; margin-top: 15px; border-radius: 4px; font-size: 13px;">
                        <strong>⚠️ Atención:</strong> Has usado el <?php echo round( $voice_percentage ); ?>% de tus minutos mensuales.
                        <?php if ( $voice_percentage >= 100 ) : ?>
                            <a href="#upgrade" style="color: #667eea; text-decoration: underline;">Actualiza a Premium</a> para continuar.
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php else : ?>
                <div style="text-align: center; padding: 30px; background: #f9fafb; border-radius: 8px;">
                    <div style="font-size: 36px; margin-bottom: 10px;">∞</div>
                    <div style="color: #6b7280; font-size: 14px;">
                        Minutos ilimitados este mes<br>
                        <strong><?php echo number_format( $stats['voice_minutes']['used'], 1 ); ?></strong> minutos usados
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Información del Modelo -->
    <div style="background: #f0f6fc; border-left: 4px solid #2271b1; padding: 20px; border-radius: 4px; margin: 30px 0;">
        <h3 style="margin-top: 0;">ℹ️ Sobre el modelo Freemium</h3>
        <p><strong>Usuarios Finales:</strong> Los visitantes de tu sitio web pueden usar el chat de forma ilimitada. No necesitan pagar nada.</p>
        <p><strong>Límites por Instalación:</strong> Los límites se aplican a nivel de tu instalación de WordPress, no por usuario individual.</p>
        <ul style="margin: 15px 0;">
            <li><strong>Plan Gratuito:</strong> 100 mensajes/mes + 30 minutos de voz/mes + Branding "Workfluz Chat Systems"</li>
            <li><strong>Plan Premium:</strong> Uso ilimitado + Sin branding + Soporte prioritario</li>
        </ul>
        <p>El contador se reinicia automáticamente cada mes desde la fecha de activación del plugin.</p>
    </div>

    <!-- Actualizar a Premium -->
    <?php if ( $stats['plan'] === 'free' ) : ?>
        <div id="upgrade" style="background: white; padding: 40px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); margin: 30px 0;">
            <div style="text-align: center; max-width: 600px; margin: 0 auto;">
                <div style="font-size: 48px; margin-bottom: 20px;">✨</div>
                <h2 style="margin: 0 0 15px 0; font-size: 32px; font-weight: 700;">Actualiza a Premium</h2>
                <p style="font-size: 18px; color: #6b7280; margin-bottom: 30px;">
                    Desbloquea todo el potencial de tu widget de IA sin límites ni restricciones.
                </p>
                
                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; border-radius: 12px; margin-bottom: 30px;">
                    <div style="font-size: 48px; font-weight: 700; margin-bottom: 10px;">$29<span style="font-size: 24px; font-weight: 400;">/mes</span></div>
                    <div style="font-size: 16px; opacity: 0.9;">o $290/año (ahorra 2 meses)</div>
                </div>
                
                <div style="text-align: left; margin-bottom: 30px;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <span style="color: #10b981; font-size: 20px;">✓</span>
                            <span>Mensajes ilimitados</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <span style="color: #10b981; font-size: 20px;">✓</span>
                            <span>Voz ilimitada</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <span style="color: #10b981; font-size: 20px;">✓</span>
                            <span>Sin branding</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <span style="color: #10b981; font-size: 20px;">✓</span>
                            <span>Soporte prioritario</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <span style="color: #10b981; font-size: 20px;">✓</span>
                            <span>Actualizaciones incluidas</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <span style="color: #10b981; font-size: 20px;">✓</span>
                            <span>Analytics avanzados</span>
                        </div>
                    </div>
                </div>
                
                <button class="button button-primary button-hero" style="background: #667eea; border: none; padding: 15px 40px; font-size: 18px; font-weight: 600;" disabled>
                    🔒 Próximamente (Integración con Stripe)
                </button>
                
                <p style="font-size: 13px; color: #9ca3af; margin-top: 20px;">
                    La integración de pagos estará disponible en una próxima actualización.
                </p>
            </div>
        </div>
    <?php else : ?>
        <!-- Gestión de Suscripción Premium -->
        <div style="background: white; padding: 30px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin: 30px 0;">
            <h3 style="margin-top: 0;">⚙️ Gestión de Suscripción</h3>
            <p>Tu plan Premium está activo. Disfruta de uso ilimitado sin restricciones.</p>
            
            <div style="background: #f9fafb; padding: 20px; border-radius: 8px; margin: 20px 0;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                        <div style="font-size: 13px; color: #6b7280; margin-bottom: 5px;">Estado de la suscripción</div>
                        <div style="font-size: 18px; font-weight: 600; color: #10b981;">✅ Activa</div>
                    </div>
                    <?php if ( $config->stripe_subscription_id ) : ?>
                        <div>
                            <div style="font-size: 13px; color: #6b7280; margin-bottom: 5px;">ID de Suscripción</div>
                            <div style="font-size: 14px; font-family: monospace;"><?php echo esc_html( substr( $config->stripe_subscription_id, 0, 20 ) . '...' ); ?></div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <button class="button" disabled>Cancelar Suscripción</button>
            <p class="description">La gestión de suscripciones estará disponible próximamente.</p>
        </div>
    <?php endif; ?>

    <!-- Usuarios Finales -->
    <h2 class="title">👥 Usuarios Finales (Visitantes)</h2>
    <p class="description">Estos son los visitantes de tu sitio web que han interactuado con el widget.</p>
    
    <?php
    global $wpdb;
    $table_end_users = $wpdb->prefix . 'ai_widget_end_users';
    
    $top_users = $wpdb->get_results( 
        "SELECT * FROM {$table_end_users} 
        ORDER BY last_interaction DESC 
        LIMIT 10"
    );
    ?>
    
    <?php if ( ! empty( $top_users ) ) : ?>
        <table class="wp-list-table widefat fixed striped" style="margin-top: 20px;">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th>Session ID</th>
                    <th style="width: 120px;">Mensajes</th>
                    <th style="width: 120px;">Minutos Voz</th>
                    <th style="width: 180px;">Última Interacción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ( $top_users as $index => $user ) : ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td><code><?php echo esc_html( substr( $user->session_id, 0, 40 ) . '...' ); ?></code></td>
                        <td><strong><?php echo number_format( $user->messages_count ); ?></strong></td>
                        <td><strong><?php echo number_format( $user->voice_minutes, 1 ); ?></strong> min</td>
                        <td><?php echo esc_html( human_time_diff( strtotime( $user->last_interaction ), current_time( 'timestamp' ) ) ); ?> ago</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <p style="margin-top: 15px; color: #6b7280; font-size: 13px;">
            Mostrando los 10 usuarios más recientes. Total: <strong><?php echo number_format( $stats['total_end_users'] ); ?></strong> usuarios finales.
        </p>
    <?php else : ?>
        <div style="background: #f9fafb; padding: 40px; text-align: center; border-radius: 8px; margin-top: 20px;">
            <p style="color: #9ca3af; margin: 0;">No hay usuarios todavía. Los visitantes aparecerán aquí cuando interactúen con el widget.</p>
        </div>
    <?php endif; ?>
</div>
