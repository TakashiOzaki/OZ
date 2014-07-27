<?php
App::uses('UsersController', 'Controller');

/**
 * UsersController Test Case
 *
 */
class UsersControllerTest extends ControllerTestCase {



    public function setUp() {
        parent::setUp();
        $UsersController = new UsersController();
        


    }

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.user'
	);
        
    /* 検証キーワードの定義    */
    public function CheckKeywordOfAccountRegistrationScreen() {
        return 'アカウント登録画面';
    }
    public function CheckKeywordOfLoginedScreen() {
        return '名刺登録画面';
    }

    public function CheckKeywordOfLoginScreen() {
        return 'Please enter your username and password';
    }
    
    public function CheckKeywordOfLoginFailureScreen() {
        return 'Invalid username or password, try again';
    }
    
    public function TestUserData() {
        return $data = [
            'User' => [
                'username' => 'test1@test.co.jp',
                'password' => 'testpass',
            ],
        ];
    }
    
    public function TestUserDataforLogin($no) {
        $data = array(
        [   #登録済みID
            'User' => [
                'username' => 'test4@test.co.jp',
                'password' => 'testpass',]
        ],[
            #未登録のID
            'User' => [
                'username' => 'test3@test.co.jp',
                'password' => 'testpass',]
        ],[
            #password誤り
            'User' => [
                'username' => 'test4@test.co.jp',
                'password' => 'worngtestpass',]
        ],[
            #IDフォーマット違反（Emailじゃない）
            'User' => [
                'username' => 'Collision check for ID',
                'password' => 'Collision check for ID',]
        ]);
        return $data[$no];
    }
    
    //検討中　ログイン画面への遷移元URLを判定するキーワード
    public function TestDestinationScreenAfterLogin($Screen) {
        $data = array(
            Edit=>"名詞作成画面",
            display=>"名詞表示画面",
            exchange=>"名詞交換画面",
        );
        return $data[$Screen];
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
            $this->assertTextContains($this->CheckKeywordOfAccountRegistrationScreen(), $result);
	}
//機能：ログイン済みであれば画面は表示されない
//テスト：
//ログイン後、/usersを指定して、Actionした場合にアカウント登録画面を表示しないか
//ログインには、アカウント登録機能がされていることが前提となる
	public function test_Viewing_the_account_registration_screen_By_NoneAction_after_login(){
            
            $result1 = $this->testAction('/users/register', array('return' => 'view'),array('data' => $this->TestUserData()));
            $result2 = $this->testAction('/users/register', array('return' => 'view'));
            debug($result2);
            $this->assertTextNotContains($this->CheckKeywordOfAccountRegistrationScreen(), $result2);
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
            $this->testAction('/users/register', array('return' => 'view'),array('data' => $this->TestUserData()));
            $result = $this->testAction('/users/register', array('return' => 'view'));
            debug($result);
            $this->assertTextNotContains($this->CheckKeywordOfAccountRegistrationError(), $result);
	}
//機能：登録できた場合は、同時にログインも行う
//テスト：登録してみて、ログイン状態を確認する
	public function test_Login_by_the_account_registration_success(){
            
            $result  = $this->testAction('/users/register', array('return' => 'view','data' => $this->TestUserData()));
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
        
            $this->testAction('/users/register', array('return' => 'view','data' => $this->TestUserData()));
            $result = $this->testAction('/users/register', array('return' => 'view'));
            debug($result);
            $this->assertTextContains($this->CheckKeywordOfLoginedScreen(), $result);
	}
//機能：直接URLを指定することでログイン画面を表示する
//テスト：
//login画面URLを指定することでログイン画面が表示されるかを確認する
	public function test_Viewing_the_login_screen(){
        
            $result = $this->testAction('/users/login', array('return' => 'contents' ));
            debug($result);
            $this->assertTextContains($this->CheckKeywordOfLoginScreen(), $result);
	}
        
        
        //テスト：
        //UserControllerへのアクセスは常にhttpsでアクセスされるかを確認する（http→httpsへリダイレクトされる）
	public function test_Schema_check_whether_https(){
            debug($_SERVER['HTTPS']);
            $this->assertTextContains("on", $_SERVER['HTTPS']);
	}
        
//機能：ログイン済みであれば画面は表示されない
//テスト検討中


//機能：未登録のログインIDを入力した場合、ログインできない
//機能：誤ったパスワードを入力した場合、ログインできない
//機能：ログインできない場合は理由を示したエラー表示を行う(ただし、ログインIDとパスワードのどちらが誤っているかは表示しない)
//　　　ログインできない場合のエラー表示を判定keyとする
//テスト１：
//未登録のIDを入力した場合、ログインできない
	public function test_Login_ID_of_the_input_the_wrong1(){
        
            $result = $this->testAction('/users/login', array('return' => 'contents' ,'data' => $this->TestUserDataforLogin(1),'method' => 'post'));
            debug($result);
            $this->assertTextContains($this->CheckKeywordOfLoginFailureScreen(), $result);
	}
//テスト2：
//passwordを誤って入力した場合、ログインできない
	public function test_Login_ID_of_the_input_the_wrong2(){
        
            $result = $this->testAction('/users/login', array('return' => 'contents' ,'data' => $this->TestUserDataforLogin(2),'method' => 'post'));
            debug($result);
            $this->assertTextContains($this->CheckKeywordOfLoginFailureScreen(), $result);
	}
//テスト3：
//usernameがemailではない場合、ログインできない
	public function test_Login_ID_of_the_input_the_wrong3(){
        
            $result = $this->testAction('/users/login', array('return' => 'contents' ,'data' => $this->TestUserDataforLogin(3),'method' => 'post'));
            debug($result);
            $this->assertTextContains($this->CheckKeywordOfLoginFailureScreen(), $result);
	}
        
        

//機能：ログインできた場合は、次のページに遷移する
//テスト：検討中
//作成中：ログイン後にリダイレクトする
//処理、遷移後の画面を判定する処理を未実装
//正しいログインIDを入力し、ログインし、ログイン後に次のページに遷移する
//遷移元URL毎に項目を作成する
//遷移元
//  index：一覧表示画面（display？）
//  exchange：名刺交換画面
//　その他：indexへ
//　edit:名詞作成画面（EditからRedirectされるためHTTP_REFERERがとれないと思われる。
//　　　　　
//
	public function test_Enter_the_correct_LOGIN_ID(){
            debug($this->TestUserDataforLogin(0));
            $result = $this->testAction('/users/login', array('return' => 'contents' ,'data' => $this->TestUserDataforLogin(0),'method' => 'post'));
            debug($result);
            $this->assertTextContains($this->TestDestinationScreenAfterLogin(), $result);
	}


}
