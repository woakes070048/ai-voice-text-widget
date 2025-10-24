<?php
/**
 * Manejo de tareas programadas (Cron Jobs).
 *
 * @package AI_Voice_Text_Widget
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Verificar y resetear período mensual
add_action( 'ai_widget_check_period_reset', 'ai_widget_handle_period_reset' );
function ai_widget_handle_period_reset() {
    require_once AI_VOICE_TEXT_WIDGET_PLUGIN_DIR . 'includes/class-freemium.php';
    AI_Widget_Freemium::check_and_reset_period();
}

// Limpiar conversaciones antiguas (90 días)
add_action( 'ai_widget_daily_cleanup', 'ai_widget_handle_daily_cleanup' );
function ai_widget_handle_daily_cleanup() {
    global $wpdb;
    $table_conversations = $wpdb->prefix . 'ai_widget_conversations';
    $table_end_users = $wpdb->prefix . 'ai_widget_end_users';
    
    // Eliminar conversaciones mayores a 90 días
    $wpdb->query( "DELETE FROM {$table_conversations} WHERE created_at < DATE_SUB(NOW(), INTERVAL 90 DAY)" );
    
    // Eliminar usuarios finales sin actividad en 90 días
    $wpdb->query( "DELETE FROM {$table_end_users} WHERE last_interaction < DATE_SUB(NOW(), INTERVAL 90 DAY)" );
}

// Generar analytics diarios
add_action( 'ai_widget_daily_analytics', 'ai_widget_handle_daily_analytics' );
function ai_widget_handle_daily_analytics() {
    global $wpdb;
    $table_analytics = $wpdb->prefix . 'ai_widget_analytics';
    $table_conversations = $wpdb->prefix . 'ai_widget_conversations';
    $table_end_users = $wpdb->prefix . 'ai_widget_end_users';
    
    $today = date( 'Y-m-d' );
    $yesterday = date( 'Y-m-d', strtotime( '-1 day' ) );
    
    // Contar mensajes de texto del día anterior
    $text_messages = $wpdb->get_var( $wpdb->prepare(
        "SELECT COUNT(*) FROM {$table_conversations} 
        WHERE interaction_type = 'text' 
        AND DATE(created_at) = %s",
        $yesterday
    ) );
    
    // Sumar minutos de voz del día anterior
    $voice_minutes = $wpdb->get_var( $wpdb->prepare(
        "SELECT SUM(duration_seconds) / 60 FROM {$table_conversations} 
        WHERE interaction_type = 'voice' 
        AND DATE(created_at) = %s",
        $yesterday
    ) );
    
    // Contar usuarios totales
    $total_users = $wpdb->get_var( "SELECT COUNT(*) FROM {$table_end_users}" );
    
    // Contar usuarios activos del día anterior
    $active_users = $wpdb->get_var( $wpdb->prepare(
        "SELECT COUNT(*) FROM {$table_end_users} 
        WHERE DATE(last_interaction) = %s",
        $yesterday
    ) );
    
    // Calcular tiempo promedio de respuesta
    $avg_response = $wpdb->get_var( $wpdb->prepare(
        "SELECT AVG(response_time) FROM {$table_conversations} 
        WHERE response_time IS NOT NULL 
        AND DATE(created_at) = %s",
        $yesterday
    ) );
    
    // Insertar o actualizar analytics
    $wpdb->replace(
        $table_analytics,
        array(
            'date' => $yesterday,
            'total_text_messages' => $text_messages ?: 0,
            'total_voice_minutes' => $voice_minutes ?: 0,
            'total_end_users' => $total_users,
            'active_end_users' => $active_users,
            'avg_response_time' => $avg_response
        ),
        array( '%s', '%d', '%f', '%d', '%d', '%f' )
    );
}

// Revalidar license keys diariamente
add_action( 'ai_widget_revalidate_license', 'ai_widget_handle_license_revalidation' );
function ai_widget_handle_license_revalidation() {
    require_once AI_VOICE_TEXT_WIDGET_PLUGIN_DIR . 'includes/class-freemium.php';
    
    $result = AI_Widget_Freemium::revalidate_current_license();
    
    // Log del resultado (opcional)
    if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
        error_log( 'AI Widget - License revalidation: ' . ( $result['success'] ? 'SUCCESS' : 'FAILED' ) . ' - ' . $result['message'] );
    }
}

