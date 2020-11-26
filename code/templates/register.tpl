
{*
{foreach $messages as $msg}
<p>{$msg}</p>
{/foreach}
*}

<!doctype html>
<html>
    <head>
            <meta name= "viewport" content="width=device-width, initial-scale=1">
            <title>Register</title>
    </head>

<form action="register" method="POST">
    <p>  <label> Nom : </label> <input type = "text" name="nom" value="{$nom|default:''}"> {$messages.nom|default:''} </p>
    <p> <label> Email : </label> <input type = "email" name="email" value="{$email|default:''}"> {$messages.email|default:''}</p>
    <p> <label>  Mot De Passe : </label> <input type = "password" name="passe"> {$messages.passe|default:''}</p>
    <p> <input type ="submit"> </p>
</form>
