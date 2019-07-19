<!DOCTYPE html>
<html>
<head>	
	<meta charset="UTF-8">
	<!-- <?php echo $this->Html->charset(); ?> -->
	<html lang="es">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>
		<?php echo $this->fetch('title'); ?>
	</title>	
	<meta name="description" content="Compra por Internet en elmundotec.com de forma segura y fácil, encontrarás miles de productos y OFERTAS increíbles. Envíos a todo el PERÚ." />
	<meta name="keywords" content="elmundotec, comprar online, elmundotec.com, comprar por internet, comprar en peru, comprar online en peru, comprar por internet en peru" />
	
	<?php
		echo $this->Html->meta('icon');
		echo $this->Html->css('bootstrap.min.css');
		echo $this->Html->css('bootstrap-datetimepicker.min.css');
		echo $this->Html->css('bootstrap.icon-large.min.css');
		echo $this->Html->css('font-awesome.min.css');
		echo $this->Html->css('elmundotec.css');
	?>
</head>
<body>
<div id="fb-root"></div>
<script type="text/javascript">

  function validarUsuario() {  
      FB.getLoginStatus(function(response) {
          if(response.status == 'connected') {  
              FB.api('/me', {"fields":"id,name,email,first_name,last_name,birthday,gender,age_range,hometown"}, function(response){
            	  console.log(JSON.stringify(response));
            	  $.post("https://www.elmundotec.com/users/login", 
          	            {data : response}, 
          	            function(postResponse){
          	                if(postResponse == 'registered') {
          	                	console.log(postResponse);
          	                	var loc = "https://www.elmundotec.com"; // or a new URL
          	                	window.location.href = loc + '?n=' + new Date().getTime(); // random number
          	                } else if( postResponse == 'authenticated') {
          	                        // do after login stuff
          	                }
          	            });
              });  
          } else if(response.status == 'not_authorized') {  
                  alert('Debes autorizar la app!');  
          } else {  
              alert('Debes ingresar a tu cuenta de Facebook!');  
          }  
      });  
  }
  
  function checkLoginState() {	  
	  FB.login(function(response){  
          validarUsuario();  
      }, {scope: 'public_profile, email'});       
  }

  (function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/es_ES/sdk.js#xfbml=1&version=v2.9&appId=288605394610781";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));

</script>
	<div id="container">
	
		<div id="header" class="page-header">		
			<h1 id="titulo" class="banner">
			<?php    	
        	echo $this->Html->link(
        	    $this->Html->image('elmundotec_banner.png', ['alt' => 'elmundotec']),
        	    '/?n='.time(),['escapeTitle' => false, 'title' =>'elmundotec']
        	    );
        	?>
        	</h1>        	     	
			<?php echo $this->element('logeado'); ?>
		</div>
		
		<div id="content" class="row">
			<!-- logeado -->
			<?php if(!empty($currentUser)): ?>
				<!-- Menu -->
				<div class="col-md-2">
					<?php echo $this->element('menu'); ?>
				</div>
				<!-- Vista -->
				<div class="index col-md-10">
					<?php echo $this->Flash->render(); ?>
					<?php echo $this->fetch('content'); ?>
				</div>
			<?php else: ?>
				<!-- Vista -->
				<div class="index col-md-12">
					<?php echo $this->Flash->render(); ?>
					<?php echo $this->fetch('content'); ?>
				</div>	
			<?php endif; ?>
		</div>
		
		<div id="footer" class="alert alert-info">
			<?php echo $this->element('informacion'); ?>
		</div>
		
	</div>
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<?php echo $this->Html->script('jquery-3.2.1.min.js'); ?>	
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <?php echo $this->Html->script('bootstrap.min.js'); ?>
    <!-- Bootstrap datetimepicker Plugin -->
    <?php echo $this->Html->script('moment.min.js'); ?>
    <?php echo $this->Html->script('bootstrap-datetimepicker.min.js'); ?>
    <?php echo $this->Html->script('locale/es.min.js'); ?>
	<?php echo $this->Html->script('elmundotec.js'); ?>
</body>
</html>
