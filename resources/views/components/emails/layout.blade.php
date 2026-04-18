@props([
    'title',
    'preheader' => '',
    'kicker' => 'AutoIQ.rs',
    'ctaUrl' => null,
    'ctaLabel' => null,
])

<!doctype html>
<html lang="sr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ $title }}</title>
</head>
<body style="margin: 0; padding: 0; background: #020617; color: #e2e8f0; font-family: Arial, Helvetica, sans-serif;">
@if($preheader)
    <div style="display: none; max-height: 0; overflow: hidden; opacity: 0; color: transparent;">
        {{ $preheader }}
    </div>
@endif

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background: #020617; margin: 0; padding: 0;">
    <tr>
        <td align="center" style="padding: 32px 16px;">
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width: 640px; width: 100%; margin: 0 auto;">
                <tr>
                    <td style="padding: 0 0 18px;">
                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                            <tr>
                                <td style="vertical-align: middle;">
                                    <a href="{{ route('home') }}" style="display: inline-block; color: #ffffff; font-size: 24px; font-weight: 800; letter-spacing: 0; text-decoration: none;">
                                        Auto<span style="color: #f59e0b;">IQ</span>
                                    </a>
                                </td>
                                <td align="right" style="vertical-align: middle;">
                                    <span style="display: inline-block; border: 1px solid rgba(34, 211, 238, 0.32); border-radius: 999px; color: #67e8f9; font-size: 12px; font-weight: 700; padding: 7px 12px; text-transform: uppercase;">
                                        {{ $kicker }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td style="background: #0f172a; border: 1px solid #1e293b; border-radius: 18px; overflow: hidden;">
                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                            <tr>
                                <td style="background: linear-gradient(135deg, #102033 0%, #0f172a 58%, #1f2937 100%); border-bottom: 1px solid #1e293b; padding: 30px 30px 26px;">
                                    <div style="color: #67e8f9; font-size: 12px; font-weight: 800; letter-spacing: 2.8px; line-height: 1.4; text-transform: uppercase;">
                                        AutoIQ.rs
                                    </div>
                                    <h1 style="color: #ffffff; font-size: 30px; font-weight: 800; line-height: 1.18; margin: 12px 0 0;">
                                        {{ $title }}
                                    </h1>
                                    @if($preheader)
                                        <p style="color: #cbd5e1; font-size: 16px; line-height: 1.7; margin: 14px 0 0;">
                                            {{ $preheader }}
                                        </p>
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <td style="padding: 30px;">
                                    {{ $slot }}

                                    @if($ctaUrl && $ctaLabel)
                                        <table role="presentation" cellspacing="0" cellpadding="0" style="margin: 28px 0 0;">
                                            <tr>
                                                <td style="background: #f59e0b; border-radius: 14px;">
                                                    <a href="{{ $ctaUrl }}" style="display: inline-block; color: #0f172a; font-size: 15px; font-weight: 800; line-height: 1; padding: 16px 22px; text-decoration: none;">
                                                        {{ $ctaLabel }}
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>

                                        <p style="color: #94a3b8; font-size: 12px; line-height: 1.6; margin: 18px 0 0;">
                                            Ako dugme ne radi, otvorite ovaj link:<br>
                                            <a href="{{ $ctaUrl }}" style="color: #67e8f9; word-break: break-all;">{{ $ctaUrl }}</a>
                                        </p>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td style="padding: 22px 6px 0;">
                        <p style="color: #94a3b8; font-size: 13px; line-height: 1.7; margin: 0;">
                            AutoIQ.rs prati oglase, cenu i tržišni kontekst da lakše procenite realnu vrednost automobila.
                        </p>
                        <p style="color: #64748b; font-size: 12px; line-height: 1.7; margin: 10px 0 0;">
                            Poslato sa platforme <a href="{{ route('home') }}" style="color: #67e8f9; text-decoration: none;">AutoIQ.rs</a>.
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
