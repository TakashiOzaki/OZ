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
	public $components = array('Paginator', 'Session', 'Auth','Security');

        
        public function beforeFilter() {
            parent::beforeFilter();
        
        // SSL接続のみを許容するための実装方法
        // ★★スーパーグローバル変数$_SERVER['HTTPS']に直接アクセスしない。
        //    if (empty($_SERVER['HTTPS'])) {
        //       header("Location: https://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}");
        //        exit;
        //    }
        //  ★★セキュアな接続の設定はSecurityコンポーネントを使用する。
            //usersControllerはhttpでの接続は許容せず、Securityコンポーネントを利用し、httpsへリダイレクトする
            //セキュリティコンポーネントは、一般的にコントローラの beforeFilter() で使用します。 行いたいセキュリティの制限をここで定義すると、セキュリティコンポーネントは起動時にそれらの制限を有効にします。
            //Securityコンポーネントを使用すると、SSL接続を強制することができる
            //SSL接続でなかった場合に処理を停止した際に呼び出すコントローラコールバック関数を設定
            $this->Security->blackHoleCallback = 'forceSSL';
            //SSL接続だけで起動するアクションを指定（未指定なのですべて*のアクションが対象）
            $this->Security->requireSecure();
        
            //Authコンポーネントの使用せずアクセス可能なメソッドを定義
            $this->Auth->allow('register','login');
            //登録済みユーザを取得しuser変数にset
            $this->set('user',$this->Auth->user());
        }

        //Securityコンポーネント用コールバック関数　：http→httpsに変更したURLにリダイレクトする
        public function forceSSL() {
            return $this->redirect('https://' . env('SERVER_NAME') . $this->here);
        }

        
    //検討中　：　遷移元URLへリダイレクトする方法$_SERVER[HTTP_REFERER]
    //　　　　　　を利用する方法でよいか？この方法だとユーザ登録後は遷移不可？
        public function login() {
            if ($this->request->is('post')) {
                if ($this->Auth->login()) {
                    
                    return $this->redirect($this->Auth->redirectUrl());
                    
                } else {
                    return $this->Session->setFlash(__('Invalid username or password, try again'));
                }
            }
        }

        public function logout() {
            return $this->redirect($this->Auth->logout());
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
        public function register() {
                if ($this->request->is('post')) {
                        $this->User->create();
                        if ($this->User->save($this->request->data)) {
                                $this->Session->setFlash(__('The user has been saved.'));
/*                                return $this->redirect(array('action' => 'index'));*/
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
