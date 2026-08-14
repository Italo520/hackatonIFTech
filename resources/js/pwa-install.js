/**
 * Gerenciador de Instalação do PWA (Progressive Web App)
 * Suporte a Android, Chrome/Edge Desktop, Samsung Internet e iOS (Safari).
 */

let deferredInstallPrompt = null;
const INSTALL_DISMISSED_KEY = 'pwa_install_dismissed_time';

export const PWAInstaller = {
    isStandalone() {
        return (
            window.matchMedia('(display-mode: standalone)').matches ||
            window.navigator.standalone === true ||
            document.referrer.includes('android-app://')
        );
    },

    isIOS() {
        const ua = window.navigator.userAgent.toLowerCase();
        return /iphone|ipad|ipod/.test(ua) && !window.MSStream;
    },

    init() {
        // 1. Registra o Service Worker
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then((reg) => {
                        console.log('✅ PWA Service Worker registrado com escopo:', reg.scope);
                        reg.update();
                    })
                    .catch((err) => {
                        console.warn('⚠️ Falha ao registrar Service Worker:', err);
                    });
            });
        }

        // 2. Se já estiver instalado/standalone, oculta botões de instalação
        if (this.isStandalone()) {
            console.log('📱 App rodando em modo Standalone (PWA Instalado).');
            this.hideInstallUI();
            return;
        }

        // 3. Captura o evento nativo beforeinstallprompt
        window.addEventListener('beforeinstallprompt', (e) => {
            console.log('🎯 Evento beforeinstallprompt capturado!');
            e.preventDefault();
            deferredInstallPrompt = e;
            this.showInstallUI();
        });

        // 4. Captura evento de instalação concluída
        window.addEventListener('appinstalled', () => {
            console.log('🎉 PWA instalado com sucesso!');
            deferredInstallPrompt = null;
            localStorage.setItem('pwa_is_installed', 'true');
            this.hideInstallUI();
            this.showInstalledToast();
        });

        // 5. Se for iOS, configura suporte a instruções do Safari
        if (this.isIOS() && !this.isStandalone()) {
            setTimeout(() => {
                this.showInstallUI(true);
            }, 2000);
        }

        // 6. Conecta botões existentes na tela
        this.bindEvents();
    },

    bindEvents() {
        document.addEventListener('DOMContentLoaded', () => {
            // Botões de acionamento de instalação
            document.querySelectorAll('.btn-trigger-pwa-install, #btn-install-banner, #btn-header-install').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.promptInstall();
                });
            });

            // Botão dispensar banner
            const dismissBtn = document.getElementById('btn-dismiss-install-banner');
            if (dismissBtn) {
                dismissBtn.addEventListener('click', () => {
                    this.dismissBanner();
                });
            }
        });
    },

    showInstallUI(isIOSPrompt = false) {
        // Verifica se o usuário dispensou recentemente (últimas 24 horas)
        const lastDismissed = localStorage.getItem(INSTALL_DISMISSED_KEY);
        const hoursSinceDismiss = lastDismissed ? (Date.now() - parseInt(lastDismissed, 10)) / (1000 * 60 * 60) : 999;

        // Exibe o botão de instalação no cabeçalho
        document.querySelectorAll('#btn-header-install, .pwa-install-badge').forEach(el => {
            el.classList.remove('d-none');
        });

        // Exibe o banner inferior flutuante se não tiver sido dispensado nas últimas 24h
        if (hoursSinceDismiss > 24) {
            const banner = document.getElementById('pwa-install-banner');
            if (banner) {
                banner.classList.remove('d-none');
                banner.classList.add('animate-slide-up');
            }
        }
    },

    hideInstallUI() {
        document.querySelectorAll('#pwa-install-banner, #btn-header-install, .pwa-install-badge').forEach(el => {
            el.classList.add('d-none');
        });
    },

    dismissBanner() {
        const banner = document.getElementById('pwa-install-banner');
        if (banner) banner.classList.add('d-none');
        localStorage.setItem(INSTALL_DISMISSED_KEY, Date.now().toString());
    },

    async promptInstall() {
        // Se for iOS, abre o modal com o passo a passo
        if (this.isIOS()) {
            const iosModal = document.getElementById('pwaIosInstallModal');
            if (iosModal && typeof bootstrap !== 'undefined') {
                const modal = bootstrap.Modal.getInstance(iosModal) || new bootstrap.Modal(iosModal);
                modal.show();
            } else {
                alert("Para instalar no iOS: Toque no botão de Compartilhar do Safari e selecione 'Adicionar à Tela de Início'.");
            }
            return;
        }

        // Se houver prompt nativo capturado
        if (deferredInstallPrompt) {
            deferredInstallPrompt.prompt();
            const { outcome } = await deferredInstallPrompt.userChoice;
            console.log(`Resposta do usuário para instalação: ${outcome}`);
            
            if (outcome === 'accepted') {
                this.hideInstallUI();
            }
            deferredInstallPrompt = null;
        } else {
            // Caso o navegador ainda não tenha disparado ou não suporte prompt programático
            const modal = document.getElementById('pwaManualInstallModal');
            if (modal && typeof bootstrap !== 'undefined') {
                const modalInstance = bootstrap.Modal.getInstance(modal) || new bootstrap.Modal(modal);
                modalInstance.show();
            } else {
                alert("Para instalar o App: Abra o menu do seu navegador (três pontinhos) e toque em 'Instalar Aplicativo' ou 'Adicionar à Tela Inicial'.");
            }
        }
    },

    showInstalledToast() {
        const toastEl = document.getElementById('pwa-installed-toast');
        if (toastEl && typeof bootstrap !== 'undefined') {
            const toast = new bootstrap.Toast(toastEl);
            toast.show();
        }
    }
};

// Inicialização automática
if (typeof window !== 'undefined') {
    window.PWAInstaller = PWAInstaller;
    PWAInstaller.init();
}

export default PWAInstaller;
