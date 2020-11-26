<html>
    <head>
        <meta name= "viewport" content="width=device-width, initial-scale=1">
        <title>Login</title>
    </head>
    <body>
        <h1> Connexion : </h1>

        <br> 

    <form action="login" method="POST">

        <label><b>Email</b></label>
        <input type="email" name="email" value="{$email|default:''}"  required> {$messages.Email|default:''}
        
        <br>
        
        <label><b>Mot de passe</b></label>
        <input type="password"  name="passe" required> {$messages.passe|default:''}

        <p> <input type ="submit"> </p>


    </body>
</html>

