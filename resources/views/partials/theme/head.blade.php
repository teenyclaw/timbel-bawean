<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                fontFamily: { sans: ['Inter', 'system-ui', 'sans-serif'] },
                colors: {
                    brand: {
                        50: '#f0fdfa', 100: '#ccfbf1', 200: '#99f6e4',
                        500: '#14b8a6', 600: '#0d9488', 700: '#0f766e', 800: '#115e59',
                    },
                },
                boxShadow: {
                    'premium': '0 1px 2px rgba(15, 23, 42, 0.04), 0 4px 20px rgba(15, 23, 42, 0.06)',
                    'premium-lg': '0 4px 6px rgba(15, 23, 42, 0.04), 0 12px 40px rgba(15, 23, 42, 0.08)',
                },
            },
        },
    };
</script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<style>
    [x-cloak] { display: none !important; }

    body {
        font-family: 'Inter', system-ui, sans-serif;
        -webkit-font-smoothing: antialiased;
    }

    .staff-bg {
        background-color: #f1f5f9;
        background-image:
            radial-gradient(ellipse 80% 60% at 0% 0%, rgba(13, 148, 136, 0.07), transparent 55%),
            radial-gradient(ellipse 70% 50% at 100% 100%, rgba(13, 148, 136, 0.05), transparent 50%);
    }

    .customer-bg {
        background-color: #f1f5f9;
        background-image:
            radial-gradient(ellipse 90% 60% at 50% -10%, rgba(13, 148, 136, 0.08), transparent 50%),
            radial-gradient(ellipse 60% 40% at 100% 80%, rgba(13, 148, 136, 0.04), transparent 45%);
    }

    .staff-main .bg-white.rounded-xl,
    .staff-main .bg-white.rounded-2xl,
    .staff-main .ui-card {
        border: 1px solid rgba(226, 232, 240, 0.95);
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04), 0 4px 16px rgba(15, 23, 42, 0.04);
    }

    .staff-main h1 {
        letter-spacing: -0.025em;
    }

    .staff-main table thead tr {
        border-bottom: 1px solid #e2e8f0;
    }

    .staff-main input[type="text"],
    .staff-main input[type="email"],
    .staff-main input[type="password"],
    .staff-main input[type="number"],
    .staff-main input[type="search"],
    .staff-main input[type="tel"],
    .staff-main input[type="date"],
    .staff-main select,
    .staff-main textarea {
        transition: border-color 0.15s, box-shadow 0.15s;
    }

    .staff-main input:focus,
    .staff-main select:focus,
    .staff-main textarea:focus {
        outline: none;
        border-color: #0d9488;
        box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.12);
    }

    .ui-card {
        background: white;
        border-radius: 1rem;
        border: 1px solid rgba(226, 232, 240, 0.9);
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04), 0 4px 16px rgba(15, 23, 42, 0.04);
    }

    .ui-btn-primary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        background: #0d9488;
        color: white;
        font-weight: 600;
        padding: 0.625rem 1rem;
        border-radius: 0.75rem;
        transition: all 0.15s;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.06);
    }
    .ui-btn-primary:hover { background: #0f766e; }

    .ui-btn-secondary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        background: white;
        color: #334155;
        font-weight: 500;
        padding: 0.625rem 1rem;
        border-radius: 0.75rem;
        border: 1px solid #e2e8f0;
        transition: all 0.15s;
    }
    .ui-btn-secondary:hover { background: #f8fafc; }

    .ui-page-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #0f172a;
        letter-spacing: -0.025em;
    }

    .ui-page-subtitle {
        font-size: 0.875rem;
        color: #64748b;
        margin-top: 0.125rem;
    }

    /* Premium button override for legacy slate-900 buttons */
    .staff-main a.bg-slate-900,
    .staff-main button.bg-slate-900 {
        background-color: #0d9488 !important;
        border-radius: 0.75rem;
        transition: background-color 0.15s;
    }
    .staff-main a.bg-slate-900:hover,
    .staff-main button.bg-slate-900:hover {
        background-color: #0f766e !important;
    }

    .staff-main a.bg-green-600,
    .staff-main button.bg-green-600 {
        border-radius: 0.75rem;
    }

    .staff-main a.bg-blue-600,
    .staff-main button.bg-blue-600 {
        background-color: #0d9488 !important;
        border-radius: 0.75rem;
    }

    .nav-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.625rem 0.75rem;
        border-radius: 0.75rem;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.15s;
        border: 1px solid transparent;
    }

    .nav-item-active {
        background: #f0fdfa;
        color: #115e59;
        border-color: rgba(153, 246, 228, 0.7);
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
    }

    .nav-item-idle {
        color: #475569;
    }
    .nav-item-idle:hover {
        background: rgba(255, 255, 255, 0.8);
        color: #0f766e;
        border-color: rgba(226, 232, 240, 0.6);
    }

    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
    ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>
