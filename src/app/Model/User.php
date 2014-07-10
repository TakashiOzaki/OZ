<?php
App::uses('AppModel', 'Model');
App::uses('SimplePasswordHasher', 'Controller/Component/Auth');
/**
 * User Model
 *
 */
class User extends AppModel {
    public $validate = array(
        'username' => array(
            'email' => array(
                'rule' => 'email',
                'message' => 'ログインIDには、E-mailを入力してください',
            ),
            'isUnique' => array(
                'rule' => 'isUnique',
                'message' => '既に登録されたログインIDです',
            ),
        )
    );
    
    public function beforeSave($options = array()) {
        
        IF(!$this->id){
            //パスワードハッシュ化
            $passwordHasher = new SimplePasswordHasher();
            $this->data['User']['password'] = $passwordHasher->hash($this->data['User']['password']);            
        }
        return TRUE;
    }

}
