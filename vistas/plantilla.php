
<!DOCTYPE html>
<html lang="es">
<head>
	<title><?php echo COMPANY ?></title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<link rel="stylesheet" href="<?PHP  echo SERVERURL ?>vistas/css/main.css"> <!--TIENES QUE PONER LA RUTA COMPLETA YA QUE HEMOS UTILIZADO HTACCESS-->
	<!--====== Scripts -->
    <?php include "vistas/modulos/script.php"; ?>
</head>
<body>
<?php 
		$peticionAjax=false;
		require_once "./controladores/viewControler.php";
		
		$vt = new viewControler();
		$vistaR=$vt->obtener_vista_Controler();
		if($vistaR=="login" || $vistaR=="404" ):
			if ($vistaR=="login") {
				require_once "./vistas/contenido/login-view.php";
			}else{
				require_once "./vistas/contenido/404-view.php";
			}
			
		else:
			
			session_start(['name'=>'SBP']);

			//comprabar que  el usuario ha iniciado sesion bien
			require_once "./controladores/LoginControler.php";
			$lc= new LoginControler();
			if (!isset($_SESSION['token_sbp']) || !isset($_SESSION['usuario_sbp'])) {
				$lc->force_logut_controler();
			}

?>

		<!-- SideBar -->
		<?php include "./vistas/modulos/sliderbar.php"; ?>
		<!-- Content page-->
		<section class="full-box dashboard-contentPage">

			<!-- NavBar -->
			<?php include "./vistas/modulos/navbar.php"; ?>

			<!--content page -->
			<?php require_once $vistaR;  ?>	

		</section>	  
		<?php include "./vistas/modulos/logoutScript.php"; ?>
		<?php endif; ?>
		<script>
		$.material.init();
		</script>

</body>
</html>