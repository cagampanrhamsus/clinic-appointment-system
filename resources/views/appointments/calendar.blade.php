<x-layout>

<div style="padding:20px;">
    <h2 style="margin-bottom:15px;">Approved Appointments Calendar</h2>

    <div id="calendar"></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const calendarEl = document.getElementById('calendar');

    const calendar = new FullCalendar.Calendar(calendarEl, {

        initialView: 'timeGridWeek',

        // 🚫 Disable Saturday & Sunday completely
        weekends: false,

        height: "auto",
        editable: false,
        selectable: true,

        // ⏰ Working hours (FIXED 8AM - 6PM)
        slotMinTime: "08:00:00",
        slotMaxTime: "18:00:00",

        // 📅 Appointments + Lunch Break Overlay
        events: [
            ...@json($events),

            {
                title: "Lunch Break",
                daysOfWeek: [1, 2, 3, 4, 5],
                startTime: "12:00",
                endTime: "13:00",
                display: "background",
                overlap: false,
                color: "#ffcccc"
            }
        ],

        // 🚫 Prevent selecting invalid lunch hours
        selectAllow: function(selectInfo) {

            const start = selectInfo.start;
            const end = selectInfo.end;

            const startHour = start.getHours();
            const endHour = end.getHours();

            const isLunchOverlap = (startHour < 13 && endHour > 12);

            return !isLunchOverlap;
        },

        // 👀 Click event
        eventClick: function(info) {

            const start = new Date(info.event.start);

            alert(
                "Patient: " + info.event.title +
                "\nTime: " + start.toLocaleString()
            );
        }

    });

    calendar.render();
});
</script>

<style>
#calendar {
    background: white;
    padding: 15px;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
</style>

</x-layout>