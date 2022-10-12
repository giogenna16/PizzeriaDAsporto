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
	<title>Home Pizzeria Da Giovanni</title>
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
                <h2>HOME</h2>
                <p>
                    Un po' di storia: il sostantivo femminile "pizza" ha origini antiche; la voce infatti era già presente nel latino medievale e indicava "boccone, pezzo di pane, focaccia". Il termine &egrave; stato diffuso in epoca recente attraverso il napoletano: la leggenda narra che nel giugno 1889, per onorare la Regina d'Italia, Margherita di Savoia, il cuoco Raffaele Esposito della Pizzeria Brandi invent&ograve; una pietanza che chiam&ograve; proprio "Pizza Margherita"; come tutti oggi sanno, gli ingredienti erano e sono pomodoro, mozzarella e basilico, i quali rappresentano gli stessi colori della bandiera italiana.
                </p>
                <p>
                    Quel che noi proponiamo &egrave; la cosiddetta "pizza a portar via", da poter comodamente gustare dove si desidera! Per il nostro asporto, con orgoglio, puntualizziamo l'utilizzo di ingredienti di prima scelta: la buona riuscita di una pietaza dipende largamente dalle materie prime adoperate! Offriamo naturalmente opzioni vegane e vegetariane.
                </p>
                <p>
                    In questo sito, per poter ordinare, &egrave; necessario registrarsi ("Registra"), se siete dei nuovi clienti; in caso contrario basta autenticarsi ("Login"); successivamente sar&agrave; possibile scegliere le pizze che preferite e concludere l'ordinazione! Per l'orario selezionato, le nostre prelibatezze saranno all'indirizzo specificato per la consegna!</p>
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