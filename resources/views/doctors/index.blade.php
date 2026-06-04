<x-layout>

<div style="max-width:1000px; margin:auto;">

    <h2 style="margin-bottom:20px;">Doctors List</h2>

    <a href="{{ route('doctors.create') }}"
       style="display:inline-block; padding:10px 15px; background:#3490ff; color:white; text-decoration:none; border-radius:5px; margin-bottom:15px;">
        + Add Doctor
    </a>

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(250px, 1fr)); gap:15px;">

        @foreach($doctors as $doctor)
            <div style="background:white; padding:15px; border-radius:10px; box-shadow:0 2px 5px rgba(0,0,0,0.1);">

                <h3 style="margin:0 0 10px 0;">
                    {{ $doctor->name }}
                </h3>

                <p><b>Specialization:</b> {{ $doctor->specialization }}</p>
                <p><b>Contact:</b> {{ $doctor->contact }}</p>

            </div>
        @endforeach

    </div>

</div>

</x-layout>