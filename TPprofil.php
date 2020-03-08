<?php
session_start();  //demarre session
echo '<pre>',
print_r($_SESSION);
echo '</pre>';
?>
<?php
					$bdd = new PDO('mysql:host=localhost;dbname=tpinscription;charset=utf8', 'root', '');
					$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

					if(isset($_GET['id']) AND $_GET['id'] > 0)
					{
						$getid = intval($_GET['id']); //securise le get 
						$requser = $bdd->prepare('SELECT * FROM membres WHERE id = ?');
						$requser->execute(array($getid));
						$userinfo = $requser->fetch();



?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Profil</title>
    </head>
        <body>
        	<div align="center">
                <h1>Profil de <?php echo htmlspecialchars($userinfo['pseudo']); ?></h1>
                <br />
 				Pseudo : <?php echo htmlspecialchars($userinfo['pseudo']); ?>
 				<br />
 				Mail : <?php echo htmlspecialchars($userinfo['email']); ?>
 				<br />
               
			      
	                <?php
	               	if(isset($_SESSION['id']) AND $userinfo['id'] == $_SESSION['id'])
	               	{

	               	?>
	               	<a href="TPeditionprofil.php">Editer mon profil</a>
	               	<a href="TPdeconnexion.php">Se déconnecter</a>
	               	<?php
	               	}

	                ?>
            </div>
				
		</body>
</html>
<?php
}
else
?>
