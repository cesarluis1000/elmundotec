<style>
<!--
.float{
	position:fixed;
	width:60px;
	height:60px;
	bottom:40px;
	right:40px;
	background-color:#25d366;
	color:#FFF;
	border-radius:50px;
	text-align:center;
  font-size:30px;
	box-shadow: 2px 2px 3px #999;
  z-index:100;
}

.my-float{
	margin-top:16px;
}
-->
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
  	<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
  		<br>
  		<h4 itemprop="name"><?php echo h($producto['Producto']['nombre']); ?></h4>
      	<dl>
    		<dt><?php echo 'Nro de Producto'; ?></dt>
    		<dd>
    			<span itemprop="productID"><?php echo h($producto['Producto']['id']); ?></span>
    			&nbsp;
    			<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
                <a href="https://api.whatsapp.com/send?phone=51998886686&text=Hola%21%20Quisiera%20m%C3%A1s%20informaci%C3%B3n%20sobre%20el%20producto:%20<?php echo h($producto['Producto']['id']); ?>" class="float" target="_blank">
                <i class="fa fa-whatsapp my-float"></i>
                </a>
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

	
