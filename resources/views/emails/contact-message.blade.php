<h1>Nova kontakt poruka</h1>

<p><strong>Ime:</strong> {{ $messageData['name'] }}</p>
<p><strong>Email:</strong> {{ $messageData['email'] }}</p>

@if(!empty($messageData['phone']))
    <p><strong>Telefon:</strong> {{ $messageData['phone'] }}</p>
@endif

<p><strong>Tema:</strong> {{ $messageData['topic'] }}</p>

<h2>Poruka</h2>
<p style="white-space: pre-line;">{{ $messageData['message'] }}</p>

<hr>

<p><strong>Vreme slanja:</strong> {{ $messageData['submitted_at'] }}</p>
<p><strong>IP adresa:</strong> {{ $messageData['ip'] }}</p>
<p><strong>Browser:</strong> {{ $messageData['user_agent'] }}</p>
