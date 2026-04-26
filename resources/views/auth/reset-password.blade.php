<h2>Reset Password</h2>

<form method="POST" action="/api/password/reset">
    @csrf

    <input type="hidden" name="email" value="{{ $email }}">
    <input type="hidden" name="token" value="{{ $token }}">

    <div>
        <label>Password Baru</label>
        <input type="password" name="password" required>
    </div>

    <div>
        <label>Konfirmasi Password</label>
        <input type="password" name="password_confirmation" required>
    </div>

    <button type="submit">Reset Password</button>
</form>