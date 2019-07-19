<h2><?php echo __('Productos'); ?></h2>

<!-- Buscador -->
<div class="row">
	<div class="col-md-10">
			<?php echo $this->Form->create('Producto', array('type' => 'get','url' => 'index','class' => 'form-inline','inputDefaults'=>array('div' => array('class' => 'form-group'),'class'=>'form-control input-xs'))); ?>	
			<?php echo $this->Form->input($campo,array('required' => false,'label'=>false)); ?>
			<?php echo $this->Form->button('Buscar', array('type' => 'submit','class'=>'btn btn-primary btn-xs'));  ?>
			<?php echo $this->Form->button('Limpiar', array('type' => 'reset','class'=>'btn btn-primary btn-xs'));  ?>		
			<?php echo $this->Form->end(); ?>	
     </div>
     <div class="col-md-2 text-right">
    		<?php echo $this->Html->link($this->Html->tag('span','', array('class' => 'glyphicon glyphicon-file')).__(' Nuevo'),array('action' => 'add'),array('class' => 'btn btn-success btn-xs','escape'=>false)); ?>
    </div>
</div>

<!-- Paginador y boton Nuevo -->
<?php $this->Paginator->options['url']['?'] = $this->params['url']; ?>
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

<!-- Contenido de los registros y las acciones -->
<div class="table-responsive">
<table class="table table-hover table-condensed">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('codigo'); ?></th>
			<th><?php echo $this->Paginator->sort('nombre'); ?></th>
			<th><?php echo $this->Paginator->sort('estado'); ?></th>
			<th><?php echo $this->Paginator->sort('subcategoria_id'); ?></th>
			<th><?php echo $this->Paginator->sort('marca_id'); ?></th>
			<th><?php echo $this->Paginator->sort('imagen'); ?></th>
			<th><?php echo $this->Paginator->sort('stock'); ?></th>
			<th><?php echo $this->Paginator->sort('precio'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($productos as $producto): ?>
	<tr>
		<td><?php echo h($producto['Producto']['id']); ?>&nbsp;</td>
		<td><?php echo h($producto['Producto']['codigo']); ?>&nbsp;</td>
		<td><?php echo h($producto['Producto']['nombre']); ?>&nbsp;</td>
		<td><?php echo h($producto['Producto']['estado']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($producto['Subcategoria']['nombre'], array('controller' => 'subcategorias', 'action' => 'view', $producto['Subcategoria']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($producto['Marca']['nombre'], array('controller' => 'marcas', 'action' => 'view', $producto['Marca']['id'])); ?>
		</td>
		<td><?php echo h($producto['Producto']['imagen']); ?>&nbsp;</td>
		<td><?php echo h($producto['Producto']['stock']); ?>&nbsp;</td>
		<td><?php echo h($producto['Producto']['precio']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-eye-open')), array('action' => 'view', $producto['Producto']['id']),array('class' => 'btn btn-info btn-xs','escape'=>false)); ?>			
			<?php $a_url = array('controller'=>'productos', 'action'=>'detalle','slug' => $this->App->generateSeoURL($producto['Producto']['nombre']),'id' =>$producto['Producto']['id']) ?>
			<?php echo $this->Html->link($this->Html->tag('span', '', array('class' => 'fa fa-tv')), $a_url,array('class' => 'btn btn-primary btn-xs','escape'=>false)); ?>
			<?php echo $this->Html->link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-edit')), array('action' => 'edit', $producto['Producto']['id']),array('class' => 'btn btn-warning btn-xs','escape'=>false)); ?>
			<?php echo $this->Form->postLink($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-trash')), array('action' => 'delete', $producto['Producto']['id']),array('class' => 'btn btn-danger btn-xs','escape'=>false), __('Are you sure you want to delete # %s?', $producto['Producto']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</tbody>
	</table>	
</div>