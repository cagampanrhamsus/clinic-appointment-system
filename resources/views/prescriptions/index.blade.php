<x-layout>

<div class="card">

<div class="header">
    <h2>Prescriptions</h2>

    @if(auth()->user()->role === 'doctor')
        <a href="{{ route('prescriptions.create') }}" class="btn-create">
            + Create Prescription
        </a>
    @endif

</div>

<table class="prescription-table">
    <thead>
        <tr>

            <th>
                {{ auth()->user()->role === 'doctor' ? 'Patient' : 'Doctor' }}
            </th>

            <th>Appointment ID</th>
            <th>Illness</th>
            <th>Medicine</th>
            <th>Instructions</th>

            @if(auth()->user()->role === 'patient')
                <th>Download Prescription</th>
            @endif

        </tr>
    </thead>

    <tbody>

    @forelse($prescriptions as $prescription)

        <tr>

            <td>
                @if(auth()->user()->role === 'doctor')
                    {{ $prescription->appointment->patient->name ?? 'N/A' }}
                @else
                    {{ $prescription->appointment->doctor->name ?? 'N/A' }}
                @endif
            </td>

            <td>
                #{{ $prescription->appointment->id ?? 'N/A' }}
            </td>

            <td>
                {{ $prescription->illness ?? 'N/A' }}
            </td>

            <td>
                {{ $prescription->medicine ?? 'N/A' }}
            </td>

            <td>
                {{ $prescription->instructions ?? 'N/A' }}
            </td>

            @if(auth()->user()->role === 'patient')
                <td>

                    <a href="{{ route('prescriptions.pdf', $prescription->id) }}"
                       class="btn-download btn-pdf">
                        PDF
                    </a>

                    <a href="{{ route('prescriptions.json', $prescription->id) }}"
                       class="btn-download btn-json">
                        JSON
                    </a>

                    <a href="{{ route('prescriptions.xml', $prescription->id) }}"
                       class="btn-download btn-xml">
                        XML
                    </a>

                    <a href="{{ route('prescriptions.xsd', $prescription->id) }}"
                       class="btn-download btn-xsd">
                        XSD
                    </a>

                </td>
            @endif

        </tr>

    @empty

        <tr>
            <td colspan="{{ auth()->user()->role === 'patient' ? 6 : 5 }}"
                style="text-align:center;">
                No prescriptions found.
            </td>
        </tr>

    @endforelse

    </tbody>
</table>

</div>

<style>
.card {
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0,0,0,.08);
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.header h2 {
    margin: 0;
    color: #1f2937;
}

.btn-create {
    background: #2563eb;
    color: white;
    text-decoration: none;
    padding: 10px 16px;
    border-radius: 8px;
    font-weight: bold;
}

.btn-create:hover {
    background: #1d4ed8;
}

.btn-download {
    color: white;
    text-decoration: none;
    padding: 8px 12px;
    border-radius: 6px;
    font-weight: bold;
    margin-right: 5px;
    display: inline-block;
}

.btn-pdf {
    background: #16a34a;
}

.btn-json {
    background: #f59e0b;
}

.btn-xml {
    background: #7c3aed;
}

.btn-xsd {
    background: #dc2626;
}

.btn-pdf:hover {
    background: #15803d;
}

.btn-json:hover {
    background: #d97706;
}

.btn-xml:hover {
    background: #6d28d9;
}

.btn-xsd:hover {
    background: #b91c1c;
}

.prescription-table {
    width: 100%;
    border-collapse: collapse;
}

.prescription-table th {
    background: #1f2937;
    color: white;
    padding: 14px;
    text-align: left;
}

.prescription-table td {
    padding: 14px;
    border-bottom: 1px solid #e5e7eb;
}

.prescription-table tr:hover {
    background: #f9fafb;
}
</style>

</x-layout>