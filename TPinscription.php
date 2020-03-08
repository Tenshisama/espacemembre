<?php
					$bdd = new PDO('mysql:host=localhost;dbname=tpinscription;charset=utf8', 'root', '');
					$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if(isset($_POST['forminscription'])) //se connecte a la touche de validation de formulaire
{
		$pseudo = htmlspecialchars($_POST['pseudo']);
		$email = htmlspecialchars($_POST['email']);
		$pass = htmlspecialchars($_POST['pass']);
		$passverif = htmlspecialchars($_POST['passverif']);

		$hashedpass = password_hash($pass, PASSWORD_DEFAULT);

	if(!empty($_POST['pseudo']) AND !empty($_POST['pass']) AND !empty($_POST['passverif'])AND !empty($_POST['email']))
	{
		$pseudolenght = strlen($pseudo);
		if($pseudolenght <= 17)  //limite la taille du pseudo
		{
						$reqpseudo = $bdd->prepare("SELECT * FROM membres WHERE pseudo = ?");
						$reqpseudo->execute(array($pseudo));
						$pseudoexist = $reqpseudo->rowCount();
						if($pseudoexist == 0) //verif que le pseudo n'existe pas déjà
						{
								
								//	$pass_exist = $bdd->prepare('SELECT pass FROM membres WHERE pseudo= :pseudo');
								//	$pass_exist->execute(array('pseudo' => $pseudo));
								//	$resultat = $pass_exist->fetch();
								//	$resultat2 = password_verify($pass,$resultat['pass']);

									if($pass == $passverif)


								{
									if(filter_var($email, FILTER_VALIDATE_EMAIL)) //sensé vérifier format de l'email mais semble pas efficace
									{
										$reqmail = $bdd->prepare("SELECT * FROM membres WHERE email = ?");
										$reqmail->execute(array($email));
										$mailexist = $reqmail->rowCount();
										if($mailexist == 0) //verifie si le mail est déjà utilisé
										{

											$insertmbr = $bdd->prepare('INSERT INTO membres(pseudo, pass, email, date_inscription) VALUES( ?, ?, ?, CURDATE())');  //une fois que tout est bon les données sont envoyé a la table membre de la bdd tpinscription mysql
											$insertmbr->execute(array($pseudo, $hashedpass, $email));


											$erreur ="Votre compte a bien été créé ! <a href= \"TPconnexion.php\">Me connecter</a>"; //voir tuto php primfx a 30:30 pour explication sur redirection
										}
										else
										{
											$erreur = "email déjà utilisé!";
										}
									} 
									else
									{
										$erreur = "email invalide";
									}
								}
								else
								{
									$erreur = "Les mots de passes ne correspondent pas.";
								}
						}
						else
						{
							$erreur = "Ce pseudo est déjà pris.";
						}		
		}
		else
		{
			$erreur = "Votre pseudo ne doit pas dépasser 17 caractères! ";
		}
	}
	else
	{
		$erreur = "Tous les champs ne sont pas complétés";
	}
}

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Inscription</title>
    </head>
        <body>
        	<div align="center">
                <h1>Inscription</h1>
                <br />
 
                <form method="post" action="">
                	<table>
                		<tr>
	                		<td>
			                    <label for="pseudo">Pseudo </label>
			                </td>
			                <td>
			                    <input align="right" type="text" name="pseudo" id="pseudo" value="<?php if(isset($pseudo)) {echo $pseudo; } ?>" />
			                </td>
			            </tr>
			            <tr>
			                <td>
			                    <label for="pass">Mot de passe </label>
			                </td>
			                <td>
			                    <input type="password" name="pass" id="pass" />  
			                </td>
			            </tr>
			            <tr>
			                <td>
			                    <label for="passverif">Retapez votre mot de passe</label>
			                </td>
			                <td>
			                    <input type="password" name="passverif" id="passverif" />
			                </td>
			            </tr>
			            <tr>
			                <td>
			                    <label for="email">E-mail</label>
			                </td>
			                <td>
			                    <input type="email" name="email" id="email" value="<?php if(isset($email)) {echo $email; } ?>" />
			                </td>
			            </tr>
			            <tr>	
			                <td></td>
			                <td>
			                	 <br />
			               		 <input type="submit" name="forminscription" value="envoyer" /> 
			                </td>             
			            </tr>
			        </table> 
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