/**
 * Session Monitor
 * 
 * Monitors user session and handles automatic logout on expiration.
 * Shows warning before session expires and redirects to login on timeout.
 */

(function() {
    'use strict';
    
    // Configuration
    const CHECK_INTERVAL = 60000; // Check every 60 seconds
    const WARNING_THRESHOLD = 300; // Show warning when 5 minutes left
    
    let warningShown = false;
    let warningModal = null;
    let checkInterval = null;
    
    /**
     * Check session status via API
     */
    async function checkSession() {
        try {
            const response = await fetch('/api/session-check.php', {
                method: 'GET',
                credentials: 'same-origin',
                headers: {
                    'Cache-Control': 'no-cache'
                }
            });
            
            const data = await response.json();
            
            if (!data.valid) {
                // Session expired or invalid
                handleSessionExpired(data.reason);
                return;
            }
            
            // Check if we should show warning
            if (data.remaining <= WARNING_THRESHOLD && !warningShown) {
                showSessionWarning(data.remaining);
            } else if (data.remaining > WARNING_THRESHOLD && warningShown) {
                hideSessionWarning();
            }
            
            // Update remaining time in warning if shown
            if (warningShown && warningModal) {
                updateWarningTime(data.remaining);
            }
            
        } catch (error) {
            console.error('Session check failed:', error);
        }
    }
    
    /**
     * Handle session expiration
     */
    function handleSessionExpired(reason) {
        // Stop checking
        if (checkInterval) {
            clearInterval(checkInterval);
        }
        
        // Show expiration message
        showExpiredModal(reason);
        
        // Redirect after short delay
        setTimeout(function() {
            window.location.href = '/login?expired=1';
        }, 2000);
    }
    
    /**
     * Show session warning modal
     */
    function showSessionWarning(remaining) {
        warningShown = true;
        
        // Create modal if not exists
        if (!warningModal) {
            warningModal = document.createElement('div');
            warningModal.id = 'session-warning-modal';
            warningModal.innerHTML = `
                <div class="fixed bottom-4 right-4 z-50 bg-yellow-500/90 backdrop-blur-sm text-black px-6 py-4 rounded-xl shadow-2xl max-w-sm animate-fade-in">
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <div class="flex-1">
                            <h4 class="font-bold text-sm">Сессия скоро истечёт</h4>
                            <p class="text-sm mt-1">Осталось: <span id="session-time-left">${formatTime(remaining)}</span></p>
                            <div class="flex gap-2 mt-3">
                                <button onclick="window.sessionMonitor.extend()" class="px-3 py-1.5 bg-black text-white text-xs font-medium rounded-lg hover:bg-gray-800 transition-colors">
                                    Продлить
                                </button>
                                <button onclick="window.sessionMonitor.logout()" class="px-3 py-1.5 bg-white/30 text-black text-xs font-medium rounded-lg hover:bg-white/50 transition-colors">
                                    Выйти
                                </button>
                            </div>
                        </div>
                        <button onclick="window.sessionMonitor.dismissWarning()" class="text-black/50 hover:text-black">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
            `;
            document.body.appendChild(warningModal);
        } else {
            warningModal.style.display = 'block';
        }
    }
    
    /**
     * Update warning time display
     */
    function updateWarningTime(remaining) {
        const timeEl = document.getElementById('session-time-left');
        if (timeEl) {
            timeEl.textContent = formatTime(remaining);
        }
    }
    
    /**
     * Hide session warning
     */
    function hideSessionWarning() {
        warningShown = false;
        if (warningModal) {
            warningModal.style.display = 'none';
        }
    }
    
    /**
     * Show expired modal
     */
    function showExpiredModal(reason) {
        const modal = document.createElement('div');
        modal.innerHTML = `
            <div class="fixed inset-0 z-[9999] bg-black/70 backdrop-blur-sm flex items-center justify-center">
                <div class="bg-slate-900 border border-white/10 rounded-2xl p-8 max-w-md mx-4 text-center animate-fade-in">
                    <div class="w-16 h-16 mx-auto mb-4 bg-red-500/20 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Сессия истекла</h3>
                    <p class="text-slate-400 mb-4">Ваша сессия истекла из-за неактивности. Пожалуйста, войдите снова.</p>
                    <p class="text-sm text-slate-500">Перенаправление на страницу входа...</p>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
    }
    
    /**
     * Format seconds to MM:SS
     */
    function formatTime(seconds) {
        const mins = Math.floor(seconds / 60);
        const secs = seconds % 60;
        return `${mins}:${secs.toString().padStart(2, '0')}`;
    }
    
    /**
     * Extend session by making activity
     */
    async function extendSession() {
        try {
            await fetch('/api/session-check.php', {
                method: 'GET',
                credentials: 'same-origin'
            });
            hideSessionWarning();
        } catch (error) {
            console.error('Failed to extend session:', error);
        }
    }
    
    /**
     * Logout user
     */
    function logout() {
        window.location.href = '/logout';
    }
    
    /**
     * Dismiss warning (but keep monitoring)
     */
    function dismissWarning() {
        hideSessionWarning();
        // Will show again on next check if still low
        warningShown = false;
    }
    
    /**
     * Initialize session monitor
     */
    function init() {
        // Only run if user appears to be logged in (check for common logged-in indicators)
        const isLoggedIn = document.body.classList.contains('logged-in') || 
                          document.querySelector('[data-user-logged-in]') ||
                          document.querySelector('.user-menu') ||
                          window.location.pathname.includes('/admin') ||
                          window.location.pathname.includes('/profile');
        
        // Also check if we're on login/register pages - don't monitor there
        const isAuthPage = window.location.pathname.includes('/login') || 
                          window.location.pathname.includes('/register') ||
                          window.location.pathname.includes('/forgot') ||
                          window.location.pathname.includes('/reset');
        
        if (isAuthPage) {
            return; // Don't monitor on auth pages
        }
        
        // Start monitoring
        checkSession(); // Initial check
        checkInterval = setInterval(checkSession, CHECK_INTERVAL);
        
        // Also check on user activity (extends session)
        let activityTimeout;
        const activityEvents = ['mousedown', 'keydown', 'scroll', 'touchstart'];
        
        activityEvents.forEach(function(event) {
            document.addEventListener(event, function() {
                // Debounce activity updates
                clearTimeout(activityTimeout);
                activityTimeout = setTimeout(function() {
                    // Just update local activity, API call happens on interval
                }, 1000);
            }, { passive: true });
        });
    }
    
    // Expose API
    window.sessionMonitor = {
        extend: extendSession,
        logout: logout,
        dismissWarning: dismissWarning,
        check: checkSession
    };
    
    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
