<x-layout>

<div class="card">

    <h2>Medical History</h2>

    <table class="history-table">

        <thead>
            <tr>
                <th>Diagnosis</th>
                <th>Treatment</th>
                <th>Notes</th>
                <th>Date</th>
            </tr>
        </thead>

        <tbody>

        @forelse($histories as $history)

            <tr>
                <td>{{ $history->diagnosis }}</td>
                <td>{{ $history->treatment }}</td>
                <td>{{ $history->notes }}</td>
                <td>{{ $history->created_at->format('M d, Y') }}</td>
            </tr>

        @empty

            <tr>
                <td colspan="4" style="text-align:center;">
                    No medical history found.
                </td>
            </tr>

        @endforelse

        </tbody>

    </table>

</div>

<style>
.card{
    background:white;
    padding:25px;
    border-radius:12px;
    box-shadow:0 4px 10px rgba(0,0,0,.08);
}

.history-table{
    width:100%;
    border-collapse:collapse;
    table-layout:fixed;
}

.history-table th,
.history-table td{
    border:1px solid #e5e7eb;
    padding:12px;
    text-align:left;
    vertical-align:middle;
    word-wrap:break-word;
}

.history-table th{
    background:#1f2937;
    color:white;
}

.history-table tr:nth-child(even){
    background:#f9fafb;
}

.history-table tr:hover{
    background:#eef2ff;
}
</style>

</x-layout>