@extends('emails.layout', [
    'title' => 'Your login code',
    'preheader' => 'Your Delphina Studio login code is '.$code.'.',
])

@section('body')
    <p style="margin:0 0 6px; font-size:12px; font-weight:600; letter-spacing:3px; text-transform:uppercase; color:#554F13;">Delphina Studio</p>
    <h1 style="margin:0 0 12px; font-size:24px; line-height:32px; color:#2F2A09;">Your login code</h1>
    <p style="margin:0 0 24px; font-size:15px; line-height:24px; color:#5E5720;">
        Enter this code to finish logging in to your studio agenda.
    </p>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center" style="background-color:#FAF8EF; border-radius:16px; padding:22px 12px;">
                <span style="font-family:'Courier New', Courier, monospace; font-size:36px; font-weight:700; letter-spacing:10px; color:#554F13;">{{ $code }}</span>
            </td>
        </tr>
    </table>

    <p style="margin:24px 0 0; font-size:13px; line-height:20px; color:#8D8540;">
        The code expires in {{ $minutes }} minutes. If you didn't try to log in, you can ignore this email — nobody can get in without this code — but consider changing your password.
    </p>
@endsection
