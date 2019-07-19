<fieldset>
	<legend><?php echo __('Nuevo Usuario'); ?></legend>
	<?php echo $this->Form->create('User', array('class' => 'form-horizontal',
		'inputDefaults'=>array('div' => array('class' => 'form-group'),'between' => '<div class="col-sm-6">','after' => '</div>','class'=>'form-control input-xs','error' => array('attributes' => array('wrap' => 'span', 'class' => 'help-inline'))))); ?>
		<?php
		echo $this->Form->input('group_id',array('label'=>array('class'=>'control-label col-sm-2'),'empty' => 'Seleccionar'));
		echo $this->Form->input('nombres',array('label'=>array('class'=>'control-label col-sm-2')));
		echo $this->Form->input('apellido_paterno',array('label'=>array('class'=>'control-label col-sm-2')));
		echo $this->Form->input('apellido_materno',array('label'=>array('class'=>'control-label col-sm-2')));
		echo $this->Form->input('dni',array('label'=>array('class'=>'control-label col-sm-2'),'type'=>'text'));
		echo $this->Form->input('sexo',array('label'=>array('class'=>'control-label col-sm-2'),'options'=> $a_sexo,'empty' => 'Seleccionar'));
		echo $this->Form->input('fecha_nacimiento',array('label'=>array('class'=>'control-label col-sm-2'),'type' => 'text','placeholder'=>'YYYY-MM-DD'));	
		echo $this->Form->input('username',array('label'=>array('class'=>'control-label col-sm-2')));
		echo $this->Form->input('password',array('label'=>array('class'=>'control-label col-sm-2')));		
		echo $this->Form->input('estado',array('label'=>array('class'=>'control-label col-sm-2'),'options'=> $a_estados,'empty' => 'Seleccionar'));
		echo $this->Form->input('celular',array('label'=>array('class'=>'control-label col-sm-2'),'type'=>'text'));
		echo $this->Form->input('telefono',array('label'=>array('class'=>'control-label col-sm-2'),'type'=>'text'));
		echo $this->Form->input('email',array('label'=>array('class'=>'control-label col-sm-2')));
	?>
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
					<?php echo $this->Form->button('Guardar', array('type' => 'submit','class'=>'btn btn-success'));  ?>
		</div>
	</div>
		<?php echo $this->Form->end(); ?>
</fieldset>