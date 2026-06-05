<!DOCTYPE html>
<html>
<head>
    <title>Clinic System</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f9;
        }

        .sidebar {
            width: 220px;
            height: 100vh;
            background: #1f2937;
            color: white;
            position: fixed;
            padding: 20px;
            box-shadow: 2px 0 10px rgba(0,0,0,0.2);
            overflow-y: auto;
        }

        .sidebar h3 {
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid rgba(255,255,255,0.2);
        }

        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 12px;
            margin-bottom: 8px;
            border-radius: 6px;
            transition: 0.2s;
        }

        .sidebar a:hover {
            background: #374151;
            padding-left: 18px;
        }

        .content {
            margin-left: 260px;
            padding: 20px;
        }

        .logout-btn {
            background: #dc2626;
            color: white;
            width: 100%;
            border-radius: 6px;
            font-weight: bold;
            padding: 10px;
            border: none;
            cursor: pointer;
        }

        .logout-btn:hover {
            background: #b91c1c;
        }
    </style>
</head>

<body>

<div class="sidebar">
    <h3>Clinic</h3>

    @if(auth()->user()->role === 'doctor')

        <a href="/dashboard">Dashboard</a>
        <a href="/appointments">Manage Appointments</a>
        <a href="/patients">Patient Records</a>
        <a href="/prescriptions">Issue Prescriptions</a>

        {{-- 📅 ONLY CALENDAR LINK --}}
        <a href="{{ route('appointments.calendar') }}">Appointments Calendar</a>

    @elseif(auth()->user()->role === 'patient')

        <a href="/dashboard">Dashboard</a>
        <a href="/appointments/create">Book Appointment</a>
        <a href="/appointments">My Appointments</a>
        <a href="/prescriptions">My Prescriptions</a>
        <a href="{{ route('medical-history.index') }}">Medical History</a>
        <a href="{{ route('notifications') }}">Notifications</a>

    @endif

    <form method="POST" action="/logout" style="margin-top:20px;">
        @csrf
        <button type="submit" class="logout-btn">
            Logout
        </button>
    </form>
</div>

<div class="content">
    {{ $slot }}
</div>

</body>
</html>