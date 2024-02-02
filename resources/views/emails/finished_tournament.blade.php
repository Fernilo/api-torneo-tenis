<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <title>Torneo FInalizado</title>

  </head>
  <body>
    <p><strong>Nombre:</strong>{{$tournament['name']}}</p>
    <p><strong>Campeón:</strong>{{$tournament['player']['name']}}</p>
    {{-- <p>
        <strong>Partidos:</strong>
        @foreach ($tournament['matches'] as $match)
            <li>{{$match['id']}}</li>
        @endforeach
    </p> --}}
  </body>
</html>