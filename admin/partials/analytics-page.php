<?php
/**
 * Página de Estadísticas y Analíticas.
 *
 * @package AI_Voice_Text_Widget
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

global $wpdb;
$table_users = $wpdb->prefix . 'ai_widget_users';
$table_conversations = $wpdb->prefix . 'ai_widget_conversations';
$table_analytics = $wpdb->prefix . 'ai_widget_analytics';

// Estadísticas generales
$total_users = $wpdb->get_var( "SELECT COUNT(*) FROM {$table_users}" );
$total_conversations = $wpdb->get_var( "SELECT COUNT(*) FROM {$table_conversations}" );
$total_messages = $wpdb->get_var( "SELECT SUM(message_count) FROM {$table_users}" );
$avg_messages_per_user = $total_users > 0 ? round( $total_messages / $total_users, 2 ) : 0;

// Usuarios activos hoy
$today = date( 'Y-m-d' );
$users_today = $wpdb->get_var( 
    $wpdb->prepare( 
        "SELECT COUNT(*) FROM {$table_users} WHERE DATE(last_interaction) = %s",
        $today
    )
);

// Mensajes de los últimos 7 días
$messages_last_7_days = $wpdb->get_results(
    "SELECT DATE(created_at) as date, COUNT(*) as count 
    FROM {$table_conversations} 
    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) 
    GROUP BY DATE(created_at) 
    ORDER BY date ASC"
);

// Preparar datos para el gráfico
$chart_labels = array();
$chart_data = array();
for ( $i = 6; $i >= 0; $i-- ) {
    $date = date( 'Y-m-d', strtotime( "-$i days" ) );
    $chart_labels[] = date( 'D', strtotime( $date ) );
    
    $count = 0;
    foreach ( $messages_last_7_days as $day ) {
        if ( $day->date === $date ) {
            $count = $day->count;
            break;
        }
    }
    $chart_data[] = $count;
}
?>

<div class="wrap">
    <h1>📊 Estadísticas y Analíticas</h1>
    <p class="description">Analiza el uso y rendimiento del widget de IA.</p>
    
    <!-- KPIs Principales -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin: 30px 0;">
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 25px; border-radius: 12px; color: white; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
            <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px;">Total Usuarios</div>
            <div style="font-size: 36px; font-weight: 700;"><?php echo number_format( $total_users ); ?></div>
        </div>
        
        <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); padding: 25px; border-radius: 12px; color: white; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
            <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px;">Conversaciones</div>
            <div style="font-size: 36px; font-weight: 700;"><?php echo number_format( $total_conversations ); ?></div>
        </div>
        
        <div style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); padding: 25px; border-radius: 12px; color: white; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
            <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px;">Total Mensajes</div>
            <div style="font-size: 36px; font-weight: 700;"><?php echo number_format( $total_messages ); ?></div>
        </div>
        
        <div style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); padding: 25px; border-radius: 12px; color: white; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
            <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px;">Promedio por Usuario</div>
            <div style="font-size: 36px; font-weight: 700;"><?php echo number_format( $avg_messages_per_user, 1 ); ?></div>
        </div>
        
        <div style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); padding: 25px; border-radius: 12px; color: white; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
            <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px;">Activos Hoy</div>
            <div style="font-size: 36px; font-weight: 700;"><?php echo number_format( $users_today ); ?></div>
        </div>
    </div>

    <!-- Gráfico de Mensajes -->
    <div style="background: white; padding: 30px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin: 30px 0;">
        <h2 style="margin-top: 0;">📈 Actividad de los últimos 7 días</h2>
        <canvas id="messagesChart" style="max-height: 300px;"></canvas>
    </div>

    <!-- Distribución de Uso -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin: 30px 0;">
        <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <h3 style="margin-top: 0;">🎯 Distribución por Uso</h3>
            <?php
            $free_limit = get_option( 'ai_widget_free_limit', 100 );
            $under_25 = $wpdb->get_var( "SELECT COUNT(*) FROM {$table_users} WHERE message_count < 25 AND message_count > 0" );
            $between_25_50 = $wpdb->get_var( "SELECT COUNT(*) FROM {$table_users} WHERE message_count >= 25 AND message_count < 50" );
            $between_50_75 = $wpdb->get_var( "SELECT COUNT(*) FROM {$table_users} WHERE message_count >= 50 AND message_count < 75" );
            $over_75 = $wpdb->get_var( "SELECT COUNT(*) FROM {$table_users} WHERE message_count >= 75" );
            ?>
            <div style="margin-top: 20px;">
                <div style="margin-bottom: 15px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                        <span>0-25 mensajes</span>
                        <strong><?php echo $under_25; ?> usuarios</strong>
                    </div>
                    <div style="background: #e5e7eb; height: 10px; border-radius: 5px; overflow: hidden;">
                        <div style="background: #10b981; height: 100%; width: <?php echo $total_users > 0 ? ( $under_25 / $total_users ) * 100 : 0; ?>%;"></div>
                    </div>
                </div>
                
                <div style="margin-bottom: 15px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                        <span>25-50 mensajes</span>
                        <strong><?php echo $between_25_50; ?> usuarios</strong>
                    </div>
                    <div style="background: #e5e7eb; height: 10px; border-radius: 5px; overflow: hidden;">
                        <div style="background: #3b82f6; height: 100%; width: <?php echo $total_users > 0 ? ( $between_25_50 / $total_users ) * 100 : 0; ?>%;"></div>
                    </div>
                </div>
                
                <div style="margin-bottom: 15px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                        <span>50-75 mensajes</span>
                        <strong><?php echo $between_50_75; ?> usuarios</strong>
                    </div>
                    <div style="background: #e5e7eb; height: 10px; border-radius: 5px; overflow: hidden;">
                        <div style="background: #f59e0b; height: 100%; width: <?php echo $total_users > 0 ? ( $between_50_75 / $total_users ) * 100 : 0; ?>%;"></div>
                    </div>
                </div>
                
                <div style="margin-bottom: 15px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                        <span>75+ mensajes</span>
                        <strong><?php echo $over_75; ?> usuarios</strong>
                    </div>
                    <div style="background: #e5e7eb; height: 10px; border-radius: 5px; overflow: hidden;">
                        <div style="background: #ef4444; height: 100%; width: <?php echo $total_users > 0 ? ( $over_75 / $total_users ) * 100 : 0; ?>%;"></div>
                    </div>
                </div>
            </div>
        </div>

        <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <h3 style="margin-top: 0;">⏰ Actividad Reciente</h3>
            <?php
            $recent_activity = $wpdb->get_results(
                "SELECT session_id, message_count, last_interaction 
                FROM {$table_users} 
                ORDER BY last_interaction DESC 
                LIMIT 5"
            );
            ?>
            <?php if ( ! empty( $recent_activity ) ) : ?>
                <div style="margin-top: 20px;">
                    <?php foreach ( $recent_activity as $activity ) : ?>
                        <div style="padding: 12px; background: #f9fafb; border-radius: 8px; margin-bottom: 10px; border-left: 3px solid #3b82f6;">
                            <div style="font-size: 12px; color: #6b7280; margin-bottom: 4px;">
                                <?php echo esc_html( human_time_diff( strtotime( $activity->last_interaction ), current_time( 'timestamp' ) ) ); ?> ago
                            </div>
                            <div style="font-family: monospace; font-size: 11px; color: #9ca3af;">
                                <?php echo esc_html( substr( $activity->session_id, 0, 25 ) . '...' ); ?>
                            </div>
                            <div style="margin-top: 6px; font-size: 13px; font-weight: 600; color: #374151;">
                                <?php echo number_format( $activity->message_count ); ?> mensajes
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <p style="color: #9ca3af; text-align: center; margin-top: 40px;">Sin actividad reciente</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Información adicional -->
    <div style="background: #f0f6fc; border-left: 4px solid #2271b1; padding: 20px; border-radius: 4px; margin-top: 30px;">
        <h3 style="margin-top: 0;">💡 Tips para Mejorar el Engagement</h3>
        <ul style="margin: 0;">
            <li>Si muchos usuarios alcanzan el límite rápidamente, considera <strong>aumentar el límite gratuito</strong></li>
            <li>Analiza las horas de mayor actividad para <strong>optimizar la disponibilidad</strong></li>
            <li>Usuarios con <strong>0 mensajes</strong> pueden indicar problemas de UX o configuración</li>
            <li>Un promedio bajo por usuario puede sugerir que el asistente <strong>no está siendo útil</strong></li>
        </ul>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
jQuery(document).ready(function($) {
    const ctx = document.getElementById('messagesChart');
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode( $chart_labels ); ?>,
            datasets: [{
                label: 'Mensajes',
                data: <?php echo json_encode( $chart_data ); ?>,
                borderColor: '#2271b1',
                backgroundColor: 'rgba(34, 113, 177, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });
});
</script>
