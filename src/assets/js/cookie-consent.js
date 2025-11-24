/**
 * Cookie Consent Banner
 * Показывает уведомление о использовании cookies
 */

(function() {
    'use strict';
    
    const COOKIE_NAME = 'cookie_consent';
    const COOKIE_EXPIRY_DAYS = 365;
    
    /**
     * Проверяет, дал ли пользователь согласие
     */
    function hasConsent() {
        return document.cookie.split('; ').some(cookie => cookie.startsWith(COOKIE_NAME + '='));
    }
    
    /**
     * Сохраняет согласие пользователя
     */
    function saveConsent() {
        const date = new Date();
        date.setTime(date.getTime() + (COOKIE_EXPIRY_DAYS * 24 * 60 * 60 * 1000));
        document.cookie = COOKIE_NAME + '=accepted; expires=' + date.toUTCString() + '; path=/; SameSite=Strict';
    }
    
    /**
     * Создает и показывает баннер
     */
    function showBanner() {
        // Проверяем, не показан ли уже баннер
        if (document.getElementById('cookie-consent-banner')) {
            return;
        }
        
        // Создаем HTML баннера
        const banner = document.createElement('div');
        banner.id = 'cookie-consent-banner';
        banner.innerHTML = `
            <div class="cookie-consent-content">
                <div class="cookie-consent-icon">
                    🍪
                </div>
                <div class="cookie-consent-text">
                    <p class="cookie-consent-title">Мы используем cookies</p>
                    <p class="cookie-consent-description">
                        Этот сайт использует cookies для обеспечения работы сессий и улучшения вашего опыта. 
                        Продолжая использовать сайт, вы соглашаетесь с использованием cookies.
                    </p>
                </div>
                <div class="cookie-consent-actions">
                    <button id="cookie-consent-accept" class="cookie-consent-btn cookie-consent-btn-accept">
                        Принять
                    </button>
                    <button id="cookie-consent-decline" class="cookie-consent-btn cookie-consent-btn-decline">
                        Отклонить
                    </button>
                </div>
            </div>
        `;
        
        // Добавляем стили
        const style = document.createElement('style');
        style.textContent = `
            #cookie-consent-banner {
                position: fixed;
                bottom: 20px;
                left: 50%;
                transform: translateX(-50%);
                max-width: 600px;
                width: calc(100% - 40px);
                background: rgba(30, 41, 59, 0.95);
                backdrop-filter: blur(12px);
                border: 1px solid rgba(168, 85, 247, 0.3);
                border-radius: 16px;
                padding: 24px;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5), 0 0 20px rgba(168, 85, 247, 0.2);
                z-index: 10000;
                animation: slideUp 0.4s ease-out;
            }
            
            @keyframes slideUp {
                from {
                    opacity: 0;
                    transform: translateX(-50%) translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateX(-50%) translateY(0);
                }
            }
            
            .cookie-consent-content {
                display: flex;
                align-items: center;
                gap: 20px;
            }
            
            .cookie-consent-icon {
                font-size: 48px;
                flex-shrink: 0;
                animation: bounce 2s infinite;
            }
            
            @keyframes bounce {
                0%, 100% { transform: translateY(0); }
                50% { transform: translateY(-5px); }
            }
            
            .cookie-consent-text {
                flex: 1;
            }
            
            .cookie-consent-title {
                color: #e2e8f0;
                font-size: 18px;
                font-weight: 600;
                margin: 0 0 8px 0;
            }
            
            .cookie-consent-description {
                color: #cbd5e1;
                font-size: 14px;
                line-height: 1.6;
                margin: 0;
            }
            
            .cookie-consent-actions {
                display: flex;
                flex-direction: column;
                gap: 8px;
                flex-shrink: 0;
            }
            
            .cookie-consent-btn {
                padding: 10px 24px;
                border: none;
                border-radius: 8px;
                font-size: 14px;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.2s ease;
                white-space: nowrap;
            }
            
            .cookie-consent-btn-accept {
                background: linear-gradient(135deg, #a855f7 0%, #ec4899 100%);
                color: white;
                box-shadow: 0 4px 12px rgba(168, 85, 247, 0.3);
            }
            
            .cookie-consent-btn-accept:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 16px rgba(168, 85, 247, 0.4);
            }
            
            .cookie-consent-btn-decline {
                background: rgba(100, 116, 139, 0.2);
                color: #94a3b8;
                border: 1px solid rgba(100, 116, 139, 0.3);
            }
            
            .cookie-consent-btn-decline:hover {
                background: rgba(100, 116, 139, 0.3);
                color: #cbd5e1;
            }
            
            @media (max-width: 640px) {
                #cookie-consent-banner {
                    bottom: 10px;
                    width: calc(100% - 20px);
                    padding: 20px;
                }
                
                .cookie-consent-content {
                    flex-direction: column;
                    text-align: center;
                }
                
                .cookie-consent-icon {
                    font-size: 40px;
                }
                
                .cookie-consent-actions {
                    width: 100%;
                }
                
                .cookie-consent-btn {
                    width: 100%;
                }
            }
        `;
        
        document.head.appendChild(style);
        document.body.appendChild(banner);
        
        // Обработчики кнопок
        document.getElementById('cookie-consent-accept').addEventListener('click', function() {
            saveConsent();
            hideBanner();
        });
        
        document.getElementById('cookie-consent-decline').addEventListener('click', function() {
            hideBanner();
            // Можно добавить логику для отключения необязательных cookies
        });
    }
    
    /**
     * Скрывает баннер с анимацией
     */
    function hideBanner() {
        const banner = document.getElementById('cookie-consent-banner');
        if (banner) {
            banner.style.animation = 'slideDown 0.3s ease-out';
            setTimeout(() => {
                banner.remove();
            }, 300);
        }
    }
    
    // Добавляем анимацию скрытия
    const hideStyle = document.createElement('style');
    hideStyle.textContent = `
        @keyframes slideDown {
            from {
                opacity: 1;
                transform: translateX(-50%) translateY(0);
            }
            to {
                opacity: 0;
                transform: translateX(-50%) translateY(20px);
            }
        }
    `;
    document.head.appendChild(hideStyle);
    
    // Показываем баннер, если согласие не дано
    if (!hasConsent()) {
        // Небольшая задержка для лучшего UX
        setTimeout(showBanner, 1000);
    }
})();
