<div class="row">
	<div class="col-md-12">	
        <div class="panel panel-group" id="main">
        
        	<div class="panel-body">
        		        		
        		<?php if (!empty($a_categoria)):?>
        		<div class="panel-collapse panel-scroll-categoria" id="accordion2">        			
        			<div class="panel panel-default">					
        					<div class="panel-heading">
        						<h4 class="panel-title">
        							<a data-toggle="collapse" data-parent="#accordion2" href="#collapse2">
        								<span class="glyphicon glyphicon-folder-close text-primary"></span>&nbsp;Categorias
        							</a>
        						</h4>
        					</div>
        					<div id="collapse2" class="panel-collapse collapse">
        						<div class="panel-body">
        							<table class="table table-condensed">
        								<tbody>
        									<?php $a_categoria_id_principal = array('1','2','10','31','32','39','40','50','48','44','36','29'); ?>
        									<?php foreach ($a_categoria as $Categoria):?>
        									<tr>
        										<td>                                        
        											<?php $categoria_nombre = $this->App->nombreMostrar($Categoria['Categoria']['nombre'],26); ?>
        											<?php $categoria_nombre = (in_array($Categoria['Categoria']['id'], $a_categoria_id_principal))?'<strong class="text-danger">'.$categoria_nombre.'</strong>':$categoria_nombre ?>
        											<?php        											     
        											     echo $this->Html->link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-list text-primary')).'&nbsp;'.$categoria_nombre,
        											            array('controller'=>'productos', 'action'=>'buscador','slug' =>  $Categoria['Categoria']['seo_url']),array('escape'=>false,'class'=>'menu-level2') ); ?>
        										</td>
        									</tr>
        									<?php endforeach;?>
        								</tbody>
        							</table>
        						</div>
        					</div>
        			</div>
        		</div>
        		<?php endif; ?>
        		
        		<?php if (!empty($a_subcategorias)):?>
        		<br>
        		<div class="panel-collapse panel-scroll-subcategoria" id="accordion3">
        			<div class="panel panel-default">					
        					<div class="panel-heading">
        						<h4 class="panel-title">
        							<a data-toggle="collapse" data-parent="#accordion3" href="#collapse3">
        								<span class="glyphicon glyphicon-folder-close text-primary"></span>&nbsp;Subcategorias
        							</a>
        						</h4>
        					</div>
        					<div id="collapse3" class="panel-collapse collapse">
        						<div class="panel-body">
        							<table class="table table-condensed">
        								<tbody>
        									<?php foreach ($a_subcategorias as $a_subcategorias):?>
        									<tr>
        										<td>                                        
        											<?php
        											echo $this->Html->link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-list text-primary')).'&nbsp;'.$this->App->nombreMostrar($a_subcategorias['Subcategoria']['nombre'],26).' ('.$a_subcategorias['Subcategoria']['productos'].')',
        											            array('controller'=>'productos', 'action'=>'buscador','slug' =>  $a_subcategorias['Categoria']['seo_url'], 'slug2' => $a_subcategorias['Subcategoria']['seo_url']),array('escape'=>false,'class'=>'menu-level2') ); ?>
        										</td>
        									</tr>
        									<?php endforeach;?>
        								</tbody>
        							</table>
        						</div>
        					</div>
        			</div>
        		</div>
        		<?php endif; ?>
        		
        		<?php if (!empty($a_marcas)):?>
        		<br>
        		<div class="panel-collapse panel-scroll-marca" id="accordion1">
        			<div class="panel panel-default">					
        					<div class="panel-heading">
        						<h4 class="panel-title">
        							<a data-toggle="collapse" data-parent="#accordion1" href="#collapse1">
        								<span class="glyphicon glyphicon-folder-close text-primary"></span>&nbsp;Marcas
        							</a>
        						</h4>
        					</div>
        					<div id="collapse1" class="panel-collapse collapse">
        						<div class="panel-body">
        							<table class="table table-condensed">
        								<tbody>
        									<?php foreach ($a_marcas as $marca_id => $marca):?>
        									<tr>
        										<td>
        											<?php if (!empty($categoria_id) && !empty($subcategoria_id)):?>
        												<?php  echo $this->Html->link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-list text-primary')).'&nbsp;'.$this->App->nombreMostrar($marca['Marca']['nombre'],26).' ('.$marca['Marca']['productos'].')',
        											            array('controller'=>'productos', 'action'=>'buscador','slug' =>  $this->request->params['pass']['0'],'slug2' => $this->request->params['pass']['1'],'slug3' =>  $marca['Marca']['seo_url']),array('escape'=>false,'class'=>'menu-level2') ); ?>
        											<?php elseif(!empty($categoria_id) && empty($subcategoria_id)):?>
        												<?php  echo $this->Html->link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-list text-primary')).'&nbsp;'.$this->App->nombreMostrar($marca['Marca']['nombre'],26).' ('.$marca['Marca']['productos'].')',
        											            array('controller'=>'productos', 'action'=>'buscador','slug' =>  $this->request->params['pass']['0'],'slug2' => $marca['Marca']['seo_url']),array('escape'=>false,'class'=>'menu-level2') ); ?>
        											<?php else: ?>
        												<?php  echo $this->Html->link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-list text-primary')).'&nbsp;'.$this->App->nombreMostrar($marca['Marca']['nombre'],26).' ('.$marca['Marca']['productos'].')',
        											            array('controller'=>'productos', 'action'=>'buscador','slug' =>  $marca['Marca']['seo_url']),array('escape'=>false,'class'=>'menu-level2') ); ?>
        											<?php endif;?>
        											                                        
        											
        										</td>
        									</tr>
        									<?php endforeach;?>
        								</tbody>
        							</table>
        						</div>
        					</div>
        			</div>
        		</div>
        		<?php endif; ?>
        	</div>
        	
        </div>        
    </div>
</div>