<?php
App::uses('AppController', 'Controller');
/**
 * 
 * 
 * Users Controller
 *
 * @property User $User
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class UsersController extends AppController {

 /**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Session', 'Auth');
        
        public function beforeFilter() {
            parent::beforeFilter();
            
            if (empty($_SERVER['HTTPS'])) {
                header("Location: https://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}");
                exit;
            }
            
            $this->Auth->allow('register','login');
            $this->set('user',$this->Auth->user());
        }
    
    //検討中　：　遷移元URLへリダイレクトする方法$_SERVER[HTTP_REFERER]
    //　　　　　　を利用する方法でよいか？この方法だとユーザ登録後は遷移不可？
        public function login() {
            if ($this->request->is('post')) {
                if ($this->Auth->login()) {
                    //redirect先は$_SERVER[HTTP_REFERER]を参照
                    //$this->redirect($this->Auth->redirect());
                    
                    // リファラーを分析して表示する。（分析方法未実装）
                    if( $_SERVER[HTTP_REFERER] == NULL ){
                            $this->redirect(array('controller' => 'users', 'action' => 'index'));
                    }
                    //検討中
                    else if ($_SERVER[HTTP_REFERER] == "edit") {
                            $this->redirect(array('controller' => 'users', 'action' => 'edit'));
                    }
                    else if( $_SERVER[HTTP_REFERER] == "display" ){
                            $this->redirect(array('controller' => 'users', 'action' => 'index'));
                    }                    
                    else if( $_SERVER[HTTP_REFERER] == "exchange" ){
                            $this->redirect(array('controller' => 'users', 'action' => 'exchange'));
                    }
                    else{
                            $this->redirect(array('controller' => 'users', 'action' => 'login'));
                    }

                    
                } else {
                    $this->Session->setFlash(__('Invalid username or password, try again'));
                }
            }
        }

        public function logout() {
            $this->redirect($this->Auth->logout());
        }

    

/**
 * index method
 *
 * @return void
 */
                
        public function index() {
		$this->User->recursive = 0;
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
 /* 以前のコードをコメントアウトしておく
          public function register() {
                if ($this->request->is('post')) {
                        $this->User->create();
                        if ($this->User->save($this->request->data)) {
                                $this->Session->setFlash(__('The user has been saved.'));
                                return $this->redirect(array('controller' => 'Mcard', 'action' => 'Mcard'));
                         } else {
                                $this->Session->setFlash(__('The user could not be saved. Please, try again.'));
                         }
                }  elseif ($this->request->is('get')){
 
                        if($this->Auth->loggedIn()){
                             return $this->redirect(array('controller' => 'Mcard', 'action' => 'Mcard'));                            
                        }else{
                            
                        }
                }
        }
*/
        public function register() {
                if ($this->request->is('post')) {
                        $this->User->create();
                        if ($this->User->save($this->request->data)) {
                                $this->Session->setFlash(__('The user has been saved.'));
                                return $this->redirect(array('controller' => 'Card', 'action' => 'card'));
                         } else {
                                $this->Session->setFlash(__('The user could not be saved. Please, try again.'));
                         }
                }  elseif ($this->request->is('get')){
 
                        if($this->Auth->loggedIn()){
/*                             return $this->redirect(array('controller' => 'Card', 'action' => 'card'));                            
                                $this->Session->setFlash(__('The user has been saved.')); 
                                return $this->redirect(array('action' => 'index'));
                                return $this->redirect(array('controller' => 'Card', 'action' => 'Mcard'));
 */
                         } else {
 
 /*                               $this->Session->setFlash(__('The user could not be saved. Please, try again.'));
 */
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
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
			$this->request->data = $this->User->find('first', $options);
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
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->User->delete()) {
			$this->Session->setFlash(__('The user has been deleted.'));
		} else {
			$this->Session->setFlash(__('The user could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
