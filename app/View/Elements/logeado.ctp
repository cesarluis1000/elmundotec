<div class="row">
    <div class="col-md-8">
    	<h6>    		
    		<!-- 
    		<a href="mailto:ventas@elmundotec.com">&nbsp;<span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>&nbsp;ventas@elmundotec.com&nbsp;</a>
    		<a href="tel://998886686">&nbsp;<span class="fa fa-whatsapp" aria-hidden="true" style="font-size:16px"></span>&nbsp;998886686&nbsp;</a>
    		 -->     		
       		<a href="tel://998886686">&nbsp;<span class="glyphicon glyphicon-phone" aria-hidden="true"></span>&nbsp;Rpc:998886686&nbsp;</a>       		
       		<a href="https://api.whatsapp.com/send?phone=51998886686&text=Hola%21%20quisiera%20m%C3%A1s%20informaci%C3%B3n%20">&nbsp;<span class="fa fa-whatsapp" aria-hidden="true" style="font-size:16px"></span>&nbsp;998886686&nbsp;</a>
       		<span class="fa fa-bank" aria-hidden="true" style="font-size:16px"></span>&nbsp;Interbank: 041-3102316450 &nbsp;
       		<span class="fa fa-address-card-o" aria-hidden="true" style="font-size:16px"></span>&nbsp;Ruc: 10426840249       		
       	</h6>
    </div>
    <div class="col-md-4">
    	<h6 class="text-right">    	
    	<div class="fb-follow" data-href="https://www.facebook.com/elmundotec/" data-layout="button_count" data-size="small" data-show-faces="false"></div>
        <?php if(!empty($currentUser)): ?>
        	<span class="glyphicon glyphicon-user" aria-hidden="true"></span>
        		<?php echo $currentUser['username'].' - '.$currentUser['Group']['name']; ?>
        		<?php  echo $this->Html->link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-off text-danger')).'&nbsp;'.__('Logout'),array('controller' => 'Users','action' => 'logout/?n='.time()),array('escape'=>false,'class'=>'menu-level2') ); ?>	
        <?php else: ?>
        		<?php echo $this->Form->button($this->Html->tag('span', '', array('class' => 'fa fa-facebook-official')).'&nbsp'.'iniciar con facebook', array('type' => 'button','class'=>'btn btn-primary btn-xs','onclick' => 'checkLoginState();'));?>
        		<!-- 
        		<span class="glyphicon glyphicon-user" aria-hidden="true"></span> Iniciar Sesión
        		 --> 	
        		<?php // echo $this->Html->link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-off text-danger')).'&nbsp;'.__('Login'),array('controller' => 'Users','action' => 'login/?n='.time()),array('escape'=>false,'class'=>'menu-level2') ); ?>	
        <?php endif ?>
        </h6>
    </div>
</div>
