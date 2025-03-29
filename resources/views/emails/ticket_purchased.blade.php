<!DOCTYPE html>
<html>
<head>
    <title>Билет на фильм</title>
</head>
<body>
<h1>Билет успешно приобретен</h1>
<p>Фильм: {{ $ticket->session->movie->title }}</p>
<p>Время сеанса: {{ $ticket->session->start_time }}</p>
<p>Место: {{$ticket->seat_number}}</p>
<p>Цена: {{ $ticket->price }} руб.</p>
</body>
</html>
