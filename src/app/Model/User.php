<?php
App::uses('AppModel', 'Model');
/**
 * User Model
 *
 */
class User extends AppModel {
    
    public $validate = array(
        'username' => array(
            'rule' => 'email'
        )
    );
}
