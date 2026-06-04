<x-layout>

<div class="card">

    <h2 style="margin-bottom:20px; color:#1f2937;">
        Patient Records
    </h2>

    <table class="patient-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
            </tr>
        </thead>

        <tbody>
            @forelse($patients as $patient)
                <tr>
                    <td>{{ $patient->name }}</td>
                    <td>{{ $patient->email }}</td>
                    <td>
                        <span class="badge">
                            {{ ucfirst($patient->role) }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" style="text-align:center;">
                        No patients found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>

<style>
.patient-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 10px;
    overflow: hidden;
}

.patient-table th {
    background: #1f2937;
    color: white;
    padding: 14px;
    text-align: left;
}

.patient-table td {
    padding: 14px;
    border-bottom: 1px solid #e5e7eb;
}

.patient-table tr:hover {
    background: #f9fafb;
}

.badge {
    background: #dbeafe;
    color: #1e40af;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: bold;
}

.card {
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0,0,0,.08);
}
</style>

</x-layout>