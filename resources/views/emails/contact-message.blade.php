<x-emails.layout
    title="Nova kontakt poruka"
    preheader="Stigla je nova poruka preko AutoIQ kontakt forme."
    :cta-url="'mailto:'.$messageData['email']"
    cta-label="Odgovori pošiljaocu"
>
    <p style="color: #cbd5e1; font-size: 16px; line-height: 1.75; margin: 0 0 20px;">
        Korisnik je poslao novu poruku preko kontakt stranice. Podaci su ispod radi brze provere i odgovora.
    </p>

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border: 1px solid #1e293b; border-radius: 14px; margin: 0 0 24px; overflow: hidden;">
        <tr>
            <td style="background: #111827; color: #94a3b8; font-size: 12px; font-weight: 800; letter-spacing: 2px; padding: 14px 16px; text-transform: uppercase;">
                Kontakt podaci
            </td>
        </tr>
        <tr>
            <td style="padding: 0;">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                    <tr>
                        <td style="border-bottom: 1px solid #1e293b; color: #94a3b8; font-size: 13px; padding: 13px 16px; width: 34%;">Ime</td>
                        <td style="border-bottom: 1px solid #1e293b; color: #f8fafc; font-size: 15px; font-weight: 700; padding: 13px 16px;">{{ $messageData['name'] }}</td>
                    </tr>
                    <tr>
                        <td style="border-bottom: 1px solid #1e293b; color: #94a3b8; font-size: 13px; padding: 13px 16px;">Email</td>
                        <td style="border-bottom: 1px solid #1e293b; color: #f8fafc; font-size: 15px; font-weight: 700; padding: 13px 16px;">
                            <a href="mailto:{{ $messageData['email'] }}" style="color: #67e8f9;">{{ $messageData['email'] }}</a>
                        </td>
                    </tr>
                    @if(!empty($messageData['phone']))
                        <tr>
                            <td style="border-bottom: 1px solid #1e293b; color: #94a3b8; font-size: 13px; padding: 13px 16px;">Telefon</td>
                            <td style="border-bottom: 1px solid #1e293b; color: #f8fafc; font-size: 15px; font-weight: 700; padding: 13px 16px;">{{ $messageData['phone'] }}</td>
                        </tr>
                    @endif
                    <tr>
                        <td style="color: #94a3b8; font-size: 13px; padding: 13px 16px;">Tema</td>
                        <td style="color: #f8fafc; font-size: 15px; font-weight: 700; padding: 13px 16px;">{{ $messageData['topic'] }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div style="background: #111827; border: 1px solid #1e293b; border-radius: 14px; margin: 0 0 24px; padding: 18px;">
        <div style="color: #67e8f9; font-size: 12px; font-weight: 800; letter-spacing: 2px; margin: 0 0 12px; text-transform: uppercase;">
            Poruka
        </div>
        <p style="color: #e2e8f0; font-size: 16px; line-height: 1.75; margin: 0; white-space: pre-line;">{{ $messageData['message'] }}</p>
    </div>

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-top: 1px solid #1e293b; margin-top: 24px; padding-top: 18px;">
        <tr>
            <td style="color: #94a3b8; font-size: 13px; line-height: 1.7; padding: 4px 0;"><strong style="color: #cbd5e1;">Vreme slanja:</strong> {{ $messageData['submitted_at'] }}</td>
        </tr>
        <tr>
            <td style="color: #94a3b8; font-size: 13px; line-height: 1.7; padding: 4px 0;"><strong style="color: #cbd5e1;">IP adresa:</strong> {{ $messageData['ip'] }}</td>
        </tr>
        <tr>
            <td style="color: #94a3b8; font-size: 13px; line-height: 1.7; padding: 4px 0;"><strong style="color: #cbd5e1;">Browser:</strong> {{ $messageData['user_agent'] }}</td>
        </tr>
    </table>
</x-emails.layout>
