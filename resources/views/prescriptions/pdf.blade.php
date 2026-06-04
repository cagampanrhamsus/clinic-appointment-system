<!DOCTYPE html>

<html>
<head>
    <meta charset="utf-8">
    <title>Prescription</title>


<style>
    body {
        font-family: Arial, sans-serif;
        padding: 30px;
    }

    h1 {
        text-align: center;
    }

    .section {
        margin-bottom: 15px;
    }

    .label {
        font-weight: bold;
    }

    .box {
        border: 1px solid #ccc;
        padding: 10px;
        margin-top: 5px;
    }
</style>


</head>
<body>


<h1>Medical Prescription</h1>

<div class="section">
    <span class="label">Doctor:</span>
    {{ $prescription->appointment->doctor->name }}
</div>

<div class="section">
    <span class="label">Patient:</span>
    {{ $prescription->appointment->patient->name }}
</div>

<div class="section">
    <span class="label">Appointment ID:</span>
    #{{ $prescription->appointment->id }}
</div>

<div class="section">
    <span class="label">Illness:</span>
    <div class="box">
        {{ $prescription->illness }}
    </div>
</div>

<div class="section">
    <span class="label">Medicine:</span>
    <div class="box">
        {{ $prescription->medicine }}
    </div>
</div>

<div class="section">
    <span class="label">Instructions:</span>
    <div class="box">
        {{ $prescription->instructions }}
    </div>
</div>


</body>
</html>
