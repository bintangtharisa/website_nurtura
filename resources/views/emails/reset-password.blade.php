<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Nurtura</title>
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
                <h2 style="color: #3A3A3A; font-size: 20px; text-align: center; margin-bottom: 20px; font-weight: 600;">Permintaan Reset Password</h2>
                
                <p style="color: #3A3A3A; font-size: 15px; line-height: 1.6; margin-bottom: 20px;">Halo,</p>
                
                <p style="color: #3A3A3A; font-size: 15px; line-height: 1.6; margin-bottom: 30px;">Kami menerima permintaan untuk mereset password pada akun Nurtura kamu. Untuk melanjutkan, silakan klik tombol di bawah ini dan buat password baru kamu.</p>
                
                <div style="text-align: center; margin-bottom: 30px;">
                    <a href="{{ $link }}" style="background-color: #A3B18A; color: #ffffff; padding: 14px 28px; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: 16px; display: inline-block;">Atur Ulang Password</a>
                </div>
                
                <hr style="border: none; border-top: 1px solid #DDBEA9; margin-bottom: 20px;">
                
                <div style="background-color: #EDE0D4; border-radius: 6px; padding: 15px; margin-bottom: 20px;">
                    <p style="color: #3A3A3A; font-size: 12px; line-height: 1.5; text-align: center; margin: 0; font-weight: bold;">
                        Link hanya berlaku selama 1 jam.
                    </p>
                </div>
                
                <p style="color: #3A3A3A; font-size: 12px; line-height: 1.5; text-align: center; margin: 0; opacity: 0.8;">
                    Jika kamu tidak pernah meminta reset password, kamu bisa mengabaikan email ini. Akun kamu akan tetap aman.
                </p>
            </td>
        </tr>
    </table>
</body>
</html>