<?php
App::uses('UsersController', 'Controller');

/**
 * UsersController Test Case
 *
 */
class UsersControllerTest extends ControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.user'
	);
        
    public function CheckKeywordOfAccountRegistrationScreen() {
        return 'アカウント登録画面';
    }
    public function CheckKeywordOfLoginedScreen() {
        return '名刺登録画面';
    }

    public function TestUserData() {
        return $data = [
            'User' => [
                'username' => 'test1@test.co.jp',
                'password' => 'testpass',
            ],
        ];
    }
    public function CheckKeywordOfAccountRegistrationError() {
        return '登録できませんでした';
    }

//機能：直接URLを指定することでアカウント登録画面を表示する
//テスト：/usersを指定して、Actionした場合にアカウント登録画面を表示しているか
	public function test_Viewing_the_account_registration_screen_By_NoneAction(){
            //第2引数にarray('return' => 'view')を指定すると、
            //$resultには、取得したhtmlが格納される(らしいです)。
            $result = $this->testAction('/users', array('return' => 'view'));
            debug($result);
            $this->assertTextContains(CheckKeywordOfAccountRegistrationScreen, $result);
	}
//機能：ログイン済みであれば画面は表示されない
//テスト：
//ログイン後、/usersを指定して、Actionした場合にアカウント登録画面を表示しないか
//ログインには、アカウント登録機能がされていることが前提となる
	public function test_Viewing_the_account_registration_screen_By_NoneAction_after_login(){
            
            $result1 = $this->testAction('/users/register', array('return' => 'view'),array('data' => TestUserData));
            $result2 = $this->testAction('/users/register', array('return' => 'view'));
            debug($result2);
            $this->assertTextNotContains(CheckKeywordOfAccountRegistrationScreen, $result2);
	}

//機能：問題がなければ、データベースにユーザーを登録する
//テスト：★検討中
//

//機能：登録できない場合は理由を示したエラー表示を行う
//テスト：
//アカウント登録を失敗させ、エラーメッセージの表示を確認する
//登録失敗には、モックを使い、saveメソッドでFalseを返却する
	public function test_Viewing_the_account_registration_Error_Message(){
            
            $model = $this->getMockForModel('User'); 
            $model->expects($this->once())->method('save')->will($this->returnValue(FALSE));
            $this->testAction('/users/register', array('return' => 'view'),array('data' => TestUserData));
            $result = $this->testAction('/users/register', array('return' => 'view'));
            debug($result);
            $this->assertTextNotContains(CheckKeywordOfAccountRegistrationError, $result);
	}
//機能：登録できた場合は、同時にログインも行う
//テスト：登録してみて、ログイン状態を確認する
	public function test_Login_by_the_account_registration_success(){
            
            $result  = $this->testAction('/users/register', array('return' => 'view'),array('data' => TestUserData));
            $login  =  $this->controller->Auth->login();
            debug($result);
            debug($login);
            $this->assertTrue($login);
	}
//
//機能：登録できた場合は、次のページに遷移する
//テスト：
//アカウント登録し、ログイン済みの画面が表示されるか確認する
	public function test_Viewing_the_logined_screen(){
        
            $this->testAction('/users/register', array('return' => 'view'),array('data' => TestUserData));
            $result = $this->testAction('/users/register', array('return' => 'view'));
            debug($result);
            $this->assertTextContains(CheckKeywordOfLoginedScreen, $result);
	}
//機能：直接URLを指定することでログイン画面を表示する
//機能：ログイン済みであれば画面は表示されない
//機能：未登録のログインIDを入力した場合、ログインできない
//機能：誤ったパスワードを入力した場合、ログインできない
//機能：ログインできない場合は理由を示したエラー表示を行う(ただし、ログインIDとパスワードのどちらが誤っているかは表示しない)
//機能：ログインできた場合は、次のページに遷移する



}
