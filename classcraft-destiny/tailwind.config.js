/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./resources/**/*.ts",
    "./resources/**/*.jsx",
    "./resources/**/*.tsx",
  ],
  theme: {
    extend: {
      fontFamily: {
        'orbitron': ['Orbitron', 'monospace'],
        'exo': ['Exo 2', 'sans-serif'],
      },
      colors: {
        'cosmic': {
          'purple': '#7C3AED',
          'purple-light': '#A855F7',
          'purple-dark': '#5B21B6',
          'cyan': '#0EA5E9',
          'cyan-light': '#38BDF8',
          'cyan-dark': '#0284C7',
          'gold': '#F59E0B',
          'gold-light': '#FBBF24',
          'blue': '#3B82F6',
          'green': '#10B981',
          'red': '#EF4444',
          'orange': '#F97316',
          'dark': '#0B0B1A',
          'darker': '#050511',
          'panel': '#1A1B2E',
          'panel-light': '#262B47',
          'text': '#F8FAFC',
          'text-secondary': '#E2E8F0',
          'text-muted': '#94A3B8',
          'text-disabled': '#64748B',
          'border': 'rgba(139, 92, 246, 0.2)',
          'border-bright': 'rgba(139, 92, 246, 0.5)',
        }
      },
      backgroundImage: {
        'gradient-cosmic': 'linear-gradient(135deg, #7C3AED 0%, #A855F7 25%, #38BDF8 50%, #A855F7 75%, #7C3AED 100%)',
        'gradient-cosmic-dark': 'linear-gradient(180deg, #0B0B1A 0%, #050511 100%)',
      },
      animation: {
        'cosmic-glow': 'cosmic-glow 5s ease-in-out infinite alternate',
        'cosmic-float': 'cosmic-float 4s ease-in-out infinite',
        'cosmic-spin': 'cosmic-spin 1s linear infinite',
        'cosmic-pulse': 'cosmic-pulse 3s infinite',
      },
      keyframes: {
        'cosmic-glow': {
          '0%': { 
            filter: 'drop-shadow(0 0 30px rgba(124, 58, 237, 0.6)) brightness(1)'
          },
          '100%': { 
            filter: 'drop-shadow(0 0 50px rgba(14, 165, 233, 0.8)) brightness(1.1)'
          }
        },
        'cosmic-float': {
          '0%, 100%': { 
            transform: 'translateY(0px) rotate(0deg)',
            filter: 'drop-shadow(0 0 30px rgba(124, 58, 237, 0.8))'
          },
          '50%': { 
            transform: 'translateY(-15px) rotate(2deg)',
            filter: 'drop-shadow(0 0 40px rgba(14, 165, 233, 0.9))'
          }
        },
        'cosmic-spin': {
          '0%': { transform: 'rotate(0deg)' },
          '100%': { transform: 'rotate(360deg)' }
        },
        'cosmic-pulse': {
          '0%, 100%': { 
            opacity: '1',
            filter: 'drop-shadow(0 0 20px rgba(14, 165, 233, 0.8))'
          },
          '50%': { 
            opacity: '0.6',
            filter: 'drop-shadow(0 0 30px rgba(124, 58, 237, 0.9))'
          }
        }
      }
    },
  },
  plugins: [],
}