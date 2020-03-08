<?php
session_start();
					$bdd = new PDO('mysql:host=localhost;dbname=tpinscription;charset=utf8', 'root', '');
					$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

					if(isset($_POST['formconnect']))
					{
						if(!empty($_POST['pseudoconnect']) AND !empty($_POST['mdpconnect']))
						{
							$requser = $bdd->prepare("SELECT * FROM membres WHERE pseudo = :pseudo");
							$requser->execute(array('pseudo' => $_POST['pseudoconnect']));
							$result = $requser->fetch();
							if ($result && password_verify($_POST['mdpconnect'], $result['pass']))
							{	
								$_SESSION['id'] = $result['id'];
								$_SESSION['pseudo'] = $result['pseudo'];
								$_SESSION['email'] = $result['email'];
								header("LOCATION: TPprofil.php?id=".$result['id']);							  
							}
							else
							{ $erreur ="Pseudo ou mot de passe incorrecte.";
							}
						}
						else
						{ $erreur ="Champs incomplets.";
						}
					}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Connexion</title>
    </head>
        <body>
        	<div align="center">
                <h1>Connexion</h1>
                <br />
 
                <form method="post" action="">
                	      <input type="text" name="pseudoconnect" placeholder="Pseudo" />
                	      <input type="password" name="mdpconnect" placeholder="Mot de passe" />   
                	      <input type="submit" name="formconnect" placeholder="Se connecter !" />  
			      
	                </form>
	                <br />
	                <?php
	                if(isset($erreur))
	                {
	                	echo '<font color="red">' .$erreur."</font>";
	                }

	                ?>
            </div>
				
		</body>
</html>