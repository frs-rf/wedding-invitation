const animate = require("tailwindcss-animate")
const defaultTheme = require('tailwindcss/defaultTheme');
const forms = require('@tailwindcss/forms');

/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: ["class"],
  safelist: ["dark"],
  prefix: "",
  
  content: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
    './resources/js/**/*.{ts,tsx,vue}',
  ],
  
  theme: {
    container: {
      center: true,
      padding: "2rem",
      screens: {
        "2xl": "1400px",
      },
    },
    extend: {
      fontFamily: {
        sans: ['Figtree', ...defaultTheme.fontFamily.sans],
        "label-xs": ["Geist"],
        "headline-md": ["Geist"],
        "display": ["Geist"],
        "display-mobile": ["Geist"],
        "body-lg": ["Geist"],
        "body-md": ["Geist"],
        "headline-lg": ["Geist"],
        "label-sm": ["Geist"]
      },
      fontSize: {
        "label-xs": ["12px", {"lineHeight": "16px", "fontWeight": "600"}],
        "headline-md": ["24px", {"lineHeight": "32px", "letterSpacing": "-0.01em", "fontWeight": "500"}],
        "display": ["48px", {"lineHeight": "1.1", "letterSpacing": "-0.04em", "fontWeight": "600"}],
        "display-mobile": ["36px", {"lineHeight": "1.2", "letterSpacing": "-0.02em", "fontWeight": "600"}],
        "body-lg": ["18px", {"lineHeight": "28px", "fontWeight": "400"}],
        "body-md": ["16px", {"lineHeight": "24px", "fontWeight": "400"}],
        "headline-lg": ["30px", {"lineHeight": "36px", "letterSpacing": "-0.02em", "fontWeight": "500"}],
        "label-sm": ["14px", {"lineHeight": "20px", "letterSpacing": "0.02em", "fontWeight": "500"}]
      },
      spacing: {
        "container-max": "1280px",
        "gutter": "1.5rem",
        "2xl": "4rem",
        "lg": "1.5rem",
        "base": "4px",
        "xl": "2.5rem",
        "sm": "0.5rem",
        "md": "1rem",
        "xs": "0.25rem"
      },
      colors: {
        "on-primary-fixed": "#0f0069",
        "on-primary-container": "#a9a7ff",
        "tertiary-container": "#752c00",
        "on-background": "#1b1b1e",
        "secondary": "#5d5e66",
        "error": "#ba1a1a",
        "surface-variant": "#e4e1e6",
        "outline-variant": "#c8c4d5",
        "tertiary-fixed": "#ffdbcc",
        "tertiary-fixed-dim": "#ffb694",
        "primary-fixed": "#e2dfff",
        "secondary-fixed-dim": "#c6c5cf",
        "on-tertiary-fixed-variant": "#7a3003",
        "tertiary": "#511c00",
        "surface-bright": "#fbf8fc",
        "surface-tint": "#544fc0",
        "on-surface": "#1b1b1e",
        "on-tertiary": "#ffffff",
        "inverse-surface": "#303033",
        "secondary-fixed": "#e3e1ec",
        "surface-container-lowest": "#ffffff",
        "on-secondary": "#ffffff",
        "on-error-container": "#93000a",
        "on-primary-fixed-variant": "#3b35a7",
        "on-primary": "#ffffff",
        "primary-container": "#3730a3",
        "surface-container-high": "#eae7eb",
        "on-error": "#ffffff",
        "error-container": "#ffdad6",
        "inverse-primary": "#c3c0ff",
        "surface-container-low": "#f6f2f7",
        "on-secondary-fixed": "#1a1b22",
        "surface-container": "#f0edf1",
        "primary-fixed-dim": "#c3c0ff",
        "surface": "#fbf8fc",
        "inverse-on-surface": "#f3f0f4",
        "on-tertiary-fixed": "#351000",
        "surface-dim": "#dcd9dd",
        "on-secondary-container": "#63646c",
        "secondary-container": "#e3e1ec",
        "outline": "#777584",
        "on-tertiary-container": "#fe9562",
        "surface-container-highest": "#e4e1e6",
        "on-surface-variant": "#464553",
        "on-secondary-fixed-variant": "#46464e",
        // Shadcn UI Colors preserved below
        border: "var(--border)",
        input: "var(--input)",
        ring: "var(--ring)",
        background: "var(--background)",
        foreground: "var(--foreground)",
        primary: {
          DEFAULT: "var(--primary)",
          foreground: "var(--primary-foreground)",
        },
        destructive: {
          DEFAULT: "var(--destructive)",
          foreground: "var(--destructive-foreground)",
        },
        muted: {
          DEFAULT: "var(--muted)",
          foreground: "var(--muted-foreground)",
        },
        accent: {
          DEFAULT: "var(--accent)",
          foreground: "var(--accent-foreground)",
        },
        popover: {
          DEFAULT: "var(--popover)",
          foreground: "var(--popover-foreground)",
        },
        card: {
          DEFAULT: "var(--card)",
          foreground: "var(--card-foreground)",
        },
      },
      borderRadius: {
        xl: "calc(var(--radius) + 4px)",
        lg: "var(--radius)",
        md: "calc(var(--radius) - 2px)",
        sm: "calc(var(--radius) - 4px)",
      },
      keyframes: {
        "accordion-down": {
          from: { height: 0 },
          to: { height: "var(--radix-accordion-content-height)" },
        },
        "accordion-up": {
          from: { height: "var(--radix-accordion-content-height)" },
          to: { height: 0 },
        },
        "collapsible-down": {
          from: { height: 0 },
          to: { height: 'var(--radix-collapsible-content-height)' },
        },
        "collapsible-up": {
          from: { height: 'var(--radix-collapsible-content-height)' },
          to: { height: 0 },
        },
      },
      animation: {
        "accordion-down": "accordion-down 0.2s ease-out",
        "accordion-up": "accordion-up 0.2s ease-out",
        "collapsible-down": "collapsible-down 0.2s ease-in-out",
        "collapsible-up": "collapsible-up 0.2s ease-in-out",
      },
    },
  },
  plugins: [animate, forms],
}
