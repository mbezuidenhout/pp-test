<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
</head>
<body>
<h1>Welcome, {{ $person->name }}!</h1>

<p>We're excited to have you on board. Here's what we have on file for you:</p>

<ul>
    <li><strong>Full Name:</strong> {{ $person->name }} {{ $person->surname }}</li>
    <li><strong>ID Number:</strong> {{ $person->sa_id_number }}</li>
    <li><strong>Mobile Number:</strong> {{ $person->mobile_number }}</li>
    <li><strong>Email:</strong> {{ $person->email }}</li>
    <li><strong>Birth Date:</strong> {{ $person->birth_date }}</li>
    <li><strong>Language:</strong> {{ $autoloadOptions['languages'][$person->language_code] }}</li>
    <li><strong>Interests:</strong> {{ implode(', ', $person->interests) }}</li>
</ul>

<p>If anything is incorrect, please let us know.</p>

<p>Best regards,<br>Your Team</p>
</body>
</html>
