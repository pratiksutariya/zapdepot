<!DOCTYPE html>
<html>
<head>
 <title>Lister Pro</title>
 <style>
     p{
         color:black;
     }
 </style>
</head>
<body>

 <h1>@if($details['fname']) {{ $details['fname'] }} @endif @if($details['lname']) {{ $details['lname'] }} @endif, welcome to Zapdepot!</h1>
    <p>Welcome to Zapdepot!</p>

    <p>Congratulations on joining. Your possibilities are endless.</p>
    <p>Now, are you ready to conquer the Internet?</p>

    <p>Here are you contact details.</p>

    <p>UserName : @if($details['uname']) {{ $details['uname'] }} @endif </p>
    <p>Password : @if($details['upass']) {{ $details['upass'] }} @endif </p>

    <p>Have any questions about how to set up your account? Just hit reply and let us know.</p>
    <p>Thanks!</p>
    <p>Zapdepot Team</p>

</body>
</html>
