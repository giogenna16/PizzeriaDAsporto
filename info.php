<?php
    $session = true;
    
    if( session_status() === PHP_SESSION_DISABLED  )
        $session = false;
    elseif( session_status() !== PHP_SESSION_ACTIVE )
        session_start();
   
?>
<!DOCTYPE html>
<html lang="it">
<head>
	<meta charset="utf-8">
	<title>Info Pizzeria Da Giovanni</title>
    <meta name="author" content="Giovanni Genna" >
    <link rel="stylesheet" type="text/css" href="stile.css">
    <script>
        function avverti(){
            window.alert("Pagina accessibile solo ai gestori, previa autenticazione!");
        }

        function avvLog(){
            window.alert("Non è possibile effettuare il LOGOUT prima di essersi autenticati!");
        }

        function avvLogin(){
            window.alert("L'autenticazione è già avvenuta con successo!");
        }
    </script>
</head>
<body>
    <?php
        if(!$session)
        {
            echo "<p>SESSIONI DISABILITATE, impossibile proseguire</p>";
        }
        else
        {
    ?>  
    
    <?php
            if(isset($_POST["logout"])){
                $_SESSION=array();
                session_destroy();
            }
        ?>      

    <div class="grid-container">
         
        <div class= "theHeader">
        <?php
            if(isset($_SESSION["nick"]) && isset($_SESSION["pass"]) &&  $_SESSION["nick"]!="" && $_SESSION["pass"]!=""){
                printf("<p class='infoU'>Username: ".$_SESSION["nick"].". Credito: %.2f .</p>",$_SESSION["credito"]);
            }else{
                printf("<p class='infoU'>Username: ANONIMO. Credito: 0.00 .</p>");
            }
        ?>
            <h1>Pizzeria Da Giovanni<h1>

        </div>

        <div class="menu1">
            <a href="home.php">Home</a>
        </div>
        <div class="menu2">
            <a href="registra.php">Registra</a>
        </div>
        <div class="menu3">
            <?php
            if(isset($_SESSION["nick"]) && isset($_SESSION["pass"]) && isset($_SESSION["gestore"]) && $_SESSION["nick"]!="" && $_SESSION["pass"]!="" && $_SESSION["gestore"]==1){
                echo "<a href='cambia.php'>Cambia</a>";
            }else{
                echo "<p class='cambia' onclick='avverti()'>Cambia</p>";
            }
            ?>
           
        </div>
        <div class="menu4">
            <a href="info.php">Info</a> 
        </div>
        <div class="menu5">
        <?php
         if(isset($_SESSION["nick"]) && isset($_SESSION["pass"]) && $_SESSION["nick"]!="" && $_SESSION["pass"]!=""){
             echo "<p onclick='avvLogin()'>Login</p>";
         }else{
            echo "<a href='login.php'>Login</a> ";
         }
        ?>
        </div>
        <div class="menu6">
        <a href="ordina.php">Ordina</a> 
        </div>

        <div class="menu7">
        <?php
            if(isset($_SESSION["nick"]) && isset($_SESSION["pass"]) && $_SESSION["nick"]!="" && $_SESSION["pass"]!=""){
            ?>
            <form name="f"  method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>">
            <input class="logout" type="submit" name="logout" value="Logout">
            </form>  
                
           <?php
            }else{
                echo "<p class='cambia' onclick='avvLog()'>Logout</p>";
            }
            ?>
        </div>

        <div class= "theMain">
            <main>
            <h2>Info</h2>
            <p>Questo che segue &egrave; l'elenco delle nostre gustose pizze:</p>
            <?php
              if(isset($_SESSION["nick"]) && isset($_SESSION["pass"]) && $_SESSION["nick"]!="" && $_SESSION["pass"]!=""){
                $con = mysqli_connect("172.17.0.90", "uWeak", "posso_leggere?", "pizzasporto");
                if(mysqli_connect_errno())
                    printf("<p class='error'>errore - collegamento al DB impossibile: %s</p>", mysqli_connect_errno());
                else{
                    $query="SELECT nome, ingredienti, tipo, qty, prezzo FROM pizze WHERE qty>0 ";
                    $result= mysqli_query($con, $query);
                    if(! $result)
                        printf("<p class='error'>errore - query SELECT fallita: %s</p>", mysqli_error($con));
                    else{
                        printf("<table class='tabella'> <tr> <th>NOME</th> <th>INGREDIENTI</th> <th>TIPO</th> <th>QUANTIT&Agrave;</th> <th>PREZZO</th> </tr>");
                        while($row=  mysqli_fetch_assoc($result)){
                            printf("<tr> <td>%s</td> <td>%s</td> <td>%s</td>  <td>%d</td>  <td>%.2f</td> </tr>", $row["nome"], $row["ingredienti"], $row["tipo"], $row["qty"], $row["prezzo"]/100);
                        }
                        printf("</table>");
                    }
                }
                mysqli_close($con);
              }else{
                $con = mysqli_connect("172.17.0.90", "uWeak", "posso_leggere?", "pizzasporto");
                if(mysqli_connect_errno())
                    printf("<p class='error'>errore - collegamento al DB impossibile: %s</p>", mysqli_connect_errno());
                else{
                $query="SELECT nome, ingredienti, tipo, prezzo FROM pizze WHERE qty>0 ";
                $result= mysqli_query($con, $query);
                if(! $result)
                    printf("<p class='error'>errore - query SELECT fallita: %s</p>", mysqli_error($con));
                else{
                    printf("<table class='tabella'> <tr> <th>NOME</th> <th>INGREDIENTI</th> <th>TIPO</th> <th>PREZZO</th> </tr>");
                    while($row=  mysqli_fetch_assoc($result)){
                        printf("<tr> <td>%s</td> <td>%s</td> <td>%s</td> <td>%.2f</td> </tr>", $row["nome"], $row["ingredienti"], $row["tipo"], $row["prezzo"]/100);
                    }
                    printf("</table>");
                }
               
              }
              mysqli_close($con);
            }

            ?>
            </main>

        </div>

        <div class= theFooter>
            <footer>
                <p>
                   &copy; Autore: Giovanni Genna .
                </p>
                <p>
                Pagina: "<?php printf($_SERVER["PHP_SELF"]);?>"
                </p>
                
            </footer>
        </div>


    </div>
    <?php
        }
    ?>

</body>
</html>