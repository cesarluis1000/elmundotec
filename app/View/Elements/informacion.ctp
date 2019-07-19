<div class="row">
  <div class="col-md-4">
  	<b>Servicio al Cliente</b>
  		<ul>
  			<li>
  				<?php  echo $this->Html->link($this->Html->tag('span', '', array('class' => 'fa fa-credit-card-alt')).'&nbsp;'.__('Formas de pago'),
        		          array('controller'=>'pages','action'=>'forma_pago'),array('escape'=>false) ); ?>
  			</li>
  			<li>
  				<?php  echo $this->Html->link($this->Html->tag('span', '', array('class' => 'fa fa-map-marker')).'&nbsp;&nbsp;&nbsp;&nbsp;'.__('Despacho'),
        		          'https://goo.gl/wPS5d3',array('target'=>'_blank','escape'=>false) ); ?>
  			</li>
  			<li>
  				<?php  echo $this->Html->link($this->Html->tag('span', '', array('class' => 'fa fa-handshake-o')).'&nbsp;'.__('Garantía'),
        		          '/',array('escape'=>false) ); ?>
  			</li>
  		</ul>
  </div>
  <div class="col-md-4">
  <b>Contáctanos</b>
  <ul>
      <li><a href="mailto:ventas@elmundotec.com">&nbsp;<span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>&nbsp;ventas@elmundotec.com&nbsp;</a></li>
      <li><a href="tel://998886686">&nbsp;<span class="glyphicon glyphicon-phone" aria-hidden="true"></span>&nbsp;Rpc:998886686&nbsp;</a></li>
      <li><a href="tel://998886686">&nbsp;<span class="fa fa-whatsapp" aria-hidden="true" style="font-size:16px"></span>&nbsp;998886686&nbsp;</a></li>
      <li><a href="tel://993014303">&nbsp;<span class="fa fa-whatsapp" aria-hidden="true" style="font-size:16px"></span>&nbsp;993014303&nbsp;</a></li>
      <li><a href="tel://989231175">&nbsp;<span class="fa fa-whatsapp" aria-hidden="true" style="font-size:16px"></span>&nbsp;989231175&nbsp;</a></li>
  </ul>
  </div>
  <div class="col-md-4">
  	<b>Horario de atención</b>
  	<ul>
  		<li><span class="glyphicon glyphicon-time" aria-hidden="true"></span> Lunes a Domingo: 9:00 am - 11:00 pm</li>
  	</ul>
  	<b>Horario de entrega</b>
  	<ul>
  		<li><span class="glyphicon glyphicon-time" aria-hidden="true"></span> Lunes a Viernes: 9:30 am - 7:00 pm</li>
  		<li><span class="glyphicon glyphicon-time" aria-hidden="true"></span> Sabados: 9:30 am - 1:00 pm</li>
  	</ul>
  </div>
  <div class="col-md-12 text-center"><?php echo 'Copyright ©2014-2017 Desarrollado por el Grupo Hnos Ramos'; ?></div>
</div>