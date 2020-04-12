<?php

 session_start();
					$bdd = new PDO('mysql:host=localhost;dbname=tpinscription;charset=utf8', 'root', '');
					$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
if(isset($_SESSION['id']) AND !empty($_SESSION['id'])) {

					$msg = $bdd->prepare('SELECT * FROM message WHERE id_destinataire = ?');
					$msg->execute(array($_SESSION['id']));
					$msg_nbr = $msg->rowCount();

?>

<!DOCTYPE html>
<html>
	<head>
		<title>Boite de réception</title>
	</head>
	<body>
	  		<a href="TPenvoi.php">Envoyer un message</a><br /><br />
	  		<h3>Votre boite de réception:</h3>
		    <?php 
		    if($msg_nbr == 0) { echo "Pas de nouveaux messages"; }
		    while($m = $msg->fetch()) {
		    $p_exp = $bdd->prepare('SELECT pseudo FROM membres WHERE id =?');
		    $p_exp->execute(array($m['id_expediteur']));
		    $p_exp = $p_exp->fetch();
		    $p_exp = $p_exp['pseudo'];
		    ?>
		   <b><?= $p_exp ?></b> vous a envoyé: <br />
		    <?= nl2br($m['message']) ?><br />
		    ----------------------<br />


			<?php 
			} 
			?>

	</body>
</html>
<?php } ?>