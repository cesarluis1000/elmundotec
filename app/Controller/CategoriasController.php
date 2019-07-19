<?php
App::uses('AppController', 'Controller');
/**
 * Categorias Controller
 *
 * @property Categoria $Categoria
 * @property PaginatorComponent $Paginator
 */
class CategoriasController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Categoria->recursive = 0;
		//Si se busca campo displayField del modelo
		$campo = !empty($this->Categoria->displayField)?$this->Categoria->displayField:'id';
		$this->set('campo',$campo);
		if (!empty($this->request->query[$campo])){	    
		    $nombre = $this->request->query[$campo];
			$this->request->data['Categoria'][$campo] = $nombre ;
			$conditions = array('conditions' => array('Categoria.'.$campo.' LIKE' => '%'.$nombre.'%'));
			$this->Paginator->settings = array_merge($this->Paginator->settings,$conditions);
		}
		$this->set('categorias', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Categoria->exists($id)) {
			throw new NotFoundException(__('Invalido(a) categoria'));
		}
		$options = array('conditions' => array('Categoria.' . $this->Categoria->primaryKey => $id));
		$this->set('categoria', $this->Categoria->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Categoria->create();
			if ($this->Categoria->save($this->request->data)) {
				$this->Flash->success(__('El/la categoria se ha guardado.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('No se pudo guardar el/la categoria. Por favor, inténtelo de nuevo.'));
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
		if (!$this->Categoria->exists($id)) {
			throw new NotFoundException(__('Invalido(a) categoria'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Categoria->save($this->request->data)) {
				$this->Flash->success(__('El/la categoria se ha guardado.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('No se pudo guardar el/la categoria. Por favor, inténtelo de nuevo.'));
			}
		} else {
			$options = array('conditions' => array('Categoria.' . $this->Categoria->primaryKey => $id));
			$this->request->data = $this->Categoria->find('first', $options);
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
		$this->Categoria->id = $id;
		if (!$this->Categoria->exists()) {
			throw new NotFoundException(__('Invalid categoria'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Categoria->delete()) {
			$this->Flash->success(__('El/la categoria ha sido borrado.'));
		} else {
			$this->Flash->error(__('El/la categoria no ha sido borrado. Por favor inténtelo de nuevo.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
