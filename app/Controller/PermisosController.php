<?php
class PermisosController extends AppController {

	public $uses = array();

	public function aplicaciones() {
	    $conditions = array('Aro.lft >='=>'1','Aro.rght <='=>'4');
	    $conditions = null;
	    $data = $this->Acl->Aco->generateTreeList($conditions,'{n}.Aco.id','{n}.Aco.alias','|');
	    //pr($data);
	    $this->set(compact('data'));
	}
	
	public function index(){
	    $params = array('conditions' => array('Aro.model' => 'Group'),'recursive' => 1);	    
	    $accesos = $this->Acl->Aro->find('all', $params);
	    $data = null;
		foreach ($accesos as $row){
		    $id = $row['Aro']['id'];
		    $data[$id] = $row['Aro']['alias'];
		    if (empty($row['Aco'])){
		        $row['Aco'][] = array('id'=>1,
		                            'parent_id' => '',
		                            'alias' => 'controllers',
		                            'Permission' => array('_create' => -1));
		    }
		    $a_acos =$row['Aco'];
	        foreach ($a_acos as $a_aco){
	            if ($a_aco['parent_id'] == 1 || $a_aco['parent_id'] == ''){	                
	                $id1 = $id.'|'.$a_aco['id'];
	                $data[$id1] = '|'.$a_aco['alias'].' '.$a_aco['Permission']['_create'];
	                $params = array('conditions' => array('Aco.parent_id' => $a_aco['id']),'recursive' => 0);
	                $a_acciones = $this->Acl->Aco->find('all', $params);
	                foreach ($a_acciones as $a_accion){
	                    $id2 = $id1.'|'.$a_accion['Aco']['id'];
	                    $ruta = $a_aco['alias'].'/'.$a_accion['Aco']['alias'];	                    
	                    if(strpos($ruta,'controllers')=== false){
	                        $ruta = 'controllers/'.$ruta;
	                    }	                    
	                    $_create =$this->Acl->check(array('model' => 'Group', 'foreign_key' => $row['Aro']['foreign_key']), $ruta);
	                    if(empty($_create)){
	                        $_create = -1;
	                    }
	                    $data[$id2] = '||'.$a_accion['Aco']['alias'].' '.$_create;
	                }
	            }
		    }
		}
		//pr($data);
		$this->set(compact('data'));
	}	
	
	public function acceso($tipo,$aro_id,$aco_id){
	    $params = array('conditions' => array('Aco.id' => $aco_id),'recursive' => 0);	    
	    $data = $this->Acl->Aco->find('first', $params);
	    
	    $conditions = array('Aco.lft <='=>$data['Aco']['lft'],'Aco.rght >='=>$data['Aco']['lft']);
	    $data = $this->Acl->Aco->generateTreeList($conditions,'{n}.Aco.id','{n}.Aco.alias',null);
	    $alias = implode('/', $data);
	    
	       App::import('Model','Group');
	       $this->Group = new Group();
	       $this->Group->id = $aro_id;
	       if ($tipo == 1){
	           $this->Acl->allow($this->Group, $alias);
	       }
	       if ($tipo == 0){
	           $this->Acl->deny($this->Group, $alias);
	       }
	       $this->Flash->success(__('Accesos modificados'));
	       return $this->redirect(array('action' => 'index'));
	}
		
	public function add($aco_id){
	   	    
	    if ($this->request->is(array('post', 'put'))) {
	        $parent_id = $this->request->data['Permisos']['parent_id'];
	        $alias = $this->request->data['Permisos']['alias'];
	        $this->Acl->Aco->create(array('parent_id' => $parent_id, 'alias' => $alias));
	        $this->Acl->Aco->save();
	        $this->Flash->success(__('Aplicacion creado'));
	        return $this->redirect(array('action' => 'aplicaciones'));
	    }else{
    	    $params = array('conditions' => array('Aco.id' => $aco_id),'recursive' => 0);	    
    	    $aco = $this->Acl->Aco->find('first', $params);    	    
    	    $conditions = array('Aco.lft <='=>$aco['Aco']['lft'],'Aco.rght >='=>$aco['Aco']['lft']);
    	    $data = $this->Acl->Aco->generateTreeList($conditions,'{n}.Aco.id','{n}.Aco.alias',null);
    	    $alias = implode('/', $data);
    	    $this->set(compact('aco_id','alias'));
	    }
	}
	
	public function edit($aco_id){
		if ($this->request->is(array('post', 'put'))) {
	        $id = $this->request->data['Permisos']['id'];
	        $alias = $this->request->data['Permisos']['alias'];
	        $this->Acl->Aco->save(array('id' => $id, 'alias' => $alias));
	        $this->Flash->success(__('Aplicacion editado'));
	        return $this->redirect(array('action' => 'aplicaciones'));
	    }else{
    	    $params = array('conditions' => array('Aco.id' => $aco_id),'recursive' => 0);	    
    	    $aco = $this->Acl->Aco->find('first', $params);    	    
    	    $conditions = array('Aco.lft <='=>$aco['Aco']['lft'],'Aco.rght >='=>$aco['Aco']['lft']);
    	    $data = $this->Acl->Aco->generateTreeList($conditions,'{n}.Aco.id','{n}.Aco.alias',null);
    	    $alias = implode('/', $data);
    	    $this->set(compact('aco','alias'));
	    }
	}
	
	public function delete($aco_id){
	        $this->Acl->Aco->delete(array('id' => $aco_id));
	        $this->Flash->success(__('Aplicacion eliminada'));
	        return $this->redirect(array('action' => 'aplicaciones'));
	}
}