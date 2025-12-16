<h2>ROOM 911 – Access Log</h2>

<p>
    Employee: {{ $employee->full_name }}<br>
    Internal ID: {{ $employee->internal_id }}
</p>

<table width="100%" border="1" cellspacing="0" cellpadding="6">
    <thead>
    <tr>
        <th class="text-left py-2">#</th>
        <th class="text-left py-2">Date</th>
        <th class="text-left py-2">Result</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($logs as $i => $log)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $log->attempted_at }}</td>
            <td>
                {{ $log->access_granted ? 'Granted' : 'Denied' }}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
