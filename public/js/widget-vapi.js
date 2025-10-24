/**
 * AI Widget by Workfluz - Widget App VAPI
 * 
 * @package AI_Voice_Text_Widget
 * @version 1.0.0
 * @copyright Copyright (c) 2024-2025 Workfluz. All rights reserved.
 * @license Proprietary - See LICENSE.txt
 * 
 * ============================================================================
 * AVISO LEGAL - CÓDIGO PROPIETARIO Y CONFIDENCIAL
 * ============================================================================
 * 
 * Este código es propiedad exclusiva de Workfluz y está protegido por las
 * leyes de derechos de autor y tratados internacionales de propiedad intelectual.
 * 
 * QUEDA PROHIBIDO:
 * - Copiar, modificar o redistribuir este código
 * - Realizar ingeniería inversa o descompilación
 * - Usar este código en proyectos no autorizados
 * - Eliminar estos avisos de copyright
 * 
 * El uso no autorizado puede resultar en acciones legales.
 * Para licencias: legal@workfluz.com | https://workfluz.com
 * 
 * © 2024-2025 Workfluz. Todos los derechos reservados.
 * ============================================================================
 */

(function() {
    'use strict';

    class AIVAPIWidget {
        constructor() {
            this.apiUrl = aiWidgetData.apiUrl;
            this.sessionId = this.getOrCreateSessionId();
            this.provider = aiWidgetData.provider || 'vapi';
            this.vapiPublicKey = aiWidgetData.vapiPublicKey || '';
            this.vapiAssistantId = aiWidgetData.vapiAssistantId || '';
            this.assistantName = aiWidgetData.assistantName || 'Asistente IA';
            
            this.isCallActive = false;
            this.isChatOpen = false;
            this.isSpeaking = false;
            this.currentMode = null;
            this.plan = 'free';
            this.remaining = 100;
            
            this.vapiInstance = null;
            
            // Voice tracking
            this.callStartTime = null;
            this.callDuration = 0;
            
            // Voice recording
            this.mediaRecorder = null;
            this.audioChunks = [];
            this.isRecording = false;

            this.init();
        }

        init() {
            this.injectCSS();
            this.createWidgetHTML();
            this.loadVapiSDK();
            this.checkLimits();
        }

        /**
         * Inyecta los estilos CSS
         */
        injectCSS() {
            const css = `
            @property --angle {
                syntax: '<angle>';
                initial-value: 0deg;
                inherits: false;
            }
            @keyframes rotate-gradient {
                from { --angle: 0deg; }
                to { --angle: 360deg; }
            }

            body > button[class*="vapi-"], body > div[class*="vapi-"][style*="position: fixed"]:not(#ai-vapi-widget-container) { display: none !important; visibility: hidden !important; }
            
            #ai-vapi-widget-container { 
                position: fixed !important; 
                bottom: 24px; 
                right: 24px; 
                z-index: 9999; 
                display: flex !important; 
                flex-direction: column; 
                align-items: flex-end; 
                gap: 12px; 
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; 
                transform: translate3d(0, 0, 0); 
                will-change: transform;
                pointer-events: none;
            }
            
            #ai-vapi-widget-container > * {
                pointer-events: auto;
            }
            
            #ai-status-text { background: rgba(255, 255, 255, 0.98); color: #1e293b; font-size: 14px; padding: 10px 16px; border-radius: 20px; box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12), 0 2px 4px rgba(0, 0, 0, 0.08); transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); font-weight: 500; white-space: nowrap; opacity: 0; transform: translateY(10px) scale(0.9); pointer-events: none; max-width: 250px; text-align: right; border: 1px solid rgba(0, 0, 0, 0.08); }
            #ai-status-text.visible { opacity: 1; transform: translateY(0) scale(1); }
            #ai-status-text.connecting { background: linear-gradient(135deg, #fff8e1 0%, #ffecb3 100%); color: #e65100; border-color: rgba(230, 81, 0, 0.2); }
            #ai-status-text.active { background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%); color: #2e7d32; border-color: rgba(46, 125, 50, 0.2); }
            #ai-status-text.speaking { background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%); color: #1565c0; border-color: rgba(21, 101, 192, 0.2); }
            
            .ai-logo-container { --glow-color-1: ${aiWidgetData.primaryColor || '#76b4e3'}; --glow-color-2: ${aiWidgetData.secondaryColor || '#4a90e2'}; --border-highlight: #ffffff; --glow-intensity: 18px; --glow-opacity: 0.75; position: relative; z-index: 10001; overflow: visible !important; }
            .ai-logo-container.session-active { --glow-color-1: #2196F3; --glow-color-2: #1976D2; --border-highlight: #ffffff; --glow-intensity: 22px; }
            
            @keyframes rotate-glow { from { transform: translate3d(0, 0, 0) rotate(0deg); } to { transform: translate3d(0, 0, 0) rotate(360deg); } }
            @keyframes bounce-in { 0% { transform: scale(0) rotate(-180deg); opacity: 0; } 50% { transform: scale(1.1) rotate(10deg); } 100% { transform: scale(1) rotate(0deg); opacity: 1; } }
            
            .ai-logo-button { position: relative; width: 70px; height: 70px; z-index: 10002; cursor: pointer; transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); animation: bounce-in 0.6s cubic-bezier(0.34, 1.56, 0.64, 1); transform: translate3d(0, 0, 0); will-change: transform; backface-visibility: hidden; -webkit-backface-visibility: hidden; overflow: visible !important; border: none; background: transparent; padding: 0; }
            .ai-logo-button:hover { transform: scale(1.08) translate3d(0, 0, 0); }
            .ai-logo-button:active { transform: scale(0.95) translate3d(0, 0, 0); }
            
            .ai-logo-button::before, .ai-logo-button::after { content: ''; position: absolute; left: 0; top: 0; width: 100%; height: 100%; border-radius: 50%; background-image: conic-gradient(from 0deg, transparent 35%, var(--glow-color-2), var(--glow-color-1) 49%, var(--border-highlight) 50%, var(--glow-color-1) 51%, var(--glow-color-2), transparent 65%); animation: rotate-glow 10s linear infinite; will-change: transform; transform: translate3d(0, 0, 0); backface-visibility: hidden; -webkit-backface-visibility: hidden; }
            .ai-logo-button::before { z-index: -2; filter: blur(var(--glow-intensity)); opacity: var(--glow-opacity); }
            .ai-logo-button::after { z-index: -1; }
            .ai-logo-container.loading .ai-logo-button::before, .ai-logo-container.loading .ai-logo-button::after { animation-duration: 1.8s; }
            .ai-logo-container.session-active .ai-logo-button::before, .ai-logo-container.session-active .ai-logo-button::after { animation-duration: 8s; }
            
            .ai-logo-background { position: absolute; z-index: 1; left: 2px; top: 2px; width: calc(100% - 4px); height: calc(100% - 4px); background: linear-gradient(135deg, #ffffff 0%, #f8fafc 50%, #f1f5f9 100%); border-radius: 50%; display: flex; justify-content: center; align-items: center; box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15), inset 0 1px 3px rgba(255, 255, 255, 0.8); }
            
            .ai-logo-svg { position: relative; z-index: 10; width: 65%; height: 65%; transform: translate(-5%, -5%); transition: opacity 0.4s ease, transform 0.4s ease; filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1)); }
            
            #ai-sound-wave-canvas { position: absolute; width: 100%; height: 100%; top: 0; left: 0; opacity: 0; z-index: 5; transition: opacity 0.5s ease; pointer-events: none; border-radius: 50%; }
            .ai-logo-container.session-active .ai-logo-svg { opacity: 0; transform: scale(0.7); }
            .ai-logo-container.session-active #ai-sound-wave-canvas { opacity: 1; }
            
            .ai-tooltip { position: absolute; bottom: calc(100% + 12px); right: 0; background: rgba(0, 0, 0, 0.9); color: white; padding: 8px 14px; border-radius: 8px; font-size: 13px; white-space: nowrap; opacity: 0; pointer-events: none; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25); }
            .ai-logo-container:not(.session-active) .ai-logo-button:hover .ai-tooltip { opacity: 1; transform: translateY(-4px); }
            
            /* Botones de acción pequeños (voz y chat) */
            .ai-action-button {
                position: absolute;
                width: 36px;
                height: 36px;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
                border: 2px solid rgba(255, 255, 255, 0.4);
                box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12), inset 0 1px 2px rgba(255, 255, 255, 0.8);
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
                z-index: 10003;
                opacity: 0;
                transform: translateY(0) scale(0.5);
                pointer-events: none;
            }
            
            /* Mostrar botones cuando el logo tiene clase 'menu-open' */
            .ai-logo-container.menu-open .ai-action-button {
                opacity: 1;
                pointer-events: auto;
            }
            
            .ai-action-button svg {
                width: 18px;
                height: 18px;
            }
            
            .ai-action-button:hover {
                transform: translateY(0) scale(1.15);
                box-shadow: 0 6px 24px rgba(0, 0, 0, 0.18), inset 0 1px 3px rgba(255, 255, 255, 0.9);
            }
            
            .ai-action-button:active {
                transform: translateY(0) scale(1.05);
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15), inset 0 1px 2px rgba(255, 255, 255, 0.7);
            }
            
            /* Botón de VOZ - se despliega hacia arriba */
            .ai-voice-button {
                bottom: 80px;
                right: 17px;
            }
            
            .ai-logo-container.menu-open .ai-voice-button {
                transform: translateY(0) scale(1);
            }
            
            .ai-voice-button svg {
                stroke: #3b82f6;
                transition: all 0.2s ease;
            }
            
            .ai-voice-button:hover {
                background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
                border-color: rgba(59, 130, 246, 0.6);
            }
            
            .ai-voice-button:hover svg {
                stroke: #ffffff;
                transform: scale(1.1);
            }
            
            /* Botón de CHAT - se despliega hacia arriba (más arriba que VOZ) */
            .ai-chat-button {
                bottom: 125px;
                right: 17px;
            }
            
            .ai-logo-container.menu-open .ai-chat-button {
                transform: translateY(0) scale(1);
            }
            
            .ai-chat-button svg {
                stroke: #10b981;
                transition: all 0.2s ease;
            }
            
            .ai-chat-button:hover {
                background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                border-color: rgba(16, 185, 129, 0.6);
            }
            
            .ai-chat-button:hover svg {
                stroke: #ffffff;
                transform: scale(1.1);
            }
            
            /* Ocultar botones cuando hay sesión activa o cargando */
            .ai-logo-container.session-active .ai-action-button,
            .ai-logo-container.loading .ai-action-button {
                opacity: 0;
                pointer-events: none;
                transform: translateY(0) scale(0.3);
            }
            
            @keyframes fade-in{from{opacity:0; transform:scale(.97)} to{opacity:1; transform:scale(1)}}
            
            .ai-mode-card, .ai-chat-container {
                border: 0;
                position: relative;
                background: transparent;
                box-shadow: 0 20px 60px rgba(0,0,0,.15), 0 8px 24px rgba(0,0,0,.08);
                font-family: -apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,system-ui,sans-serif;
            }
            
            .ai-mode-card::before, .ai-chat-container::before {
                content: '';
                position: absolute;
                inset: -2px;
                border-radius: 18px;
                background: linear-gradient(160deg, #e0f2fe, #bae6fd, #7dd3fc);
                z-index: -2;
                filter: blur(4px);
                opacity: 0.8;
            }
            .ai-mode-card::after, .ai-chat-container::after {
                content: '';
                position: absolute;
                inset: 0;
                border-radius: 16px;
                background: rgba(255, 255, 255, 0.92);
                backdrop-filter: blur(18px) saturate(160%);
                -webkit-backdrop-filter: blur(18px) saturate(160%);
                z-index: -1;
                border: 1px solid rgba(255, 255, 255, 0.6);
            }
            
            .ai-mode-card {
                width: 420px;
                max-width: calc(100vw - 40px);
                padding: 24px;
                animation: fade-in .25s cubic-bezier(.4,0,.2,1);
                box-sizing: border-box;
                margin: 0 auto;
                position: relative;
            }
            .ai-chat-container {
                position:fixed; bottom:110px; right:24px; width:360px; height:520px; max-height: calc(100vh - 140px); z-index:10001;
                display:flex; flex-direction:column; animation: fade-in .25s cubic-bezier(.4,0,.2,1); transform: translate3d(0, 0, 0); will-change: transform;
            }

            .ai-mode-prompt{ 
                position: fixed; 
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                width: 100vw;
                height: 100vh;
                display: none; 
                align-items: center; 
                justify-content: center; 
                padding: 20px; 
                margin: 0;
                background: rgba(0, 0, 0, 0.15); 
                z-index: 99999; 
                backdrop-filter: blur(3px);
                -webkit-backdrop-filter: blur(3px);
                pointer-events: auto;
                opacity: 0;
                transition: opacity 0.2s ease;
            }
            
            .ai-mode-prompt.visible {
                display: flex;
                opacity: 1;
            }
            
            .ai-mode-header{ 
                z-index: 2; 
                position: relative; 
                display: flex; 
                align-items: center; 
                justify-content: space-between; 
                margin-bottom: 4px;
            }
            .ai-mode-header h4{ 
                margin: 0; 
                color: #0f172a; 
                font-size: 18px; 
                font-weight: 600; 
                line-height: 1.4;
            }
            
            .ai-mode-close{ 
                background: rgba(255, 255, 255, 0.9); 
                border: 1px solid rgba(0, 0, 0, 0.1); 
                width: 32px; 
                height: 32px; 
                border-radius: 8px; 
                color: #64748b; 
                cursor: pointer; 
                display: flex; 
                align-items: center; 
                justify-content: center; 
                font-size: 24px; 
                line-height: 1;
                transition: all .2s;
                flex-shrink: 0;
            }
            .ai-mode-close:hover { 
                background: rgba(248, 113, 113, 0.1); 
                border-color: rgba(239, 68, 68, 0.3); 
                color: #ef4444; 
                transform: scale(1.08); 
            }
            
            .ai-mode-actions{ 
                z-index: 2; 
                position: relative; 
                display: flex; 
                gap: 12px; 
                margin-top: 16px; 
                flex-direction: column;
            }
            .ai-mode-hint{ 
                z-index: 2; 
                position: relative; 
                margin: 12px 2px 0; 
                color: #64748b; 
                font-size: 13px;
                text-align: center;
            }
            
            .ai-mode-buttons {
                z-index: 2;
                position: relative;
                display: flex;
                gap: 16px;
                margin-top: 20px;
                justify-content: center;
                flex-wrap: wrap;
            }
            
            .ai-mode-button {
                flex: 1;
                min-width: 140px;
                padding: 24px 20px;
                border-radius: 16px;
                cursor: pointer;
                font-size: 16px;
                font-weight: 600;
                position: relative;
                overflow: hidden;
                border: 2px solid transparent;
                background: transparent;
                transition: all .25s cubic-bezier(0.4, 0, 0.2, 1);
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 12px;
            }
            
            .ai-mode-button::before {
                content: "";
                position: absolute;
                z-index: -1;
                inset: 0;
                border-radius: inherit;
                background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
                border: 1px solid #e2e8f0;
                transition: all .25s ease;
            }
            
            .ai-mode-button:hover {
                transform: translateY(-4px);
                box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            }
            
            .ai-mode-button:hover::before {
                background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
                border-color: #cbd5e1;
            }
            
            .ai-mode-button.voice:hover {
                border-color: rgba(59, 130, 246, 0.3);
                box-shadow: 0 8px 24px rgba(59, 130, 246, 0.2);
            }
            
            .ai-mode-button.text:hover {
                border-color: rgba(16, 185, 129, 0.3);
                box-shadow: 0 8px 24px rgba(16, 185, 129, 0.2);
            }
            
            .ai-mode-icon {
                width: 40px;
                height: 40px;
                stroke-width: 2;
                transition: all .25s ease;
            }
            
            .ai-mode-button.voice .ai-mode-icon {
                stroke: #3b82f6;
            }
            
            .ai-mode-button.text .ai-mode-icon {
                stroke: #10b981;
            }
            
            .ai-mode-button:hover .ai-mode-icon {
                transform: scale(1.15);
            }
            
            .ai-mode-label {
                color: #1e293b;
                font-size: 16px;
                font-weight: 600;
                transition: color .2s ease;
            }
            
            .ai-mode-button.voice:hover .ai-mode-label {
                color: #2563eb;
            }
            
            .ai-mode-button.text:hover .ai-mode-label {
                color: #059669;
            }
            
            .ai-mode-button:active {
                transform: translateY(-2px) scale(0.98);
            }
            
            .ai-segmented {
                width: 100%;
                padding: 14px 18px;
                border-radius: 12px;
                color: #1e293b;
                cursor: pointer;
                font-size: 15px;
                font-weight: 500;
                position: relative;
                overflow: hidden;
                border: 0;
                background: transparent;
                transition: transform .15s ease, box-shadow .2s ease;
                text-align: center;
            }
            .ai-segmented::before {
                content: "";
                position: absolute;
                z-index: -2;
                inset: -6px;
                border-radius: inherit;
                background: conic-gradient(from var(--angle), #bae6fd, #e0f2fe 25%, #e0f2fe 75%, #bae6fd 100%);
                animation: rotate-gradient 6s linear infinite;
            }
            .ai-segmented::after {
                content: "";
                position: absolute;
                z-index: -1;
                inset: 0;
                border-radius: inherit;
                background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
                border: 1px solid #cbd5e1;
            }
            .ai-segmented:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
            }
            .ai-segmented:hover::before {
                animation-duration: 1.5s;
            }
            .ai-segmented.primary { 
                outline: none; 
                box-shadow: 0 0 0 2px rgba(33,150,243,.4), 0 2px 8px rgba(33,150,243,.2);
                font-weight: 600;
            }
            .ai-segmented.primary::after {
                background: linear-gradient(135deg, #ffffff 0%, #f0f9ff 100%);
                border-color: #2196F3;
            }

            .ai-chat-header{ z-index: 2; position: relative; padding:14px 16px; background:rgba(248, 250, 252, 0.8); border-bottom:1px solid rgba(0, 0, 0, 0.08); display:flex; align-items:center; justify-content:space-between; border-radius: 16px 16px 0 0; }
            .ai-chat-header h3{ margin:0; color:#0f172a; font-size:15px; font-weight:600; text-align:center; flex-grow:1; }
            
            .ai-header-btn { background:rgba(255, 255, 255, 0.9); border:1px solid rgba(0, 0, 0, 0.1); color:#475569; width:32px; height:32px; border-radius:8px; cursor:pointer; transition: all .2s; display: flex; align-items: center; justify-content: center; }
            .ai-header-btn:hover{ color:#0f172a; border-color: rgba(0, 0, 0, 0.2); background-color: rgba(248, 250, 252, 1); transform: scale(1.05); }
            .ai-close-btn { font-size: 22px; }
            
            .ai-chat-messages{ z-index: 2; position: relative; flex:1; padding:18px; overflow-y:auto; display:flex; flex-direction:column; gap:12px; background: rgba(248, 250, 252, 0.4); }
            
            .ai-chat-bubble{ padding:10px 14px; border-radius:12px; max-width:80%; font-size:14px; line-height:1.5; word-wrap:break-word; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08); }
            .ai-chat-bubble.user{ background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%); color:#fff; align-self:flex-end; border-bottom-right-radius:4px; box-shadow: 0 2px 8px rgba(33, 150, 243, 0.3); }
            .ai-chat-bubble.ai{ background:rgba(255, 255, 255, 0.95); color:#1e293b; align-self:flex-start; border-bottom-left-radius:4px; border: 1px solid rgba(0, 0, 0, 0.06); }
            
            #ai-chat-form{ z-index: 2; position: relative; display:flex; gap:10px; padding:12px; border-top:1px solid rgba(0, 0, 0, 0.08); background: rgba(255, 255, 255, 0.6); }
            #ai-chat-input{ flex:1; background:rgba(255, 255, 255, 0.95); border:1px solid rgba(0, 0, 0, 0.12); border-radius:10px; padding:12px 14px; color:#0f172a; font-size:16px; outline:none; transition: border-color .2s, box-shadow .2s; }
            #ai-chat-input:focus{ border-color: #2196F3; box-shadow:0 0 0 3px rgba(33,150,243,.15); background: #ffffff; }
            #ai-chat-input:disabled { background: rgba(241, 245, 249, 0.8); color: #94a3b8; }
            #ai-chat-input::placeholder { color: #94a3b8; }
            
            #ai-chat-form button{ background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%); color:#fff; border:none; border-radius:10px; padding:0 14px; font-size:16px; cursor:pointer; transition: transform .2s, box-shadow .2s; }
            #ai-chat-form button:hover { transform: scale(1.05); box-shadow: 0 4px 12px rgba(33, 150, 243, 0.4); }
            #ai-chat-form button:disabled { background: linear-gradient(135deg, #cbd5e1 0%, #94a3b8 100%); cursor: not-allowed; transform: scale(1); }
            
            /* Botón de grabación de voz en el chat */
            .ai-voice-record-btn {
                background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
                width: 42px !important;
                height: 42px !important;
                padding: 0 !important;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 10px;
                transition: all 0.2s ease;
            }
            
            .ai-voice-record-btn:hover {
                transform: scale(1.05) !important;
                box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4) !important;
            }
            
            .ai-voice-record-btn.recording {
                background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
                animation: pulse-recording 1.5s ease-in-out infinite;
            }
            
            .ai-voice-record-btn.recording:hover {
                box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4) !important;
            }
            
            @keyframes pulse-recording {
                0%, 100% { opacity: 1; transform: scale(1); }
                50% { opacity: 0.8; transform: scale(1.05); }
            }
            
            .ai-voice-record-btn svg {
                stroke: currentColor;
            }
            
            @keyframes bounce-dot { 0%, 80%, 100% { transform: scale(0); } 40% { transform: scale(1.0); } }
            .ai-typing-indicator .dot { display: inline-block; width: 8px; height: 8px; background-color: #64748b; border-radius: 50%; animation: bounce-dot 1.4s infinite ease-in-out both; margin: 0 2px; }
            .ai-typing-indicator .dot:nth-child(2) { animation-delay: .2s; }
            .ai-typing-indicator .dot:nth-child(3) { animation-delay: .4s; }
            
            .ai-chat-messages::-webkit-scrollbar { width: 8px; }
            .ai-chat-messages::-webkit-scrollbar-track { background: rgba(0, 0, 0, 0.04); border-radius: 10px; }
            .ai-chat-messages::-webkit-scrollbar-thumb { background: rgba(0, 0, 0, 0.2); border-radius: 10px; }
            .ai-chat-messages::-webkit-scrollbar-thumb:hover { background: rgba(0, 0, 0, 0.3); }
            
            /* Branding Workfluz */
            .ai-widget-branding {
                z-index: 2;
                position: relative;
                padding: 8px 12px;
                text-align: center;
                font-size: 11px;
                color: #94a3b8;
                background: rgba(248, 250, 252, 0.6);
                border-top: 1px solid rgba(0, 0, 0, 0.06);
                border-radius: 0 0 16px 16px;
            }
            
            .ai-widget-branding a {
                color: #64748b;
                text-decoration: none;
                font-weight: 500;
                transition: color 0.2s ease;
            }
            
            .ai-widget-branding a:hover {
                color: #2196F3;
            }
            
            /* Branding de Workfluz.com debajo del formulario */
            .ai-chat-branding {
                padding: 8px 12px;
                text-align: center;
                font-size: 10px;
                color: #94a3b8;
                background: rgba(248, 250, 252, 0.6);
                border-top: 1px solid rgba(0, 0, 0, 0.05);
                border-radius: 0 0 16px 16px;
            }
            
            .ai-chat-branding a {
                color: #64748b;
                text-decoration: none;
                font-weight: 500;
                transition: color 0.2s ease;
            }
            
            .ai-chat-branding a:hover {
                color: #2196F3;
            }
            
            /* Mensaje temporal de branding para voz */
            .ai-voice-branding {
                position: fixed;
                bottom: 110px;
                right: 24px;
                background: rgba(255, 255, 255, 0.98);
                color: #64748b;
                font-size: 11px;
                padding: 8px 14px;
                border-radius: 12px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                opacity: 0;
                transform: translateY(10px) scale(0.9);
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                pointer-events: none;
                z-index: 10000;
                white-space: nowrap;
                border: 1px solid rgba(0, 0, 0, 0.08);
            }
            
            .ai-voice-branding.visible {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
            
            .ai-voice-branding a {
                color: #2196F3;
                text-decoration: none;
                font-weight: 500;
            }
            
            @media (max-width: 768px) {
                .ai-logo-container { --glow-intensity: 15px; }
                .ai-logo-button::before { filter: blur(calc(var(--glow-intensity) * 0.75)); }
                #ai-vapi-widget-container { bottom: 16px; right: 16px; }
                .ai-chat-container { bottom: 90px; right: 16px; width: calc(100vw - 32px); max-width: 360px; }
                .ai-mode-card { 
                    width: calc(100vw - 40px); 
                    max-width: 380px;
                    padding: 20px; 
                }
                .ai-mode-header h4 { font-size: 16px; }
                .ai-segmented { padding: 12px 16px; font-size: 14px; }
                .ai-voice-branding {
                    bottom: 90px;
                    right: 16px;
                }
            }
            `;
            const styleEl = document.createElement('style');
            styleEl.textContent = css;
            document.head.appendChild(styleEl);
        }

        /**
         * Crea la estructura HTML del widget
         */
        createWidgetHTML() {
            const logoSVG = aiWidgetData.logoSVG || `<svg class="ai-logo-svg" viewBox="0 0 1972.8 1870.45" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true">
                <defs>
                    <linearGradient id="workfluz-gradient" x1="0" y1="935.23" x2="1972.8" y2="935.23" gradientUnits="userSpaceOnUse">
                        <stop offset="0" stop-color="#76b4e3"/>
                        <stop offset="1" stop-color="#009bf0"/>
                    </linearGradient>
                </defs>
                <path fill="url(#workfluz-gradient)" d="M1852.35,0c-53.22,0-100.11,34.94-115.34,85.94l-457.68,1533.26-232.79-916.61c-13.55-53.37-61.6-90.73-116.65-90.73h-85.51c-54.63,0-102.42,36.8-116.37,89.63l-243.58,922.28-239.29-921.78c-13.78-53.07-61.67-90.12-116.5-90.12h-8.2c-81.18,0-139.09,78.72-114.89,156.23l317.74,1017.87c15.7,50.27,62.23,84.5,114.89,84.5h93.66c54.27,0,101.84-36.33,116.12-88.69l229.35-840.98,229.36,840.98c14.28,52.36,61.84,88.69,116.13,88.69h94.97c52.02,0,98.16-33.42,114.37-82.84L1966.71,157.88C1992.24,80.04,1934.26,0,1852.35,0Z"/>
            </svg>`;

            // Determinar texto del tooltip según modos habilitados
            let tooltipText = '';
            if (aiWidgetData.voiceEnabled && !aiWidgetData.textEnabled) {
                tooltipText = `Hablar con ${this.assistantName}`;
            } else if (!aiWidgetData.voiceEnabled && aiWidgetData.textEnabled) {
                tooltipText = `Chatear con ${this.assistantName}`;
            } else {
                tooltipText = `Interactuar con ${this.assistantName}`;
            }

            // Crear contenedor del widget
            const widget = document.createElement('div');
            widget.id = 'ai-vapi-widget-container';
            widget.innerHTML = `
                <div class="ai-logo-container" id="ai-logo-container" role="application" aria-label="Asistente de IA">
                    <button class="ai-logo-button" id="ai-logo-button" role="button" tabindex="0" aria-label="Logotipo del asistente" aria-pressed="false">
                        <div class="ai-logo-background">
                            ${logoSVG}
                            <canvas id="ai-sound-wave-canvas" aria-hidden="true"></canvas>
                        </div>
                        <span class="ai-tooltip" role="tooltip">${tooltipText}</span>
                    </button>
                    ${aiWidgetData.voiceEnabled ? `
                    <button class="ai-action-button ai-voice-button" id="ai-voice-btn" aria-label="Llamar por voz">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                        </svg>
                    </button>
                    ` : ''}
                    ${aiWidgetData.textEnabled ? `
                    <button class="ai-action-button ai-chat-button" id="ai-chat-btn" aria-label="Abrir chat">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                        </svg>
                    </button>
                    ` : ''}
                </div>
                <div id="ai-status-text" role="status" aria-live="polite" aria-atomic="true">Asistente disponible</div>

                <div class="ai-chat-container" id="ai-chat-container" style="display:none;">
                    <div class="ai-chat-header">
                        ${aiWidgetData.voiceEnabled ? `<button class="ai-header-btn" id="ai-switch-to-voice-btn" aria-label="Cambiar a modo voz" title="Cambiar a modo voz">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"/><path d="M19 10v2a7 7 0 0 1-14 0v-2"/><line x1="12" y1="19" x2="12" y2="23"/></svg>
                        </button>` : ''}
                        <h3>${this.assistantName}</h3>
                        <button class="ai-header-btn ai-close-btn" id="ai-chat-close-btn" aria-label="Cerrar chat" title="Cerrar Chat">&times;</button>
                    </div>
                    <div class="ai-chat-messages" id="ai-chat-messages" role="log" aria-live="polite"></div>
                    <form id="ai-chat-form" aria-label="Formulario de entrada de chat">
                        <button type="button" id="ai-voice-record-btn" class="ai-voice-record-btn" aria-label="Grabar mensaje de voz" title="Grabar mensaje de voz">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"/>
                                <path d="M19 10v2a7 7 0 0 1-14 0v-2"/>
                                <line x1="12" y1="19" x2="12" y2="23"/>
                                <line x1="8" y1="23" x2="16" y2="23"/>
                            </svg>
                        </button>
                        <input type="text" id="ai-chat-input" placeholder="${aiWidgetData.placeholder || 'Escribe tu mensaje...'}" autocomplete="off" inputmode="text" aria-label="Entrada de mensaje">
                        <button type="submit" id="ai-chat-submit-btn" aria-label="Enviar mensaje">&#x27A4;</button>
                    </form>
                    ${aiWidgetData.showBranding ? '<div class="ai-chat-branding">Powered by <a href="https://workfluz.com" target="_blank" rel="noopener">Workfluz.com</a></div>' : ''}
                </div>
            `;

            document.body.appendChild(widget);
            this.attachEventListeners();
        }

        /**
         * Carga el SDK de VAPI
         */
        loadVapiSDK() {
            // Cargar VAPI si el modo de voz está habilitado y hay API key
            const voiceEnabled = aiWidgetData.voiceEnabled;
            
            if (!voiceEnabled || !this.vapiPublicKey) {
                console.log('VAPI no configurado o modo de voz deshabilitado');
                return;
            }

            const sdkScript = document.createElement('script');
            sdkScript.src = "https://cdn.jsdelivr.net/gh/VapiAI/html-script-tag@latest/dist/assets/index.js";
            sdkScript.defer = true;
            sdkScript.async = true;

            sdkScript.onload = () => {
                if (!window.vapiSDK) {
                    console.error('SDK de Vapi no encontrado');
                    return;
                }

                this.vapiInstance = window.vapiSDK.run({
                    apiKey: this.vapiPublicKey,
                    assistant: this.vapiAssistantId
                });

                console.log('✅ VAPI SDK cargado correctamente');
                this.setupVapiListeners();
            };

            sdkScript.onerror = () => console.error('Error al cargar el SDK de Vapi');
            document.body.appendChild(sdkScript);
        }

        /**
         * Configura los listeners de VAPI
         */
        setupVapiListeners() {
            if (!this.vapiInstance) return;

            this.vapiInstance.on('call-start', () => {
                this.isCallActive = true;
                this.callStartTime = Date.now(); // Registrar tiempo de inicio
                
                document.getElementById('ai-logo-container').classList.remove('loading');
                document.getElementById('ai-logo-container').classList.add('session-active');
                this.updateStatus('Conectado', 'active', true);
                
                console.log('🎤 Llamada iniciada, timer comenzado');
                
                if (this.currentMode === 'text') {
                    this.vapiInstance.setMuted(false);
                    this.vapiInstance.setVolume(0);
                    this.setChatInputEnabled(true);
                    document.getElementById('ai-chat-input').focus();
                } else {
                    this.drawWaveAnimation();
                }
            });

            this.vapiInstance.on('call-end', async () => {
                // Calcular duración de la llamada
                if (this.callStartTime) {
                    this.callDuration = Math.floor((Date.now() - this.callStartTime) / 1000); // Duración en segundos
                    console.log(`📞 Llamada finalizada. Duración: ${this.callDuration} segundos (${(this.callDuration / 60).toFixed(2)} minutos)`);
                    
                    // Enviar duración al servidor
                    await this.logVoiceUsage(this.callDuration);
                    
                    // Resetear variables
                    this.callStartTime = null;
                    this.callDuration = 0;
                }
                
                this.isCallActive = false;
                this.isSpeaking = false;
                
                if (this.isChatOpen && this.currentMode === 'text') {
                    this.toggleChatWindow(false);
                }
                
                document.getElementById('ai-logo-container').classList.remove('session-active', 'loading');
                this.updateStatus('Llamada finalizada', '', true);
                
                if (this.animationId) {
                    cancelAnimationFrame(this.animationId);
                }
                
                this.currentMode = null;
            });

            this.vapiInstance.on('speech-start', () => {
                this.isSpeaking = true;
                if (this.currentMode !== 'text') {
                    this.updateStatus('IA hablando...', 'speaking');
                }
            });

            this.vapiInstance.on('speech-end', () => {
                this.isSpeaking = false;
                if (this.isCallActive && this.currentMode !== 'text') {
                    this.updateStatus('Tu turno', 'active', true);
                }
            });

            this.vapiInstance.on('message', (message) => {
                if (this.currentMode === 'text' && message.type === 'transcript' && 
                    message.role === 'assistant' && message.transcriptType === 'final') {
                    this.removeTypingIndicator();
                    this.addMessage(message.transcript, 'ai');
                }
            });

            this.vapiInstance.on('error', (error) => {
                console.error('Error de Vapi:', error);
                
                // Si había llamada activa, registrar el tiempo antes del error
                if (this.callStartTime) {
                    this.callDuration = Math.floor((Date.now() - this.callStartTime) / 1000);
                    console.log(`⚠️ Error en llamada. Duración antes de error: ${this.callDuration} segundos`);
                    this.logVoiceUsage(this.callDuration);
                    this.callStartTime = null;
                    this.callDuration = 0;
                }
                
                this.isCallActive = false;
                document.getElementById('ai-logo-container').classList.remove('session-active', 'loading');
                this.updateStatus('Error de conexión', '', true);
                
                if (this.isChatOpen && this.currentMode === 'text') {
                    this.removeTypingIndicator();
                    this.setChatInputEnabled(false);
                    document.getElementById('ai-chat-input').placeholder = "Error. Por favor reinicia.";
                    this.addMessage('Lo siento, ocurrió un error de conexión.', 'ai');
                }
            });
        }

        /**
         * Adjunta event listeners
         */
        attachEventListeners() {
            console.log('🔧 Adjuntando event listeners...');
            
            const logoButton = document.getElementById('ai-logo-button');
            const voiceBtn = document.getElementById('ai-voice-btn');
            const chatBtn = document.getElementById('ai-chat-btn');
            const chatCloseBtn = document.getElementById('ai-chat-close-btn');
            const chatForm = document.getElementById('ai-chat-form');
            const switchToVoiceBtn = document.getElementById('ai-switch-to-voice-btn');
            const voiceRecordBtn = document.getElementById('ai-voice-record-btn');

            console.log('Botones encontrados:', {
                logoButton: !!logoButton,
                voiceBtn: !!voiceBtn,
                chatBtn: !!chatBtn,
                chatCloseBtn: !!chatCloseBtn,
                chatForm: !!chatForm,
                voiceRecordBtn: !!voiceRecordBtn
            });

            // Event listener para el botón del logo
            logoButton.addEventListener('click', async () => {
                const logoContainer = document.getElementById('ai-logo-container');
                
                if (this.isCallActive) {
                    // Si hay llamada activa, detenerla
                    console.log('🛑 Deteniendo llamada de voz...');
                    if (this.vapiInstance) {
                        this.vapiInstance.stop();
                    }
                    logoContainer.classList.remove('menu-open');
                } else if (this.isChatOpen) {
                    // Si hay chat abierto, cerrarlo
                    console.log('🛑 Cerrando chat...');
                    this.toggleChatWindow(false);
                    this.currentMode = null;
                    logoContainer.classList.remove('menu-open');
                } else {
                    // Detectar si solo hay un modo habilitado
                    const voiceOnly = aiWidgetData.voiceEnabled && !aiWidgetData.textEnabled;
                    const textOnly = !aiWidgetData.voiceEnabled && aiWidgetData.textEnabled;
                    
                    if (voiceOnly) {
                        // Iniciar VOZ automáticamente
                        console.log('🎤 Solo modo voz habilitado - Iniciando automáticamente...');
                        
                        // Verificar límites de voz
                        const voiceLimits = await this.checkVoiceLimits();
                        
                        if (!voiceLimits.allowed) {
                            console.warn('⚠️ Límite de voz alcanzado');
                            this.updateStatus(voiceLimits.message, '', true);
                            return;
                        }
                        
                        // Iniciar modo de voz
                        this.currentMode = 'voice';
                        logoContainer.classList.add('loading');
                        this.updateStatus('Conectando...', 'connecting');
                        
                        // Mostrar branding temporal si está habilitado
                        if (aiWidgetData.showBranding) {
                            this.showVoiceBranding();
                        }
                        
                        if (this.vapiInstance) {
                            console.log('✅ Iniciando VAPI...');
                            this.vapiInstance.start(this.vapiAssistantId);
                        } else {
                            console.error('❌ VAPI Instance no disponible');
                            this.updateStatus('Error: VAPI no configurado', 'connecting');
                            logoContainer.classList.remove('loading');
                        }
                        
                    } else if (textOnly) {
                        // Abrir CHAT automáticamente
                        console.log('💬 Solo modo chat habilitado - Abriendo automáticamente...');
                        this.currentMode = 'text';
                        this.toggleChatWindow(true);
                        
                    } else {
                        // Ambos modos habilitados - Toggle del menú de botones
                        console.log('🔄 Toggle menú de botones (ambos modos habilitados)');
                        logoContainer.classList.toggle('menu-open');
                    }
                }
            });

            // Event listener para botón de VOZ (pequeño)
            if (voiceBtn) {
                voiceBtn.addEventListener('click', async (e) => {
                    e.stopPropagation();
                    console.log('🎤 Botón de voz clickeado');
                    
                    const logoContainer = document.getElementById('ai-logo-container');
                    
                    // Cerrar menú de botones
                    logoContainer.classList.remove('menu-open');
                    
                    // Si ya hay una llamada activa, detenerla
                    if (this.isCallActive) {
                        console.log('🛑 Deteniendo llamada activa...');
                        if (this.vapiInstance) {
                            this.vapiInstance.stop();
                        }
                        return;
                    }
                    
                    // Verificar límites ANTES de iniciar llamada
                    console.log('🔍 Verificando límites de voz...');
                    const voiceLimits = await this.checkVoiceLimits();
                    
                    if (!voiceLimits.allowed) {
                        console.warn('⚠️ Límite de voz alcanzado');
                        this.updateStatus(voiceLimits.message, '', true);
                        
                        // Si el chat está cerrado, mostrarlo con el mensaje de límite
                        if (!this.isChatOpen && aiWidgetData.textEnabled) {
                            this.currentMode = 'text';
                            this.toggleChatWindow(true);
                            this.addMessage(voiceLimits.message, 'ai');
                            this.showUpgradePrompt();
                        }
                        return;
                    }
                    
                    console.log('✅ Límites OK, iniciando llamada...');
                    
                    // Si el chat está abierto, cerrarlo primero
                    if (this.isChatOpen) {
                        console.log('🔄 Cerrando chat para iniciar voz...');
                        this.toggleChatWindow(false);
                    }
                    
                    // Iniciar modo de voz
                    this.currentMode = 'voice';
                    logoContainer.classList.add('loading');
                    this.updateStatus('Conectando...', 'connecting');
                    
                    // Mostrar branding temporal si está habilitado
                    if (aiWidgetData.showBranding) {
                        this.showVoiceBranding();
                    }
                    
                    if (this.vapiInstance) {
                        console.log('✅ Iniciando VAPI...');
                        this.vapiInstance.start(this.vapiAssistantId);
                    } else {
                        console.error('❌ VAPI Instance no disponible');
                        this.updateStatus('Error: VAPI no configurado', 'connecting');
                    }
                });
            } else {
                console.warn('⚠️ Botón de voz no encontrado');
            }

            // Event listener para botón de CHAT (pequeño)
            if (chatBtn) {
                chatBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    console.log('💬 Botón de chat clickeado');
                    
                    const logoContainer = document.getElementById('ai-logo-container');
                    
                    // Cerrar menú de botones
                    logoContainer.classList.remove('menu-open');
                    
                    // Si ya hay chat abierto, cerrarlo
                    if (this.isChatOpen) {
                        console.log('🛑 Cerrando chat...');
                        this.toggleChatWindow(false);
                        this.currentMode = null;
                        return;
                    }
                    
                    // Si hay una llamada activa, detenerla primero
                    if (this.isCallActive) {
                        console.log('🔄 Deteniendo voz para abrir chat...');
                        if (this.vapiInstance) {
                            this.vapiInstance.stop();
                        }
                        // Esperar un momento para que se detenga
                        setTimeout(() => {
                            this.currentMode = 'text';
                            this.toggleChatWindow(true);
                        }, 300);
                        return;
                    }
                    
                    // Abrir chat
                    this.currentMode = 'text';
                    this.toggleChatWindow(true);
                });
            } else {
                console.warn('⚠️ Botón de chat no encontrado');
            }

            if (chatCloseBtn) {
                chatCloseBtn.addEventListener('click', () => {
                    this.toggleChatWindow(false);
                    this.currentMode = null;
                });
            }

            if (chatForm) {
                chatForm.addEventListener('submit', (e) => {
                    e.preventDefault();
                    this.sendTextMessage();
                });
            }

            if (switchToVoiceBtn) {
                switchToVoiceBtn.addEventListener('click', () => {
                    this.toggleChatWindow(false);
                    setTimeout(() => {
                        if (voiceBtn) voiceBtn.click();
                    }, 200);
                });
            }

            // Event listener para botón de grabación de voz
            if (voiceRecordBtn) {
                voiceRecordBtn.addEventListener('click', () => {
                    if (this.isRecording) {
                        this.stopVoiceRecording();
                    } else {
                        this.startVoiceRecording();
                    }
                });
            }
        }

        /**
         * Inicia grabación de voz
         */
        async startVoiceRecording() {
            const recordBtn = document.getElementById('ai-voice-record-btn');
            
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                this.mediaRecorder = new MediaRecorder(stream);
                this.audioChunks = [];

                this.mediaRecorder.ondataavailable = (event) => {
                    if (event.data.size > 0) {
                        this.audioChunks.push(event.data);
                    }
                };

                this.mediaRecorder.onstop = async () => {
                    const audioBlob = new Blob(this.audioChunks, { type: 'audio/webm' });
                    await this.sendVoiceMessage(audioBlob);
                    
                    // Detener el stream
                    stream.getTracks().forEach(track => track.stop());
                };

                this.mediaRecorder.start();
                this.isRecording = true;
                recordBtn.classList.add('recording');
                recordBtn.title = 'Detener grabación';
                
                console.log('🎤 Grabación iniciada');
            } catch (error) {
                console.error('Error al iniciar grabación:', error);
                this.addMessage('No se pudo acceder al micrófono. Por favor, verifica los permisos.', 'ai');
            }
        }

        /**
         * Detiene grabación de voz
         */
        stopVoiceRecording() {
            const recordBtn = document.getElementById('ai-voice-record-btn');
            
            if (this.mediaRecorder && this.isRecording) {
                this.mediaRecorder.stop();
                this.isRecording = false;
                recordBtn.classList.remove('recording');
                recordBtn.title = 'Grabar mensaje de voz';
                
                console.log('🛑 Grabación detenida');
            }
        }

        /**
         * Envía mensaje de voz
         */
        async sendVoiceMessage(audioBlob) {
            // Verificar límites
            const limits = await this.checkMessageLimit();
            if (!limits.allowed) {
                this.addMessage(limits.message, 'ai');
                this.showUpgradePrompt();
                return;
            }

            // Mostrar mensaje del usuario
            this.addMessage('🎤 Mensaje de voz enviado', 'user');
            this.addTypingIndicator();

            // Convertir audio a base64
            const reader = new FileReader();
            reader.readAsDataURL(audioBlob);
            
            reader.onloadend = async () => {
                const base64Audio = reader.result;
                
                // Enviar a n8n
                if (aiWidgetData.chatProvider === 'n8n' && aiWidgetData.n8nWebhookUrl) {
                    try {
                        const response = await fetch(aiWidgetData.n8nWebhookUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                audio: base64Audio,
                                audioType: 'base64',
                                mimeType: audioBlob.type,
                                session_id: this.sessionId,
                                conversation_history: this.conversationHistory || [],
                                isVoiceMessage: true
                            }),
                        });

                        const data = await response.json();
                        this.removeTypingIndicator();

                        // Soporte flexible para diferentes formatos de respuesta
                        const botResponse = data.response || data.output || data[0]?.output;
                        console.log('📥 Respuesta de voz de n8n:', data);
                        console.log('🎤 Bot dice (voz):', botResponse);

                        if (botResponse) {
                            this.addMessage(botResponse, 'ai');
                            
                            // Guardar threadId si existe
                            if (data.threadId || data[0]?.threadId) {
                                this.threadId = data.threadId || data[0]?.threadId;
                                console.log('🔗 ThreadId guardado (voz):', this.threadId);
                            }
                            
                            // Guardar en historial
                            if (!this.conversationHistory) {
                                this.conversationHistory = [];
                            }
                            this.conversationHistory.push({
                                role: 'user',
                                content: '[Mensaje de voz]'
                            });
                            this.conversationHistory.push({
                                role: 'assistant',
                                content: botResponse
                            });
                        } else {
                            console.error('❌ No se encontró respuesta en:', data);
                            this.addMessage('Lo siento, no pude procesar tu mensaje de voz.', 'ai');
                        }
                    } catch (error) {
                        this.removeTypingIndicator();
                        this.addMessage('Error al enviar el mensaje de voz. Por favor, intenta de nuevo.', 'ai');
                        console.error('Error enviando voz a n8n:', error);
                    }
                } else {
                    this.removeTypingIndicator();
                    this.addMessage('Los mensajes de voz solo están disponibles con n8n configurado.', 'ai');
                }
            };
        }

        /**
         * Envia mensaje de texto
         */
        async sendTextMessage() {
            const input = document.getElementById('ai-chat-input');
            const message = input.value.trim();

            if (!message) return;

            input.value = '';
            this.addMessage(message, 'user');

            // Verificar límites
            const limits = await this.checkMessageLimit();
            if (!limits.allowed) {
                this.addMessage(limits.message, 'ai');
                this.showUpgradePrompt();
                return;
            }

            // Agregar indicador de escritura
            this.addTypingIndicator();

            // Si está usando VAPI en modo texto
            if (this.provider === 'vapi' && this.vapiInstance && this.isCallActive) {
                this.vapiInstance.send({
                    type: 'add-message',
                    message: { role: 'user', content: message },
                });
            } 
            // Si está usando n8n webhook
            else if (aiWidgetData.chatProvider === 'n8n' && aiWidgetData.n8nWebhookUrl) {
                try {
                    const response = await fetch(aiWidgetData.n8nWebhookUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            message: message,
                            session_id: this.sessionId,
                            conversation_history: this.conversationHistory || [],
                        }),
                    });

                    const data = await response.json();
                    this.removeTypingIndicator();

                    // Soportar tanto 'response' como 'output' (n8n usa 'output')
                    const botResponse = data.response || data.output || data[0]?.output;
                    
                    console.log('📥 Respuesta de n8n:', data); // DEBUG
                    console.log('🤖 Bot dice:', botResponse); // DEBUG

                    if (botResponse) {
                        this.addMessage(botResponse, 'ai');
                        
                        // Guardar threadId si viene
                        if (data.threadId || data[0]?.threadId) {
                            this.threadId = data.threadId || data[0]?.threadId;
                        }
                        
                        // Guardar en historial
                        if (!this.conversationHistory) {
                            this.conversationHistory = [];
                        }
                        this.conversationHistory.push({
                            role: 'user',
                            content: message
                        });
                        this.conversationHistory.push({
                            role: 'assistant',
                            content: botResponse
                        });
                    } else {
                        console.error('❌ No se encontró respuesta en:', data);
                        this.addMessage('Lo siento, no pude procesar tu mensaje.', 'ai');
                    }
                } catch (error) {
                    this.removeTypingIndicator();
                    this.addMessage('Error de conexión con n8n. Por favor, verifica la configuración.', 'ai');
                    console.error('Error n8n:', error);
                }
            } 
            // Usar REST API del plugin (OpenAI)
            else {
                try {
                    const response = await fetch(`${this.apiUrl}/chat`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            message: message,
                            session_id: this.sessionId,
                        }),
                    });

                    const data = await response.json();
                    this.removeTypingIndicator();

                    if (data.success) {
                        this.addMessage(data.response, 'ai');
                        this.updateRemaining(data.remaining);
                    } else if (data.upgrade_required) {
                        this.addMessage(data.error, 'ai');
                        this.showUpgradePrompt();
                    } else {
                        this.addMessage('Lo siento, ocurrió un error. Por favor, intenta de nuevo.', 'ai');
                    }
                } catch (error) {
                    this.removeTypingIndicator();
                    this.addMessage('Error de conexión. Por favor, verifica tu conexión a internet.', 'ai');
                    console.error('Error:', error);
                }
            }
        }

        /**
         * Toggle ventana de chat
         */
        toggleChatWindow(show) {
            this.isChatOpen = show;
            const chatContainer = document.getElementById('ai-chat-container');
            chatContainer.style.display = show ? 'flex' : 'none';
            
            if (show && this.currentMode === 'text') {
                if (this.provider === 'vapi' && this.vapiInstance) {
                    document.getElementById('ai-logo-container').classList.add('loading');
                    this.updateStatus('Conectando...', 'connecting');
                    this.setChatInputEnabled(false);
                    this.vapiInstance.start(this.vapiAssistantId);
                } else {
                    this.setChatInputEnabled(true);
                }
                document.getElementById('ai-chat-messages').innerHTML = '';
                this.addMessage(aiWidgetData.welcomeMessage || "¡Hola! Puedo ayudarte con lo que necesites.", 'ai');
            } else if (!show) {
                if (this.isCallActive && this.vapiInstance) {
                    this.vapiInstance.stop();
                }
                this.setChatInputEnabled(false);
            }
        }

        /**
         * Añade mensaje al chat
         */
        addMessage(text, who) {
            const messagesContainer = document.getElementById('ai-chat-messages');
            const div = document.createElement('div');
            div.className = `ai-chat-bubble ${who}`;
            div.textContent = text;
            messagesContainer.appendChild(div);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        /**
         * Añade indicador de escritura
         */
        addTypingIndicator() {
            if (document.getElementById('ai-typing-indicator')) return;
            const messagesContainer = document.getElementById('ai-chat-messages');
            const div = document.createElement('div');
            div.id = 'ai-typing-indicator';
            div.className = 'ai-chat-bubble ai ai-typing-indicator';
            div.innerHTML = '<span class="dot"></span><span class="dot"></span><span class="dot"></span>';
            messagesContainer.appendChild(div);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        /**
         * Elimina indicador de escritura
         */
        removeTypingIndicator() {
            const indicator = document.getElementById('ai-typing-indicator');
            if (indicator) indicator.remove();
        }

        /**
         * Habilita/deshabilita input de chat
         */
        setChatInputEnabled(enabled) {
            const input = document.getElementById('ai-chat-input');
            const submitBtn = document.getElementById('ai-chat-submit-btn');
            input.disabled = !enabled;
            submitBtn.disabled = !enabled;
            input.placeholder = enabled ? (aiWidgetData.placeholder || "Escribe tu mensaje...") : "Conectando...";
        }

        /**
         * Muestra branding temporal para voz (2 segundos)
         */
        showVoiceBranding() {
            // Crear elemento de branding si no existe
            let brandingEl = document.getElementById('ai-voice-branding');
            if (!brandingEl) {
                brandingEl = document.createElement('div');
                brandingEl.id = 'ai-voice-branding';
                brandingEl.className = 'ai-voice-branding';
                brandingEl.innerHTML = 'Powered by <a href="https://workfluz.com" target="_blank" rel="noopener">Workfluz.com</a>';
                document.body.appendChild(brandingEl);
            }
            
            // Mostrar con animación
            setTimeout(() => {
                brandingEl.classList.add('visible');
            }, 100);
            
            // Ocultar después de 2 segundos
            setTimeout(() => {
                brandingEl.classList.remove('visible');
            }, 2100);
        }

        /**
         * Actualiza estado
         */
        updateStatus(text, className, autoHide) {
            const statusText = document.getElementById('ai-status-text');
            statusText.textContent = text;
            statusText.className = className ? `${className} visible` : 'visible';
            
            if (this.statusTimeout) clearTimeout(this.statusTimeout);
            if (autoHide) {
                this.statusTimeout = setTimeout(() => {
                    statusText.classList.remove('visible');
                }, 3000);
            }
        }

        /**
         * Dibuja animación de onda
         */
        drawWaveAnimation() {
            if (!this.isCallActive) return;
            this.animationId = requestAnimationFrame(() => this.drawWaveAnimation());
            
            const canvas = document.getElementById('ai-sound-wave-canvas');
            const ctx = canvas.getContext('2d');
            const w = canvas.width = canvas.offsetWidth * 2;
            const h = canvas.height = canvas.offsetHeight * 2;
            const t = Date.now() * 0.002;
            
            ctx.clearRect(0, 0, w, h);
            
            const targetVolume = this.isSpeaking ? 40 : 15;
            this.smoothVolume = this.smoothVolume || 0;
            this.smoothVolume += (targetVolume - this.smoothVolume) * 0.1;
            
            const waveParams = [
                { color: 'rgba(33, 150, 243, 0.8)', freq: 0.03, phase: 0 },
                { color: 'rgba(25, 118, 210, 0.7)', freq: 0.04, phase: 1 },
                { color: 'rgba(66, 165, 245, 0.6)', freq: 0.06, phase: 2 },
            ];
            
            waveParams.forEach(wave => {
                ctx.beginPath();
                ctx.strokeStyle = wave.color;
                ctx.lineWidth = 2;
                ctx.globalAlpha = 0.5 + (Math.sin(t + wave.phase) + 1) / 4;
                
                for (let x = 0; x < w; x++) {
                    const scaling = 1 - Math.pow(Math.abs(x - w / 2) / (w / 2), 2);
                    const y = (h / 2) + Math.sin(x * wave.freq + t + wave.phase) * 
                              (this.smoothVolume + Math.random() * 5) * scaling;
                    ctx.lineTo(x, y);
                }
                ctx.stroke();
            });
        }

        /**
         * Verifica límites
         */
        async checkLimits() {
            try {
                const response = await fetch(`${this.apiUrl}/check-limits?session_id=${this.sessionId}`);
                const data = await response.json();
                if (data.allowed !== undefined) {
                    this.plan = data.plan;
                    this.updateRemaining(data.remaining);
                }
            } catch (error) {
                console.error('Error checking limits:', error);
            }
        }

        /**
         * Verifica límite de mensaje
         */
        async checkMessageLimit() {
            try {
                const response = await fetch(`${this.apiUrl}/check-limits?session_id=${this.sessionId}`);
                const data = await response.json();
                
                // Adaptar respuesta del servidor
                return {
                    allowed: data.can_send !== false,
                    remaining: data.usage ? data.usage.remaining : '?',
                    message: data.can_send === false ? '¡Has alcanzado tu límite! Actualiza a Premium.' : ''
                };
            } catch (error) {
                console.error('Error verificando límites:', error);
                // En caso de error, permitir el envío
                return { allowed: true, remaining: '?' };
            }
        }

        /**
         * Actualiza contador restante
         */
        updateRemaining(remaining) {
            this.remaining = remaining;
            // Podrías mostrar esto en algún lugar del UI si lo deseas
        }

        /**
         * Muestra prompt de actualización
         */
        showUpgradePrompt() {
            const messagesContainer = document.getElementById('ai-chat-messages');
            const upgradeDiv = document.createElement('div');
            upgradeDiv.className = 'ai-chat-bubble ai';
            upgradeDiv.innerHTML = `
                <strong>¡Has alcanzado tu límite!</strong><br>
                Actualiza a Premium para mensajes ilimitados y funciones avanzadas.
            `;
            messagesContainer.appendChild(upgradeDiv);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        /**
         * Registra uso de voz en el servidor
         */
        async logVoiceUsage(durationSeconds) {
            if (!durationSeconds || durationSeconds < 1) {
                console.log('⏭️ Duración muy corta, no se registra');
                return;
            }

            try {
                console.log(`📊 Enviando duración al servidor: ${durationSeconds} segundos`);
                
                const response = await fetch(`${this.apiUrl}/log-voice`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        session_id: this.sessionId,
                        duration_seconds: durationSeconds,
                    }),
                });

                const data = await response.json();
                
                if (data.success) {
                    console.log('✅ Uso de voz registrado correctamente');
                    console.log('📈 Límites de voz:', data.voice_limits);
                    
                    // Verificar si está cerca del límite
                    if (data.voice_limits && data.voice_limits.percentage >= 80 && data.voice_limits.limit !== 'unlimited') {
                        this.updateStatus(`⚠️ ${data.voice_limits.remaining} minutos restantes`, '', true);
                    }
                } else {
                    console.warn('⚠️ No se pudo registrar el uso de voz:', data);
                }
            } catch (error) {
                console.error('❌ Error al registrar uso de voz:', error);
            }
        }

        /**
         * Verifica límites antes de iniciar llamada de voz
         */
        async checkVoiceLimits() {
            try {
                const response = await fetch(`${this.apiUrl}/check-limits?session_id=${this.sessionId}`);
                const data = await response.json();
                
                if (data.voice && !data.voice.allowed) {
                    return {
                        allowed: false,
                        message: `¡Has alcanzado tu límite de ${data.voice.limit} minutos de voz! Actualiza a Premium para voz ilimitada.`,
                        remaining: data.voice.remaining
                    };
                }
                
                return { allowed: true };
            } catch (error) {
                console.error('Error verificando límites de voz:', error);
                // En caso de error, permitir el uso
                return { allowed: true };
            }
        }

        /**
         * Obtiene o crea session ID
         */
        getOrCreateSessionId() {
            let sessionId = localStorage.getItem('ai_widget_session_id');
            if (!sessionId) {
                sessionId = 'session_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
                localStorage.setItem('ai_widget_session_id', sessionId);
            }
            return sessionId;
        }
    }

    // Inicializar widget cuando el DOM esté listo
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => new AIVAPIWidget());
    } else {
        new AIVAPIWidget();
    }

})();
