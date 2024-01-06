<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Players</title>

        <!-- Styles -->
        <link rel="stylesheet" href="{{ public_path('css/app.css') }}" type="text/css">
    </head>
    <body>
        <h1 class="pdf-title">Players of {{$tournament->name}}</h1>
        <table>
            <thead>
                <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Skill</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($playersTotal as $player)
                    <tr>
                        <th scope="row">{{$player->id}}</th>
                        <td>{{$player->name}}</td>
                        <td>{{$player->skill}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </body>
</html>
