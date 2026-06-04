<x-layout>

<div style="max-width:1000px; margin:auto; padding:20px;">

<h2 style="margin-bottom:20px;">Appointments Dashboard</h2>

<br><br>

<table style="width:100%; border-collapse:collapse;">

    <thead>
        <tr style="background:#f4f4f4;">
            <th style="padding:10px; border:1px solid #ddd;">Doctor</th>
            <th style="padding:10px; border:1px solid #ddd;">Patient</th>
            <th style="padding:10px; border:1px solid #ddd;">Date</th>
            <th style="padding:10px; border:1px solid #ddd;">Time</th>
            <th style="padding:10px; border:1px solid #ddd;">Status</th>

            @if(auth()->user()->role === 'doctor')
                <th style="padding:10px; border:1px solid #ddd;">Actions</th>
            @endif
        </tr>
    </thead>

    <tbody>

    @foreach($appointments as $appointment)
        <tr>

            <td style="padding:10px; border:1px solid #ddd;">
                {{ $appointment->doctor->name }}
            </td>

            <td style="padding:10px; border:1px solid #ddd;">
                {{ $appointment->patient->name }}
            </td>

            <td style="padding:10px; border:1px solid #ddd;">
                {{ $appointment->appointment_date }}
            </td>

            <td style="padding:10px; border:1px solid #ddd;">
                {{ $appointment->appointment_time }}
            </td>

            <td style="padding:10px; border:1px solid #ddd;">
                <span style="
                    padding:5px 10px;
                    border-radius:5px;
                    color:white;
                    background:
                        {{ $appointment->status == 'pending' ? 'orange' : '' }}
                        {{ $appointment->status == 'approved' ? 'green' : '' }}
                        {{ $appointment->status == 'rejected' ? 'red' : '' }}
                        {{ $appointment->status == 'completed' ? 'blue' : '' }}
                ">
                    {{ ucfirst($appointment->status) }}
                </span>
            </td>

            @if(auth()->user()->role === 'doctor')
            <td style="padding:10px; border:1px solid #ddd;">

                <form method="POST" action="/appointments/{{ $appointment->id }}/approve" style="display:inline;">
                    @csrf
                    <button style="background:green; color:white; padding:5px;">Approve</button>
                </form>

                <form method="POST" action="/appointments/{{ $appointment->id }}/reject" style="display:inline;">
                    @csrf
                    <button style="background:red; color:white; padding:5px;">Reject</button>
                </form>

                <form method="POST" action="/appointments/{{ $appointment->id }}/complete" style="display:inline;">
                    @csrf
                    <button style="background:blue; color:white; padding:5px;">Done</button>
                </form>

            </td>
            @endif

        </tr>
    @endforeach

    </tbody>

</table>

</div>

</x-layout>
