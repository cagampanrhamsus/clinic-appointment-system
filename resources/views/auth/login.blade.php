<x-guest-layout>

<div class="login-container">

    <div class="login-card">

        <h1>CLINIC</h1>
        <p class="subtitle">
            Sign in to manage appointments, records, and prescriptions.
        </p>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label>Email</label>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    class="form-input">

                <x-input-error :messages="$errors->get('email')" />
            </div>

            <div class="form-group">
                <label>Password</label>

                <input
                    type="password"
                    name="password"
                    required
                    class="form-input">

                <x-input-error :messages="$errors->get('password')" />
            </div>

            <div class="remember">
                <input type="checkbox" name="remember">
                <span>Remember me</span>
            </div>

            <button type="submit" class="login-btn">
                Log In
            </button>

            @if (Route::has('password.request'))
                <div class="link-area">
                    <a href="{{ route('password.request') }}">
                        Forgot your password?
                    </a>
                </div>
            @endif

            <div class="link-area">
                <a href="{{ route('register') }}">
                    Don't have an account? Register here
                </a>
            </div>

        </form>

    </div>

</div>

<style>
html, body{
    background:#f4f6f9;
    font-family:Arial, sans-serif;
    margin:0;
    padding:0;
    height:100%;
}

.login-container{
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    padding:20px;
}

.login-card{
    width:100%;
    max-width:500px;
    background:white;
    padding:35px;
    border-radius:12px;
    box-shadow:0 4px 12px rgba(0,0,0,0.08);
}

.login-card h1{
    text-align:center;
    color:#111827;
    font-size:48px;
    margin-bottom:10px;
}

.subtitle{
    text-align:center;
    color:#6b7280;
    margin-bottom:25px;
}

.form-group{
    margin-bottom:15px;
}

.form-group label{
    display:block;
    margin-bottom:5px;
    font-weight:bold;
    color:#374151;
}

.form-input{
    width:100%;
    padding:12px;
    border:1px solid #d1d5db;
    border-radius:8px;
    box-sizing:border-box;
}

.form-input:focus{
    outline:none;
    border-color:#111827;
}

.remember{
    display:flex;
    align-items:center;
    gap:8px;
    margin-bottom:20px;
}

.login-btn{
    width:100%;
    padding:12px;
    background:#111827;
    color:white;
    border:none;
    border-radius:8px;
    cursor:pointer;
    font-weight:bold;
}

.login-btn:hover{
    opacity:0.9;
}

.link-area{
    text-align:center;
    margin-top:15px;
}

.link-area a{
    color:#2563eb;
    text-decoration:none;
}

.link-area a:hover{
    text-decoration:underline;
}
</style>

</x-guest-layout>