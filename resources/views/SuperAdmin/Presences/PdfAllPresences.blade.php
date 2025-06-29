<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Tableau de présence</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .date {
            color: #666;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .total {
            font-weight: bold;
            margin-top: 20px;
        }
        .status-present {
            color: green;
            font-weight: bold;
        }
        .status-late {
            color: orange;
            font-weight: bold;
        }
        .status-absent {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Tableau de présence</h1>
        <p class="date">Généré le : {{ $date }}</p>
        <div class="total">
            Total des présences : {{ $presences->count() }}<br>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Nom</th>
                <th>E-mail</th>
                <th>Arrivée</th>
                <th>Départ</th>
                <th>Retard</th>
                <th>Statut</th>
                <th>Motif</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($presences as $presence)
                <tr>
                    <td>{{ $presence->id }}</td>
                    <td>{{ $presence->date }}</td>
                    <td>{{ $presence->user ? $presence->user->name : '-' }}</td>
                    <td>{{ $presence->user ? $presence->user->email : '-' }}</td>
                    <td>{{ $presence->arrival_time ?? '-' }}</td>
                    <td>{{ $presence->departure_time ?? '-' }}</td>
                    <td>{{ $presence->late_minutes ? $presence->late_minutes.' min' : '-' }}</td>
                    <td class="@if(is_null($presence->arrival_time) && is_null($presence->departure_time)) status-absent 
                              @elseif($presence->late_minutes > 0) status-late 
                              @else status-present @endif">
                        @if(is_null($presence->arrival_time) && is_null($presence->departure_time))
                            Absent
                        @elseif($presence->late_minutes > 0)
                            Retard
                        @else
                            Présent
                        @endif
                    </td>
                    <td>{{ $presence->absenceReason ? $presence->absenceReason->name : '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
