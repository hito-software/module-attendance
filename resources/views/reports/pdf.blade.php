<div style="margin-bottom: 5mm">
    <h1 style="text-align: center; margin: 0">Attendance report</h1>
    <p style="text-align: center; margin-top: 1mm">
        Generated for the following period:
        <b>
            {{ $report->start_at->format('Y-m-d') }} - {{ $report->end_at?->format('Y-m-d') }}
        </b>
    </p>
</div>

<table class="table-report">
    <thead>
    <tr>
        <th>Name</th>
        <th>Request Date</th>
        <th>Period</th>
        <th>Type</th>
    </tr>
    </thead>
    @foreach($requests as $request)
        <tr>
            <td style="text-align: left;">{{ $request->user->full_name }}</td>
            <td>{{ $request->created_at->format('Y-m-d') }}</td>
            <td>{{ $request->start_at->format('Y-m-d') }}
                - {{ $request->end_at?->format('Y-m-d') }}</td>
            <td>{{ $request->type->name }}</td>
        </tr>
    @endforeach
</table>

<style>
    .table-report {
        width: 100%;
        border-spacing: 0;
        border-collapse: collapse;
        border: 1px solid #ccc;
    }

    .table-report th {
        padding: 4mm 4mm;
        border-bottom: 1px solid #ccc;
    }

    .table-report th:nth-child(1n) {
        border-left: 1px solid #ccc;
        background-color: rgba(0, 0, 0, .14);
    }

    .table-report td {
        padding: 2mm 4mm;
        text-align: center;
    }

    .table-report td:nth-child(1n) {
        border-left: 1px solid #ccc;
    }

    .table-report tr:nth-child(1n) {
        border: 1px solid #ccc;
    }

    .table-report tr:nth-child(2n + 2) {
        background-color: rgba(0, 0, 0, .07);
    }
</style>
