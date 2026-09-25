{{--
    Email layout. Mail clients ignore <style>/<script> and external CSS, so
    everything is inline and table-based, using the site's palette
    (olive #554F13, beige #FBF7F1, charcoal #2F2A09).
--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light">
    <title>{{ $title ?? 'Nails by Delphina' }}</title>
</head>
<body style="margin:0; padding:0; background-color:#FBF7F1; font-family:'Poppins', Helvetica, Arial, sans-serif; color:#2F2A09;">
    @isset($preheader)
        <div style="display:none; max-height:0; overflow:hidden; opacity:0;">{{ $preheader }}</div>
    @endisset
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#FBF7F1;">
        <tr>
            <td align="center" style="padding:32px 16px;">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:520px;">
                    <tr>
                        <td align="center" style="padding-bottom:24px;">
                            <img src="{{ asset('img/logo_green.png') }}" alt="Nails by Delphina" height="56" style="display:block; height:56px; width:auto; border:0;">
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color:#ffffff; border-radius:24px; padding:32px 28px; box-shadow:0 10px 30px rgba(65,59,14,0.08);">
                            @yield('body')
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding:24px 12px 0; font-size:12px; line-height:18px; color:#8D8540;">
                            Nails by Delphina · Tallaght, Dublin 24<br>
                            <a href="{{ route('home') }}" style="color:#554F13; text-decoration:underline;">{{ parse_url(url('/'), PHP_URL_HOST) }}</a>
                            · <a href="https://wa.me/353899409670" style="color:#554F13; text-decoration:underline;">WhatsApp</a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
