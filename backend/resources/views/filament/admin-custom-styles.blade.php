<style>
/* =============================================================================
   KhidmaApp Admin - Modern Professional Navigation Design
   ============================================================================= */

/* Color Variables */
:root {
    /* Light Mode Colors */
    --khidma-gold: 212, 175, 55;
    --khidma-cyan: 20, 184, 166;
    --khidma-navy: 27, 38, 59;
    --khidma-dark-navy: 15, 23, 42;
    --khidma-gray: 71, 85, 105;
    --khidma-light-gray: 148, 163, 184;
    
    /* Dark Mode Colors */
    --khidma-dark-bg: 15, 23, 42;
    --khidma-dark-surface: 30, 41, 59;
}

/* =============================================================================
   LIGHT MODE NAVIGATION (Default) - Modern & Professional
   ============================================================================= */

/* Sidebar Base - Light Mode */
.fi-sidebar {
    background: rgba(248, 250, 252, 0.95) !important; /* Çok hafif gri-beyaz */
    border-right: 1px solid rgba(var(--khidma-gold), 0.2) !important;
    backdrop-filter: blur(10px) !important;
}

/* Sidebar Header - Light Mode */
.fi-sidebar-header {
    background: linear-gradient(135deg, rgba(var(--khidma-gold), 0.05), rgba(var(--khidma-cyan), 0.05)) !important;
    border-bottom: 1px solid rgba(var(--khidma-gold), 0.3) !important;
    padding: 1rem !important;
}

/* Brand - Light Mode */
.fi-sidebar-brand {
    color: rgb(var(--khidma-gold)) !important;
    font-weight: 700 !important;
    font-size: 1.125rem !important;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1) !important;
}

/* Navigation Items Container - Light Mode */
.fi-sidebar-nav-item {
    margin: 2px 12px 6px 12px !important;
    border-radius: 10px !important;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
}

/* Navigation Items - Normal State (Light Mode) */
.fi-sidebar-nav-item .fi-sidebar-nav-item-button,
.fi-sidebar-nav-item a {
    padding: 12px 16px !important;
    border-radius: 10px !important;
    display: flex !important;
    align-items: center !important;
    gap: 12px !important;
    text-decoration: none !important;
    transition: all 0.2s ease !important;
}

/* Text - Normal State (Light Mode) */
.fi-sidebar-nav-item .fi-sidebar-nav-item-label,
.fi-sidebar-nav-item span {
    color: rgb(var(--khidma-navy)) !important;
    font-weight: 500 !important;
    font-size: 0.875rem !important;
    letter-spacing: 0.025em !important;
}

/* Icons - Normal State (Light Mode) */
.fi-sidebar-nav-item .fi-sidebar-nav-item-icon,
.fi-sidebar-nav-item svg {
    color: rgb(var(--khidma-gray)) !important;
    width: 20px !important;
    height: 20px !important;
    transition: all 0.2s ease !important;
}

/* Hover State - Light Mode */
.fi-sidebar-nav-item:hover {
    background: linear-gradient(135deg, rgba(var(--khidma-gold), 0.1), rgba(var(--khidma-gold), 0.05)) !important;
    transform: translateX(2px) !important;
    box-shadow: 0 2px 8px rgba(var(--khidma-gold), 0.15) !important;
}

.fi-sidebar-nav-item:hover .fi-sidebar-nav-item-label,
.fi-sidebar-nav-item:hover span {
    color: rgb(var(--khidma-dark-navy)) !important;
    font-weight: 600 !important;
}

.fi-sidebar-nav-item:hover .fi-sidebar-nav-item-icon,
.fi-sidebar-nav-item:hover svg {
    color: rgb(var(--khidma-gold)) !important;
    transform: scale(1.1) !important;
}

/* Active/Selected State - Light Mode */
.fi-sidebar-nav-item[aria-current="page"] {
    background: linear-gradient(135deg, rgb(var(--khidma-gold)), rgba(var(--khidma-gold), 0.8)) !important;
    border-left: 4px solid rgb(var(--khidma-cyan)) !important;
    transform: translateX(4px) !important;
    box-shadow: 
        0 4px 12px rgba(var(--khidma-gold), 0.3),
        0 2px 4px rgba(var(--khidma-gold), 0.2) !important;
}

.fi-sidebar-nav-item[aria-current="page"] .fi-sidebar-nav-item-label,
.fi-sidebar-nav-item[aria-current="page"] span {
    color: white !important;
    font-weight: 700 !important;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1) !important;
}

.fi-sidebar-nav-item[aria-current="page"] .fi-sidebar-nav-item-icon,
.fi-sidebar-nav-item[aria-current="page"] svg {
    color: white !important;
    filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.1)) !important;
}

/* Group Labels - Light Mode */
.fi-sidebar-group-label {
    color: rgb(var(--khidma-gold)) !important;
    font-weight: 600 !important;
    font-size: 0.75rem !important;
    text-transform: uppercase !important;
    letter-spacing: 0.1em !important;
    margin: 1.5rem 16px 0.5rem 16px !important;
    padding-bottom: 0.5rem !important;
    border-bottom: 1px solid rgba(var(--khidma-gold), 0.2) !important;
}

/* =============================================================================
   DARK MODE NAVIGATION - Elegant & Professional
   ============================================================================= */

/* Sidebar Base - Dark Mode */
.dark .fi-sidebar {
    background: rgba(var(--khidma-dark-bg), 0.95) !important;
    border-right: 1px solid rgba(var(--khidma-gold), 0.3) !important;
    backdrop-filter: blur(10px) !important;
}

/* Sidebar Header - Dark Mode */
.dark .fi-sidebar-header {
    background: linear-gradient(135deg, rgba(var(--khidma-gold), 0.1), rgba(var(--khidma-cyan), 0.1)) !important;
    border-bottom: 1px solid rgba(var(--khidma-gold), 0.4) !important;
}

/* Brand - Dark Mode */
.dark .fi-sidebar-brand {
    color: rgb(var(--khidma-gold)) !important;
    text-shadow: 0 1px 3px rgba(var(--khidma-gold), 0.3) !important;
}

/* Text - Normal State (Dark Mode) */
.dark .fi-sidebar-nav-item .fi-sidebar-nav-item-label,
.dark .fi-sidebar-nav-item span {
    color: rgba(255, 255, 255, 0.9) !important;
    font-weight: 500 !important;
}

/* Icons - Normal State (Dark Mode) */
.dark .fi-sidebar-nav-item .fi-sidebar-nav-item-icon,
.dark .fi-sidebar-nav-item svg {
    color: rgba(var(--khidma-cyan), 0.8) !important;
}

/* Hover State - Dark Mode */
.dark .fi-sidebar-nav-item:hover {
    background: linear-gradient(135deg, rgba(var(--khidma-gold), 0.15), rgba(var(--khidma-gold), 0.1)) !important;
}

.dark .fi-sidebar-nav-item:hover .fi-sidebar-nav-item-label,
.dark .fi-sidebar-nav-item:hover span {
    color: rgb(var(--khidma-gold)) !important;
    font-weight: 600 !important;
}

.dark .fi-sidebar-nav-item:hover .fi-sidebar-nav-item-icon,
.dark .fi-sidebar-nav-item:hover svg {
    color: rgb(var(--khidma-gold)) !important;
}

/* Active/Selected State - Dark Mode */
.dark .fi-sidebar-nav-item[aria-current="page"] {
    background: linear-gradient(135deg, rgb(var(--khidma-gold)), rgba(var(--khidma-gold), 0.8)) !important;
    border-left: 4px solid rgb(var(--khidma-cyan)) !important;
}

.dark .fi-sidebar-nav-item[aria-current="page"] .fi-sidebar-nav-item-label,
.dark .fi-sidebar-nav-item[aria-current="page"] span {
    color: rgb(var(--khidma-dark-navy)) !important;
    font-weight: 700 !important;
    text-shadow: none !important;
}

.dark .fi-sidebar-nav-item[aria-current="page"] .fi-sidebar-nav-item-icon,
.dark .fi-sidebar-nav-item[aria-current="page"] svg {
    color: rgb(var(--khidma-dark-navy)) !important;
}

/* Group Labels - Dark Mode */
.dark .fi-sidebar-group-label {
    color: rgb(var(--khidma-gold)) !important;
    border-bottom: 1px solid rgba(var(--khidma-gold), 0.3) !important;
}

/* =============================================================================
   OTHER COMPONENTS - Professional Styling
   ============================================================================= */

/* Buttons */
.fi-btn-primary {
    background: linear-gradient(135deg, rgb(var(--khidma-cyan)), rgba(var(--khidma-cyan), 0.8)) !important;
    border: 1px solid rgb(var(--khidma-gold)) !important;
    color: white !important;
    border-radius: 8px !important;
    font-weight: 600 !important;
    transition: all 0.2s ease !important;
}

.fi-btn-primary:hover {
    background: linear-gradient(135deg, rgba(var(--khidma-cyan), 0.9), rgba(var(--khidma-cyan), 0.7)) !important;
    border: 1px solid rgb(var(--khidma-gold)) !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 8px rgba(var(--khidma-cyan), 0.3) !important;
}

/* Login Page */
.fi-simple-layout {
    background: linear-gradient(135deg, rgb(var(--khidma-cyan)), rgba(var(--khidma-cyan), 0.8)) !important;
}

/* Cards */
.fi-section-content-ctn {
    border-left: 4px solid rgb(var(--khidma-gold)) !important;
    background: linear-gradient(135deg, #ffffff, rgba(var(--khidma-cyan), 0.02)) !important;
}

/* Tables */
.fi-table-header-cell {
    background: rgb(var(--khidma-navy)) !important;
    color: rgba(255, 255, 255, 0.95) !important;
    border-bottom: 2px solid rgb(var(--khidma-gold)) !important;
}

/* Stats Cards */
.fi-stats-overview-stat {
    border-left: 4px solid rgb(var(--khidma-gold)) !important;
}

.fi-stats-overview-stat-icon {
    background: linear-gradient(135deg, rgb(var(--khidma-cyan)), rgba(var(--khidma-cyan), 0.8)) !important;
    color: white !important;
}

/* Input Focus */
.fi-input:focus {
    border-color: rgb(var(--khidma-cyan)) !important;
    box-shadow: 0 0 0 3px rgba(var(--khidma-cyan), 0.1) !important;
}

/* =============================================================================
   DARK MODE - FORM STYLING (Beyaz Text & Borders)
   ============================================================================= */

/* Dark Mode - Ana Content Container */
.dark .fi-main {
    background: rgb(var(--khidma-dark-bg)) !important;
    color: white !important;
}

/* Dark Mode - Form Containers */
.dark .fi-section,
.dark .fi-section-content,
.dark .fi-section-content-ctn,
.dark .fi-form-section,
.dark .fi-form-section-content {
    background: rgba(var(--khidma-dark-surface), 0.5) !important;
    border: 1px solid rgba(255, 255, 255, 0.2) !important;
    border-radius: 12px !important;
    color: white !important;
}

/* Dark Mode - Section Headers */
.dark .fi-section-header,
.dark .fi-section-header-heading,
.dark .fi-form-section-header,
.dark .fi-form-section-header-heading {
    background: rgba(var(--khidma-dark-surface), 0.8) !important;
    border-bottom: 1px solid rgba(255, 255, 255, 0.15) !important;
    color: white !important;
    border-radius: 12px 12px 0 0 !important;
}

/* Dark Mode - All Text Elements */
.dark .fi-section h1,
.dark .fi-section h2,
.dark .fi-section h3,
.dark .fi-section h4,
.dark .fi-section h5,
.dark .fi-section h6,
.dark .fi-section p,
.dark .fi-section span,
.dark .fi-section div,
.dark .fi-section label,
.dark .fi-form-field-label,
.dark .fi-field-wrp label,
.dark .fi-field-label,
.dark .fi-form label {
    color: white !important;
    font-weight: 500 !important;
}

/* Dark Mode - Form Inputs */
.dark .fi-input,
.dark .fi-select,
.dark .fi-textarea,
.dark input[type="text"],
.dark input[type="email"],
.dark input[type="tel"],
.dark input[type="password"],
.dark select,
.dark textarea {
    background: rgba(var(--khidma-dark-bg), 0.8) !important;
    border: 1px solid rgba(255, 255, 255, 0.3) !important;
    color: white !important;
    border-radius: 8px !important;
}

/* Dark Mode - Input Focus */
.dark .fi-input:focus,
.dark .fi-select:focus,
.dark .fi-textarea:focus,
.dark input:focus,
.dark select:focus,
.dark textarea:focus {
    border-color: rgb(var(--khidma-gold)) !important;
    box-shadow: 0 0 0 3px rgba(var(--khidma-gold), 0.2) !important;
    background: rgba(var(--khidma-dark-bg), 0.9) !important;
}

/* Dark Mode - Input Placeholders */
.dark .fi-input::placeholder,
.dark .fi-select::placeholder,
.dark .fi-textarea::placeholder,
.dark input::placeholder,
.dark textarea::placeholder {
    color: rgba(255, 255, 255, 0.6) !important;
}

/* Dark Mode - Field Separators */
.dark .fi-field-wrp,
.dark .fi-form-field {
    border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
    padding: 1rem 0 !important;
    margin-bottom: 0.5rem !important;
}

/* Dark Mode - Field Groups */
.dark .fi-fieldset,
.dark .fi-form-fieldset {
    border: 1px solid rgba(255, 255, 255, 0.2) !important;
    border-radius: 8px !important;
    background: rgba(var(--khidma-dark-surface), 0.3) !important;
    padding: 1rem !important;
    margin: 1rem 0 !important;
}

/* Dark Mode - Fieldset Legends */
.dark .fi-fieldset-legend,
.dark .fi-form-fieldset legend {
    color: rgb(var(--khidma-gold)) !important;
    font-weight: 600 !important;
    background: rgba(var(--khidma-dark-bg), 1) !important;
    padding: 0 0.5rem !important;
    border: 1px solid rgba(255, 255, 255, 0.2) !important;
    border-radius: 4px !important;
}

/* Dark Mode - Form Buttons */
.dark .fi-btn,
.dark button {
    border: 1px solid rgba(255, 255, 255, 0.3) !important;
    color: white !important;
    background: rgba(var(--khidma-dark-surface), 0.8) !important;
    border-radius: 8px !important;
}

.dark .fi-btn:hover,
.dark button:hover {
    border: 1px solid rgba(255, 255, 255, 0.5) !important;
    background: rgba(var(--khidma-dark-surface), 1) !important;
}

/* Dark Mode - Select Dropdowns */
.dark .fi-select-option,
.dark select option {
    background: rgba(var(--khidma-dark-bg), 1) !important;
    color: white !important;
    border: 1px solid rgba(255, 255, 255, 0.2) !important;
}

/* Dark Mode - Required Field Indicators */
.dark .fi-field-required,
.dark .required-indicator {
    color: rgb(var(--khidma-gold)) !important;
}

/* Dark Mode - Help Text */
.dark .fi-field-help,
.dark .fi-help-text,
.dark .help-text {
    color: rgba(255, 255, 255, 0.7) !important;
    border-left: 2px solid rgba(var(--khidma-gold), 0.5) !important;
    padding-left: 0.5rem !important;
}

/* Dark Mode - Error Messages */
.dark .fi-field-error,
.dark .fi-error-message,
.dark .error-message {
    color: #ff6b6b !important;
    background: rgba(255, 107, 107, 0.1) !important;
    border: 1px solid rgba(255, 107, 107, 0.3) !important;
    border-radius: 4px !important;
    padding: 0.5rem !important;
}

/* Dark Mode - Tables */
.dark .fi-table,
.dark table {
    background: rgba(var(--khidma-dark-surface), 0.5) !important;
    border: 1px solid rgba(255, 255, 255, 0.2) !important;
    color: white !important;
}

.dark .fi-table th,
.dark .fi-table td,
.dark table th,
.dark table td {
    border: 1px solid rgba(255, 255, 255, 0.15) !important;
    color: white !important;
    padding: 0.75rem !important;
}

.dark .fi-table-header-cell,
.dark table thead th {
    background: rgba(var(--khidma-dark-surface), 0.8) !important;
    color: rgb(var(--khidma-gold)) !important;
    font-weight: 600 !important;
    border-bottom: 2px solid rgba(255, 255, 255, 0.3) !important;
}
</style>

<script>
/* =============================================================================
   KhidmaApp Admin - Modern Navigation JavaScript Enhancement
   ============================================================================= */

document.addEventListener('DOMContentLoaded', function() {
    // Varsayılan olarak Light Mode ayarla
    if (!localStorage.getItem('theme')) {
        document.documentElement.classList.remove('dark');
        localStorage.setItem('theme', 'light');
        console.log('KhidmaApp: Light Mode varsayılan olarak ayarlandı! ☀️');
    }
    
    // Navigation enhancement başlat
    setTimeout(function() {
        initializeNavigation();
        console.log('KhidmaApp: Modern Professional Navigation initialized! 🚀');
    }, 500);
    
    // Theme değişikliklerini dinle
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                updateNavigationTheme();
            }
        });
    });
    
    observer.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['class']
    });
});

function initializeNavigation() {
    // Navigation item'ları al ve enhance et
    const navItems = document.querySelectorAll('.fi-sidebar-nav-item');
    
    navItems.forEach(function(item) {
        // Modern hover effects ekle
        enhanceNavigationItem(item);
    });
    
    // Theme'e göre başlangıç durumunu ayarla
    updateNavigationTheme();
}

function enhanceNavigationItem(item) {
    // Smooth transition ekle
    item.style.transition = 'all 0.2s cubic-bezier(0.4, 0, 0.2, 1)';
    
    // Modern hover effect
    item.addEventListener('mouseenter', function() {
        // CSS'de tanımlanan hover effects aktif
        // Ek animasyonlar burada eklenebilir
        const icon = item.querySelector('svg');
        if (icon) {
            icon.style.transform = 'scale(1.1)';
        }
    });
    
    item.addEventListener('mouseleave', function() {
        const icon = item.querySelector('svg');
        if (icon && !item.hasAttribute('aria-current')) {
            icon.style.transform = 'scale(1)';
        }
    });
    
    // Active state enhancement
    if (item.hasAttribute('aria-current')) {
        const icon = item.querySelector('svg');
        if (icon) {
            icon.style.transform = 'scale(1.05)';
        }
    }
}

function updateNavigationTheme() {
    const isDarkMode = document.documentElement.classList.contains('dark');
    const themeDisplay = isDarkMode ? 'Dark Mode 🌙' : 'Light Mode ☀️';
    
    // Brand ve group labels her zaman altın kalsın
    const brandElements = document.querySelectorAll('.fi-sidebar-brand, .fi-sidebar-brand *');
    brandElements.forEach(function(element) {
        element.style.setProperty('color', 'rgb(212, 175, 55)', 'important');
    });
    
    const groupLabels = document.querySelectorAll('.fi-sidebar-group-label, .fi-sidebar-group-label *');
    groupLabels.forEach(function(element) {
        element.style.setProperty('color', 'rgb(212, 175, 55)', 'important');
    });
    
    // Dark mode'da form elemanlarını beyazla
    if (isDarkMode) {
        enhanceDarkModeFormElements();
    }
    
    console.log(`KhidmaApp Navigation: ${themeDisplay} aktif!`);
}

function enhanceDarkModeFormElements() {
    // Tüm form yazılarını beyazla
    const formTexts = document.querySelectorAll('.fi-section label, .fi-section span, .fi-section div, .fi-section p, .fi-section h1, .fi-section h2, .fi-section h3, .fi-section h4, .fi-section h5, .fi-section h6');
    formTexts.forEach(function(element) {
        if (!element.closest('.fi-sidebar')) {
            element.style.setProperty('color', 'white', 'important');
        }
    });
    
    // Form input'larını beyaz border yap
    const formInputs = document.querySelectorAll('.fi-input, .fi-select, .fi-textarea, input, select, textarea');
    formInputs.forEach(function(element) {
        if (!element.closest('.fi-sidebar')) {
            element.style.setProperty('border', '1px solid rgba(255, 255, 255, 0.3)', 'important');
            element.style.setProperty('color', 'white', 'important');
            element.style.setProperty('background', 'rgba(15, 23, 42, 0.8)', 'important');
        }
    });
    
    // Form container'ları beyaz border yap
    const formContainers = document.querySelectorAll('.fi-section, .fi-section-content, .fi-form-section');
    formContainers.forEach(function(element) {
        if (!element.closest('.fi-sidebar')) {
            element.style.setProperty('border', '1px solid rgba(255, 255, 255, 0.2)', 'important');
            element.style.setProperty('background', 'rgba(30, 41, 59, 0.5)', 'important');
        }
    });
    
    // Field separator'ları beyaz yap
    const fieldSeparators = document.querySelectorAll('.fi-field-wrp, .fi-form-field');
    fieldSeparators.forEach(function(element) {
        if (!element.closest('.fi-sidebar')) {
            element.style.setProperty('border-bottom', '1px solid rgba(255, 255, 255, 0.1)', 'important');
        }
    });
}

// Theme toggle desteği
function toggleTheme() {
    const isDark = document.documentElement.classList.contains('dark');
    
    if (isDark) {
        document.documentElement.classList.remove('dark');
        localStorage.setItem('theme', 'light');
    } else {
        document.documentElement.classList.add('dark');
        localStorage.setItem('theme', 'dark');
    }
    
    updateNavigationTheme();
}

// Dinamik content için periyodik kontrol
setInterval(function() {
    // Yeni navigation item'ları kontrol et
    const newItems = document.querySelectorAll('.fi-sidebar-nav-item:not([data-enhanced])');
    
    newItems.forEach(function(item) {
        item.setAttribute('data-enhanced', 'true');
        enhanceNavigationItem(item);
    });
    
    // Dark mode'da yeni form elemanlarını kontrol et
    const isDarkMode = document.documentElement.classList.contains('dark');
    if (isDarkMode) {
        enhanceDarkModeFormElements();
    }
}, 2000);
</script>
