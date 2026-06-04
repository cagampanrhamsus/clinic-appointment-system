<x-layout>

<div class="page-header">
    <h2>Notifications</h2>
</div>

@forelse($notifications as $notification)

    <div class="card notification-card">

        <div class="notification-icon">
            🔔
        </div>

        <div>
            <h3>{{ $notification->data['message'] }}</h3>

            <p>
                {{ $notification->created_at->diffForHumans() }}
            </p>
        </div>

    </div>

@empty

    <div class="card">
        <h3>No Notifications Yet</h3>

        <p>
            Appointment updates and prescription notifications will appear here.
        </p>
    </div>

@endforelse

<style>
.page-header{
    margin-bottom:20px;
}

.page-header h2{
    color:#111827;
}

.card{
    background:white;
    padding:20px;
    border-radius:12px;
    box-shadow:0 4px 10px rgba(0,0,0,.08);
    margin-bottom:15px;
}

.notification-card{
    display:flex;
    align-items:center;
    gap:15px;
}

.notification-icon{
    font-size:26px;
}

.card h3{
    margin:0 0 5px;
    color:#111827;
    font-size:16px;
}

.card p{
    margin:0;
    color:#6b7280;
    font-size:13px;
}
</style>

</x-layout>