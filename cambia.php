<?php
    $session = true;
    
    if( session_status() === PHP_SESSION_DISABLED  )
        $session = false;
    elseif( session_status() !== PHP_SESSION_ACTIVE ){
        session_start();

        for()
        
    }  
?>
<!DOCTYPE html>
<html lang="it">
<head>
	<meta charset="utf-8">
	<title>Cambia Pizzeria Da Giovanni</title>
    <meta name="author" content="Giovanni Genna" >
    <link rel="stylesheet" type="text/css" href="stile.css">
    <script>

        function Verifica(nome, ingr, qta, prezzo){
            var s="";
            var exprNome= /^[A-Za-z0-9]{1,}$/ ;
            var exprIngr= /^[A-Za-z]{1,}[A-Za-z, ]{1,}$/ ;
            var exprTipo=/^\s*$/ ;
            var exprQta= /^[0-9]{1,}$/ ;
            if(exprNome.test(nome.trim()) && exprIngr.test(ingr.trim()) && exprQta.test(qta.trim()) && prezzo.trim()>0){
                return true;

            }else{
                if(!exprNome.test(nome.trim())){
                    s+="Il campo 'NOME' non è accettabile! ";
                }
                if(!exprIngr.test(ingr.trim())){
                    s+="Il campo 'INGREDIENTI' non è accettabile! ";
                }
                if(!exprQta.test(qta.trim())){
                    s+="Il campo 'QUANTITA' non è accettabile! ";
                }
                var exprP=/^[^0-9]$/;
                if(prezzo.trim()<=0 || exprP.test(prezzo.trim())){
                    s+="Il campo 'PREZZO' non è accettabile!";
                }
                window.alert(s);

                return false;
            }
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
            <a href="cambia.php">Cambia</a>       
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
                <h2>Cambia</h2>
                <?php
                if(isset($_POST["nome"]) && isset($_POST["ingr"]) && isset($_POST["qta"]) && isset($_POST["prezzo"]) && isset($_POST["invia"])){
                    if(preg_match('/^[A-Za-z0-9]{1,}$/', trim($_POST["nome"])) &&             //nome
                     preg_match('/^[A-Za-z]{1,}[A-Za-z, ]{1,}$/', trim($_POST["ingr"])) &&    //ingredienti
                      preg_match('/^[0-9]{1,}$/', trim($_POST["qta"])) &&   //quantita
                      trim($_POST["prezzo"])>0              /*prezzo*/ ){

                        $con = mysqli_connect("172.17.0.90", "uStrong", "SuperPippo!!!", "pizzasporto");
                        if(mysqli_connect_errno())
                           printf("<p class='error'>errore - collegamento al DB impossibile: %s</p>", mysqli_connect_errno());
                        else{
                            $vuoto=false;
                           if(trim($_POST["tipo"])!="vegan" && trim($_POST["tipo"])!="veggy"){
                               $vuoto=true;
                           }
                           if($vuoto==false){
                           $query="INSERT INTO pizze(ingredienti, nome, tipo, prezzo, qty) VALUES('".trim($_POST['ingr'])."', '".trim($_POST['nome'])."', '".trim($_POST['tipo'])."', '".trim($_POST['prezzo'])*100 ."', '".trim($_POST['qta'])."')";
                           $result = mysqli_query($con, $query);
                           if(! $result)
                               printf("<p class='error'>errore - query INSERT fallita: %s</p>", mysqli_error($con));
                           else{
                            echo  "<p class= 'success'>Aggiornato correttamente!</p>";
                           }
                        }else{
                           $query="INSERT INTO pizze(ingredienti, nome, tipo, prezzo, qty) VALUES('".trim($_POST['ingr'])."' , '".trim($_POST['nome'])."' , NULL , '".trim($_POST['prezzo'])*100 ."' , '".trim($_POST['qta'])."')";
                           $result = mysqli_query($con, $query);
                           if(! $result)
                               printf("<p class='error'>errore - query INSERT fallita: %s</p>", mysqli_error($con));
                           else{
                            echo "<p class= 'success'>Aggiornato correttamente!</p>";
                           }

                        }
                        }
                    }else{
                        $s="";
                        if(!preg_match('/^[A-Za-z0-9]{1,}$/', trim($_POST["nome"]))){
                            $s.="Il campo 'NOME' non &egrave; accettabile! ";
                        }
                        if(!preg_match('/^[A-Za-z]{1,}[A-Za-z, ]{1,}$/', trim($_POST["ingr"]))){
                            $s.="Il campo 'INGREDIENTI' non &egrave; accettabile! ";
                        }
                        if(trim($_POST["tipo"])!="vegan" && !trim($_POST["tipo"])!="veggy" && !preg_match('/^\s*$/',$_POST["tipo"]) && isset($_POST["tipo"])){
                            $s.="Il campo 'TIPO' non &egrave; accettabile! ";
                        }
                        if(!preg_match('/^[0-9]{1,}$/', trim($_POST["qta"]))){
                            $s.="Il campo 'QUANTIT&Agrave;' non &egrave; accettabile! ";
                        }
                        if(trim($_POST["prezzo"])<=0 || preg_match('/^[^0-9]$/')){
                            $s.="Il campo 'PREZZO' non &egrave; accettabile! ";
                        }
                        printf("<p class='error'>".$s."</p>");
                    }
                }
                ?>
                <?php
                    $settato=false;
                    $qty=0;
                    $id=0;
                    for($i=1; $i<=$_SESSION["nPizzeCambia"]; $i++){
                        if(isset($_POST["q".$i])){
                        if(preg_match('/^[0-9]{1,}$/', trim($_POST["q".$i])) && trim($_POST["q".$i])>0 && isset($_POST[$i])){
                            $settato=true;
                            $qty= trim($_POST["q".$i]);
                            $id=$i;
                            break;
                        }
                    }
                        
                    }
                    if($settato==true){
                        $con = mysqli_connect("172.17.0.90", "uStrong", "SuperPippo!!!", "pizzasporto");
                        if(mysqli_connect_errno())
                           printf("<p class='error'>errore - collegamento al DB impossibile: %s</p>", mysqli_connect_errno());
                        else{
                           $query="UPDATE pizze SET qty=".$qty." WHERE id=".$id."";
                           $result = mysqli_query($con, $query);
                           if(! $result)
                               printf("<p class='error'>errore - query UPDATE fallita: %s</p>", mysqli_error($con));
                           else{
                               echo "<p class= 'success'>Aggiornato correttamente!</p>";

                           }

                        }
                    }else{
                        $cliccato=false;
                       
                        for($i=1; $i<=$_SESSION["nPizzeCambia"]; $i++){
                            if(isset($_POST[$i])){
                                $cliccato=true;
                            }
                            
                        }
                        if($cliccato==true){
                            printf("<p class='error'>Per aggiornare la quantit&agrave; &egrave; necessario inserire un numero INTERO POSITIVO e cliccare sul tasto CORRISPONDENTE!</p>");
                        }
                    }
                
                ?>
                <p>Da qui &egrave; possibile aggiornare le quantit&agrave; delle pizze presenti:</p>

                <form name="f"  method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>">
                <?php
                 
                 $con = mysqli_connect("172.17.0.90", "uWeak", "posso_leggere?", "pizzasporto");
                 if(mysqli_connect_errno())
                    printf("<p class='error'>errore - collegamento al DB impossibile: %s</p>", mysqli_connect_errno());
                 else{
                    $query="SELECT id, nome, prezzo, qty FROM pizze";
                    $result = mysqli_query($con, $query);
                    if(! $result)
                        printf("<p class='error'>errore - query SELECT fallita: %s</p>", mysqli_error($con));
                    else{
                        $_SESSION["nPizzeCambia"]=mysqli_num_rows($result)+2;//+2 perchè durante lo sviluppo avevo modificato il db eliminando 2 pizze, quindi, gli id, che incrementano automaticamente, hanno un valore che, da quelli elimiati in poi è pari a 2 unita in piu di quello che dovrebbe essere
                        printf("<table class='tabella'> <tr> <th>NOME</th> <th>PREZZO</th> <th>QUANTIT&Agrave;</th> <th>NUOVA QUANTIT&Agrave;</th> <th>AGGIORNA</th> </tr>");
                        while($row=  mysqli_fetch_assoc($result)){
                            printf("<tr> <td>%s</td> <td>%.2f</td>  <td>%d</td> <td><input class='cambiaT' type='text' name='q".$row["id"]."' value='0' ></td> <td><input class='cambia' type='submit' name='%s' value='Aggiorna prodotto'></td> </tr>", $row["nome"], $row["prezzo"]/100, $row["qty"], $row["id"]);
                            
                        }
                        printf("</table>");
                    }
                 }
                 mysqli_close($con);
                ?>
                </form>

                <p>Da qui &egrave; possibile aggiungere una nuova pizza al men&ugrave;:</p>
                <form name="fN"  method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>" onsubmit="return Verifica(nome.value, ingr.value, qta.value, prezzo.value)">
                <table class='tabella'>
                    <tr> <th>NOME</th> <th>INGREDIENTI</th> <th>TIPO</th> <th>QUANTIT&Agrave;</th> <th>COSTO UNITARIO</th> <th>AGGIUNGI</th> </tr>
                    <tr> <td><input class='cambiaT' type="text" name="nome" placeholder="Nome pizza"></td>  <td><input class='cambiaT' type="text" name="ingr" placeholder="Elenco ingredienti"></td>  <td><select name="tipo" ><option value="" selected></option> <option value="veggy">veggy</option> <option value="vegan" >vegan</option> </select></td>  <td><input class='cambiaT' type="text" name="qta" placeholder="Quantit&agrave;"></td>  <td><input class='cambiaT'  type="text" name="prezzo" placeholder="Prezzo"></td>  <td><input class='cambia' type="submit" name="invia" value="Aggiungi Pizza!"></td> </tr>
                </table>
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