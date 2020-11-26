<!doctype html>
<html>
    <head>
            <meta name= "viewport" content="width=device-width, initial-scale=1">
            <title>index</title>
    </head>
    <body>
        <h1> Accueil </h1>
            {if isset ($user)}
            <p> <h2> Salut {$user} ! </h2> </p>
            
            <br>
            <br>

            <a href="logout"><input type="button" value="Se déconnecter"> </a>
            <br>
            <br>
            <a href="profil"><input type="button" value="Voir le profil"> </a>


            {else}
            <p>  Vous êtes déjà inscrits ? <a href="login"> Connectez vous </a> </p> 
                <br> <br> 
                OU 
                <br> <br>
            <p> <a href = "register"> Inscrivez vous </a> <br> Vous verrez, c'est cool !  </p>
            {/if}
    </body>
</html> 