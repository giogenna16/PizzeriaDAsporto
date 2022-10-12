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
	<title>Registra Pizzeria di Giovanni</title>
    <meta name="author" content="Giovanni Genna" >
    <link rel="stylesheet" type="text/css" href="stile.css">
    <script>
        function Verifica(name, surn, date, addr, money, nick, pass){
            var s="";
            //nome e cognome
            var exprNS= /^[A-Za-z]{1,}([ ][A-Za-z]{1,}?)*$/;  // può contenere uno o più nomi o cognomi (caratteri alfabetici) separati da spazio, come da specifiche
            
            //data
            var exprData= /^\d{4}[-]\d{1,2}[-]\d{1,2}$/ ;//controlla che il formato sia aaaa-mm-gg
            var okTrentuno=false;
			var okTrenta= false;
			var okVentotto= false;
			var okVentinove= false;
            var vData=date.trim().split("-");
            if(vData.length==3){
            var giorno = vData[2];
			var mese= vData[1];
			var anno= vData[0];
            //controlli validità data
			if((mese==1 || mese== 3 || mese ==5 || mese== 7 || mese== 8 || mese ==10 || mese == 12) && giorno>=1 && giorno<=31){
				okTrentuno= true;
			}else if((mese==4 || mese==6 || mese== 9 || mese== 11) && giorno>=1 && giorno<=30){
				okTrenta= true;
			}else if(mese==2 && giorno>=1 && giorno<=28){
				okVentotto= true;
			}else if(mese==2 && giorno>=1 && giorno<=29 && anno%4==0){//verifica anno bisestile
				okVentinove= true;
			}
            }

            //domicilio
            var vDomi= addr.trim().split(" ");
            var okDomi= false;
            var okNomeDomi= false;
            var okNumDomi= false;
            if(vDomi.length>=3){
            if(vDomi[0]=="Via" || vDomi[0]=="Corso" || vDomi[0]=="Largo" || vDomi[0]== "Piazza"|| vDomi[0]=="Vicolo"){
                okDomi= true;
            }
            var contaNomiDomi=0;
            for(var i=1; i<=vDomi.length-2; i++){
                var expr=/^[A-Za-z]{2,}$/;
                if(expr.test(vDomi[i])){
                    contaNomiDomi++;
                }
            }
            if(contaNomiDomi==vDomi.length-2){
                okNomeDomi=true;
            }
            var exprCiv=/^[0-9]{1,4}$/;
            if(exprCiv.test(vDomi[vDomi.length-1])){
                okNumDomi=true;
            }
            }

            //credito
            var exprMoney=/^[0-9]{1,}[.][0-9]{2}$/;

            //username
            var exprUser=/^[a-zA-Z]{1}[a-zA-Z0-9_-]{2,7}$/;

            //password
            var exprPass=/^[0-9A-Za-z[\-\![?,.|*\'\^+>|)\/"\({};%&:^\/\=-_\]]{6,12}$/ ;
            var exprPAlmDueNum= /(?=(?:.*?[0-9]){2})/; //almeno 2 numeri
            var exprPAlmDuePunt=/(?=(?:.*?[\-\![?,.|*\'\^+>|)\/"\({};%&:^\/\=-_\]]){2})/; // almeno due caratteri di punteggiatura
            var exprPMaiusc= /[A-Z]/; 
            var exprPMin= /[a-z]/;
            var okPass=false;
            if(exprPass.test(pass.trim()) && exprPAlmDueNum.test(pass.trim()) && exprPAlmDuePunt.test(pass.trim()) &&  exprPMaiusc.test(pass.trim()) && exprPMin.test(pass.trim())){
                okPass=true;
            }

            if(exprNS.test(name.trim()) && name.trim().length>=2 && name.trim().length<=25 && exprNS.test(surn.trim()) &&  surn.trim().length>=2 && surn.trim().length<=30 && exprData.test(date.trim()) && (okVentotto== true || okTrenta== true || okTrentuno== true || okVentinove==true) && okDomi==true && okNomeDomi==true && okNumDomi==true && exprMoney.test(money.split()) && Number(money.trim())>0 && exprUser.test(nick.trim()) && okPass==true){
                return true;
            }else{
                if(!exprNS.test(name.trim()) || name.trim().length<2 || name.trim().length>25){
                    s+="Il campo 'NOME' non è accettabile! ";
                }
                if(!exprNS.test(surn.trim()) ||  surn.trim().length<2 || surn.trim().length>30 ){
                    s+="Il campo 'COGNOME' non è accettabile! ";
                }
                if(!exprData.test(date.trim()) || (okVentotto== false && okTrenta== false && okTrentuno== false && okVentinove==false) ){
                    s+="Il campo 'DATA' non è accettabile! ";
                }
                if(okDomi==false || okNomeDomi==false || okNumDomi==false ){
                    s+="Il campo 'DOMICILIO' non è accettabile! ";
                }
                if(!exprMoney.test(money.split()) || Number(money.split())<=0){
                    s+="Il campo 'CREDITO' non è accettabile! ";
                }
                if(!exprUser.test(nick.split())){
                    s+="Il campo 'USERNAME' non è accettabile! ";
                }
                if(okPass==false){
                    s+="Il campo 'PASSWORD' non è accettabile! "; 
                }

                window.alert(s);
                return false;
            }

        }

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
            <h2>Registra</h2>
            <p>&Egrave; sempre un piacere servire NUOVI clienti! Per completare la registrazione si compilino i seguenti campi:</p>
            
            <?php
            //Controlli:
            if(isset($_POST["name"]) && isset($_POST["surn"]) && isset($_POST["date"]) && isset($_POST["addr"]) && isset($_POST["money"]) && isset($_POST["nick"]) && isset($_POST["pass"])){
            //DATA
            $exprData='/^\d{4}[-]\d{1,2}[-]\d{1,2}$/' ;
            $trentuno= false;
            $trenta= false;
            $ventinove= false; 
            $ventotto= false;
            $vettData= preg_split('/[-]/', trim($_POST["date"]));
            if(count($vettData)==3){
                $giorno= $vettData[2];
                $mese= $vettData[1];
                $anno= $vettData[0];
                if(($mese==1 || $mese== 3 || $mese ==5 || $mese== 7 || $mese== 8 || $mese ==10 || $mese == 12) && $giorno>=1 && $giorno<=31){
                    $trentuno= true;
                }else if(($mese==4 || $mese==6 || $mese== 9 || $mese== 11) && $giorno>=1 && $giorno<=30){
                    $trenta= true;
                }else if($mese==2 && $giorno>=1 && $giorno<=28){
                    $ventotto= true;
                }else if($mese==2 && $giorno>=1 && $giorno<=29 && $anno%4==0){//verifica anno bisestile
                    $ventinove= true;
                }

            }

            //DOMICILIO
            $vettDom= preg_split("/[ ]/", trim($_POST["addr"]));
            $domi=false;
            $nomeDomi= false;
            $numDomi= false;
            if(count($vettDom)>=3){
                if($vettDom[0]=="Via" || $vettDom[0]=="Corso" || $vettDom[0]=="Largo" || $vettDom[0]== "Piazza"|| $vettDom[0]=="Vicolo"){
                    $domi= true;
                }
                $contaNomiDomi=0;
                for($i=1; $i<=count($vettDom)-2; $i++){
                    $expr='/^[A-Za-z]{2,}$/';
                    if(preg_match($expr, $vettDom[$i])){
                        $contaNomiDomi++;
                    }
                }
                if($contaNomiDomi==count($vettDom)-2){
                    $nomeDomi=true;
                }
                $exprCiv='/^[0-9]{1,4}$/';
                if( preg_match($exprCiv, $vettDom[count($vettDom)-1])){
                    $numDomi=true;
                }
            }

            //PASSWORD
            $exprPass= '/^[0-9A-Za-z[\-\![?,.|*\'\^+>|)\/"\({};%&:^\/\=-_\]]{6,12}$/' ;
            $exprPAlmDueNum= "/(?=(?:.*?[0-9]){2})/" ; //almeno 2 numeri
            $exprPAlmDuePunt= '/(?=(?:.*?[\-\![?,.|*\'\^+>|)\/"\({};%&:^\/\=-_\]]){2})/'; // almeno due caratteri di punteggiatura
            $exprPMaiusc= "/[A-Z]/"; 
            $exprPMin= "/[a-z]/";
            $okPass=false;
            if(preg_match($exprPass, trim($_POST["pass"])) && preg_match($exprPAlmDueNum, trim($_POST["pass"])) && preg_match($exprPAlmDuePunt, trim($_POST["pass"])) && preg_match($exprPMaiusc, trim($_POST["pass"])) && preg_match($exprPMin, trim($_POST["pass"]))){
                $okPass=true;
            }

            //VERIFICA DI TUTTI I CAMPI
            if(preg_match('/^[A-Za-z]{1,}([ ][A-Za-z]{1,}?)*$/', trim($_POST["name"])) && strlen(trim($_POST["name"]))>=2 && strlen(trim($_POST["name"]))<=25 &&   //nome
            preg_match('/^[A-Za-z]{1,}([ ][A-Za-z]{1,}?)*$/', trim($_POST["surn"])) && strlen(trim($_POST["surn"]))>=2 && strlen(trim($_POST["surn"]))<=30 &&     //cognome
            preg_match($exprData, trim($_POST["date"])) && ($trentuno==true || $trenta==true || $ventinove== true || $ventotto==true ) &&                         //data
            $domi==true && $nomeDomi==true && $numDomi==true &&                                                                                                    //domicilio
            preg_match('/^[0-9]{1,}[.][0-9]{2}$/', trim($_POST["money"])) &&  trim($_POST["money"])>0    &&                                                                //credito
            preg_match('/^[a-zA-Z]{1}[a-zA-Z0-9_-]{2,7}$/', trim($_POST["nick"])) &&                                                                                      //username
            $okPass==true                                                                                                                                           /*password */   )
            {
                
                //dati validati: aggiungere al database l'utente registrato!
                $con = mysqli_connect("172.17.0.90", "uWeak", "posso_leggere?", "pizzasporto");
                if(mysqli_connect_errno())
                    printf("<p class='error'>errore - collegamento al DB impossibile: %s</p>", mysqli_connect_errno());
                else{
                    $query="SELECT username FROM utenti";
                    $result= mysqli_query($con, $query);
                    
                    if(! $result)
                        printf("<p class='error'>errore - query SELECT fallita: %s</p>", mysqli_error($con));
                    else{
                        $giaPresente=false;
                        while($row=  mysqli_fetch_assoc($result)){
                            if($row["username"]==$_POST["nick"]){
                                $giaPresente=true;
                            }
                        }
                        if($giaPresente==true){
                            printf("<p class='error'>Lo username selezionato &egrave; gi&agrave; utilizzato da un altro utente: si prega di sceglierne un altro o, se gi&agrave; registrati, di effettuare il login!!!</p>");
                        }else{
                            $conIns = mysqli_connect("172.17.0.90", "uStrong", "SuperPippo!!!", "pizzasporto");
                            if(mysqli_connect_errno())
                              printf("<p class='error'>errore - collegamento al DB impossibile: %s</p>", mysqli_connect_errno());
                            else{
                            $queryIns="INSERT INTO utenti(nome, cognome, data, indirizzo, username, pwd, credito, gestore) VALUES
                            ('".trim($_POST['name'])."' , '".trim($_POST['surn'])."' , '".trim($_POST['date'])."' , '".trim($_POST['addr'])."' , '".trim($_POST['nick'])."' , '".trim($_POST['pass'])."' , '".trim($_POST['money'])*100 ."' , '". 0 ."')";
                            $resultIns=  mysqli_query($conIns, $queryIns);
                            if(!$resultIns)
                                printf("<p class='error'>errore - query INSERT fallita: %s</p>", mysqli_error($conIns));
                            else
                                printf("<p class='success'>Registrazione effettuata con successo!!!</p>");
                            }
                            mysqli_close($conIns);
                        }
                    }
                }
                mysqli_close($con);

            }else{
                $s="";
                if(!preg_match('/^[A-Za-z]{1,}([ ][A-Za-z]{1,}?)*$/', trim($_POST["name"])) || strlen(trim($_POST["name"]))<2 || strlen(trim($_POST["name"]))>25){
                    $s.="Il campo 'NOME' non è accettabile! ";
                }
                if(!preg_match('/^[A-Za-z]{1,}([ ][A-Za-z]{1,}?)*$/', trim($_POST["surn"])) || strlen(trim($_POST["surn"]))<2 && strlen(trim($_POST["surn"]))>30){
                    $s.="Il campo 'COGNOME' non è accettabile! ";
                }
                if(!preg_match($exprData, trim($_POST["date"])) || ($trentuno==false && $trenta==false && $ventinove==false && $ventotto==false)){
                    $s.="Il campo 'DATA' non è accettabile! ";
                }
                if($domi==false && $nomeDomi==false && $numDomi==false){
                    $s.="Il campo 'DOMICILIO' non è accettabile! ";
                }
                if(!preg_match('/^[0-9]{1,}[.][0-9]{2}$/', trim($_POST["money"])) || trim($_POST["money"])<=0){
                    $s.="Il campo 'CREDITO' non è accettabile! ";
                }
                if(!preg_match('/^[a-zA-Z]{1}[a-zA-Z0-9_-]{2,7}$/', trim($_POST["nick"]))){
                    $s.="Il campo 'USERNAME' non è accettabile! ";
                }
                if($okPass==false){
                    $s.="Il campo 'PASSWORD' non è accettabile! ";
                }
                echo "<p class='error'>".$s."</p>";
            }
            }
            ?>
            
            <form name="f"  method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>" onsubmit="return Verifica(name.value, surn.value, date.value, addr.value, money.value, nick.value, pass.value);">
                <p class="sopraInput">Nome (Name):</p>
                <p><input type="text" name="name" placeholder="Nome"></p>
                <p class="consiglio">Si accettano solo caratteri alfabetici e lo spazio (nel caso di pi&ugrave; nomi).</p>
                <p class="sopraInput">Cognome (Surname):</p>
                <p><input type="text" name="surn" placeholder="Cognome"></p>
                <p class="consiglio">Si accettano solo caratteri alfabetici e lo spazio (nel caso di pi&ugrave; cognomi).</p>
                <p class="sopraInput">Data di nascita (Birthdate):</p>
                <p><input type="text" name="date" placeholder="aaaa-mm-gg"></p>
                <p class="sopraInput">Domicilio (Address):</p>
                <p><input type="text" name="addr" placeholder="Domicilio"></p>
                <p class="sopraInput">Credito (Money):</p>
                <p><input type="text" name="money" placeholder="Credito"></p>
                <p class="consiglio">Da esprimere con la precisione dei centesimi, ad es. 100.00, 25.90 etc.</p>
                <p class="sopraInput">Username:</p>
                <p><input type="text" name="nick" placeholder="Il tuo username"></p>
                <p class="consiglio">Inserisci da 3 a 8 caratteri: solo lettere, numeri e '-' o '_'. Deve cominciare con un carattere alfabetico.</p>
                <p class="sopraInput">Password:</p>
                <p><input type="password" name="pass" placeholder="La tua password"></p>
                <p class="consiglio">Inserisci da 6 a 12 caratteri: lettere, numeri e segni di interpunzione. Deve contenere almeno 1 lettera maiuscola, 1 lettera minuscola, 2 numeri, e 2 caratteri di interpunzione.</p>
                <p><input class="registra" type="submit" value="Registrati!"></p>
            
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