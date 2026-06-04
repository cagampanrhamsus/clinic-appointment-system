<x-layout>

<div class="hero">

    <h1>CLINIC</h1>

    @if(auth()->user()->role === 'doctor')

        <p>
            Manage appointments, patient records, and prescriptions
            in one place.
        </p>

        <div class="hero-buttons">
            <a href="/appointments" class="btn-primary">
                Manage Appointments
            </a>

            <a href="/patients" class="btn-secondary">
                Patient Records
            </a>
        </div>

    @else

        <p>
            Welcome to your healthcare portal.
            Book appointments, view schedules,
            and access your prescriptions anytime.
        </p>

    @endif

</div>

@if(auth()->user()->role === 'doctor')

<div class="stats">

    <div class="stat-card">
        <h3>Doctors</h3>
        <h1>{{ $doctors ?? 0 }}</h1>
    </div>

    <div class="stat-card">
        <h3>Patients</h3>
        <h1>{{ $patients ?? 0 }}</h1>
    </div>

    <div class="stat-card">
        <h3>Appointments</h3>
        <h1>{{ $appointments ?? 0 }}</h1>
    </div>

</div>

@else

<div class="patient-info">

    <div class="stat-card">
        <h3>Your Health</h3>
        <p>Keep track of your appointments and prescriptions.</p>
    </div>

    <div class="stat-card">
        <h3>Clinic Services</h3>
        <p>Schedule consultations and receive prescriptions from your doctor.</p>
    </div>

</div>

@endif

<style>
.hero{
    min-height:60vh;
    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;
    text-align:center;
}

.hero h1{
    font-size:90px;
    font-weight:900;
    letter-spacing:8px;
    margin:0;
    color:#111827;
}

.hero p{
    font-size:20px;
    color:#6b7280;
    max-width:600px;
    margin:20px 0 40px;
}

.hero-buttons{
    display:flex;
    gap:15px;
}

.btn-primary{
    background:#111827;
    color:white;
    padding:14px 28px;
    text-decoration:none;
    border-radius:30px;
    font-weight:bold;
}

.btn-secondary{
    background:white;
    color:#111827;
    border:2px solid #111827;
    padding:14px 28px;
    text-decoration:none;
    border-radius:30px;
    font-weight:bold;
}

.stats,
.patient-info{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
    gap:20px;
    margin-top:20px;
}

.stat-card{
    background:white;
    padding:25px;
    border-radius:15px;
    text-align:center;
    box-shadow:0 4px 10px rgba(0,0,0,.08);
}

.stat-card h3{
    color:#6b7280;
}

.stat-card h1{
    font-size:40px;
    color:#111827;
}
</style>

</x-layout>