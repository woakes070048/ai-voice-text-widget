<?php
/**
 * API REST del plugin.
 *
 * @package AI_Voice_Text_Widget
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class AI_Voice_Text_Widget_API {

    public function register_routes() {
        register_rest_route( 'ai-widget/v1', '/chat', array(
            'methods' => 'POST',
            'callback' => array( $this, 'handle_chat' ),
            'permission_callback' => '__return_true',
        ) );

        register_rest_route( 'ai-widget/v1', '/usage', array(
            'methods' => 'GET',
            'callback' => array( $this, 'get_usage' ),
            'permission_callback' => '__return_true',
        ) );

        register_rest_route( 'ai-widget/v1', '/check-limits', array(
            'methods' => 'GET',
            'callback' => array( $this, 'check_limits' ),
            'permission_callback' => '__return_true',
        ) );

        register_rest_route( 'ai-widget/v1', '/log-voice', array(
            'methods' => 'POST',
            'callback' => array( $this, 'log_voice_usage' ),
            'permission_callback' => '__return_true',
        ) );

        register_rest_route( 'ai-widget/v1', '/upgrade', array(
            'methods' => 'POST',
            'callback' => array( $this, 'handle_upgrade' ),
            'permission_callback' => '__return_true',
        ) );
    }

    public function handle_chat( $request ) {
        $message = $request->get_param( 'message' );
        $session_id = $request->get_param( 'session_id' );

        if ( empty( $message ) || empty( $session_id ) ) {
            return new WP_Error( 'invalid_params', 'Parámetros inválidos', array( 'status' => 400 ) );
        }

        // Verificar límites con el nuevo sistema freemium
        $limits = AI_Widget_Freemium::can_send_text_message();
        
        if ( ! $limits['allowed'] ) {
            return new WP_Error( 
                'limit_exceeded', 
                '¡Has alcanzado tu límite mensual de mensajes! Actualiza a Premium para mensajes ilimitados.',
                array( 
                    'status' => 429,
                    'data' => array(
                        'used' => $limits['used'],
                        'limit' => $limits['limit'],
                        'remaining' => $limits['remaining'],
                        'upgrade_url' => admin_url( 'admin.php?page=ai-widget-freemium' )
                    )
                )
            );
        }

        // Crear o actualizar usuario final en la tabla
        $database = new AI_Voice_Text_Widget_Database();
        $database->get_or_create_user( $session_id );
        
        // Guardar mensaje del usuario
        $database->save_message( $session_id, 'user', $message, 0, 0, null, 'text' );

        // Procesar mensaje con el motor de IA
        $ai_engine = new AI_Voice_Text_Widget_AI_Engine();
        $response = $ai_engine->process_message( $message, $session_id );

        if ( is_wp_error( $response ) ) {
            return $response;
        }

        // Guardar respuesta de la IA
        $database->save_message( $session_id, 'ai', $response['text'], $response['tokens'], $response['time'], null, 'text' );

        // Registrar uso en el sistema freemium (incrementa contadores)
        AI_Widget_Freemium::log_text_message( $session_id );

        // Obtener límites actualizados
        $updated_limits = AI_Widget_Freemium::can_send_text_message();

        return rest_ensure_response( array(
            'response' => $response['text'],
            'limits' => array(
                'used' => $updated_limits['used'],
                'limit' => $updated_limits['limit'],
                'remaining' => $updated_limits['remaining'],
                'percentage' => $updated_limits['limit'] !== 'unlimited' 
                    ? round( ( $updated_limits['used'] / $updated_limits['limit'] ) * 100 )
                    : 0
            ),
        ) );
    }

    public function get_usage( $request ) {
        $session_id = $request->get_param( 'session_id' );

        if ( empty( $session_id ) ) {
            return new WP_Error( 'invalid_params', 'Parámetros inválidos', array( 'status' => 400 ) );
        }

        // Obtener configuración y límites
        $config = AI_Widget_Freemium::get_installation_config();
        $text_limits = AI_Widget_Freemium::can_send_text_message();
        
        // Calcular límites de voz
        $voice_used = (float) $config->voice_minutes_used;
        $voice_limit = $config->plan === 'premium' ? 'unlimited' : (int) $config->voice_minutes_limit;
        $voice_remaining = $config->plan === 'premium' ? 'unlimited' : max( 0, $voice_limit - $voice_used );

        // Obtener datos del usuario final
        global $wpdb;
        $table_end_users = $wpdb->prefix . 'ai_widget_end_users';
        $end_user = $wpdb->get_row( $wpdb->prepare(
            "SELECT * FROM {$table_end_users} WHERE session_id = %s",
            $session_id
        ) );

        return rest_ensure_response( array(
            'plan' => $config->plan,
            'text' => array(
                'used' => $text_limits['used'],
                'limit' => $text_limits['limit'],
                'remaining' => $text_limits['remaining']
            ),
            'voice' => array(
                'used' => $voice_used,
                'limit' => $voice_limit,
                'remaining' => $voice_remaining
            ),
            'end_user' => array(
                'messages' => $end_user ? (int) $end_user->messages_count : 0,
                'voice_minutes' => $end_user ? (float) $end_user->voice_minutes : 0
            )
        ) );
    }

    public function check_limits( $request ) {
        $session_id = $request->get_param( 'session_id' );

        if ( empty( $session_id ) ) {
            return new WP_Error( 'invalid_params', 'Parámetros inválidos', array( 'status' => 400 ) );
        }

        // Obtener límites de mensajes de texto
        $text_limits = AI_Widget_Freemium::can_send_text_message();
        
        // Obtener configuración de instalación para límites de voz
        $config = AI_Widget_Freemium::get_installation_config();
        
        // Calcular límites de voz
        $voice_used = (float) $config->voice_minutes_used;
        $voice_limit = $config->plan === 'premium' ? 'unlimited' : (int) $config->voice_minutes_limit;
        $voice_remaining = $config->plan === 'premium' ? 'unlimited' : max( 0, $voice_limit - $voice_used );

        // Obtener stats del usuario final (si existe)
        global $wpdb;
        $table_end_users = $wpdb->prefix . 'ai_widget_end_users';
        $end_user = $wpdb->get_row( $wpdb->prepare(
            "SELECT * FROM {$table_end_users} WHERE session_id = %s",
            $session_id
        ) );

        return rest_ensure_response( array(
            'plan' => $config->plan,
            'text' => array(
                'allowed' => $text_limits['allowed'],
                'used' => $text_limits['used'],
                'limit' => $text_limits['limit'],
                'remaining' => $text_limits['remaining'],
                'percentage' => $text_limits['limit'] !== 'unlimited'
                    ? round( ( $text_limits['used'] / $text_limits['limit'] ) * 100 )
                    : 0
            ),
            'voice' => array(
                'allowed' => $config->plan === 'premium' || $voice_remaining > 0,
                'used' => $voice_used,
                'limit' => $voice_limit,
                'remaining' => $voice_remaining,
                'percentage' => $voice_limit !== 'unlimited'
                    ? round( ( $voice_used / $voice_limit ) * 100 )
                    : 0
            ),
            'end_user' => array(
                'messages' => $end_user ? (int) $end_user->messages_count : 0,
                'voice_minutes' => $end_user ? (float) $end_user->voice_minutes : 0
            ),
            'period' => array(
                'start' => $config->current_period_start,
                'end' => $config->current_period_end,
                'days_remaining' => max( 0, ceil( ( strtotime( $config->current_period_end ) - time() ) / 86400 ) )
            ),
            'show_branding' => AI_Widget_Freemium::should_show_branding()
        ) );
    }

    public function handle_upgrade( $request ) {
        $session_id = $request->get_param( 'session_id' );
        $plan = $request->get_param( 'plan' );

        if ( empty( $session_id ) || empty( $plan ) ) {
            return new WP_Error( 'invalid_params', 'Parámetros inválidos', array( 'status' => 400 ) );
        }

        // Aquí iría la integración con Stripe o el sistema de pagos
        return rest_ensure_response( array(
            'success' => true,
            'message' => 'Upgrade procesado (integración de pago pendiente)',
        ) );
    }

    /**
     * Registra el uso de voz (minutos).
     * 
     * @param WP_REST_Request $request Request con session_id y duration_seconds
     * @return WP_REST_Response|WP_Error
     */
    public function log_voice_usage( $request ) {
        $session_id = $request->get_param( 'session_id' );
        $duration_seconds = $request->get_param( 'duration_seconds' );

        if ( empty( $session_id ) || ! is_numeric( $duration_seconds ) ) {
            return new WP_Error( 'invalid_params', 'Parámetros inválidos', array( 'status' => 400 ) );
        }

        // Validar que haya minutos disponibles ANTES de registrar
        $can_use = AI_Widget_Freemium::can_use_voice_minutes( (int) $duration_seconds );
        
        if ( ! $can_use['allowed'] ) {
            return new WP_Error( 
                'voice_limit_exceeded', 
                '¡Has alcanzado tu límite mensual de minutos de voz! Actualiza a Premium para voz ilimitada.',
                array( 
                    'status' => 429,
                    'data' => array(
                        'used' => $can_use['used'],
                        'limit' => $can_use['limit'],
                        'remaining' => $can_use['remaining'],
                        'upgrade_url' => admin_url( 'admin.php?page=ai-widget-freemium' )
                    )
                )
            );
        }

        // Registrar uso de voz
        AI_Widget_Freemium::log_voice_usage( $session_id, (int) $duration_seconds );

        // Guardar en conversaciones para histórico
        $database = new AI_Voice_Text_Widget_Database();
        $database->save_message( 
            $session_id, 
            'user', 
            '[Interacción de voz]', 
            0, 
            0, 
            null, 
            'voice', 
            (int) $duration_seconds 
        );

        // Obtener límites actualizados
        $config = AI_Widget_Freemium::get_installation_config();
        $voice_used = (float) $config->voice_minutes_used;
        $voice_limit = $config->plan === 'premium' ? 'unlimited' : (int) $config->voice_minutes_limit;
        $voice_remaining = $config->plan === 'premium' ? 'unlimited' : max( 0, $voice_limit - $voice_used );

        return rest_ensure_response( array(
            'success' => true,
            'message' => 'Uso de voz registrado correctamente',
            'voice_limits' => array(
                'used' => $voice_used,
                'limit' => $voice_limit,
                'remaining' => $voice_remaining,
                'percentage' => $voice_limit !== 'unlimited'
                    ? round( ( $voice_used / $voice_limit ) * 100 )
                    : 0
            )
        ) );
    }
}
