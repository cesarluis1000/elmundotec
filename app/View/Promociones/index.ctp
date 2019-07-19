<h2><?php echo __('Promociones'); ?></h2>

<!-- Buscador -->
<div class="row">
	<div class="col-md-10">
			<?php echo $this->Form->create('Promocion', array('type' => 'get','url' => 'index','class' => 'form-inline','inputDefaults'=>array('div' => array('class' => 'form-group'),'class'=>'form-control input-xs'))); ?>	
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
			<th><?php echo $this->Paginator->sort('producto_id'); ?></th>
			<th><?php echo $this->Paginator->sort('nombre'); ?></th>
			<th><?php echo $this->Paginator->sort('Producto.precio','Precio'); ?></th>
			<th><?php echo $this->Paginator->sort('precio','Promoción'); ?></th>
			<th><?php echo $this->Paginator->sort('fecha_fin'); ?></th>
			<th><?php echo 'Activo'; ?></th>
			<th><?php echo $this->Paginator->sort('estado'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($promociones as $promocion): ?>
	<tr>
		<td><?php echo h($promocion['Promocion']['id']); ?>&nbsp;</td>
		<td>
			<?php echo h($promocion['Producto']['nombre']); ?>
		</td>
		<td><?php echo h($promocion['Promocion']['nombre']); ?>&nbsp;</td>
		<?php 
    	   $precio = $promocion['Producto']['precio']*$igv*$margen;
    	   $precio = number_format($precio, 0, ',', ' '); 
    	?>
		<td><?php echo h($precio); ?>&nbsp;</td>
		<?php 
    	   $precio = $promocion['Promocion']['precio']*$igv*$margen;
    	   $precio = number_format($precio, 0, ',', ' '); 
    	?>
		<td><?php echo h($precio); ?>&nbsp;</td>		
		<td><?php echo h($promocion['Promocion']['fecha_fin']); ?>&nbsp;</td>
		<td><?php echo ( $promocion['Promocion']['fecha_fin'] >= date("Y-m-d H:i:s") )?'Y':'N'; ?>&nbsp;</td>
		<td><?php echo h($promocion['Promocion']['estado']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-eye-open')), array('action' => 'view', $promocion['Promocion']['id']),array('class' => 'btn btn-info btn-xs','escape'=>false)); ?>
			<?php $a_url = array('controller'=>'productos', 'action'=>'detalle','slug' => $this->App->generateSeoURL($promocion['Producto']['nombre']),'id' =>$promocion['Producto']['id']) ?>
			<?php echo $this->Html->link($this->Html->tag('span', '', array('class' => 'fa fa-tv')), $a_url,array('class' => 'btn btn-primary btn-xs','escape'=>false)); ?>
			<?php echo $this->Html->link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-edit')), array('action' => 'edit', $promocion['Promocion']['id']),array('class' => 'btn btn-warning btn-xs','escape'=>false)); ?>
			<?php echo $this->Form->postLink($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-trash')), array('action' => 'delete', $promocion['Promocion']['id']),array('class' => 'btn btn-danger btn-xs','escape'=>false), __('Are you sure you want to delete # %s?', $promocion['Promocion']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</tbody>
	</table>	
</div>