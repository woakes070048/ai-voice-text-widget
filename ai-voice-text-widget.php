<?php
/**
 * Plugin Name: AI Widget by Workfluz
 * Plugin URI: https://workfluz.com/ai-widget
 * Description: AI-powered chat and voice widget using VAPI, ElevenLabs, and OpenAI. Includes freemium model with usage limits. Connects to external services: OpenAI API, VAPI SDK, and ElevenLabs.
 * Version: 1.0.0
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * Author: Josue Ayala - Workfluz
 * Author URI: https://workfluz.com
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: ai-voice-text-widget
 * Domain Path: /languages
 * 
 * @package AI_Voice_Text_Widget
 * 
 * This plugin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 * 
 * This plugin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this plugin. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
 * 
 * EXTERNAL SERVICES DISCLOSURE:
 * This plugin connects to the following third-party services:
 * 
 * 1. OpenAI API (https://api.openai.com)
 *    - Purpose: AI chat completions and assistant functionality
 *    - Data sent: User messages, conversation context
 *    - Terms: https://openai.com/terms
 *    - Privacy: https://openai.com/privacy
 * 
 * 2. VAPI SDK (https://vapi.ai)
 *    - Purpose: Voice call functionality
 *    - Data sent: Voice audio, call metadata
 *    - Terms: https://vapi.ai/terms
 *    - Privacy: https://vapi.ai/privacy
 * 
 * 3. ElevenLabs API (https://elevenlabs.io)
 *    - Purpose: Text-to-speech conversion (optional)
 *    - Data sent: Text for voice synthesis
 *    - Terms: https://elevenlabs.io/terms
 *    - Privacy: https://elevenlabs.io/privacy
 * 
 * By using this plugin and configuring API keys, you agree to the terms
 * and privacy policies of these services.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

define( 'AI_VOICE_TEXT_WIDGET_VERSION', '1.0.0' );
define( 'AI_VOICE_TEXT_WIDGET_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'AI_VOICE_TEXT_WIDGET_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * Activación del plugin.
 */
function activate_ai_voice_text_widget() {
    require_once AI_VOICE_TEXT_WIDGET_PLUGIN_DIR . 'includes/class-activator.php';
    AI_Voice_Text_Widget_Activator::activate();
}
register_activation_hook( __FILE__, 'activate_ai_voice_text_widget' );

/**
 * Desactivación del plugin.
 */
function deactivate_ai_voice_text_widget() {
    require_once AI_VOICE_TEXT_WIDGET_PLUGIN_DIR . 'includes/class-deactivator.php';
    AI_Voice_Text_Widget_Deactivator::deactivate();
}
register_deactivation_hook( __FILE__, 'deactivate_ai_voice_text_widget' );

/**
 * Carga las clases principales.
 */
require AI_VOICE_TEXT_WIDGET_PLUGIN_DIR . 'includes/class-database.php';
require AI_VOICE_TEXT_WIDGET_PLUGIN_DIR . 'includes/class-ai-engine.php';
require AI_VOICE_TEXT_WIDGET_PLUGIN_DIR . 'includes/class-freemium.php';
require AI_VOICE_TEXT_WIDGET_PLUGIN_DIR . 'includes/class-analytics.php';
require AI_VOICE_TEXT_WIDGET_PLUGIN_DIR . 'includes/cron-jobs.php';

/**
 * Carga la administración si estamos en el admin.
 */
if ( is_admin() ) {
    require AI_VOICE_TEXT_WIDGET_PLUGIN_DIR . 'admin/class-admin.php';
    require AI_VOICE_TEXT_WIDGET_PLUGIN_DIR . 'admin/class-settings.php';
    
    $admin = new AI_Voice_Text_Widget_Admin();
    $settings = new AI_Voice_Text_Widget_Settings();
    
    add_action( 'admin_init', array( $settings, 'register_settings' ) );
}

/**
 * Carga el frontend.
 */
require AI_VOICE_TEXT_WIDGET_PLUGIN_DIR . 'public/class-public.php';
$public = new AI_Voice_Text_Widget_Public();

/**
 * Registra los endpoints de la API REST.
 */
require AI_VOICE_TEXT_WIDGET_PLUGIN_DIR . 'includes/class-api.php';
$api = new AI_Voice_Text_Widget_API();
add_action( 'rest_api_init', array( $api, 'register_routes' ) );
