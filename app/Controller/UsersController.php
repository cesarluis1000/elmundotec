<?php
App::uses('AppController', 'Controller');
/**
 * Users Controller
 *
 * @property User $User
 * @property PaginatorComponent $Paginator
 */
class UsersController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

	/*public function beforeFilter() {
	    parent::beforeFilter();
	    $this->Auth->allow('initDB'); // We can remove this line after we're finished
	}*/
	
	public function initDB() {
	    $group = $this->User->Group;
	
	    // Allow admins to everything
	    $group->id = 1;
	    $this->Acl->allow($group, 'controllers');
	
	    // allow managers to posts and widgets
	    $group->id = 2;
	    $this->Acl->deny($group, 'controllers');
	    $this->Acl->allow($group, 'controllers/Categorias');
	    $this->Acl->allow($group, 'controllers/Subcategorias');
	    $this->Acl->allow($group, 'controllers/Marcas');
	    $this->Acl->allow($group, 'controllers/Productos');
	
	    // allow users to only add and edit on posts and widgets
	    $group->id = 3;
	    $this->Acl->deny($group, 'controllers');
	    $this->Acl->allow($group, 'controllers/Productos');
	    $this->Acl->deny($group, 'controllers/Productos/edit');
	
	    // allow basic users to log out
	    $this->Acl->allow($group, 'controllers/users/logout');
	
	    // we add an exit to avoid an ugly "missing views" error message
	    echo "all done";
	    exit;
	}
	
	public function login() {
	    //$currentUser = $this->Auth->user();	    pr($currentUser);
	    if ($this->request->is('post') && !isset($this->request->data['id'])) {
	        //pr($this->request);
	        if ($this->Auth->login()) {
	            return $this->redirect($this->Auth->redirectUrl());
	        }
	        $this->Flash->error(__('Your username or password was incorrect.'));
	    }
	    
	    if ($this->request->is('ajax')){
	        //Configure::write('debug', 1);
	        //pr($this->request->data);	        
	        $params = array('conditions' => array('User.facebook' => $this->request->data['id']));
	        $data = $this->User->find('first', $params);
	        
	        if (!isset($data['User'])){
	            $data['User'] = array(
	                'username'         => $this->request->data['name'],
	                'facebook'         => $this->request->data['id'],
	                'nombres'          => $this->request->data['first_name'],
	                'apellido_paterno' => $this->request->data['last_name'],
	                'apellido_materno' => '',
	                'dni'              => '',
	                'sexo'             => ($this->request->data['gender']=='male')?'H':'M',
	                'group_id'         => '6',
	                'estado'           => 'A',
	                'email'            => $this->request->data['email'],
	                'password'         => AuthComponent::password(uniqid(md5($this->request->data['id'])))
	            );
	            //pr($data);
	            //$this->User->set($data);  $this->User->validates();   pr($this->User->invalidFields());
	            $this->User->create();
	            $this->User->save($data);
	            
	            $params = array('conditions' => array('User.facebook' => $this->request->data['id']));
	            $data = $this->User->find('first', $params);
	        }
	        
	        if ($data['User']){
	            //pr($data);
	            //$user = array('username'=>$this->request->data['name'],'password'=>$this->request->data['id']);
	            //$this->request->data = null;
	            //$this->request->data['User'] = $user;
	            //pr($this->request->data);exit;
	            $this->Auth->login($data['User']);            # Manual Login
	            //$this->Auth->redirectUrl();
	            //$json = array( 'response' => 'registered');
	            echo 'registered';
	        }
	        exit;	        
	    }
	    
	}
	
	public function logout() {
	    $this->Flash->success(__('Good-Bye'));
	    return $this->redirect($this->Auth->logout());
	}
	
/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->User->recursive = 0;
		//Si se busca campo displayField del modelo
		$campo = !empty($this->User->displayField)?$this->User->displayField:'id';
		$this->set('campo',$campo);
		if (!empty($this->request->query[$campo])){	    
		    $nombre = $this->request->query[$campo];
			$this->request->data['User'][$campo] = $nombre ;
			$conditions = array('conditions' => array('User.'.$campo.' LIKE' => '%'.$nombre.'%'));
			$this->Paginator->settings = array_merge($this->Paginator->settings,$conditions);
		}
		$this->set('users', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
		$this->set('user', $this->User->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
	    $a_sexo = array('H'=>'Hombre','M'=>'Mujer');
		if ($this->request->is('post')) {
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				$this->Flash->success(__('Se ha guardado el usuario.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('No se pudo guardar el usuario. Por favor, int�ntelo de nuevo.'));
			}
		}
		$groups = $this->User->Group->find('list');
		$this->set(compact('groups','a_sexo'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		$a_sexo = array('H'=>'Hombre','M'=>'Mujer');
		if ($this->request->is(array('post', 'put'))) {
			if ($this->User->save($this->request->data)) {
				$this->Flash->success(__('Se ha guardado el usuario.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('No se pudo guardar el usuario. Por favor, int�ntelo de nuevo.'));
			}
		} else {		    
			$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
			$this->request->data = $this->User->find('first', $options);
			$this->request->data['User']['password'] = '';
		}
		$groups = $this->User->Group->find('list');
		$this->set(compact('groups','a_sexo'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->User->delete()) {
			$this->Flash->success(__('Se ha eliminado el usuario.'));
		} else {
			$this->Flash->error(__('No se pudo eliminar el usuario. Por favor, int�ntelo de nuevo.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
