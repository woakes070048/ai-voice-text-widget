# AI Widget by Workfluz

![WordPress Plugin Version](https://img.shields.io/badge/version-1.0.0-blue.svg)
![WordPress Compatibility](https://img.shields.io/badge/wordpress-6.0%2B-green.svg)
![PHP Version](https://img.shields.io/badge/php-7.4%2B-purple.svg)
![License](https://img.shields.io/badge/license-GPLv2%2B-red.svg)

AI-powered chat and voice widget for WordPress with OpenAI, VAPI, and ElevenLabs integration.

## 🚀 Features

### Core Capabilities
- **💬 Text Chat**: AI-powered conversations using OpenAI
- **🎤 Voice Calls**: Real-time voice interaction with VAPI
- **🎨 Customizable**: Brand colors, logo, and appearance
- **📊 Analytics**: Track usage and conversations
- **🔒 Freemium Model**: Free plan with limits, premium for unlimited
- **🌐 Multi-language**: Works with 100+ languages
- **📱 Responsive**: Desktop and mobile-friendly
- **⚡ Auto-start**: Single-click activation for one-mode setups

### 🔥 Advanced Features (Coming Soon)

#### 🛠️ Function Calling & Tools Integration
- **HTTP Request Tools**: AI can make external API calls directly
- **Workflow Automation**: Trigger remote workflows and automations
- **Custom Functions**: Define custom tools for AI to execute
- **Real-time Actions**: AI can perform actions based on user requests

#### 🔗 n8n Integration
- **Native n8n Support**: Direct integration with n8n workflows
- **Webhook Triggers**: Activate n8n workflows from AI conversations
- **Data Exchange**: Send/receive data between AI and n8n
- **Automation Workflows**: Complex automation triggered by AI

#### 🛒 E-commerce Integration
- **WooCommerce Native**: Deep WooCommerce integration
- **Product Recommendations**: AI suggests products based on conversation
- **Shopping Cart Actions**: Add/remove products, view cart, checkout
- **Order Management**: Check order status, track shipments
- **Inventory Queries**: Real-time product availability
- **Price Comparisons**: Compare products and prices
- **Purchase Assistance**: Guided shopping experience

#### 🌐 Context-Aware Intelligence
- **Page Content Analysis**: AI reads and understands current page content
- **Product Page Context**: Knows which product user is viewing
- **Dynamic Responses**: Answers based on visible page information
- **Smart Navigation**: Can guide users to relevant pages
- **Content-Based Assistance**: Provides help based on current context

## 📋 Requirements

- WordPress 6.0 or higher
- PHP 7.4 or higher
- API Keys from:
  - [OpenAI](https://platform.openai.com/api-keys) (required for chat)
  - [VAPI](https://vapi.ai) (required for voice)
  - [ElevenLabs](https://elevenlabs.io) (optional for TTS)
  
### Optional Integrations
- **WooCommerce** 3.0+ (for e-commerce features)
- **n8n** (self-hosted or cloud) (for workflow automation)
- **HTTP endpoints** (for custom integrations)

## 📦 Installation

### From WordPress.org (Recommended)

1. Go to **Plugins > Add New** in WordPress admin
2. Search for "AI Widget by Workfluz"
3. Click **Install Now**
4. Click **Activate**

### Manual Installation

1. Download the plugin ZIP file
2. Go to **Plugins > Add New > Upload Plugin**
3. Choose the ZIP file and click **Install Now**
4. Click **Activate**

### From GitHub

```bash
cd wp-content/plugins
git clone https://github.com/workfluz/ai-widget.git ai-voice-text-widget
```

## ⚙️ Configuration

1. Navigate to **AI Widget > General** in WordPress admin
2. Enter your API keys:
   - **OpenAI API Key** (required for chat)
   - **VAPI Public Key** (required for voice)
   - **VAPI Assistant ID** (required for voice)
   - **ElevenLabs API Key** (optional)
3. Choose interaction modes (text, voice, or both)
4. Customize appearance (colors, logo, position)
5. Set system prompt to define assistant personality
6. Save settings

The widget will appear automatically on your website.

## 🎨 Customization

### Colors
- **Primary Color**: Main widget color
- **Secondary Color**: Accent color for buttons

### Position
- Bottom Right (default)
- Bottom Left

### Logo
- Upload custom SVG logo (Premium)
- Default Workfluz logo included

### Messages
- Welcome message
- Placeholder text
- Assistant name

## 💎 Freemium Model

### Free Plan
- ✅ 100 text messages/month
- ✅ 30 voice minutes/month
- ✅ Basic features
- ⚠️ "Powered by Workfluz" branding

### Premium Plan
- ✅ Unlimited messages
- ✅ Unlimited voice minutes
- ✅ Remove branding
- ✅ Priority support
- ✅ Advanced customization

**Get Premium**: Contact support@workfluz.com

## 🔐 Privacy & External Services

This plugin connects to third-party services:

### OpenAI API
- **Purpose**: AI chat responses
- **Data Sent**: User messages, conversation context
- **Privacy**: [OpenAI Privacy Policy](https://openai.com/privacy)
- **Terms**: [OpenAI Terms](https://openai.com/terms)

### VAPI SDK
- **Purpose**: Voice call functionality
- **Data Sent**: Voice audio, call metadata
- **Privacy**: [VAPI Privacy Policy](https://vapi.ai/privacy)
- **Terms**: [VAPI Terms](https://vapi.ai/terms)

### ElevenLabs (Optional)
- **Purpose**: Text-to-speech synthesis
- **Data Sent**: Text for voice conversion
- **Privacy**: [ElevenLabs Privacy](https://elevenlabs.io/privacy)
- **Terms**: [ElevenLabs Terms](https://elevenlabs.io/terms)

**Important**: Ensure your website's privacy policy discloses the use of these services.

## 📊 Database Tables

The plugin creates the following tables:

- `wp_ai_widget_installation` - Installation config & usage limits
- `wp_ai_widget_end_users` - Visitor sessions and stats
- `wp_ai_widget_conversations` - Message history
- `wp_ai_widget_analytics` - Daily analytics data

## 🛠️ Developer Hooks

### Filters

```php
// Custom license validation
add_filter( 'ai_widget_custom_license_validation', function( $result, $license_key ) {
    // Your validation logic
    return array(
        'success' => true,
        'data' => array( 'plan' => 'premium' ),
        'message' => 'License valid'
    );
}, 10, 2 );

// Modify free plan limits
add_filter( 'ai_widget_max_messages', function( $limit ) {
    return 200; // Increase to 200 messages
} );

add_filter( 'ai_widget_max_voice_minutes', function( $limit ) {
    return 60; // Increase to 60 minutes
} );
```

### Actions

```php
// Daily cleanup hook
add_action( 'ai_widget_daily_cleanup', function() {
    // Custom cleanup logic
} );

// Daily analytics hook
add_action( 'ai_widget_daily_analytics', function() {
    // Custom analytics logic
} );
```

## 📞 Support

- **Email**: support@workfluz.com
- **WhatsApp**: +57 333 430 8871
- **WordPress Forum**: [Support Forum](https://wordpress.org/support/plugin/ai-voice-text-widget/)
- **Documentation**: [workfluz.com/docs](https://workfluz.com/docs)

## 👨‍💻 About the Developer

**Josue Ayala**
- Founder & Lead Developer at Workfluz
- Location: Medellín, Colombia
- Contact: support@workfluz.com
- WhatsApp: +57 333 430 8871

## 📄 License

This plugin is licensed under GPLv2 or later.

```
Copyright (C) 2024-2025 Josue Ayala - Workfluz

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
```

## 🌟 Credits

Powered by:
- [OpenAI](https://openai.com)
- [VAPI](https://vapi.ai)
- [ElevenLabs](https://elevenlabs.io)

---

**Made with ❤️ in Medellín, Colombia**