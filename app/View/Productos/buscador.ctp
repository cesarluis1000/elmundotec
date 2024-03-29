<?php //pr($productos); ?>
<!-- Buscador -->
<div class="row">
	<div class="col-md-4">
			<?php echo $this->Form->create('Producto', array('type' => 'get',array(),'class' => 'form-inline form-buscador',
			                                                 'inputDefaults'=>array('div' => array('class' => 'form-group'),'class'=>'form-control input-xs','placeholder'=>'Busca Productos')
    			                                             )
			                               ); ?>	
			<?php echo $this->Form->input($campo,array('required' => false,'label'=>false)); ?>
			<?php echo $this->Form->button('Buscar', array('type' => 'submit','class'=>'btn btn-primary btn-xs'));  ?>
			<?php echo $this->Form->button('Limpiar', array('type' => 'reset','class'=>'btn btn-primary btn-xs'));  ?>		
			<?php echo $this->Form->end(); ?>			
     </div>
     <div class="col-md-4">
     	<div class="titulo-buscador">
     	<?php if (!empty($titulo_subcategoria) && !empty($titulo_marca)): ?>
     		<?php echo $this->Html->link(ucfirst(strtolower($titulo_categoria['Categoria']['nombre'])),array('controller'=>'productos', 'action'=>'buscador','slug' =>  $titulo_categoria['Categoria']['seo_url'])) ?>
     		<?php echo ' / '; ?>
     		<?php echo $this->Html->link(ucfirst(strtolower($titulo_subcategoria['Subcategoria']['nombre'])),
     		             array('controller'=>'productos', 'action'=>'buscador','slug' =>  $titulo_categoria['Categoria']['seo_url'],'slug2' =>  $titulo_subcategoria['Subcategoria']['seo_url'])) ?>     		
     		<?php echo ' / '.ucfirst(strtolower($titulo_marca['Marca']['nombre'])) ?>
     	<?php elseif(!empty($titulo_subcategoria)): ?>
     		<?php echo $this->Html->link(ucfirst(strtolower($titulo_categoria['Categoria']['nombre'])),array('controller'=>'productos', 'action'=>'buscador','slug' =>  $titulo_categoria['Categoria']['seo_url'])) ?>
     		<?php echo ' / '.ucfirst(strtolower($titulo_subcategoria['Subcategoria']['nombre'])) ?>
     	<?php elseif(!empty($titulo_categoria) && !empty($titulo_marca)):?>
     		<?php echo $this->Html->link(ucfirst(strtolower($titulo_categoria['Categoria']['nombre'])),array('controller'=>'productos', 'action'=>'buscador','slug' =>  $titulo_categoria['Categoria']['seo_url'])) ?>
     		<?php echo ' / '.ucfirst(strtolower($titulo_marca['Marca']['nombre'])) ?>
     	<?php elseif(!empty($titulo_categoria)):?>
     		<?php echo ucfirst(strtolower($titulo_categoria['Categoria']['nombre']))?>
     	<?php elseif(!empty($titulo_marca)):?>
     		<?php echo ucfirst(strtolower($titulo_marca['Marca']['nombre']))?>
        <?php else:?>
         		Promociones
     	<?php endif;?>     	
     	</div>
     </div>
     <div class="col-md-4 text-right">
    	<nav aria-label="Page navigation">
            <ul class="pagination pagination-sm">
				<?php
					echo $this->Paginator->prev('< ' . __('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
					echo $this->Paginator->numbers(array('separator' => '','tag' => 'li','currentTag' => 'a', 'currentClass' => 'active','first' => 1));
					echo $this->Paginator->next(__('next') . ' >', array('tag' => 'li','currentClass' => 'disabled'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
				?>
            </ul>
        </nav>
    </div>
</div>

  <div class="row display-flex"style="padding-right: 10px; padding-left: 5px;">
  	<?php foreach ($productos as $producto): ?>
    <div class="division col-lg-2 col-md-3 col-sm-3 col-xs-6" style="padding-right: 3px; padding-left: 3px;">
        <div class="panel panel-default thumbnail"><div class="panel-body">
    	<?php if($producto['Producto']['imagen'] == 3):?>
    		<?php    	
        	echo $this->Html->link(
        	    $this->Html->image('elmundotec_producto.png', ['class' => 'sin_imagen img-fluid','alt' => 'Brownies']),
        	    array('controller'=>'productos', 'action'=>'detalle','slug' => $this->App->generateSeoURL($producto['Producto']['nombre']),'id' =>$producto['Producto']['id']),['escapeTitle' => false, 'title' => $producto['Producto']['nombre']]
        	    );
        	?>
 		<?php else: ?>
        	<?php    	
        	echo $this->Html->link(
        	    $this->Html->image('productos/'.strtolower($producto['Producto']['codigo']).'.jpg', ['class' => 'img-fluid','alt' => 'Brownies']),
        	    array('controller'=>'productos', 'action'=>'detalle','slug' => $this->App->generateSeoURL($producto['Producto']['nombre']),'id' =>$producto['Producto']['id']),['escapeTitle' => false, 'title' => $producto['Producto']['nombre']]
        	    );
        	?>
    	<?php endif; ?>
    	<p class="nombre_producto">
    	    <b><?php echo $this->App->nombreMostrar($producto['Marca']['nombre'],35); ?></b></br>
    	    <?php echo $this->App->nombreMostrar($producto['Producto']['nombre'],35); ?>
    	</p>
    		<div style="float: right;" class="bottom-align-text">
        		<?php $hoy = date("Y-m-d H:i:s"); ?>
    			<?php if (isset($producto['Promocion']['fecha_fin']) && $hoy <= $producto['Promocion']['fecha_fin']): ?>				
    					<div class="alert alert-danger" role="alert">
            				<del class="text-price">S/.&nbsp;<?php echo $producto['Producto']['precio']; ?></del>                			
                			<b><span class="text-price">S/.&nbsp;<?php echo $producto['Promocion']['precio']; ?></span></b>
        				</div>
    			<?php else: ?>
        			<span class="text-price">S/.&nbsp;<?php echo $producto['Producto']['precio']; ?></span>
    			<?php endif; ?>
            </div>
    	</div></div>
    </div>
    <?php endforeach; ?>
  </div>

<div class="row text-right">
    <div class="col-md-12">
        <nav aria-label="Page navigation">
            <ul class="pagination pagination-sm">
				<?php
					echo $this->Paginator->prev('< ' . __('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
					echo $this->Paginator->numbers(array('separator' => '','tag' => 'li','currentTag' => 'a', 'currentClass' => 'active','first' => 1));
					echo $this->Paginator->next(__('next') . ' >', array('tag' => 'li','currentClass' => 'disabled'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
				?>
            </ul>
        </nav>
    </div>        
</div>
