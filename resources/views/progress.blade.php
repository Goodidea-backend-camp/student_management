<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        table {
            border-collapse: collapse;
            margin-inline: auto;
        }
        td, th {
            border: 3px dashed wheat;
            padding: 10px;
        }
        button {
            margin: 10px;
            padding: 10px;
        }
        body {
            margin-inline: 15%;
        }
        h1 {
            text-align: center;
        }
    </style>
    <title>Student progress</title>
</head>
<body>

<h1>Student progress</h1>
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
        <dialog data-id="{{ $student->id }}">
            <button autofocus>Close</button>
            <p> email: {{ $student->email }} </p>
            <p> phone: {{ $student->phone }} </p>
        </dialog>
        <tr>
            <th scope="row">
                {{ $student->name}}
                <button data-id="{{ $student->id }}">info</button>
            </th>
            <td>
                @if (is_null($student->start_date))
                   <form method="POST" action="/students/{{ $student->id }}">
                       @csrf
                       @method('PATCH')

                       <input type="date" name="start_date" aria-label="set start date">
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
                        <input type="date" name="leave_date" aria-label="set leave date">
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
<script>
    const dialogs = document.querySelectorAll('dialog');
    dialogs.forEach((dialog => {
        document.querySelector(`button[data-id='${dialog.dataset.id}']`).addEventListener('click', () => {
            dialog.showModal();
        });

        document.querySelector(`dialog[data-id='${dialog.dataset.id}'] button`).addEventListener('click', () => {
            dialog.close();
        })
    }))
</script>
</body>
</html>
