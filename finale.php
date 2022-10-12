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
	<title>Finale Pizzeria Da Giovanni</title>
    <meta name="author" content="Giovanni Genna" >
    <link rel="stylesheet" type="text/css" href="stile.css">

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
                <h2>FINALE</h2>
                <?php
                if(isset($_SESSION["correct"])  && $_SESSION["correct"]==true){
                    
                    $con = mysqli_connect("172.17.0.90", "uStrong", "SuperPippo!!!", "pizzasporto");
                    if(mysqli_connect_errno())
                       printf("<p class='error'>errore - collegamento al DB impossibile: %s</p>", mysqli_connect_errno());
                    else{
                       $credRim= ($_SESSION["credito"]-$_SESSION["tot"])*100;
                       $query="UPDATE utenti SET credito=".$credRim." WHERE username='".$_SESSION['nick']."'";
                       $result = mysqli_query($con, $query);
                       if(! $result)
                           printf("<p class='error'>errore - query UPDATE fallita: %s</p>", mysqli_error($con));
                       else{
                           $_SESSION["credito"]=$credRim/100;

                       }

                    }
                    mysqli_close($con);

                    $vettQuantita= array();
                    $con1 = mysqli_connect("172.17.0.90", "uWeak", "posso_leggere?", "pizzasporto");
                    if(mysqli_connect_errno())
                       printf("<p class='error'>errore - collegamento al DB impossibile: %s</p>", mysqli_connect_errno());
                    else{
                       $query1="SELECT id, qty FROM pizze";
                       $result1 = mysqli_query($con1, $query1);
                       if(! $result1)
                           printf("<p class='error'>errore - query SELECT fallita: %s</p>", mysqli_error($con1));
                       else{
                          
                           while($row=  mysqli_fetch_assoc($result1)){
                              $vettQuantita[$row["id"]-1]=$row["qty"];
                               
                           }
                        } 
                    }
                    mysqli_close($con1);

                    $con2 = mysqli_connect("172.17.0.90", "uStrong", "SuperPippo!!!", "pizzasporto");
                    if(mysqli_connect_errno())
                       printf("<p class='error'>errore - collegamento al DB impossibile: %s</p>", mysqli_connect_errno());
                    else{
                        
                        for($i=1; $i<=$_SESSION["nPizze"]; $i++){
                            if(isset($_SESSION["q".$i])){
                            if($_SESSION["q".$i]>0){
                                $qRim=$vettQuantita[$i-1]-$_SESSION["q".$i];
                                $query="UPDATE pizze SET qty=".$qRim." WHERE id=".$i;
                                $result = mysqli_query($con2, $query);
                                if(! $result)
                                    printf("<p class='error'>errore - query UPDATE fallita: %s</p>", mysqli_error($con));

                            }
                        }
                            
                        }
                    }
                    mysqli_close($con2);

                    printf("<p class='riuscito'>L'ordine &egrave; andato a buon fine, in quanto la consegna, per l'orario selezionato, &egrave; realizzabile e il suo credito &egrave; stato sufficiente! Grazie e alla prossima!!!</p>");
                    printf("<p class='riuscito'><a href='home.php'>Torna alla home!</a></p>");

                 }else{
                    printf("<p class='error'>ERRORE!!!</p>");
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