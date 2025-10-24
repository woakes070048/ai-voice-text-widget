=== AI Widget by Workfluz ===
Contributors: josueayala
Donate link: https://workfluz.com/donate
Tags: ai, chatbot, voice, woocommerce, ecommerce
Requires at least: 6.0
Tested up to: 6.7
Stable tag: 1.0.0
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

AI-powered chat and voice assistant with WooCommerce integration, n8n workflows, and advanced function calling. Transform your site into an intelligent assistant.

== Description ==

AI Widget by Workfluz is the **most advanced AI assistant plugin for WordPress**. It goes beyond simple chat - your AI can take actions, integrate with e-commerce, trigger workflows, and provide context-aware assistance based on page content.

= 🌟 What Makes This Different =

Unlike other chatbot plugins, AI Widget by Workfluz includes:

* **Function Calling & Tools**: AI can execute actions, not just respond
* **WooCommerce Integration**: Deep e-commerce capabilities for shopping assistance
* **n8n Workflow Integration**: Connect AI to powerful automation workflows
* **Context-Aware**: AI understands what page the user is viewing
* **HTTP Request Tools**: AI can call external APIs and services
* **Smart Shopping Assistant**: Help customers find products, check inventory, manage cart

= Key Features =

* **Dual Mode Interaction**: Text chat and voice calls
* **Multiple AI Providers**: OpenAI, VAPI, ElevenLabs support
* **Freemium Model**: Free plan with usage limits, premium for unlimited
* **Customizable Design**: Match your brand colors and logo
* **System Prompts**: Define your assistant's personality
* **Analytics Dashboard**: Track usage and conversations
* **Responsive Widget**: Works on desktop and mobile
* **Auto-start Mode**: Single-click activation when only one mode is enabled

= 🛒 E-commerce Superpowers (Coming Soon) =

**WooCommerce Integration:**
* Product search and recommendations
* Add to cart directly from chat
* Check product availability and stock
* View cart contents and total
* Provide product comparisons
* Answer product-specific questions
* Guide checkout process
* Track order status

**Works with:**
* WooCommerce
* Easy Digital Downloads (planned)
* WP E-Commerce (planned)

= 🔗 Workflow Automation (Coming Soon) =

**n8n Integration:**
* Trigger n8n workflows from AI conversations
* Send data to external systems
* Create support tickets automatically
* Update CRM from chat interactions
* Schedule appointments
* Send notifications to Slack/Discord/etc.
* Custom automation workflows

**HTTP Request Tools:**
* Call any REST API
* Integrate with third-party services
* Trigger webhooks
* Exchange data with external systems

= 🧠 Context-Aware Intelligence (Coming Soon) =

The AI understands:
* Current page content
* Product being viewed
* User's browsing context
* Site structure and navigation
* Available products and services

This enables:
* Relevant product suggestions
* Page-specific assistance
* Smart navigation guidance
* Contextual answers
* Personalized recommendations

= Use Cases =

**E-commerce Sites:**
* Product recommendations and search
* Shopping cart assistance
* Order tracking and support
* Inventory inquiries
* Price comparisons
* Guided shopping experience

**Business Websites:**
* Customer support automation
* FAQ assistant
* Lead generation and qualification
* Appointment scheduling
* Service recommendations
* Contact form alternative

**Content Sites:**
* Article recommendations
* Content navigation
* Search assistance
* User engagement
* Newsletter signups

**Automation & Workflows:**
* Trigger n8n workflows
* Create support tickets
* Update CRM systems
* Send notifications
* Data collection and processing
* Custom integrations

= Freemium Model =

**Free Plan:**
* 100 text messages per month
* 30 voice minutes per month
* Basic features
* Workfluz branding

**Premium Plan (via license key):**
* Unlimited messages and voice minutes
* Remove branding
* Priority support
* Advanced customization

= External Services & Privacy =

**IMPORTANT**: This plugin connects to third-party services to provide AI functionality. By using this plugin, you agree to the terms and privacy policies of these services:

**1. OpenAI API** (https://api.openai.com)
* **Purpose**: AI chat completions and assistant responses
* **Data Sent**: User messages, conversation history, system prompts
* **Required**: Yes, for text chat functionality
* **Terms of Service**: https://openai.com/terms
* **Privacy Policy**: https://openai.com/privacy
* **API Key**: You must provide your own OpenAI API key

**2. VAPI SDK** (https://vapi.ai)
* **Purpose**: Real-time voice call functionality
* **Data Sent**: Voice audio data, call metadata
* **Required**: Only if voice mode is enabled
* **Terms of Service**: https://vapi.ai/terms
* **Privacy Policy**: https://vapi.ai/privacy
* **API Key**: You must provide your own VAPI public key and assistant ID
* **CDN**: Loads JavaScript SDK from https://cdn.jsdelivr.net/gh/VapiAI/

**3. ElevenLabs API** (https://elevenlabs.io) [Optional]
* **Purpose**: Text-to-speech voice synthesis
* **Data Sent**: Text for voice conversion
* **Required**: No (optional voice provider)
* **Terms of Service**: https://elevenlabs.io/terms
* **Privacy Policy**: https://elevenlabs.io/privacy
* **API Key**: You must provide your own ElevenLabs API key (if using)

**Data Processing:**
* User messages and voice recordings are sent to these services for processing
* These services may store data according to their own retention policies
* No personal data is sent unless provided by the user in conversations
* Session IDs are generated locally and stored in browser cookies

**Compliance:**
* Ensure you have proper privacy policy disclosure on your website
* Inform users about data processing by third-party AI services
* Consider GDPR, CCPA, and other privacy regulations in your jurisdiction

= Configuration Required =

Before using this plugin, you must:

1. Create accounts with desired AI service providers
2. Obtain API keys from:
   - OpenAI (for chat): https://platform.openai.com/api-keys
   - VAPI (for voice): https://vapi.ai
   - ElevenLabs (optional): https://elevenlabs.io
3. Configure API keys in plugin settings

= Documentation =

* [Getting Started Guide](https://workfluz.com/docs/ai-widget/getting-started)
* [API Configuration](https://workfluz.com/docs/ai-widget/api-setup)
* [Customization Options](https://workfluz.com/docs/ai-widget/customization)

= Support =

* [Documentation](https://workfluz.com/docs)
* [Support Forum](https://wordpress.org/support/plugin/ai-voice-text-widget/)
* Email: support@workfluz.com
* WhatsApp: +57 333 430 8871 (Medellín, Colombia)

== Installation ==

= Automatic Installation =

1. Go to Plugins > Add New
2. Search for "AI Widget by Workfluz"
3. Click Install Now
4. Activate the plugin

= Manual Installation =

1. Download the plugin ZIP file
2. Go to Plugins > Add New > Upload Plugin
3. Choose the ZIP file and click Install Now
4. Activate the plugin

= Configuration =

1. Go to **AI Widget** > **General** in WordPress admin
2. Configure your AI provider API keys:
   - OpenAI API Key (required for chat)
   - VAPI Public Key & Assistant ID (required for voice)
3. Choose interaction modes (text, voice, or both)
4. Customize colors and appearance
5. Set system prompt to define assistant behavior
6. Save settings

The widget will appear on your website automatically.

== Frequently Asked Questions ==

= Do I need API keys to use this plugin? =

Yes, you need API keys from OpenAI (for chat) and/or VAPI (for voice). These services require payment and have their own pricing models.

= Is there a free version? =

Yes! The plugin includes a free plan with:
* 100 text messages per month
* 30 voice minutes per month

After reaching limits, users will see an upgrade prompt.

= How do I get unlimited usage? =

Contact Workfluz at support@workfluz.com for premium license information, or implement your own license validation system using the provided filters.

= Does it work with mobile devices? =

Yes, the widget is fully responsive and works on desktop, tablets, and mobile phones.

= Can I customize the design? =

Yes! You can customize:
* Primary and secondary colors
* Widget position (bottom-right or bottom-left)
* Logo (upload custom SVG)
* Assistant name
* Welcome message

= Which languages are supported? =

The plugin interface is in English and Spanish. The AI assistant can understand and respond in any language supported by OpenAI's models (100+ languages).

= Is my data secure? =

* Plugin uses WordPress security best practices (nonces, capability checks, data sanitization)
* Conversations are sent to external AI services (OpenAI, VAPI, ElevenLabs)
* These services have their own security measures and privacy policies
* You should review their policies and ensure compliance with your local regulations

= Can I use this for commercial purposes? =

Yes, the plugin is licensed under GPL-2.0-or-later, which allows commercial use.

= Does this work with caching plugins? =

Yes, the widget loads dynamically via JavaScript and is compatible with caching plugins.

= Can I disable voice or text mode? =

Yes, you can enable/disable each mode independently in General Settings. If only one mode is enabled, it will start automatically when users click the widget.

= How do I hide the "Powered by Workfluz" branding? =

The branding is removed automatically when you activate a premium license key.

== Screenshots ==

1. **Floating Widget** - Beautiful floating orb that opens the assistant
2. **Chat Interface** - Clean and modern chat window
3. **Voice Mode** - Real-time voice conversation with visual feedback
4. **Admin Dashboard** - Easy-to-use configuration panel
5. **Analytics** - Track usage and conversations
6. **Customization** - Brand colors and logo options
7. **System Prompt** - Define assistant personality

== Changelog ==

= 1.0.0 - 2025-10-24 =

**Initial Release**

Features:
* Text chat with OpenAI integration
* Voice calls with VAPI integration
* Freemium model with usage limits
* Customizable colors and branding
* System prompt configuration
* Analytics dashboard
* Multi-provider support (OpenAI, VAPI, ElevenLabs)
* Auto-start mode for single interaction type
* Responsive design
* Session-based conversation tracking
* Usage limit enforcement
* License key system
* Comprehensive admin interface

== Upgrade Notice ==

= 1.0.0 =
Initial release. Welcome to AI Widget by Workfluz!

== Privacy Policy ==

This plugin:

* Stores session IDs in browser cookies for conversation continuity
* Stores conversation history in WordPress database (can be disabled)
* Sends user messages to third-party AI services (OpenAI, VAPI, ElevenLabs)
* Does not collect personal information unless provided by users in conversations
* Uses standard WordPress options to store plugin settings

External services used:
* OpenAI API - for AI chat responses
* VAPI - for voice call functionality  
* ElevenLabs - for optional text-to-speech

Please review the privacy policies of these services and ensure your website's privacy policy discloses their use.

== Developer Notes ==

= Hooks and Filters =

**Filters:**

`ai_widget_custom_license_validation` - Custom license validation
`ai_widget_max_messages` - Modify free plan message limit
`ai_widget_max_voice_minutes` - Modify free plan voice limit

**Actions:**

`ai_widget_daily_cleanup` - Runs daily to clean old conversations
`ai_widget_daily_analytics` - Runs daily to generate analytics

= Custom License Server =

To implement your own license server:

```php
define( 'AI_WIDGET_LICENSE_API_URL', 'https://your-site.com/api/validate' );
```

Or use the filter:

```php
add_filter( 'ai_widget_custom_license_validation', function( $result, $license_key ) {
    // Your custom validation logic
    return array(
        'success' => true,
        'data' => array( 'plan' => 'premium' ),
        'message' => 'License valid'
    );
}, 10, 2 );
```

== Credits ==

Developed by Josue Ayala - Workfluz
Location: Medellín, Colombia
Website: https://workfluz.com
Contact: support@workfluz.com | WhatsApp +57 333 430 8871

Powered by:
* OpenAI - https://openai.com
* VAPI - https://vapi.ai
* ElevenLabs - https://elevenlabs.io