<x-guest-layout>

<div class="register-container">

    <div class="register-card">

        <h1>CLINIC</h1>
        <p class="subtitle">
            Create an account to access the clinic system.
        </p>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="form-input">
                <x-input-error :messages="$errors->get('name')" />
            </div>

            <!-- Email -->
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="form-input">
                <x-input-error :messages="$errors->get('email')" />
            </div>

            <!-- Role -->
            <div class="form-group">
                <label>Role</label>
                <select name="role" class="form-input">
                    <option value="patient">Patient</option>
                    <option value="doctor">Doctor</option>
                </select>
                <x-input-error :messages="$errors->get('role')" />
            </div>

            <!-- Password -->
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required class="form-input">
                <x-input-error :messages="$errors->get('password')" />
            </div>

            <!-- Confirm Password -->
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="password_confirmation" required class="form-input">
                <x-input-error :messages="$errors->get('password_confirmation')" />
            </div>

            <button type="submit" class="register-btn">
                Register
            </button>

            <div class="link-area">
                <a href="{{ route('login') }}">
                    Already registered? Login here
                </a>
            </div>

        </form>

    </div>

</div>

<style>
body{
    background:#f4f6f9;
    font-family:Arial, sans-serif;
    margin:0;
}

.register-container{
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    padding:20px;
}

.register-card{
    width:100%;
    max-width:500px;
    background:white;
    padding:35px;
    border-radius:12px;
    box-shadow:0 4px 12px rgba(0,0,0,0.08);
}

.register-card h1{
    text-align:center;
    font-size:48px;
    color:#111827;
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

.register-btn{
    width:100%;
    padding:12px;
    background:#111827;
    color:white;
    border:none;
    border-radius:8px;
    cursor:pointer;
    font-weight:bold;
    margin-top:10px;
}

.register-btn:hover{
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