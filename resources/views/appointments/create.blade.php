<x-layout>

<div class="page-center">

    <div class="form-card">

        <h2>Book Appointment</h2>

        @if ($errors->any())
            <div class="error-box">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('appointments.store') }}" method="POST">
            @csrf

            <!-- Doctor -->
            <div class="form-group">
                <label>Doctor</label>

                <select name="doctor_id" required>
                    <option value="">Select Doctor</option>

                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}">
                            {{ $doctor->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Date -->
            <div class="form-group">
                <label>Appointment Date</label>

                <input
                    type="date"
                    name="appointment_date"
                    min="{{ date('Y-m-d') }}"
                    required
                >
            </div>

            <!-- Time -->
            <div class="form-group">
                <label>Appointment Time</label>

                <select name="appointment_time" required>
                    <option value="">Select Time Slot</option>

                    <option value="08:00">08:00 AM - 10:00 AM</option>
                    <option value="10:00">10:00 AM - 12:00 PM</option>
                    <option value="13:00">01:00 PM - 03:00 PM</option>
                    <option value="15:00">03:00 PM - 05:00 PM</option>
                </select>
            </div>

            <!-- Symptoms -->
            <div class="form-group">
                <label>Symptoms / Illness</label>

                <textarea
                    name="symptoms"
                    rows="4"
                    required
                    placeholder="Describe your symptoms..."
                ></textarea>
            </div>

            <!-- Submit -->
            <button type="submit" class="btn-book">
                Book Appointment
            </button>

        </form>

    </div>

</div>

<style>
.page-center{
    display:flex;
    justify-content:center;
    align-items:center;
    min-height:80vh;
}

.form-card{
    width:100%;
    max-width:650px;
    background:white;
    padding:30px;
    border-radius:15px;
    box-shadow:0 4px 12px rgba(0,0,0,.08);
}

.form-card h2{
    text-align:center;
    margin-bottom:25px;
    color:#111827;
}

.form-group{
    margin-bottom:18px;
}

.form-group label{
    display:block;
    margin-bottom:8px;
    font-weight:600;
    color:#374151;
}

.form-group select,
.form-group input,
.form-group textarea{
    width:100%;
    padding:12px;
    border:1px solid #d1d5db;
    border-radius:8px;
    font-size:14px;
    box-sizing:border-box;
}

.form-group select:focus,
.form-group input:focus,
.form-group textarea:focus{
    outline:none;
    border-color:#2563eb;
}

.btn-book{
    width:100%;
    background:#111827;
    color:white;
    padding:14px;
    border:none;
    border-radius:8px;
    font-size:15px;
    font-weight:bold;
    cursor:pointer;
    transition:.3s;
}

.btn-book:hover{
    background:#2563eb;
}

.error-box{
    background:#fee2e2;
    color:#991b1b;
    padding:12px;
    border-radius:8px;
    margin-bottom:15px;
}

.error-box ul{
    margin:0;
    padding-left:20px;
}
</style>

</x-layout>