<fieldset>
	<legend><?php echo __('Edit Producto'); ?></legend>
	<?php echo $this->Form->create('Producto', array('class' => 'form-horizontal',
		'inputDefaults'=>array('div' => array('class' => 'form-group'),'between' => '<div class="col-sm-6">','after' => '</div>','class'=>'form-control input-xs','error' => array('attributes' => array('wrap' => 'span', 'class' => 'help-inline'))))); ?>
		<?php
		echo $this->Form->input('id',array('label'=>array('class'=>'control-label col-sm-2')));
		echo $this->Form->input('codigo',array('label'=>array('class'=>'control-label col-sm-2')));
		echo $this->Form->input('nombre',array('label'=>array('class'=>'control-label col-sm-2')));
		echo $this->Form->input('descripcion',array('label'=>array('class'=>'control-label col-sm-2')));
		echo $this->Form->input('estado',array('label'=>array('class'=>'control-label col-sm-2'),'options'=> $a_estados,'empty' => 'Seleccionar'));
		echo $this->Form->input('subcategoria_id',array('label'=>array('class'=>'control-label col-sm-2'),'empty' => 'Seleccionar'));
		echo $this->Form->input('marca_id',array('label'=>array('class'=>'control-label col-sm-2'),'empty' => 'Seleccionar'));
		echo $this->Form->input('imagen',array('label'=>array('class'=>'control-label col-sm-2')));
		echo $this->Form->input('stock',array('label'=>array('class'=>'control-label col-sm-2')));
		echo $this->Form->input('precio',array('label'=>array('class'=>'control-label col-sm-2')));
	?>
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
					<?php echo $this->Form->button('Guardar', array('type' => 'submit','class'=>'btn btn-success'));  ?>
		</div>
	</div>
		<?php echo $this->Form->end(); ?>
</fieldset>