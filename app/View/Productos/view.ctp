<h2><?php echo __('Producto'); ?></h2>
	<dl class="dl-horizontal">
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($producto['Producto']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Codigo'); ?></dt>
		<dd>
			<?php echo h($producto['Producto']['codigo']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Nombre'); ?></dt>
		<dd>
			<?php echo h($producto['Producto']['nombre']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Descripcion'); ?></dt>
		<dd>
			<?php echo h($producto['Producto']['descripcion']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Estado'); ?></dt>
		<dd>
			<?php echo h($producto['Producto']['estado']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Subcategoria'); ?></dt>
		<dd>
			<?php echo $this->Html->link($producto['Subcategoria']['nombre'], array('controller' => 'subcategorias', 'action' => 'view', $producto['Subcategoria']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Marca'); ?></dt>
		<dd>
			<?php echo $this->Html->link($producto['Marca']['nombre'], array('controller' => 'marcas', 'action' => 'view', $producto['Marca']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Imagen'); ?></dt>
		<dd>
			<?php echo h($producto['Producto']['imagen']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Stock'); ?></dt>
		<dd>
			<?php echo h($producto['Producto']['stock']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Precio'); ?></dt>
		<dd>
			<?php echo h($producto['Producto']['precio']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Creador'); ?></dt>
		<dd>
			<?php echo h($producto['Producto']['creador']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Creado'); ?></dt>
		<dd>
			<?php echo h($producto['Producto']['creado']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modificador'); ?></dt>
		<dd>
			<?php echo h($producto['Producto']['modificador']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modificado'); ?></dt>
		<dd>
			<?php echo h($producto['Producto']['modificado']); ?>
			&nbsp;
		</dd>
	</dl>
