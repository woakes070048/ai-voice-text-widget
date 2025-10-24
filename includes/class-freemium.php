<?php
/**
 * Sistema Freemium del plugin.
 *
 * Gestiona límites por instalación (no por usuario final).
 *
 * @package AI_Voice_Text_Widget
 * @author Workfluz <support@workfluz.com>
 * @copyright Copyright (c) 2024-2025 Workfluz
 * @license GPL-2.0-or-later
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Acceso directo no permitido
}

class AI_Widget_Freemium {

    /**
     * Obtiene la configuración actual de la instalación.
     *
     * @return object|null Configuración de la instalación
     */
    public static function get_installation_config() {
        global $wpdb;
        $table = $wpdb->prefix . 'ai_widget_installation';
        
        $config = $wpdb->get_row( "SELECT * FROM {$table} LIMIT 1" );
        
        // Si no existe, crear registro por defecto
        if ( ! $config ) {
            $next_month = date( 'Y-m-d H:i:s', strtotime( '+1 month' ) );
            
            $wpdb->insert(
                $table,
                array(
                    'plan' => 'free',
                    'text_messages_limit' => 100,
                    'voice_minutes_limit' => 30,
                    'current_period_end' => $next_month,
                    'subscription_status' => 'free'
                )
            );
            
            $config = $wpdb->get_row( "SELECT * FROM {$table} LIMIT 1" );
        }
        
        return $config;
    }

    /**
     * Verifica si se puede enviar un mensaje de texto.
     *
     * @return array ['allowed' => bool, 'used' => int, 'limit' => int|string, 'remaining' => int|string]
     */
    public static function can_send_text_message() {
        $config = self::get_installation_config();
        
        // Premium = ilimitado
        if ( $config->plan === 'premium' ) {
            return array(
                'allowed' => true,
                'used' => (int) $config->text_messages_used,
                'limit' => 'unlimited',
                'remaining' => 'unlimited'
            );
        }
        
        // Free = verificar límite
        $used = (int) $config->text_messages_used;
        $limit = (int) $config->text_messages_limit;
        $remaining = max( 0, $limit - $used );
        
        return array(
            'allowed' => $remaining > 0,
            'used' => $used,
            'limit' => $limit,
            'remaining' => $remaining
        );
    }

    /**
     * Verifica si se pueden usar minutos de voz.
     *
     * @param int $duration_seconds Duración de la llamada en segundos
     * @return array ['allowed' => bool, 'used' => float, 'limit' => int|string, 'remaining' => float|string]
     */
    public static function can_use_voice_minutes( $duration_seconds = 60 ) {
        $config = self::get_installation_config();
        $duration_minutes = $duration_seconds / 60;
        
        // Premium = ilimitado
        if ( $config->plan === 'premium' ) {
            return array(
                'allowed' => true,
                'used' => (float) $config->voice_minutes_used,
                'limit' => 'unlimited',
                'remaining' => 'unlimited'
            );
        }
        
        // Free = verificar límite
        $used = (float) $config->voice_minutes_used;
        $limit = (int) $config->voice_minutes_limit;
        $remaining = max( 0, $limit - $used );
        
        return array(
            'allowed' => $remaining >= $duration_minutes,
            'used' => $used,
            'limit' => $limit,
            'remaining' => $remaining
        );
    }

    /**
     * Registra el envío de un mensaje de texto.
     *
     * @param string $session_id ID de sesión del usuario final
     * @return bool Éxito
     */
    public static function log_text_message( $session_id ) {
        global $wpdb;
        $table_installation = $wpdb->prefix . 'ai_widget_installation';
        $table_end_users = $wpdb->prefix . 'ai_widget_end_users';
        
        // Incrementar contador de la instalación
        $wpdb->query( "UPDATE {$table_installation} SET text_messages_used = text_messages_used + 1 WHERE id = 1" );
        
        // Actualizar o crear usuario final
        $user = $wpdb->get_row( $wpdb->prepare( 
            "SELECT * FROM {$table_end_users} WHERE session_id = %s", 
            $session_id 
        ) );
        
        if ( $user ) {
            $wpdb->update(
                $table_end_users,
                array( 
                    'messages_count' => $user->messages_count + 1,
                    'last_interaction' => current_time( 'mysql' )
                ),
                array( 'session_id' => $session_id ),
                array( '%d', '%s' ),
                array( '%s' )
            );
        } else {
            $wpdb->insert(
                $table_end_users,
                array( 
                    'session_id' => $session_id,
                    'messages_count' => 1
                ),
                array( '%s', '%d' )
            );
        }
        
        return true;
    }

    /**
     * Registra el uso de voz (minutos).
     *
     * @param string $session_id ID de sesión del usuario final
     * @param int $duration_seconds Duración en segundos
     * @return bool Éxito
     */
    public static function log_voice_usage( $session_id, $duration_seconds ) {
        global $wpdb;
        $table_installation = $wpdb->prefix . 'ai_widget_installation';
        $table_end_users = $wpdb->prefix . 'ai_widget_end_users';
        
        $duration_minutes = $duration_seconds / 60;
        
        // Incrementar contador de la instalación
        $wpdb->query( $wpdb->prepare(
            "UPDATE {$table_installation} SET voice_minutes_used = voice_minutes_used + %f WHERE id = 1",
            $duration_minutes
        ) );
        
        // Actualizar o crear usuario final
        $user = $wpdb->get_row( $wpdb->prepare( 
            "SELECT * FROM {$table_end_users} WHERE session_id = %s", 
            $session_id 
        ) );
        
        if ( $user ) {
            $wpdb->query( $wpdb->prepare(
                "UPDATE {$table_end_users} 
                SET voice_minutes = voice_minutes + %f, last_interaction = %s 
                WHERE session_id = %s",
                $duration_minutes,
                current_time( 'mysql' ),
                $session_id
            ) );
        } else {
            $wpdb->insert(
                $table_end_users,
                array( 
                    'session_id' => $session_id,
                    'voice_minutes' => $duration_minutes
                ),
                array( '%s', '%f' )
            );
        }
        
        return true;
    }

    /**
     * Verifica y resetea el período si ha expirado (solo para planes free).
     *
     * @return bool True si se hizo reset, false si no
     */
    public static function check_and_reset_period() {
        global $wpdb;
        $table = $wpdb->prefix . 'ai_widget_installation';
        
        $config = self::get_installation_config();
        
        // Solo resetear planes gratuitos
        if ( $config->plan !== 'free' ) {
            return false;
        }
        
        // Verificar si el período terminó
        $now = current_time( 'mysql' );
        if ( strtotime( $now ) >= strtotime( $config->current_period_end ) ) {
            $next_period = date( 'Y-m-d H:i:s', strtotime( $config->current_period_end . ' +1 month' ) );
            
            $wpdb->update(
                $table,
                array(
                    'text_messages_used' => 0,
                    'voice_minutes_used' => 0,
                    'current_period_start' => $config->current_period_end,
                    'current_period_end' => $next_period
                ),
                array( 'id' => 1 ),
                array( '%d', '%f', '%s', '%s' ),
                array( '%d' )
            );
            
            return true;
        }
        
        return false;
    }

    /**
     * Actualiza a plan Premium.
     *
     * @param string $stripe_customer_id ID del cliente en Stripe
     * @param string $stripe_subscription_id ID de la suscripción en Stripe
     * @return bool Éxito
     */
    public static function upgrade_to_premium( $stripe_customer_id = null, $stripe_subscription_id = null ) {
        global $wpdb;
        $table = $wpdb->prefix . 'ai_widget_installation';
        
        $data = array(
            'plan' => 'premium',
            'subscription_status' => 'active'
        );
        
        if ( $stripe_customer_id ) {
            $data['stripe_customer_id'] = $stripe_customer_id;
        }
        
        if ( $stripe_subscription_id ) {
            $data['stripe_subscription_id'] = $stripe_subscription_id;
        }
        
        $wpdb->update( $table, $data, array( 'id' => 1 ) );
        
        return true;
    }

    /**
     * Cancela el plan Premium y vuelve a Free.
     *
     * @return bool Éxito
     */
    public static function cancel_premium() {
        global $wpdb;
        $table = $wpdb->prefix . 'ai_widget_installation';
        
        $next_month = date( 'Y-m-d H:i:s', strtotime( '+1 month' ) );
        
        $wpdb->update(
            $table,
            array(
                'plan' => 'free',
                'subscription_status' => 'canceled',
                'text_messages_used' => 0,
                'voice_minutes_used' => 0,
                'current_period_start' => current_time( 'mysql' ),
                'current_period_end' => $next_month
            ),
            array( 'id' => 1 )
        );
        
        return true;
    }

    /**
     * Verifica si debe mostrarse el branding.
     *
     * @return bool True si es plan free, false si es premium
     */
    public static function should_show_branding() {
        $config = self::get_installation_config();
        return $config->plan === 'free';
    }

    /**
     * Obtiene estadísticas completas para el panel de admin.
     *
     * @return array Estadísticas
     */
    public static function get_stats() {
        global $wpdb;
        $table_end_users = $wpdb->prefix . 'ai_widget_end_users';
        $table_conversations = $wpdb->prefix . 'ai_widget_conversations';
        
        $config = self::get_installation_config();
        
        $total_end_users = $wpdb->get_var( "SELECT COUNT(*) FROM {$table_end_users}" );
        $total_conversations = $wpdb->get_var( "SELECT COUNT(*) FROM {$table_conversations}" );
        
        $text_check = self::can_send_text_message();
        $voice_check = self::can_use_voice_minutes();
        
        return array(
            'plan' => $config->plan,
            'subscription_status' => $config->subscription_status,
            'license_key' => $config->license_key ?? null,
            'license_status' => $config->license_status ?? 'none',
            'license_expires_at' => $config->license_expires_at ?? null,
            'text_messages' => array(
                'used' => $config->text_messages_used,
                'limit' => $config->plan === 'premium' ? 'unlimited' : $config->text_messages_limit,
                'remaining' => $text_check['remaining']
            ),
            'voice_minutes' => array(
                'used' => round( $config->voice_minutes_used, 2 ),
                'limit' => $config->plan === 'premium' ? 'unlimited' : $config->voice_minutes_limit,
                'remaining' => $voice_check['remaining']
            ),
            'period' => array(
                'start' => $config->current_period_start,
                'end' => $config->current_period_end
            ),
            'total_end_users' => $total_end_users,
            'total_conversations' => $total_conversations
        );
    }

    /**
     * Valida una license key con el servidor externo.
     *
     * @param string $license_key La license key a validar
     * @param bool $force_refresh Forzar validación ignorando caché
     * @return array ['success' => bool, 'data' => array|null, 'message' => string]
     */
    public static function validate_license( $license_key, $force_refresh = false ) {
        global $wpdb;
        $table = $wpdb->prefix . 'ai_widget_installation';
        
        if ( empty( $license_key ) ) {
            return array(
                'success' => false,
                'message' => __( 'License key cannot be empty', 'ai-voice-text-widget' )
            );
        }
        
        $config = self::get_installation_config();
        
        /**
         * LOCAL LICENSE VALIDATION (No external API calls)
         * 
         * This is a simplified local-only license system for WordPress.org version.
         * For a commercial version with remote validation, you can implement
         * your own license server by using the AI_WIDGET_LICENSE_API_URL constant.
         * 
         * Usage: define( 'AI_WIDGET_LICENSE_API_URL', 'https://your-site.com/api/validate' );
         */
        
        // Allow custom license validation via filter
        $custom_validation = apply_filters( 'ai_widget_custom_license_validation', null, $license_key );
        
        if ( $custom_validation !== null ) {
            return $custom_validation;
        }
        
        // Simple local validation: any non-empty license key activates premium
        // You can customize this logic for your needs
        if ( strlen( $license_key ) >= 10 ) {
            $wpdb->update(
                $table,
                array(
                    'license_key' => $license_key,
                    'license_status' => 'active',
                    'license_validated_at' => current_time( 'mysql' ),
                    'license_expires_at' => null,
                    'license_last_check' => current_time( 'mysql' ),
                    'license_plan' => 'premium',
                    'plan' => 'premium',
                    'subscription_status' => 'active'
                ),
                array( 'id' => $config->id ),
                array( '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s' ),
                array( '%d' )
            );
            
            return array(
                'success' => true,
                'data' => array(
                    'plan' => 'premium',
                    'status' => 'active',
                    'expires_at' => null
                ),
                'message' => __( 'License activated successfully', 'ai-voice-text-widget' )
            );
        } else {
            return array(
                'success' => false,
                'message' => __( 'License key must be at least 10 characters', 'ai-voice-text-widget' )
            );
        }
    }

    /**
     * Revalida la licencia actual (para cron job diario).
     *
     * @return array Resultado de la validación
     */
    public static function revalidate_current_license() {
        $config = self::get_installation_config();
        
        if ( empty( $config->license_key ) || $config->license_status !== 'active' ) {
            return array(
                'success' => false,
                'message' => 'No hay licencia activa para revalidar'
            );
        }
        
        return self::validate_license( $config->license_key, true );
    }

    /**
     * Desactiva la licencia actual.
     *
     * @return bool True si se desactivó correctamente
     */
    public static function deactivate_license() {
        global $wpdb;
        $table = $wpdb->prefix . 'ai_widget_installation';
        $config = self::get_installation_config();
        
        $result = $wpdb->update(
            $table,
            array(
                'license_key' => null,
                'license_status' => 'none',
                'license_validated_at' => null,
                'license_expires_at' => null,
                'license_last_check' => null,
                'license_plan' => null,
                'plan' => 'free',
                'subscription_status' => 'free'
            ),
            array( 'id' => $config->id ),
            array( '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s' ),
            array( '%d' )
        );
        
        return $result !== false;
    }
}

