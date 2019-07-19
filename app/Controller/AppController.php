<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
    public $components = array(
        'Acl','RequestHandler',//'DebugKit.Toolbar',
        'Auth' => array(
            'loginAction' => array(
                'controller' => 'users',
                'action' => 'login'
            ),
            'logoutRedirect' => array(
                'controller' => 'users',
                'action' => 'login'
            ),
			//Ruta de logeo
            'loginRedirect' => array(
                'controller' => 'productos',
                'action' => 'buscador'
            ),
            'authorize' => array(
                'Actions' => array('actionPath' => 'controllers')
            )
        ),
         'Paginator', 'Session', 'Flash'
    );
    public $helpers = array('Html', 'Form', 'Session');

	public $a_estados;
	
    function beforeFilter(){
            
        $this->loadModel('Categoria');
        $this->loadModel('Subcategoria');
        $this->loadModel('Marca');
        $this->loadModel('Producto');
        $this->loadModel('Group');
	        
        $this->__setErrorLayout();
		$this->__paginador();
        $this->__checkAuth();
        $this->__ecommerce();
    }
    
    private function __setErrorLayout() {
        //Pagina no encontrada 404
        if ($this->name == 'CakeError') {
            if ($this->request->params['controller']=='productos' && $this->request->params['action']=='buscador'){
                $this->Flash->error(__('Página no encontrada'));
                return $this->redirect(array('controller' => 'productos', 'action' => 'buscador/?nombre='.$this->request->data['Producto']['nombre']));
            }            
            return $this->redirect('/',true);            
        }
    }
    
    private function __ecommerce(){
        //Acciones que utilizarar el layout ecommerce
        $this->acciones_ecommerce = array('buscador','detalle','sitemap','forma_pago');
        if (in_array($this->action, $this->acciones_ecommerce)){
            $this->layout = 'ecommerce';
	        
	        $params = array('fields' => array('subcategoria_id','marca_id'), 'conditions' => array('Producto.precio >='=>'10','Producto.stock >='=>'1'),'recursive' => -1);
	        $a_productos = $this->Producto->find('all',$params);
	        //pr($a_productos);
	        $a_subcategoria_id = Set::classicExtract($a_productos, '{n}.Producto.subcategoria_id');
	        $a_marca_id = Set::classicExtract($a_productos, '{n}.Producto.marca_id');
	        //pr($a_subcategoria_id);
	        $params = array('fields'=>array('categoria_id'), 'conditions' => array('Subcategoria.id'=>$a_subcategoria_id),'recursive' => -1);
	        $a_subcategorias = $this->Subcategoria->find('all',$params);
	        $a_categoria_id = Set::classicExtract($a_subcategorias, '{n}.Subcategoria.categoria_id');
	        //pr($a_categoria_id);
	        
            $params = array('conditions' => array('Categoria.id' => $a_categoria_id, 'Categoria.estado' => 'A'),'order' => 'Categoria.nombre','recursive'=> -1);
            $a_categoria = $this->Categoria->find('all',$params);
            
            //pr($this->request->params);
            $a_subcategorias = array();
            $a_subcategoria_id_cat_sel = null;
            if ($this->action == 'buscador' && !empty($this->request->params['pass'])){
                //pr($this->request->params['pass']);
                $slug = $this->request->params['pass'][0];
                $params = array('conditions' => array('Categoria.seo_url'=>$slug),'recursive' => -1);
                $a_categoria_seo = $this->Categoria->find('first',$params);
                //Si selecciono una categoria aparece subcatetoria
                if(!empty($a_categoria_seo)){
                    
                    $categoria_id = $a_categoria_seo['Categoria']['id'];
                    
                    //subcategorias de esa categoria
                    $params = array('conditions' => array('Subcategoria.estado' => 'A','Subcategoria.categoria_id'=>$categoria_id),'order' => 'Subcategoria.nombre','recursive'=> 0);
                    $a_subcategorias = $this->Subcategoria->find('all',$params);
                    
                    //Lista de subcategoria_id de categoria seleccionada
                    $a_subcategoria_id_cat_sel = Set::classicExtract($a_subcategorias, '{n}.Subcategoria.id');
                    
                    //Todos los productos de las subcategoria de la categoria
                    $params = array('fields' => array('subcategoria_id','marca_id'), 'conditions' => array('Producto.subcategoria_id'=>$a_subcategoria_id_cat_sel,'Producto.precio >='=>'10','Producto.stock >='=>'1','Producto.modificado >='=>date('Y-m-d H:i:s', strtotime("-1 day"))),'recursive' => -1);
                    $a_productos = $this->Producto->find('all',$params);
                    $a_subcategoria_id = Set::classicExtract($a_productos, '{n}.Producto.subcategoria_id');
                    
                    //Solo subcategoria con productos
                    $params = array('conditions' => array('Subcategoria.estado' => 'A','Subcategoria.id'=>$a_subcategoria_id),'order' => 'Subcategoria.nombre','recursive'=> 0);
                    $a_subcategorias = $this->Subcategoria->find('all',$params);
                    
                    //Productos por subcategoria
                    foreach ($a_subcategorias as $id => $subcategoria){
                        $params = array('conditions' => array('Producto.subcategoria_id'=>$subcategoria['Subcategoria']['id'],'Producto.precio >='=>'10','Producto.stock >='=>'1','Producto.modificado >='=>date('Y-m-d H:i:s', strtotime("-1 day"))),'recursive' => -1);
                        $productos_count = $this->Producto->find('count',$params);
                        $a_subcategorias[$id]['Subcategoria']['productos'] = $productos_count;
                    }
                }
                
                //Marca de la subcategoria seleccionada
                if (!empty($this->request->params['pass'][1])){
                    $slug2 = $this->request->params['pass'][1];
                    $params = array('conditions' => array('Subcategoria.seo_url'=>$slug2),'recursive' => -1);
                    $a_subcategoria_seo = $this->Subcategoria->find('first',$params);
                    if (!empty($a_subcategoria_seo)){
                        $a_subcategoria_id_cat_sel = $a_subcategoria_seo['Subcategoria']['id'];
                    }
                }
                
                //Marcas de las subcategoias de la categoria seleccionada
                if (!empty($a_subcategoria_id_cat_sel)){
                    $params = array('fields' => array('subcategoria_id','marca_id'), 'conditions' => array('Producto.subcategoria_id'=>$a_subcategoria_id_cat_sel,'Producto.precio >='=>'10','Producto.stock >='=>'1'),'recursive' => -1);
                    $a_productos = $this->Producto->find('all',$params);
                    $a_marca_id = Set::classicExtract($a_productos, '{n}.Producto.marca_id');
                }
                
            }
            
            $params = array('conditions' => array('Marca.id' => $a_marca_id, 'Marca.estado' => 'A'),'order' => 'Marca.nombre','recursive'=> -1);
            
            $a_marcas = $this->Marca->find('all',$params);     
            foreach ($a_marcas as $id => $marca){
               if (!empty($a_subcategoria_id_cat_sel)){                   
                   $params = array('conditions' => array('Producto.marca_id'=>$marca['Marca']['id'],'Producto.subcategoria_id'=>$a_subcategoria_id_cat_sel,'Producto.precio >='=>'10','Producto.stock >='=>'1','Producto.modificado >='=>date('Y-m-d H:i:s', strtotime("-1 day"))),'recursive' => -1);
               }else{
                   $params = array('conditions' => array('Producto.marca_id'=>$marca['Marca']['id'],'Producto.precio >='=>'10','Producto.stock >='=>'1','Producto.modificado >='=>date('Y-m-d H:i:s', strtotime("-1 day"))),'recursive' => -1);
               }
                $productos_count = $this->Producto->find('count',$params);
                $a_marcas[$id]['Marca']['productos'] = $productos_count;                
            }
            
            //Aplicacion destino mobil
            $is_mobile = false;
            if ($this->request->is('mobile')) {
                $is_mobile = true;
            }
            
            $this->set(compact('is_mobile','a_marcas','a_categoria','a_subcategorias'));
        }
    }
    
    private function __paginador(){
        $this->paginate = array('limit'=>20);
        $a_estados = array('A'=>'Activo','D'=>'Desactivo');
        $s_url = Router::url('/',true);
        $margen = 1.05;
        $igv = 1.18;
        $this->set(compact('a_estados','s_url','margen','igv'));
    }

    private function __checkAuth() {
        $this->Auth->unauthorizedRedirect=FALSE ;
        $this->Auth->authError=__('You are not authorized to access that location.');
        $this->Auth->allow('login','logout','display','migracion_productos','migracion_promociones','buscador','detalle','sitemap','detalle2','forma_pago');
        
        $currentUser = $this->Auth->user();
        $a_menu = array();
        //pr(321);
        if(!empty($currentUser)){
            
            if (!isset($currentUser['Group']['id'])){
                $params = array('conditions' => array('Group.id' => $currentUser['group_id']),'recursive' => -1);
                $group = $this->Group->find('first',$params);
                $currentUser['Group'] = $group['Group'];
            }
           
           $params = array('conditions' => array('Aro.model' => 'Group', 'Aro.foreign_key' => $currentUser['Group']['id']),'order' => 'Aro.lft','recursive' => 0);
           $aro = $this->Acl->Aro->find('first',$params);
           //pr($aro);
           $this->loadModel('Menu');
           $this->Menu->unbindModel(array('belongsTo' => array('ParentMenu')));
           $this->Menu->unbindModel(array('hasMany' => array('ChildMenu')));
           $params1 = array('conditions' => array('Menu.aro_id' => $aro['Aro']['id'],'Menu.parent_id' => ''),'order' => 'Menu.lft');
           $a_menu = $this->Menu->find('all',$params1);
           foreach ($a_menu as $id => $item){
               
               $this->Menu->unbindModel(array('belongsTo' => array('ParentMenu')));
               $this->Menu->unbindModel(array('hasMany' => array('ChildMenu')));
               $params2 = array('conditions' => array('Menu.parent_id' => $item['Menu']['id']),'order' => 'Menu.lft');
               $a_menu1 = $this->Menu->find('all',$params2);
               
               foreach ($a_menu1 as $id1 => $item1){
                  $params3 = array('conditions' => array('Aco.id' => $item1['Aco']['parent_id']),'recursive' => 0);
                  $a_aco_controlador = $this->Acl->Aco->find('first',$params3);
                  $a_menu1[$id1]['Controlador'] = $a_aco_controlador['Aco'];
               }
               
               $a_menu[$id]['Acciones'] = $a_menu1;
           }
        }
        $this->set(compact('a_menu','currentUser'));
    }
    
    
}
