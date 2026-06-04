<x-layout>

<div class="card">

    <h2>Add Medical History</h2>

    <form action="{{ route('medical-history.store') }}" method="POST">
        @csrf

        <label>Patient</label>
        <select name="patient_id" required>
            @foreach($patients as $patient)
                <option value="{{ $patient->id }}">
                    {{ $patient->name }}
                </option>
            @endforeach
        </select>

        <label>Diagnosis</label>
        <textarea name="diagnosis" rows="3" required></textarea>

        <label>Treatment</label>
        <textarea name="treatment" rows="3" required></textarea>

        <label>Notes</label>
        <textarea name="notes" rows="3"></textarea>

        <button type="submit">
            Save Medical History
        </button>

    </form>

</div>

<style>
.card{
    background:white;
    padding:25px;
    border-radius:12px;
    box-shadow:0 4px 10px rgba(0,0,0,.08);
    max-width:700px;
    margin:auto;
    font-family: Arial, sans-serif;
}

/* labels aligned nicely */
label{
    display:block;
    margin:12px 0 6px;
    font-weight:600;
    color:#374151;
}

/* unified input + textarea style */
input,
select,
textarea{
    width:100%;
    padding:12px;
    border:1px solid #d1d5db;
    border-radius:8px;
    box-sizing:border-box;
    font-size:14px;
    font-family: Arial, sans-serif;
}

/* IMPORTANT: fixes textarea typing alignment */
textarea{
    resize:none;
    line-height:1.5;
}

/* focus effect */
input:focus,
select:focus,
textarea:focus{
    outline:none;
    border-color:#1f2937;
}

/* button */
button{
    margin-top:15px;
    width:100%;
    background:#1f2937;
    color:white;
    padding:12px;
    border:none;
    border-radius:8px;
    cursor:pointer;
    font-weight:bold;
}

button:hover{
    opacity:0.9;
}
</style>

</x-layout>