/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./public/**/*.{html,js,php}",
    "./src/**/*.{html,js,php}",
    "./src/Views/**/*.php"
  ],
  theme: {
    extend: {
      // Arapça font ailesi için
      fontFamily: {
        'arabic': ['Noto Sans Arabic', 'Cairo', 'Tajawal', 'sans-serif'],
        'sans': ['Inter', 'system-ui', 'sans-serif']
      },
      
      // RTL için özel spacing
      spacing: {
        '18': '4.5rem',
        '88': '22rem',
        '128': '32rem'
      },
      
      // Brand renkleri
      colors: {
        primary: {
          50: '#f0f9ff',
          100: '#e0f2fe',
          200: '#bae6fd',
          300: '#7dd3fc',
          400: '#38bdf8',
          500: '#0ea5e9',
          600: '#0284c7',
          700: '#0369a1',
          800: '#075985',
          900: '#0c4a6e',
          950: '#082f49'
        },
        success: {
          50: '#f0fdf4',
          100: '#dcfce7',
          200: '#bbf7d0',
          300: '#86efac',
          400: '#4ade80',
          500: '#22c55e',
          600: '#16a34a',
          700: '#15803d',
          800: '#166534',
          900: '#14532d'
        },
        warning: {
          50: '#fffbeb',
          100: '#fef3c7',
          200: '#fde68a',
          300: '#fcd34d',
          400: '#fbbf24',
          500: '#f59e0b',
          600: '#d97706',
          700: '#b45309',
          800: '#92400e',
          900: '#78350f'
        },
        danger: {
          50: '#fef2f2',
          100: '#fee2e2',
          200: '#fecaca',
          300: '#fca5a5',
          400: '#f87171',
          500: '#ef4444',
          600: '#dc2626',
          700: '#b91c1c',
          800: '#991b1b',
          900: '#7f1d1d'
        }
      },
      
      // Özel border radius değerleri
      borderRadius: {
        'xl': '1rem',
        '2xl': '1.5rem',
        '3xl': '2rem'
      },
      
      // Box shadow özelleştirmeleri
      boxShadow: {
        'soft': '0 2px 15px -3px rgba(0, 0, 0, 0.07), 0 10px 20px -2px rgba(0, 0, 0, 0.04)',
        'card': '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)',
        'float': '0 10px 25px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)'
      },
      
      // Animation süreleri
      transitionDuration: {
        '400': '400ms',
        '600': '600ms'
      },
      
      // Mobile-first breakpoints (Tailwind default)
      screens: {
        'xs': '475px',
        'sm': '640px',
        'md': '768px',
        'lg': '1024px',
        'xl': '1280px',
        '2xl': '1536px'
      }
    }
  },
  plugins: [
    // RTL Plugin için (npm install tailwindcss-rtl gerekebilir)
    function({ addUtilities }) {
      const newUtilities = {
        '.rtl': {
          direction: 'rtl',
        },
        '.ltr': {
          direction: 'ltr',
        },
        '.text-start': {
          'text-align': 'start',
        },
        '.text-end': {
          'text-align': 'end',
        },
        // RTL için özel margin/padding
        '.me-auto': {
          'margin-inline-end': 'auto',
        },
        '.ms-auto': {
          'margin-inline-start': 'auto',
        },
        '.pe-4': {
          'padding-inline-end': '1rem',
        },
        '.ps-4': {
          'padding-inline-start': '1rem',
        }
      }
      addUtilities(newUtilities)
    }
  ],
  
  // RTL support
  corePlugins: {
    // Float kullanmak yerine flexbox kullanacağız
    float: false,
  }
}








