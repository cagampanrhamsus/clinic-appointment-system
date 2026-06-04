<x-layout>

<div class="page-center">

    <div class="form-card">

        <h2>Create Prescription</h2>

        <form action="{{ route('prescriptions.store') }}" method="POST">
            @csrf

            <!-- Appointment Selection -->
            <div class="form-group">
                <label>Patient</label>

                <select name="appointment_id" id="appointmentSelect" required>
                    @foreach($appointments as $appointment)
                        <option value="{{ $appointment->id }}">
                            {{ $appointment->patient->name }} (ID: {{ $appointment->id }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Symptoms Display -->
            <div class="form-group">
                <label>Patient Symptoms</label>
                <textarea id="symptomsBox" readonly></textarea>
            </div>

            <!-- Medicine -->
            <div class="form-group">
                <label>Medicine</label>

                <textarea
                    name="medicine"
                    rows="4"
                    placeholder="Enter prescribed medicine..."
                    required></textarea>
            </div>

            <!-- Instructions -->
            <div class="form-group">
                <label>Instructions</label>

                <textarea
                    name="instructions"
                    rows="4"
                    placeholder="Enter instructions for the patient..."></textarea>
            </div>

            <button type="submit" class="btn-save">
                Save Prescription
            </button>

        </form>

    </div>

</div>

<style>
.page-center {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 80vh;
}

.form-card {
    background: white;
    width: 100%;
    max-width: 700px;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,.08);
}

.form-card h2 {
    text-align: center;
    margin-bottom: 25px;
    color: #1f2937;
}

.form-group {
    margin-bottom: 18px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
    color: #374151;
}

.form-group select,
.form-group textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    box-sizing: border-box;
    font-size: 14px;
}

.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #2563eb;
}

.btn-save {
    width: 100%;
    background: #2563eb;
    color: white;
    padding: 12px;
    border: none;
    border-radius: 8px;
    font-weight: bold;
    cursor: pointer;
}

.btn-save:hover {
    background: #1d4ed8;
}
</style>

<script>
const appointments = @json($appointments);

function updateSymptoms() {
    const select = document.getElementById('appointmentSelect');
    const selected = appointments.find(a => a.id == select.value);

    document.getElementById('symptomsBox').value =
        selected?.symptoms ?? 'No symptoms recorded';
}

// run on change
document.getElementById('appointmentSelect')
    .addEventListener('change', updateSymptoms);

// run on page load
window.onload = updateSymptoms;
</script>

</x-layout>