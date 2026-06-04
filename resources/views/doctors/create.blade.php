<x-layout>

<div class="form-card">

    <h2>Add Doctor</h2>

    <form action="{{ route('doctors.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label>Doctor Name</label>
            <input type="text" name="name" placeholder="Enter doctor name" required>
        </div>

        <div class="form-group">
            <label>Specialization</label>
            <input type="text" name="specialization" placeholder="Enter specialization" required>
        </div>

        <div class="form-group">
            <label>Contact</label>
            <input type="text" name="contact" placeholder="Enter contact number" required>
        </div>

        <button type="submit" class="btn-save">
            Save Doctor
        </button>
    </form>

</div>

<style>
.form-card {
    background: white;
    max-width: 600px;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0,0,0,.08);
}

.form-card h2 {
    margin-bottom: 20px;
    color: #1f2937;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 6px;
    font-weight: bold;
    color: #374151;
}

.form-group input {
    width: 100%;
    padding: 12px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    box-sizing: border-box;
    font-size: 14px;
}

.form-group input:focus {
    outline: none;
    border-color: #2563eb;
}

.btn-save {
    background: #2563eb;
    color: white;
    padding: 12px 18px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 14px;
    font-weight: bold;
}

.btn-save:hover {
    background: #1d4ed8;
}
</style>

</x-layout>