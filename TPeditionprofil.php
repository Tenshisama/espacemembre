<?php
session_start();
					$bdd = new PDO('mysql:host=localhost;dbname=tpinscription;charset=utf8', 'root', '');
					$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

					if(isset($_SESSION['id']))
					{
						$requser = $bdd->prepare("SELECT * FROM membres WHERE id = ?");
						$requser->execute(array($_SESSION['id']));
						$user = $requser->fetch();

						if(isset($_POST['newpseudo']) AND !empty($_POST['newpseudo']) AND $_POST['newpseudo'] != $user['pseudo'])
						{
							$newpseudo = htmlspecialchars($_POST['newpseudo']);
							$insertpseudo = $bdd->prepare("UPDATE membres SET pseudo = ? WHERE id= ?");
							$insertpseudo->execute(array($newpseudo, $_SESSION['id']));
							header('Location: TPprofil.php?id='.$_SESSION['id']);

						}
							if(isset($_POST['newmail']) AND !empty($_POST['newmail']) AND $_POST['newmail'] != $user['email'])
						{
							$newmail = htmlspecialchars($_POST['newmail']);
							$insertmail = $bdd->prepare("UPDATE membres SET email = ? WHERE id= ?");
							$insertmail->execute(array($newmail, $_SESSION['id']));
							header('Location: TPprofil.php?id='.$_SESSION['id']);

						}
							if(isset($_POST['newmdp1']) AND !empty($_POST['newmdp1']) 
							AND (isset($_POST['newmdp2']) AND !empty($_POST['newmdp2'])))
						{  
							$pass = htmlspecialchars($_POST['newmdp1']);
							$passverif = htmlspecialchars($_POST['newmdp2']);

							$hashedpass = password_hash($pass, PASSWORD_DEFAULT);

							if($pass == $passverif)
							{
								$insertmdp = $bdd->prepare("UPDATE membres SET pass =? WHERE id =?");
								$insertmdp->execute(array($hashedpass, $_SESSION['id']));
								header('Location: TPprofil.php?id='.$_SESSION['id']);

							}
							else
						{
							$msg = "Mot de passes différents";
						}
						}

						if(isset($_FILES['avatar']) AND !empty($_FILES['avatar']['name']))
						{  $taillemax = 2097152;
							$extensionvalides = array('jpg', 'jpeg', 'gif', 'png');
							if($_FILES['avatar']['size'] <= $taillemax) 
							{ 
								$extensionupload = strtolower(substr(strrchr($_FILES['avatar']['name'], '.'), 1));
								if(in_array($extensionupload, $extensionvalides))	
								{
									$chemin = "membres/avatars/".$_SESSION['id'].".".$extensionupload;
									$resultat = move_uploaded_file($_FILES['avatar']['tmp_name'], $chemin);
									if($resultat)
									{
										$updateavatar = $bdd->prepare('UPDATE membres SET avatar = :avatar WHERE id = :id');
										$updateavatar->execute(array(
										'avatar' => $_SESSION['id'].".".$extensionupload,
										'id' => $_SESSION['id']));
										header('Location: TPprofil.php?id='.$_SESSION['id']);
									}
									else
									{
										$msg = "Erreur durant l'importation de votre photo.";
									}
								}
								else
								{
									$msg ="Votre photo doit être au format jpg, jpeg, gif ou png.";
								}
							}
							else
							{ $msg ="la photo ne doit pas dépasser 2Mo"; }
//verifier que cela n'empêche pas les messages d'erreur précédent pour l'avatar de s'afficher , ce n'est pas le cas
						if(isset($_POST['newpseudo']) AND $_POST['newpseudo'] == $user['pseudo']) 
						{
							header('Location: TPprofil.php?id='.$_SESSION['id']);
						}
					}


?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Edition du Profil</title>
    </head>
        <body>
        	<div align="center">
               <h2>Editer le profil</h2>
	    		<form method="POST" action="" enctype="multipart/form-data">

	    			<label>Pseudo:</label>
	    			<input type="text" name="newpseudo" placeholder="Nouveau pseudo" value="<?php echo $user['pseudo']; ?>" /><br />
	    			<label>Mail:</label>
	    			<input type="mail" name="newmail" placeholder="Mail" value="<?php echo $user['email']; ?>" /><br />
	    			<label>Mot de passe:</label>
	    			<input type="password" name="newmdp1" placeholder="mot de passe" /><br />
	    			<label>Confirmation mot de passe:</label>
	    			<input type="password" name="newmdp2" placeholder="confirmer nouveau mot de passe" /><br />
	    			<label> Avatar :</label>
	    			<input type="file" name="avatar" /><br />
	    			<input type="submit" value="Confirmer changement" /><br />

	    		</form>
	    		<?php if(isset($msg)) { echo $msg; } ?>
            </div>
				
		</body>
</html>
<?php
}
else
	{
   		header("Location: TPconnexion.php");
  	}
?>