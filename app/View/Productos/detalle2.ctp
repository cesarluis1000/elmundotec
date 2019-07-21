<style>
    #float-cta {
	position: fixed;
	bottom: 35px;
	right: 35px;
	z-index: 9999999999
}
#float-cta a {
	display: inline-block;
	background-color: #25d366;
	-webkit-border-radius: 50%;
	-moz-border-radius: 50%;
	-ms-border-radius: 50%;
	border-radius: 50%;
	width: 55px;
	height: 55px;
	color: #fff;
	-webkit-box-shadow: 3px 3px 0px 0px rgba(0, 0, 0, 0.3);
	-moz-box-shadow: 3px 3px 0px 0px rgba(0, 0, 0, 0.3);
	box-shadow: 3px 3px 0px 0px rgba(0, 0, 0, 0.3);
	display: flex;
	align-items: center;
	justify-content: center;
	font-size: 30px;
	transition: 0.3s
}
#float-cta a:hover,
#float-cta a:focus {
	text-decoration: none;
	background-color: #128c7e
}
#float-cta a .fa-times,
#float-cta a .fa-whatsapp {
	transition: 0.3s
}
#float-cta a .fa-times {
	visibility: hidden;
	opacity: 0;
	display: none
}
#float-cta a.open .fa-times {
	visibility: visible;
	opacity: 1;
	display: block
}
#float-cta a.open .fa-whatsapp {
	visibility: hidden;
	opacity: 0;
	display: none
}
#float-cta span {
	position: absolute;
	left: -150px;
	width: 190px;
	top: 16px;
	background-color: #999;
	color: #fff;
	padding: 5px 3px;
	-webkit-border-radius: 15px;
	-moz-border-radius: 15px;
	-ms-border-radius: 15px;
	border-radius: 15px;
	text-align: center;
	letter-spacing: 0.5px;
	opacity: 0;
	transition: 0.3s;
	visibility: hidden
}
#float-cta .whatsapp-msg-container {
	visibility: hidden;
	position: absolute;
	right: 0;
	bottom: -20px;
	opacity: 0;
	transform: translateY(-70px);
	width: 300px;
	overflow: hidden;
	-webkit-box-shadow: 3px 3px 3px 0px rgba(0, 0, 0, 0.1);
	-moz-box-shadow: 3px 3px 3px 0px rgba(0, 0, 0, 0.1);
	box-shadow: 3px 3px 3px 0px rgba(0, 0, 0, 0.1);
	-webkit-border-radius: 10px;
	-moz-border-radius: 10px;
	-ms-border-radius: 10px;
	border-radius: 10px;
	background-color: #fff;
	transition: 0.3s
}
#float-cta .whatsapp-msg-container.open {
	visibility: visible;
	bottom: 0;
	opacity: 1
}
#float-cta .whatsapp-msg-header {
	text-align: center;
	background-color: #25d366;
	color: #fff;
	padding: 10px
}
#float-cta .whatsapp-msg-header h6 {
	font-weight: 700;
	font-size: 16px;
	font-size: 1rem;
	margin: 0
}
#float-cta .whatsapp-msg-body {
	padding: 5px
}
#float-cta .whatsapp-msg-body textarea {
	width: 100%;
	height: 200px;
	border: none;
	padding: 15px
}
#float-cta .whatsapp-msg-body textarea.placeholder,
#float-cta .whatsapp-msg-body textarea::placeholder {
	color: lightgray;
	font-size: 14px
}
#float-cta .whatsapp-msg-footer {
	text-align: center;
	background-color: #fff;
	border-top: 1px solid lightgray;
	padding: 5px
}
#float-cta .btn-whatsapp-send {
	display: block;
	width: 100%;
	border: 2px solid #25d366;
	font-weight: 700;
	color: #fff;
	background-color: #25d366;
	padding: 7px 15px;
	transition: 0.3s
}
#float-cta .btn-whatsapp-send:hover {
	background-color: #fff;
	color: #25d366
}
#float-cta:hover span {
	opacity: 1;
	left: -200px;
	visibility: visible
}
#float-cta.open span {
	display: none
}
</style>
<div itemscope itemtype="http://schema.org/Product" class="row">
	<meta itemprop="url" content="<?php echo $this->Html->url( null, true ); ?>">
  	<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 text-center">
  		<br>
  		<?php if($producto['Producto']['imagen'] == 3):?>
    		<?php    	
        	echo $this->Html->image('elmundotec_producto.png', ['class' => 'thumbnail img-responsive sin_imagen','alt' => 'Brownies','itemprop' => 'image']);
        	?>
 		<?php else: ?>
        	<?php    	
        	echo $this->Html->image('productos/'.strtolower($producto['Producto']['codigo']).'.jpg', 
        	                   ['class' => 'thumbnail img-responsive','alt' => 'Brownies','itemprop' => 'image', 'fullBase' => true]);
        	?>
    	<?php endif; ?>
	</div>
	
    <div id="float-cta">
        <span>Envianos un whatsapp!</span>
        <a href="javascript:void(0);">
            <i class="fa fa-whatsapp" aria-hidden="true"></i>
            <i class="fa fa-times" aria-hidden="true"></i>
        </a>
        <div class="whatsapp-msg-container">
            <div class="whatsapp-msg-header">
                <h5>WhatsApp Chat</h5>
            </div>
            <div class="whatsapp-msg-body">
                <textarea name="whatsapp-msg" class="whatsapp-msg-textarea" placeholder="Hola, podés consultar vía whatsapp...">Hola! Quisiera más información sobre el producto: <?php echo h($producto['Producto']['nombre']); ?> Código: <?php echo h($producto['Producto']['id']); ?> Stock: <?php echo h($producto['Producto']['stock']); ?>
                </textarea>
            </div>
            <div class="whatsapp-msg-footer">
                <button type="button" class="btn-whatsapp-send">Enviar</button>
            </div>
        </div>
    </div>
	
  	<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
  		<br>
  		<h4 itemprop="name"><?php echo h($producto['Producto']['nombre']); ?></h4>
      	<dl>
    		<dt><?php echo 'Nro de Producto'; ?></dt>
    		<dd>
    			<span itemprop="productID"><?php echo h($producto['Producto']['id']); ?></span>
    			&nbsp;
    		</dd>
    		<dt><?php echo __('Descripcion'); ?></dt>
    		<dd>
    			<span itemprop="description"><?php echo $producto['Producto']['descripcion']; ?></span>
    			&nbsp;
    		</dd>		
    		<dt><?php echo __('Categoria'); ?></dt>
    		<dd>
    			<span itemprop="model"><?php echo $producto['Categoria']['nombre']; ?></span>
    			&nbsp;
    		</dd>		
    		<dt><?php echo __('Subcategoria'); ?></dt>
    		<dd>
    			<?php echo $producto['Subcategoria']['nombre']; ?>
    			&nbsp;
    		</dd>
    		<dt><?php echo __('Marca'); ?></dt>
    		<dd>
    			<span itemprop="brand"><?php echo $producto['Marca']['nombre']; ?></span>
    			&nbsp;
    		</dd>
    		<dt><?php echo __('Cod Proveedor'); ?></dt>
    		<dd>
    			<span itemprop="brand"><?php echo $producto['Producto']['codigo']; ?></span>
    			&nbsp;
    		</dd>
    		<dt><?php echo __('Stock'); ?></dt>
    		<dd>
    			<?php echo h($producto['Producto']['stock']); ?>
    			&nbsp;
    		</dd>
    		<dt><?php echo __('Precio'); ?></dt>
    		<dd  itemprop="offers" itemscope itemtype="http://schema.org/Offer">				
    			<?php 
            	   $valor =  $producto['Producto']['precio'];
            	   switch($valor){
            	       case $valor < 100: $incremento=1.10; break;
            	       case $valor < 200: $incremento=1.08; break;
            	       case $valor < 300: $incremento=1.06; break;
            	       default          : $incremento=1.05;
            	   }
            	   $precio = $producto['Producto']['precio']*1.18*$incremento;
            	   $precio = number_format($precio, 0, ',', ' '); 
            	?>
                
    			<?php $hoy = date("Y-m-d H:i:s"); ?>
    			<?php if (isset($producto['Promocion']) && $hoy <= $producto['Promocion']['fecha_fin']): ?>
    				<strong class="text-muted">
    					<del>
        					<span class="h4">S/.</span>
        					<span class="h4"><?php echo $precio; ?></span>
    					</del>
    				</strong>
        			<?php 
                    	   $precio_promocion = $producto['Promocion']['precio']*1.18*1.08;
                    	   $precio_promocion = number_format($precio_promocion, 0, '.', ''); 
                    ?>
                    <br>
        			<strong class="text-price">
        				<span class="h3" itemprop="priceCurrency" content="PEN" class="text-price">S/.</span>
        				<span class="h3" itemprop="price"><?php echo $precio_promocion; ?></span>
        				<br>
        				<span class="h6" ><?php echo 'Hasta : '.substr($producto['Promocion']['fecha_fin'], 0,10); ?></span>
        			</strong>
    			<?php else: ?>
    			<strong class="text-price">
        			<span class="h3" itemprop="priceCurrency" content="PEN" class="text-price">S/.</span>
        			<span class="h3" itemprop="price" class="text-price"><?php echo $precio; ?></span>
        		</strong>	
    			<?php endif; ?>
    			&nbsp;
    			<br>
    			<br>
    		</dd>
    		<?php if(empty($currentUser)): ?>
    		<dt><?php echo __('Para ver promociones'); ?>&nbsp;<?php echo $this->Form->button($this->Html->tag('span', '', array('class' => 'fa fa-facebook-official')).'&nbsp'.'iniciar con facebook', array('type' => 'button','class'=>'btn btn-danger','onclick' => 'checkLoginState();'));?></dt>
    		<dd>
    			<br>
    			<div class="fb-like" data-href="<?php echo $this->Html->url( array('controller'=>'productos', 'action'=>'detalle','slug' => 'producto','id' =>$producto['Producto']['id']), true ); ?>" data-layout="button_count" data-action="like" data-size="small" data-show-faces="true" data-share="true"></div>
    			<br>
    			<br>
    		</dd>
    		<?php endif ?>
    		<dt><?php echo __('Caracteristicas'); ?></dt>
    		<dd>
    			<?php echo $producto['Producto']['caracteristicas']; ?>
    			&nbsp;
    		</dd>
    	</dl>
  	
  	
	</div>
</div>

	
