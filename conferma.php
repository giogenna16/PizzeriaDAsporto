<?php
    $session = true;
    
    if( session_status() === PHP_SESSION_DISABLED  )
        $session = false;
    elseif( session_status() !== PHP_SESSION_ACTIVE ){
        session_start();
        if(isset($_POST["conf"])){
             //Verifica indirizzo
             $vettDom= preg_split("/[ ]/", trim($_POST["indirizzo"]));
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

             //Verifica orario
             $exprOra='/^[0-9]{2}[:][0-9]{2}$/';
             $formatoOk=false;
             $orarioSensato=false;
             $oraOk= false;
             if(preg_match($exprOra, trim($_POST["orario"]))){
                 $formatoOk= true;
                 $vettOra= preg_split("/[:]/", trim($_POST["orario"]));
                 $ore=$vettOra[0];
                 $minuti=$vettOra[1];
                
                 if($ore>=12 && $ore<=23 && $minuti>=0 && $minuti<=59){
                     $orarioSensato= true;

                     $consegnaInSecondi=$ore*3600+$minuti*60;
                     
                     
                     $oraNow=date("G")+2; //+2 per il fuso orario
                     $minutiNow=date("i");
                     $nowInSecondi=$oraNow*3600+$minutiNow*60;
                     
                     if($consegnaInSecondi-$nowInSecondi>=45*60){
                         $oraOk=true;
                     }
                 }
             }

             //verifica Credito Sufficiente
             $creditoOk= false;
             if($_SESSION["credito"]>=$_SESSION["tot"]){
                 $creditoOk=true;

             }

             $_SESSION["correct"]=false;
             //Verifico tutte le condizioni per accettare l'ordine:
             if($domi==true && $nomeDomi==true && $numDomi==true &&
              $oraOk==true &&
              $creditoOk==true){
                $_SESSION["correct"]=true;
                header("Location: https://95.110.130.130.nip.io/s258261/website/Sito%20web%20esame/finale.php");
              }else{
                  $s="";
                if($domi!=true || $nomeDomi!=true || $numDomi!=true){
                    $s.="<p class='error'>L'indirizzo di consegna non &egrave; accettabile! </p>";
                 }

                 if($formatoOk==false){
                     $s.="<p class='error'>Il formato dell'orario inserito non &egrave; corretto; il formato corretto &egrave; 'hh:mm'! </p>";

                 }elseif($orarioSensato==false){
                     $s.="<p class='error'>&Egrave; stato inserito un orario non sensato: la pizzeria effettua consegne solo tra le ore 12:00 e le ore 23:59 </p>";
                 }elseif($oraOk==false){
                     $s.="<p class='error'>&Egrave; stato inserito un orario non sensato: o l'orario selezionato &egrave; antecendente all'orario attuale o l'ora di consegna non &egrave; almeno 45 minuti dopo il momento dell'ordine! </p>";
                 }

                 if($creditoOk==false){
                     $s.="<p class='error'>Il credito non &egrave; sufficiente. Non &egrave possibile processare l'ordine!</p>";
                 }
                 $_SESSION["error"]=$s;
              }
        }
    }
?>
<!DOCTYPE html>
<html lang="it">
<head>
	<meta charset="utf-8">
	<title>Conferma Pizzeria Da Giovanni</title>
    <meta name="author" content="Giovanni Genna" >
    <link rel="stylesheet" type="text/css" href="stile.css">
    <script>
        function Verifica(indirizzo, orario){
            var s="";
            
            //indirizzo
            var vDomi= indirizzo.trim().split(" ");
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

            //orario
            var exprOra=/^[0-9]{2}[:][0-9]{2}$/;



            if(okDomi==true && okNomeDomi==true && okNumDomi==true && exprOra.test(orario.trim())){
                return true;
            }
            else{
                if(okDomi!=true || okNomeDomi!=true || okNumDomi!=true){
                    s+="Il campo 'INDIRIZZO' non è accettabile! ";
                }
                if(!exprOra.test(orario.trim())){
                    s+="Il campo 'ORARIO' non è accettabile! ";
                }
            }
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
    <div class="grid-container">
        <div class= "theHeader">
            <h1>Pizzeria Da Giovanni<h1>

        </div>

        <div class= "theMain">
            <main>
            <h2>Conferma</h2>
            <?php
            if(!isset($_POST["conf"])){
            ?>
            <form  name="f"  method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>" onsubmit="return Verifica(indirizzo.value, orario.value)">
            <?php
            $con = mysqli_connect("172.17.0.90", "uWeak", "posso_leggere?", "pizzasporto");
            if(mysqli_connect_errno())
               printf("<p class='error'>errore - collegamento al DB impossibile: %s</p>", mysqli_connect_errno());
            else{
               $query="SELECT id, nome, prezzo FROM pizze";
               $result = mysqli_query($con, $query);
               if(! $result)
                   printf("<p class='error'>errore - query SELECT fallita: %s</p>", mysqli_error($con));
               else{
                
                   $almenoUnoMaggZero=false;
                   for($i=1; $i<=$_SESSION["nPizze"]; $i++){
                        if(isset($_POST["q".$i])){
                        if( $_POST["q".$i]>0){

                            $almenoUnoMaggZero=true;
                            
                        }
                    }   
                    }
                    
                   if($almenoUnoMaggZero==false){
                       printf("<p class='error'>Errore: il totale &egrave; pari a 0!</p>");
                       ?>
                       <p class="error"><a href="home.php">Torna alla home!</a></p>
                       <?php
                   }
                   else{
                        $tot=0.0;
                        printf("<table class='tabella'> <tr> <th>NOME</th> <th>PREZZO</th> <th>QUANTIT&Agrave;</th> <th>SUBTOTALE</th> </tr>");
                        while($row=  mysqli_fetch_assoc($result)){
                            $_SESSION["q".$row["id"]]=$_POST["q".$row["id"]];
                            if($_POST["q".$row["id"]]>0){
                                printf("<tr> <td>%s</td> <td>%.2f</td>  <td>%d</td>  <td>%.2f</td> </tr>", $row["nome"] , $row["prezzo"]/100 , $_POST["q".$row["id"]] , ($row["prezzo"]/100)*$_POST["q".$row["id"]] );
                                $tot+=($row["prezzo"]/100)*$_POST["q".$row["id"]] ;
                            }

                        }
                        $_SESSION["tot"]=$tot;
                        printf("<tr> <th></th> <th></th> <th></th> <th>TOTALE</th> </tr>");
	                    printf("<tr>  <td></td>  <td></td>  <td></td>  <td>%.2f</td>  </tr></table>", $tot);
                        ?>
                         <p class="sopraInput">Indirizzo di consegna:</p>
                         <p><input type="text" name="indirizzo" value="<?php echo $_SESSION["indirizzo"]; ?>"></p>
                         <p class="sopraInput">Orario di consegna:</p>
                         <p><input type="text" name="orario" placeholder="hh:mm"></p>
                         <p class='consiglio'>La pizzeria effettua consegne solo tra le ore 12:00 e le ore 23:59 ed &egrave; possibile prenotare solo per il giorno corrente. L’ora di consegna deve essere almeno 45 minuti dopo il momento dell’ordine.</p>
                         <p><input class="reset" type="submit" value="Annulla!" formaction="home.php"></p>
                         <p><input class="registra" type="submit" name="conf" value="Conferma!"></p>
                    <?
                    }
               }
            }
            mysqli_close($con);
            ?>

            </form>
            <?php
             }elseif(isset($_SESSION["correct"]) && $_SESSION["correct"]==false){
                 printf($_SESSION["error"]);
                 ?>
                 <form name='f' action='home.php'>
                    <p><input class="reset" type="submit" value="Annulla!"></p>
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