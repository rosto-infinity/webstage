<table>
  <thead>
  <tr>
    <th width='20' align='center'>#ID</th>
    <th width='20' align='center'>Date</th>
    <th width='20' align='center'>Name</th>
    <th width='20' align='center'>E‑mail</th>
    <th width='20' align='center'>Arrivée </th>
    <th width='20' align='center'>Depart</th>
    <th width='20' align='center'>Retard</th>
    <th width='20' align='center'>status</th>
    <th width='20' align='center'>Motif</th>
  </tr>
  </thead>
  <tbody>

       @foreach ($presences as $presence)
    <tr> 
        <td width='10'>{{ $presence->id}}</td>
        <td width='15' align='center'>{{ $presence->date}} </td>
        <td width='20'>{{ $presence->user ? $presence->user->name : '-'}}</td>
        <td width='30'>{{ $presence->user ? $presence->user->email : '-'}}</td>
        <td width='10' align='center'>{{ $presence->arrival_time}}</td>
        <td width='10' align='center'>{{ $presence->departure_time }}</td>
        <td width='7' align='center'>{{ $presence->late_minutes }}</td>
        {{-- <td width='10' align='center'>{{ $presence->late }}</td> --}}
        <td width='7' align='center'>{{ $presence->absent }}</td>
        <td width='5' align='center'>{{ $presence->absence_reason_id }}</td>
    </tr>
    @endforeach

  </tbody>
</table>