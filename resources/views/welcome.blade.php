<!DOCTYPE html>
<html lang="sr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>AutoIQ</title>
        <meta name="description" content="AutoIQ pomaže da pronađete automobil uz jasniju sliku o ceni, stanju tržišta i isplativosti kupovine.">
        <script>
            window.location.replace('/');
        </script>
        <style>
            body {
                margin: 0;
                min-height: 100vh;
                display: grid;
                place-items: center;
                background: radial-gradient(circle at top, #1e293b 0%, #020617 55%, #020617 100%);
                color: #e2e8f0;
                font-family: Manrope, system-ui, sans-serif;
            }

            .card {
                width: min(92vw, 640px);
                padding: 32px;
                border: 1px solid rgba(255, 255, 255, 0.08);
                border-radius: 28px;
                background: rgba(15, 23, 42, 0.76);
                box-shadow: 0 24px 60px rgba(2, 6, 23, 0.45);
                backdrop-filter: blur(18px);
            }

            .brand {
                display: inline-flex;
                align-items: center;
                gap: 12px;
                font-weight: 800;
                letter-spacing: -0.03em;
                color: #fff;
            }

            .brand-badge {
                width: 48px;
                height: 48px;
                display: inline-grid;
                place-items: center;
                border-radius: 18px;
                background: linear-gradient(135deg, #fde68a, #f59e0b);
                color: #0f172a;
            }

            h1 {
                margin: 22px 0 0;
                font-size: clamp(2rem, 4vw, 3.4rem);
                line-height: 0.98;
                color: #fff;
            }

            p {
                margin: 18px 0 0;
                font-size: 1.05rem;
                line-height: 1.8;
                color: #cbd5e1;
            }

            a {
                display: inline-block;
                margin-top: 26px;
                padding: 14px 18px;
                border-radius: 16px;
                background: #f59e0b;
                color: #0f172a;
                text-decoration: none;
                font-weight: 700;
            }
        </style>
    </head>
    <body>
        <div class="card">
            <div class="brand">
                <span class="brand-badge">AIQ</span>
                <span>AutoIQ</span>
            </div>

            <h1>Pametnija kupovina automobila počinje jasnom slikom tržišta.</h1>
            <p>
                Ako niste automatski preusmereni, otvorite početnu stranicu i pregledajte oglase,
                analizu cena i vozila koja se trenutno najviše isplate.
            </p>

            <a href="/">Otvori AutoIQ</a>
        </div>
    </body>
</html>
