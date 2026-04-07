/**
 * Main JavaScript Entry Point
 * 
 * File ini adalah entry point untuk semua JavaScript di aplikasi
 * Di-compile oleh Vite build system menjadi public/build/assets/app-[hash].js
 * 
 * Build Commands:
 * - Development: npm run dev (hot reload)
 * - Production: npm run build (optimized, minified)
 * 
 * Dependencies:
 * - bootstrap.js: Setup axios untuk AJAX requests
 * - app.css: Import Tailwind CSS dan custom styles
 * - Alpine.js: Lightweight reactive framework (alternative Vue/React)
 * 
 * Alpine.js Usage Examples:
 * - x-data="{ open: false }" - Reactive state
 * - @click="open = !open" - Event handling  
 * - x-show="open" - Conditional display
 * - x-transition - Smooth animations
 */

import '../css/app.css';
import './bootstrap';

// Importing Alpine.js for reactive components
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();