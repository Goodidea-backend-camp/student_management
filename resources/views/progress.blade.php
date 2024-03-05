<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        table {
            border-collapse: collapse;
        }
        td, th {
            border: 3px dashed wheat;
            padding: 10px;
        }
    </style>
</head>
<body>

<h2>Student progress</h2>
<table>
    <thead>
    <tr>
        <th scope="col">name</th>
        <th scope="col">start_date</th>
        <th scope="col">passed_days (day)</th>
        <th scope="col">proposed_leave_date</th>
        <th scope="col">leave_date</th>
        <th scope="col">progress (%)</th>
    </tr>
    </thead>
    <tbody>
    @foreach($students as $student)
        <tr>
            <th scope="row">{{ $student->name}}</th>
            <td>
                @if (is_null($student->start_date))
                   <form method="POST" action="/students/{{ $student->id }}">
                       @csrf
                       @method('PATCH')
                       <input type="date" name="start_date">
                       <button>set</button>
                   </form>
                @else
                    {{ $student->start_date}}
                @endif
            </td>
            <td>{{ $student->passed_days }}</td>
            <td>{{ $student->proposed_leave_date }}</td>
            <td>
                @if (is_null($student->leave_date))
                    <form method="POST" action="/students/{{ $student->id }}">
                        @csrf
                        @method('PATCH')
                        <input type="date" name="leave_date">
                        <button>set</button>
                    </form>
                @else
                    {{ $student->leave_date }}
                @endif
            </td>
            <td>{{ $student->progress }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
