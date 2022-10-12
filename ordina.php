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
	<title>Ordina Pizzeria Da Giovanni</title>
    <meta name="author" content="Giovanni Genna" >
    <link rel="stylesheet" type="text/css" href="stile.css">
    <script>
        function avverti(){
            window.alert("Pagina accessibile solo ai gestori, previa autenticazione!");
        }

        function Verifica(form){
           
                almenoUnElementoMaggioreDiZero = false;
                
                for(i = 0; i<form.length; ++i) {
                    if( form.elements[i].type != 'submit' && form.elements[i].type != 'reset' ) {
                        
                        if(form.elements[i].value > 0)
                        {
                        	almenoUnElementoMaggioreDiZero = true;
                        }
                    }
                } 
                if(almenoUnElementoMaggioreDiZero==false) 
                {
                	window.alert("Almeno una pizza DEVE avere una quantita' maggiore di zero per poter procedere!");
                  return false;
                }    
                return true;
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
            <h1>Pizzeria Da Giovanni</h1>

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
            <h2>Ordina</h2>
            <?php
            if(!isset($_SESSION["nick"]) || !isset($_SESSION["pass"]) || !isset($_SESSION["gestore"])  ||  $_SESSION["nick"]=="" || $_SESSION["pass"]==""){

                printf("<p class='avviso'>Attenzione! Questa pagina &egrave; accessibile solo previa autenticazione (LOGIN)!!!</p>");

            }else{
                ?>
            <form  name="f"  method="POST" action="conferma.php" onsubmit="return Verifica(f);">
            <?php
                 
                 $con = mysqli_connect("172.17.0.90", "uWeak", "posso_leggere?", "pizzasporto");
                 if(mysqli_connect_errno())
                    printf("<p class='error'>errore - collegamento al DB impossibile: %s</p>", mysqli_connect_errno());
                 else{
                    $query="SELECT id, nome, prezzo, qty FROM pizze WHERE qty>0";
                    $result = mysqli_query($con, $query);
                    if(! $result)
                        printf("<p class='error'>errore - query SELECT fallita: %s</p>", mysqli_error($con));
                    else{
                       $_SESSION["nPizze"]=mysqli_num_rows($result)+2;//+2 perchè durante lo sviluppo avevo modificato il db eliminando 2 pizze, quindi, gli id, che incrementano automaticamente, hanno un valore che, da quelli elimiati in poi è pari a 2 unita in piu di quello che dovrebbe essere
                        printf("<table class='tabella'> <tr> <th>NOME</th> <th>PREZZO</th> <th>QUANTIT&Agrave;</th> </tr>");
                        
                        while($row=  mysqli_fetch_assoc($result)){
                            
                            $s="<select name='q".$row["id"]."'> <option value='0' selected>0</option> ";
                            for($i=1; $i<=$row["qty"]; $i++){
                                $s.="<option value='".$i."'>".$i."</option> ";
                            }
                            $s.="</select>";
                            printf("<tr> <td>%s</td> <td>%.2f</td> <td>".$s."</td> </tr>", $row["nome"], $row["prezzo"]/100);

                        }
                        printf("</table>");
                    }
                 }
                 mysqli_close($con);
                ?>
                <p><input class="reset" type="reset" value="Annulla!"></p>
                <p><input class="registra" type="submit" value="Procedi!" onsubmit="return Verifica(f);"></p>

            </form>
            
            
             <?php
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