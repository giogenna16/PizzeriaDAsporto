<?php
    $session = true;
    
    if( session_status() === PHP_SESSION_DISABLED  )
        $session = false;
    elseif( session_status() !== PHP_SESSION_ACTIVE ){
        session_start();
       
        if(isset($_POST["ok"])){
            $con = mysqli_connect("172.17.0.90", "uWeak", "posso_leggere?", "pizzasporto");
            if(mysqli_connect_errno())
                printf("<p class='error'>errore - collegamento al DB impossibile: %s</p>", mysqli_connect_errno());
            else{
                $query="SELECT username, pwd, gestore, credito, indirizzo FROM utenti";
                $result= mysqli_query($con, $query);
                
                if(! $result)
                    printf("<p class='error'>errore - query SELECT fallita: %s</p>", mysqli_error($con));
                else{
                   $presente=false;
                   $gestore=0;
                   $ind="";
                   $credit=0;
                   while($row=  mysqli_fetch_assoc($result)){
                       if($row["username"]==$_POST["nick"] && $row["pwd"]==$_POST["pass"]){
                           $gestore=$row["gestore"];
                           $presente=true;
                           $ind=$row["indirizzo"];
                           $credit=$row["credito"]/100;
                       }
                   }
                   $_SESSION["loginSuccesso"]=false;
                   if($presente==true){

                      $_SESSION["loginSuccesso"]=true;

                      $_SESSION["nick"]=$_POST["nick"];
                      $_SESSION["pass"]=$_POST["pass"];
                      $_SESSION["gestore"]=$gestore;
                      $_SESSION["indirizzo"]=$ind;
                      $_SESSION["credito"]=$credit;

                      $username= $_SESSION["nick"];
                      $scadenza = time()+259200; //72h
                      setcookie("nick", $username, $scadenza, "", "", TRUE);

                      header("Location: https://95.110.130.130.nip.io/s258261/website/Sito%20web%20esame/info.php");
                   }
                }
            }
            mysqli_close($con);
        }
    
        if(!isset($_SESSION["nick"])){
            $_SESSION["nick"]="";
        }
        if(!isset($_SESSION["pass"])){
            $_SESSION["pass"]="";
        }
        if(!isset($_SESSION["gestore"])){
            $_SESSION["gestore"]=0;
        }
        if(!isset($_SESSION["credito"])){
            $_SESSION["credito"]=0;
        }
        if(!isset($_SESSION["indirizzo"])){
            $_SESSION["indirizzo"]="";
        }
        
    }
?>
<!DOCTYPE html>
<html lang="it">
<head>
	<meta charset="utf-8">
	<title>Login Pizzeria Da Giovanni</title>
    <meta name="author" content="Giovanni Genna" >
    <link rel="stylesheet" type="text/css" href="stile.css">
    <script>
        function Verifica(nick, pass){
            var s="";
            var expr= /^\s*$/ //\s include " ", \r, \t, \n and \f
            if(!expr.test(nick) && !expr.test(pass)){
                return true;
            }else{
                if(expr.test(nick)){
                    s+="Il campo 'USERNAME' è vuoto! ";
                }
                if(expr.test(pass)){
                    s+="Il campo 'PASSWORD' è vuoto! ";
                }
                window.alert(s);
                return false;
            }
        }

        function avverti(){
            window.alert("Pagina accessibile solo ai gestori, previa autenticazione!");
        }

        function avvLog(){
            window.alert("Non è possibile effettuare il LOGOUT prima di autenticarsi!");
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
            if(isset($_SESSION["nick"]) && isset($_SESSION["nick"]) && isset($_SESSION["gestore"]) && $_SESSION["nick"]!="" && $_SESSION["pass"]!="" && $_SESSION["gestore"]==1){
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
            <a href="login.php">Login</a> 
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
            <h2>Login</h2>
            <p>Siamo onorati dal suo ritorno! Effettuare il login per accedere:</p>
            <?php
            if(isset($_SESSION["loginSuccesso"]) && $_SESSION["loginSuccesso"]==false){
                printf("<p class='error'>Username e/o password errati: la invitiamo a riprovare!</p>");

            }
            ?>

            <form name="f"  method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>" onsubmit="return Verifica(nick.value, pass.value);">
                <p class="sopraInput">Username:</p>
                <?php
                if(isset($_COOKIE["nick"])){
                    echo "<p><input type='text' name='nick' value=".$_COOKIE["nick"]."></p>";
                }else{
                    echo "<p><input type='text' name='nick' placeholder='Il tuo username'></p>";
                }
                ?>
                <p class="sopraInput">Password:</p>
                <p><input type="password" name="pass" placeholder="La tua password"></p>
                <p><input class="reset" type="reset" value="Pulisci!"></p>
                <p><input class="registra" type="submit" value="OK!" name="ok" ></p>
               
            </form>
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