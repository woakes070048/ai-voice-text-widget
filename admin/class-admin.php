<?php
/**
 * Administración del plugin.
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

class AI_Voice_Text_Widget_Admin {

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
        add_action( 'admin_head', array( $this, 'add_menu_icon_style' ) );
        
        // AJAX handlers para license keys
        add_action( 'wp_ajax_validate_license', array( $this, 'ajax_validate_license' ) );
        add_action( 'wp_ajax_revalidate_license', array( $this, 'ajax_revalidate_license' ) );
        add_action( 'wp_ajax_deactivate_license', array( $this, 'ajax_deactivate_license' ) );
    }

    public function add_admin_menu() {
        // Menú principal con ícono SVG personalizado
        add_menu_page(
            __( 'AI Widget', 'ai-voice-text-widget' ),
            __( 'AI Widget', 'ai-voice-text-widget' ),
            'manage_options',
            'ai-voice-text-widget',
            array( $this, 'render_settings_page' ),
            $this->get_menu_icon_svg(),
            30
        );

        // Submenú: General (renombrar el menú principal)
        add_submenu_page(
            'ai-voice-text-widget',
            __( 'Configuración General', 'ai-voice-text-widget' ),
            __( 'General', 'ai-voice-text-widget' ),
            'manage_options',
            'ai-voice-text-widget',
            array( $this, 'render_settings_page' )
        );

        // Submenú: Proveedores de IA
        add_submenu_page(
            'ai-voice-text-widget',
            __( 'Proveedores de IA', 'ai-voice-text-widget' ),
            __( 'Proveedores IA', 'ai-voice-text-widget' ),
            'manage_options',
            'ai-widget-providers',
            array( $this, 'render_providers_page' )
        );

        // Submenú: System Prompt
        add_submenu_page(
            'ai-voice-text-widget',
            __( 'System Prompt', 'ai-voice-text-widget' ),
            __( 'System Prompt', 'ai-voice-text-widget' ),
            'manage_options',
            'ai-widget-system-prompt',
            array( $this, 'render_system_prompt_page' )
        );

        // Submenú: Apariencia
        add_submenu_page(
            'ai-voice-text-widget',
            __( 'Apariencia del Widget', 'ai-voice-text-widget' ),
            __( 'Apariencia', 'ai-voice-text-widget' ),
            'manage_options',
            'ai-widget-appearance',
            array( $this, 'render_appearance_page' )
        );

        // Submenú: Freemium
        add_submenu_page(
            'ai-voice-text-widget',
            __( 'Configuración Freemium', 'ai-voice-text-widget' ),
            __( 'Freemium', 'ai-voice-text-widget' ),
            'manage_options',
            'ai-widget-freemium',
            array( $this, 'render_freemium_page' )
        );

        // Submenú: Estadísticas
        add_submenu_page(
            'ai-voice-text-widget',
            __( 'Estadísticas y Analíticas', 'ai-voice-text-widget' ),
            __( 'Estadísticas', 'ai-voice-text-widget' ),
            'manage_options',
            'ai-widget-analytics',
            array( $this, 'render_analytics_page' )
        );
    }

    /**
     * Genera el SVG del ícono del menú.
     */
    private function get_menu_icon_svg() {
        // Logo oficial de Workfluz para el menú de WordPress
        $svg = 'data:image/svg+xml;base64,' . base64_encode(
            '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 1972.8 1870.45">
                <defs>
                    <linearGradient id="workfluz-gradient" x1="0" y1="935.23" x2="1972.8" y2="935.23" gradientUnits="userSpaceOnUse">
                        <stop offset="0" stop-color="#76b4e3"/>
                        <stop offset="1" stop-color="#009bf0"/>
                    </linearGradient>
                </defs>
                <path fill="url(#workfluz-gradient)" d="M1852.35,0c-53.22,0-100.11,34.94-115.34,85.94l-457.68,1533.26-232.79-916.61c-13.55-53.37-61.6-90.73-116.65-90.73h-85.51c-54.63,0-102.42,36.8-116.37,89.63l-243.58,922.28-239.29-921.78c-13.78-53.07-61.67-90.12-116.5-90.12h-8.2c-81.18,0-139.09,78.72-114.89,156.23l317.74,1017.87c15.7,50.27,62.23,84.5,114.89,84.5h93.66c54.27,0,101.84-36.33,116.12-88.69l229.35-840.98,229.36,840.98c14.28,52.36,61.84,88.69,116.13,88.69h94.97c52.02,0,98.16-33.42,114.37-82.84L1966.71,157.88C1992.24,80.04,1934.26,0,1852.35,0Z"/>
            </svg>'
        );
        
        return $svg;
    }

    /**
     * Agrega estilos CSS para el ícono del menú.
     */
    public function add_menu_icon_style() {
        ?>
        <style>
            /* Estilo para el ícono SVG del menú AI Widget - Logo Workfluz (W) con gradiente */
            #adminmenu #toplevel_page_ai-voice-text-widget .wp-menu-image img {
                width: 20px;
                height: 20px;
                padding: 6px 0;
                filter: none !important;
                opacity: 0.85 !important;
            }
            
            /* Efecto hover - más brillante y visible */
            #adminmenu #toplevel_page_ai-voice-text-widget:hover .wp-menu-image img {
                opacity: 1 !important;
                filter: brightness(1.15) !important;
            }
            
            /* Cuando está activo - máximo brillo */
            #adminmenu #toplevel_page_ai-voice-text-widget.current .wp-menu-image img,
            #adminmenu #toplevel_page_ai-voice-text-widget.wp-has-current-submenu .wp-menu-image img {
                opacity: 1 !important;
                filter: brightness(1.25) drop-shadow(0 0 3px rgba(7, 128, 173, 0.5)) !important;
            }
        </style>
        <?php
    }

    public function render_settings_page() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        include AI_VOICE_TEXT_WIDGET_PLUGIN_DIR . 'admin/partials/general-page.php';
    }

    public function render_providers_page() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        include AI_VOICE_TEXT_WIDGET_PLUGIN_DIR . 'admin/partials/providers-page.php';
    }

    public function render_system_prompt_page() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        include AI_VOICE_TEXT_WIDGET_PLUGIN_DIR . 'admin/partials/system-prompt-page.php';
    }

    public function render_appearance_page() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        include AI_VOICE_TEXT_WIDGET_PLUGIN_DIR . 'admin/partials/appearance-page.php';
    }

    public function render_freemium_page() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        include AI_VOICE_TEXT_WIDGET_PLUGIN_DIR . 'admin/partials/freemium-page.php';
    }

    public function render_analytics_page() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        include AI_VOICE_TEXT_WIDGET_PLUGIN_DIR . 'admin/partials/analytics-page.php';
    }

    public function enqueue_admin_assets( $hook ) {
        // Cargar assets en todas las páginas del plugin
        $plugin_pages = array(
            'toplevel_page_ai-voice-text-widget',
            'ai-widget_page_ai-widget-providers',
            'ai-widget_page_ai-widget-system-prompt',
            'ai-widget_page_ai-widget-appearance',
            'ai-widget_page_ai-widget-freemium',
            'ai-widget_page_ai-widget-analytics'
        );

        if ( ! in_array( $hook, $plugin_pages ) ) {
            return;
        }

        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker' );
        
        wp_enqueue_style(
            'ai-widget-admin',
            AI_VOICE_TEXT_WIDGET_PLUGIN_URL . 'admin/css/admin-style.css',
            array(),
            AI_VOICE_TEXT_WIDGET_VERSION
        );
        
        wp_enqueue_script(
            'ai-widget-admin',
            AI_VOICE_TEXT_WIDGET_PLUGIN_URL . 'admin/js/admin-script.js',
            array( 'jquery', 'wp-color-picker' ),
            AI_VOICE_TEXT_WIDGET_VERSION,
            true
        );
    }

    /**
     * AJAX: Validar license key.
     */
    public function ajax_validate_license() {
        check_ajax_referer( 'validate_license' );
        
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => 'Permisos insuficientes' ) );
        }
        
        $license_key = isset( $_POST['license_key'] ) ? sanitize_text_field( $_POST['license_key'] ) : '';
        
        require_once AI_VOICE_TEXT_WIDGET_PLUGIN_DIR . 'includes/class-freemium.php';
        
        $result = AI_Widget_Freemium::validate_license( $license_key );
        
        if ( $result['success'] ) {
            wp_send_json_success( array( 'message' => $result['message'] ) );
        } else {
            wp_send_json_error( array( 'message' => $result['message'] ) );
        }
    }

    /**
     * AJAX: Revalidar license key actual.
     */
    public function ajax_revalidate_license() {
        check_ajax_referer( 'revalidate_license' );
        
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => 'Permisos insuficientes' ) );
        }
        
        require_once AI_VOICE_TEXT_WIDGET_PLUGIN_DIR . 'includes/class-freemium.php';
        
        $result = AI_Widget_Freemium::revalidate_current_license();
        
        if ( $result['success'] ) {
            wp_send_json_success( array( 'message' => $result['message'] ) );
        } else {
            wp_send_json_error( array( 'message' => $result['message'] ) );
        }
    }

    /**
     * AJAX: Desactivar license key.
     */
    public function ajax_deactivate_license() {
        check_ajax_referer( 'deactivate_license' );
        
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => 'Permisos insuficientes' ) );
        }
        
        require_once AI_VOICE_TEXT_WIDGET_PLUGIN_DIR . 'includes/class-freemium.php';
        
        $result = AI_Widget_Freemium::deactivate_license();
        
        if ( $result ) {
            wp_send_json_success( array( 'message' => 'License key desactivada correctamente' ) );
        } else {
            wp_send_json_error( array( 'message' => 'Error al desactivar la license key' ) );
        }
    }
}

