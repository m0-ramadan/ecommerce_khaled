/**
 * Config
 * -------------------------------------------------------------------------------------
 * ! IMPORTANT: Make sure you clear the browser local storage In order to see the config changes in the template.
 * ! To clear local storage: (https://www.leadshook.com/help/how-to-clear-local-storage-in-google-chrome-browser/).
 */

'use strict';

// JS global variables
window.config = {
  colors: {
    primary: '#14213d',
    secondary: '#64748b',
    success: '#1e3a5f',
    info: '#06b6d4',
    warning: '#eb993d',
    danger: '#ef4444',
    dark: '#1e293b',
    black: '#000',
    white: '#fff',
    cardColor: '#ffffff',
    bodyBg: '#f6f8fb',
    bodyColor: '#334155',
    headingColor: '#0f172a',
    textMuted: '#64748b',
    borderColor: '#e2e8f0'
  },
  colors_label: {
    primary: '#14213d24',
    secondary: '#64748b24',
    success: '#1e3a5f24',
    info: '#06b6d424',
    warning: '#eb993d24',
    danger: '#ef444424',
    dark: '#1e293b24'
  },
  colors_dark: {
    cardColor: '#ffffff',
    bodyBg: '#f6f8fb',
    bodyColor: '#334155',
    headingColor: '#0f172a',
    textMuted: '#64748b',
    borderColor: '#e2e8f0'
  },
  enableMenuLocalStorage: true // Enable menu state with local storage support
};

window.assetsPath = document.documentElement.getAttribute('data-assets-path');
window.templateName = document.documentElement.getAttribute('data-template');
window.rtlSupport = true; // set true for rtl support (rtl + ltr), false for ltr only.

/**
 * TemplateCustomizer
 * ! You must use(include) template-customizer.js to use TemplateCustomizer settings
 * -----------------------------------------------------------------------------------------------
 */

// To use more themes, just push it to THEMES object.

/* TemplateCustomizer.THEMES.push({
  name: 'theme-raspberry',
  title: 'Raspberry'
}); */

// To add more languages, just push it to LANGUAGES object.
/*
TemplateCustomizer.LANGUAGES.fr = { ... };
*/

/**
 * TemplateCustomizer settings
 * -------------------------------------------------------------------------------------
 * cssPath: Core CSS file path
 * themesPath: Theme CSS file path
 * displayCustomizer: true(Show customizer), false(Hide customizer)
 * lang: To set default language, Add more langues and set default. Fallback language is 'en'
 * controls: [ 'rtl', 'style', 'headerType', 'contentLayout', 'layoutCollapsed', 'layoutNavbarOptions', 'themes' ] | Show/Hide customizer controls
 * defaultTheme: 0(Default), 1(Bordered), 2(Semi Dark)
 * defaultStyle: 'light', 'dark', 'system' (Mode)
 * defaultTextDir: 'ltr', 'rtl' (rtlSupport must be true for rtl mode)
 * defaultContentLayout: 'compact', 'wide' (compact=container-xxl, wide=container-fluid)
 * defaultHeaderType: 'static', 'fixed' (for horizontal layout only)
 * defaultMenuCollapsed: true, false (For vertical layout only)
 * defaultNavbarType: 'sticky', 'static', 'hidden' (For vertical layout only)
 * defaultFooterFixed: true, false (For vertical layout only)
 * defaultShowDropdownOnHover : true, false (for horizontal layout only)
 */

// Light mode permanently enforced — template customizer dark mode removed.
try {
  Object.keys(localStorage).forEach(function (k) {
    if (k.indexOf('templateCustomizer-') === 0 && k.indexOf('--Style') !== -1) {
      localStorage.setItem(k, 'light');
    }
  });
} catch (e) {}
