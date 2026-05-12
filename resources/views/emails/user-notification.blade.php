<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subjectText ?? 'Nurtura' }}</title>
</head>
<body style="font-family: Helvetica, Arial, sans-serif; background-color: #F8F5F2; margin: 0; padding: 40px 20px;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width: 500px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
        <tr>
            <td style="text-align: center; padding: 30px 20px 20px 20px;">
                <img src="{{ url('images/logo_nurtura.png') }}" alt="Nurtura" style="max-width: 150px; height: auto;">
            </td>
        </tr>
        <tr>
            <td style="padding: 0 30px 30px 30px;">
                <h2 style="color: #3A3A3A; font-size: 22px; text-align: center; margin-bottom: 14px; font-weight: 700;">{{ $headline }}</h2>
                <p style="color: #3A3A3A; font-size: 15px; line-height: 1.7; margin-bottom: 24px; text-align: center;">{{ $bodyMessage }}</p>
                <div style="text-align: center; margin-bottom: 30px;">
                    <a href="{{ url('/') }}" style="background-color: #A3B18A; color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 15px; display: inline-block;">Kunjungi Nurtura</a>
                </div>
                <hr style="border: none; border-top: 1px solid #DDBEA9; margin-bottom: 20px;">
                <p style="color: #3A3A3A; font-size: 12px; line-height: 1.5; text-align: center; margin: 0; opacity: 0.75;">Jika Anda tidak melakukan perubahan ini, segera hubungi tim support Nurtura.</p>
            </td>
        </tr>
    </table>
</body>
</html>
