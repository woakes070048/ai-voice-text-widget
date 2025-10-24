<?php
/**
 * Activador del plugin - Se ejecuta al activar el plugin.
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
    exit;
}

class AI_Voice_Text_Widget_Activator {

    /**
     * Activa el plugin.
     */
    public static function activate() {
        self::create_tables();
        self::set_default_options();
        self::schedule_cron_jobs();
        
        // Flush rewrite rules
        flush_rewrite_rules();
    }

    /**
     * Crea las tablas necesarias en la base de datos.
     */
    private static function create_tables() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        
        // Tabla de configuración de la instalación (WordPress admin)
        $table_installation = $wpdb->prefix . 'ai_widget_installation';
        $sql_installation = "CREATE TABLE IF NOT EXISTS $table_installation (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            plan VARCHAR(20) DEFAULT 'free',
            text_messages_used INT DEFAULT 0,
            text_messages_limit INT DEFAULT 100,
            voice_minutes_used FLOAT DEFAULT 0,
            voice_minutes_limit INT DEFAULT 30,
            current_period_start DATETIME DEFAULT CURRENT_TIMESTAMP,
            current_period_end DATETIME NULL,
            subscription_status VARCHAR(20) DEFAULT 'free',
            stripe_customer_id VARCHAR(255) NULL,
            stripe_subscription_id VARCHAR(255) NULL,
            license_key VARCHAR(255) NULL,
            license_status VARCHAR(20) DEFAULT 'none',
            license_validated_at DATETIME NULL,
            license_expires_at DATETIME NULL,
            license_last_check DATETIME NULL,
            license_plan VARCHAR(20) NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_plan (plan),
            INDEX idx_subscription (subscription_status),
            INDEX idx_license_key (license_key),
            INDEX idx_license_status (license_status)
        ) $charset_collate;";
        
        // Tabla de usuarios finales (visitantes del sitio)
        $table_end_users = $wpdb->prefix . 'ai_widget_end_users';
        $sql_end_users = "CREATE TABLE IF NOT EXISTS $table_end_users (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            session_id VARCHAR(255) NOT NULL UNIQUE,
            messages_count INT DEFAULT 0,
            voice_minutes FLOAT DEFAULT 0,
            last_interaction DATETIME DEFAULT CURRENT_TIMESTAMP,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_session (session_id),
            INDEX idx_last_interaction (last_interaction)
        ) $charset_collate;";
        
        // Tabla de conversaciones
        $table_conversations = $wpdb->prefix . 'ai_widget_conversations';
        $sql_conversations = "CREATE TABLE IF NOT EXISTS $table_conversations (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            session_id VARCHAR(255) NOT NULL,
            message_type ENUM('user', 'ai') NOT NULL,
            interaction_type ENUM('text', 'voice') NOT NULL,
            message_text TEXT,
            duration_seconds INT DEFAULT 0,
            tokens_used INT DEFAULT 0,
            response_time FLOAT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_session (session_id),
            INDEX idx_type (interaction_type),
            INDEX idx_created (created_at)
        ) $charset_collate;";
        
        // Tabla de analytics
        $table_analytics = $wpdb->prefix . 'ai_widget_analytics';
        $sql_analytics = "CREATE TABLE IF NOT EXISTS $table_analytics (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            date DATE NOT NULL,
            total_text_messages INT DEFAULT 0,
            total_voice_minutes FLOAT DEFAULT 0,
            total_end_users INT DEFAULT 0,
            active_end_users INT DEFAULT 0,
            avg_response_time FLOAT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY idx_date (date)
        ) $charset_collate;";
        
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql_installation );
        dbDelta( $sql_end_users );
        dbDelta( $sql_conversations );
        dbDelta( $sql_analytics );
        
        // Insertar registro inicial de instalación si no existe
        $count = $wpdb->get_var( "SELECT COUNT(*) FROM $table_installation" );
        if ( $count == 0 ) {
            $next_century = '2099-12-31 23:59:59'; // Licencia perpetua para Early Adopters
            $wpdb->insert(
                $table_installation,
                array(
                    'plan' => 'enterprise',
                    'text_messages_limit' => 999999,
                    'voice_minutes_limit' => 999999,
                    'current_period_end' => $next_century,
                    'subscription_status' => 'active',
                    'license_key' => 'workfluz-2025-earlyadopters-ptc-es',
                    'license_status' => 'active',
                    'license_validated_at' => current_time( 'mysql' ),
                    'license_expires_at' => $next_century,
                    'license_plan' => 'enterprise'
                ),
                array( '%s', '%d', '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s' )
            );
        }
    }

    /**
     * Establece las opciones por defecto.
     */
    private static function set_default_options() {
        // Logo oficial de Workfluz
        $workfluz_logo = '<svg class="ai-logo-svg" viewBox="0 0 1972.8 1870.45" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true"><defs><linearGradient id="workfluz-gradient" x1="0" y1="935.23" x2="1972.8" y2="935.23" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#76b4e3"/><stop offset="1" stop-color="#009bf0"/></linearGradient></defs><path fill="url(#workfluz-gradient)" d="M1852.35,0c-53.22,0-100.11,34.94-115.34,85.94l-457.68,1533.26-232.79-916.61c-13.55-53.37-61.6-90.73-116.65-90.73h-85.51c-54.63,0-102.42,36.8-116.37,89.63l-243.58,922.28-239.29-921.78c-13.78-53.07-61.67-90.12-116.5-90.12h-8.2c-81.18,0-139.09,78.72-114.89,156.23l317.74,1017.87c15.7,50.27,62.23,84.5,114.89,84.5h93.66c54.27,0,101.84-36.33,116.12-88.69l229.35-840.98,229.36,840.98c14.28,52.36,61.84,88.69,116.13,88.69h94.97c52.02,0,98.16-33.42,114.37-82.84L1966.71,157.88C1992.24,80.04,1934.26,0,1852.35,0Z"/></svg>';
        
        $default_options = array(
            'ai_widget_enabled' => true,
            'ai_widget_position' => 'bottom-right',
            'ai_widget_primary_color' => '#0780ad',
            'ai_widget_secondary_color' => '#31f0c5',
            'ai_widget_welcome_message' => '¡Hola! 👋 ¿Cómo le gustaría interactuar?',
            'ai_widget_placeholder' => 'Escribe tu mensaje...',
            'ai_widget_provider' => 'vapi',
            'ai_widget_voice_enabled' => true,
            'ai_widget_text_enabled' => true,
            'ai_widget_personality' => 'friendly',
            'ai_widget_vapi_public_key' => '',
            'ai_widget_vapi_assistant_id' => '',
            'ai_widget_elevenlabs_api_key' => '',
            'ai_widget_elevenlabs_voice_id' => '',
            'ai_widget_openai_api_key' => '',
            'ai_widget_chat_provider' => 'openai',
            'ai_widget_n8n_webhook_url' => '',
            'ai_widget_assistant_name' => 'Workfluz Assistant',
            'ai_widget_logo_svg' => $workfluz_logo,
            'ai_widget_use_openai_assistant' => '0',
            'ai_widget_openai_assistant_id' => '',
            'ai_widget_system_prompt' => 'Eres un asistente amigable y servicial. Respondes de manera clara, concisa y amable. Usa emojis ocasionalmente para ser más expresivo.',
            'ai_widget_show_branding' => true, // Show branding by default (free plan)
            'ai_widget_text_limit' => 100, // Free plan: 100 messages
            'ai_widget_voice_limit' => 30, // Free plan: 30 voice minutes
        );
        
        foreach ( $default_options as $key => $value ) {
            if ( get_option( $key ) === false ) {
                add_option( $key, $value );
            }
        }
        
        // Guardar la versión del plugin
        add_option( 'ai_widget_version', AI_VOICE_TEXT_WIDGET_VERSION );
    }

    /**
     * Programa las tareas cron.
     */
    private static function schedule_cron_jobs() {
        // Tarea diaria para limpiar conversaciones antiguas (90 días)
        if ( ! wp_next_scheduled( 'ai_widget_daily_cleanup' ) ) {
            wp_schedule_event( time(), 'daily', 'ai_widget_daily_cleanup' );
        }
        
        // Tarea diaria para generar analytics
        if ( ! wp_next_scheduled( 'ai_widget_daily_analytics' ) ) {
            wp_schedule_event( time(), 'daily', 'ai_widget_daily_analytics' );
        }
        
        // Tarea para verificar y resetear contadores mensuales
        if ( ! wp_next_scheduled( 'ai_widget_check_period_reset' ) ) {
            wp_schedule_event( time(), 'hourly', 'ai_widget_check_period_reset' );
        }
        
        // Tarea para revalidar license keys diariamente
        if ( ! wp_next_scheduled( 'ai_widget_revalidate_license' ) ) {
            wp_schedule_event( time(), 'daily', 'ai_widget_revalidate_license' );
        }
    }
}
