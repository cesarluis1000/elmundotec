<div itemscope itemtype="http://schema.org/Product" class="row">
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
                <h6>WhatsApp Chat</h6>
            </div>
            <div class="whatsapp-msg-body">
                <textarea name="whatsapp-msg" class="whatsapp-msg-textarea" placeholder="Hola, podés consultar vía whatsapp...">Hola! Quisiera más información sobre el Producto: <?php echo h($producto['Producto']['nombre']); ?> Código: <?php echo h($producto['Producto']['id']); ?> Stock: <?php echo h($producto['Producto']['stock']); ?></textarea>
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
    			<span itemprop="sku"><?php echo h($producto['Producto']['id']); ?></span>
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
    			<span><?php echo $producto['Producto']['codigo']; ?></span>
    			&nbsp;
    		</dd>
    		<dt><?php echo __('Stock'); ?></dt>
    		<dd>
    			<?php echo h($producto['Producto']['stock']); ?>
    			&nbsp;
    		</dd>
    		<dt><?php echo __('Precio'); ?></dt>
    		<dd  itemprop="offers" itemscope itemtype="http://schema.org/Offer">				
    			<meta itemprop="url" content="<?php echo $this->Html->url( null, true ); ?>">                
                
    			<?php $hoy = date("Y-m-d H:i:s"); ?>
    			<?php if (isset($producto['Promocion']) && $hoy <= $producto['Promocion']['fecha_fin']): ?>
    				<strong class="text-muted">
    					<del>
        					<span class="h4">S/.</span>
        					<span class="h4"><?php echo $producto['Producto']['precio']; ?></span>
    					</del>
    				</strong>
                    <br>
        			<strong class="text-price">
        				<span class="h3" itemprop="priceCurrency" content="PEN" class="text-price">S/.</span>
        				<span class="h3" itemprop="price"><?php echo $producto['Promocion']['precio'] ; ?></span>
        				<br>
        				<span class="h6" ><?php echo 'Hasta : '.substr($producto['Promocion']['fecha_fin'], 0,10); ?></span>
        			</strong>
    			<?php else: ?>
    			<strong class="text-price">
        			<span class="h3" itemprop="priceCurrency" content="PEN" class="text-price">S/.</span>
        			<span class="h3" itemprop="price" class="text-price"><?php echo $producto['Producto']['precio']; ?></span>
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

	
