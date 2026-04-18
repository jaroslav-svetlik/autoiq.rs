<x-emails.layout
    :title="$title"
    :preheader="$preheader"
    :kicker="$kicker ?? 'AutoIQ.rs'"
    :cta-url="$ctaUrl ?? null"
    :cta-label="$ctaLabel ?? null"
>
    @foreach($introLines as $line)
        <p style="color: #cbd5e1; font-size: 16px; line-height: 1.75; margin: 0 0 16px;">
            {{ $line }}
        </p>
    @endforeach

    @if(!empty($outroLines))
        <div style="border-top: 1px solid #1e293b; margin: 28px 0 0; padding: 22px 0 0;">
            @foreach($outroLines as $line)
                <p style="color: #94a3b8; font-size: 14px; line-height: 1.7; margin: 0 0 12px;">
                    {{ $line }}
                </p>
            @endforeach
        </div>
    @endif
</x-emails.layout>
