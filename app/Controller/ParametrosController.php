<?php
App::uses('AppController', 'Controller');
/**
 * Parametros Controller
 *
 * @property Parametro $Parametro
 * @property PaginatorComponent $Paginator
 */
class ParametrosController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'RequestHandler');

	public function sitemap(){
	    $this->layout="empty";
	    Configure::write('debug', 0);
	    $this->RequestHandler->respondAs('xml');
	   //exit;
	}
/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Parametro->recursive = 0;
		//Si se busca campo displayField del modelo
		$campo = !empty($this->Parametro->displayField)?$this->Parametro->displayField:'id';
		$this->set('campo',$campo);
		if (!empty($this->request->query[$campo])){	    
		    $nombre = $this->request->query[$campo];
			$this->request->data['Parametro'][$campo] = $nombre ;
			$conditions = array('conditions' => array('Parametro.'.$campo.' LIKE' => '%'.$nombre.'%'));
			$this->Paginator->settings = array_merge($this->Paginator->settings,$conditions);
		}
		$this->set('parametros', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Parametro->exists($id)) {
			throw new NotFoundException(__('Invalido(a) parametro'));
		}
		$options = array('conditions' => array('Parametro.' . $this->Parametro->primaryKey => $id));
		$this->set('parametro', $this->Parametro->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Parametro->create();
			if ($this->Parametro->save($this->request->data)) {
				$this->Flash->success(__('El/la parametro se ha guardado.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('No se pudo guardar el/la parametro. Por favor, int�ntelo de nuevo.'));
			}
		}
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Parametro->exists($id)) {
			throw new NotFoundException(__('Invalido(a) parametro'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Parametro->save($this->request->data)) {
				$this->Flash->success(__('El/la parametro se ha guardado.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('No se pudo guardar el/la parametro. Por favor, int�ntelo de nuevo.'));
			}
		} else {
			$options = array('conditions' => array('Parametro.' . $this->Parametro->primaryKey => $id));
			$this->request->data = $this->Parametro->find('first', $options);
		}
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Parametro->id = $id;
		if (!$this->Parametro->exists()) {
			throw new NotFoundException(__('Invalid parametro'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Parametro->delete()) {
			$this->Flash->success(__('El/la parametro ha sido borrado.'));
		} else {
			$this->Flash->error(__('El/la parametro no ha sido borrado. Por favor int�ntelo de nuevo.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
