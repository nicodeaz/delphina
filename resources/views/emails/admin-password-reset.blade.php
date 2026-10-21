@extends('emails.layout', [
    'title' => 'Choose a new password',
    'preheader' => 'Your one-time link to get back into Delphina Studio.',
])

@section('body')
    <p style="margin:0 0 6px; font-size:12px; font-weight:600; letter-spacing:3px; text-transform:uppercase; color:#554F13;">Delphina Studio</p>
    <h1 style="margin:0 0 12px; font-size:24px; line-height:32px; color:#2F2A09;">Choose a new password</h1>
    <p style="margin:0 0 24px; font-size:15px; line-height:24px; color:#5E5720;">
        Tap the button to choose a new password. You'll go straight to your studio agenda afterwards.
    </p>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">
                <a href="{{ $url }}" style="display:inline-block; background-color:#554F13; border-radius:16px; padding:14px 28px; font-size:15px; font-weight:600; color:#ffffff; text-decoration:none;">Choose a new password</a>
            </td>
        </tr>
    </table>

    <p style="margin:24px 0 0; font-size:13px; line-height:20px; color:#8D8540;">
        The link works once and expires in {{ $minutes }} minutes. If you didn't ask for it, you can ignore this email — your password stays the same.
    </p>
@endsection
