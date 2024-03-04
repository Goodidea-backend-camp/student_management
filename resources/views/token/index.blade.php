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
        }
    </style>
</head>
<body>
    <form method="POST" action="/tokens">
        @csrf
        <label for="proposed_username">Enter the proposed registrant </label>
        <input type="text" id="proposed_username" name="proposed_username">
        <button>submit</button>
    </form>

    @if(session()->has('link'))
        <p>Registration link for {{ session('name') }}: {{ session('link') }}</p>
    @endif

    <h2>Token list</h2>
    <table>
        <thead>
            <tr>
                <th scope="col">proposed_username</th>
                <th scope="col">id</th>
                <th scope="col">is_valid</th>
                <th scope="col">user_id</th>
                <th scope="col">value</th>
                <th scope="col">expired_time</th>
                <th scope="col">used_time</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tokens as $token)
                <tr>
                    <th scope="row">{{ $token->proposed_username }}</th>
                    <td>{{ $token->id }}</td>
                    <td>{{ $token->is_valid ? 'true' : 'false'}}</td>
                    <td>{{ $token->user_id }}</td>
                    <td>{{ $token->value }}</td>
                    <td>{{ $token->expired_time }}</td>
                    <td>{{ $token->used_time }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
