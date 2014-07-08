<?php
App::uses('User', 'Model');

/**
 * User Test Case
 *
 */
class UserTest extends CakeTestCase {

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = array(
        'app.user'
    );

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp() {
        parent::setUp();
        $this->User = ClassRegistry::init('User');
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown() {
        unset($this->User);

        parent::tearDown();
    }

    //ここからテストプログラムを記述する
    //参考(メソッドの記述方法)http://phpunit.de/manual/3.7/ja/writing-tests-for-phpunit.html
    
    //各テストパラメータ
    private function get_user_param_success(){
        $param =  [
            'User' => [
                'username' => 'test@test.co.jp',
                'password' => 'testpass',
            ],
        ];
        return $param;
    }
    private function get_user_param_username_not_email(){
        $param =  [
            'User' => [
                'username' => 'test:test.co.jp',
                'password' => 'testpass',
            ],
        ];
        return $param;
    }
    private function get_user_param_password_7_characters(){
        $param =  [
            'User' => [
                'username' => 'test@test.co.jp',
                'password' => 'testpas',
            ],
        ];
        return $param;
    }
    //テスト機能：
    //ログインIDにはemailアドレスでなければならない
    //テスト内容：
    //ユーザ名のEmailのバリデーションルールが働いているかどうか、saveを成功/失敗をさせ、確認する
    //期待値：
    //save成功時はsaveしたデータ(配列)を返し、save失敗時はデータ(配列)を返さない(Ture・Falseを返す)
    public function test_usename_email_validation_rule_working() {
        
        // テストデータを準備し、テスト対象メソッドを呼び出す
        // save成功：userrnameにemailを設定
        $this->User->create();
        $save_success_result = $this->User->save($this->get_user_param_success());
        // save失敗：userrnameにemail以外を設定
        $this->User->create();
        $save_failed_result = $this->User->save($this->get_user_param_username_not_email());
        
        // 期待される結果が得られたか？
        $this->assertTrue(is_array($save_success_result) and (is_array($save_failed_result) == FALSE));
    }

            
    //テスト機能：
    //パスワードは8文字以上でなければならない
    //テスト内容：
    //パスワードの8文字以上のバリデーションルールが働いているかどうか、saveを成功/失敗をさせ、確認する
    //期待値：
    //save成功時はsaveしたデータ(配列)を返し、save失敗時はデータ(配列)を返さない(Ture・Falseを返す)
    public function test_password_8_or_more_characters_validation_rule_working() {

        // テストデータを準備し、テスト対象メソッドを呼び出す
        // save成功：passwordに8文字の文字列を設定
        $this->User->create();
        $save_success_result = $this->User->save($this->get_user_param_success());
        // save失敗：passwordに7文字の文字列を設定
        $this->User->create();
        $save_failed_result = $this->User->save($this->get_user_param_password_7_characters());
        
        // 期待される結果が得られたか？
        $this->assertTrue(is_array($save_success_result) and (is_array($save_failed_result) == FALSE));
    }
    //テスト機能：
    //登録済みのログインIDを入力した際は登録できない
    //テスト内容：
    //ユーザ名のユニークのバリデーションルールが働いているかどうか、saveを成功/失敗をさせ、確認する
    //期待値：
    //save成功時はsaveしたデータ(配列)を返し、save失敗時はデータ(配列)を返さない(Ture・Falseを返す)
    public function test_usename_unique_validation_rule_working() {
        
        // テストデータを準備し、テスト対象メソッドを呼び出す
        // 2回、正常パラメータを登録する
        $this->User->create();
        $save_success_result = $this->User->save($this->get_user_param_success());
        $this->User->create();
        $save_failed_result = $this->User->save($this->get_user_param_success());
        
        // 期待される結果が得られたか？
        $this->assertTrue(is_array($save_success_result) and (is_array($save_failed_result) == FALSE));
    }   
    ////機能：問題がなければ、データベースにユーザーを登録する
    ////テスト：他のテストに踏襲するため、この機能のテストは省略
    
    //テスト機能：
    //データベースに登録する際、パスワードは暗号化して登録する
    //テスト内容：
    //パスワードのハッシュ化がされているかどうか、saveを成功させ、登録内容を見て確認する
    //期待値：
    //save成功時はsaveしたデータ(配列)を返し、登録する前と比較して一致しない(暗号化されるため)
    public function test_password_hash_working() {
        // テストデータを準備
        //save成功用：passwordに8文字の文字列を設定
        $save_success_param = $this->get_user_param_success();
        
        // テストデータを準備し、テスト対象メソッドを呼び出す
        $this->User->create();
        $save_success_result = $this->User->save($save_success_param);
        
        // 期待される結果が得られたか？
        $this->assertTrue(is_array($save_success_result) and ($save_success_param['User']['password'] <> $save_success_result['User']['password']));
    }
    //テスト機能：
    //登録できない場合は理由を示したエラー表示を行う
    //テスト内容：
    //ユーザ名のエラーメッセージが登録されているかどうか、saveを失敗をさせ、確認する
    //期待値：
    //save失敗時は、validationErrors[フィールド名]に配列としてメッセージが格納される(save成功時は何も格納されない。格納されるのはvalidationErrors[フィールド名][0])
    //エラーメッセージは、デフォルトメッセージ'This field cannot be left blank'以外が格納される（任意のメッセージが格納される）
    public function test_usename_email_validation_message_inputing(){
        
        // テストデータを準備し、テスト対象メソッドを呼び出す
        // save失敗：userrnameにemail以外を設定
        $this->User->create();
        $this->User->save($this->get_user_param_username_not_email());
        
        // 期待される結果が得られたか？
        debug($this->User->validationErrors['username'][0]);
        $this->assertTrue(is_array($this->User->validationErrors['username']) And ($this->User->validationErrors['username'][0] <> 'This field cannot be left blank'));
        
    }
    public function test_usename_unique_validation_message_inputing(){
        
        // テストデータを準備し、テスト対象メソッドを呼び出す
        // 2回、正常パラメータを登録する
        $this->User->create();
        $this->User->save($this->get_user_param_success());
        $this->User->create();
        $this->User->save($this->get_user_param_success());
        
        // 期待される結果が得られたか？
        debug($this->User->validationErrors['username'][0]);
        $this->assertTrue(is_array($this->User->validationErrors['username']) And ($this->User->validationErrors['username'][0] <> 'This field cannot be left blank'));
         
    }
    //テスト機能：
    //登録できない場合は理由を示したエラー表示を行う
    //テスト内容：
    //パスワードのバリデーションメッセージが登録されているかどうか、saveを失敗をさせ、確認する
    //期待値：
    //save失敗時は、validationErrors[フィールド名]に配列としてメッセージが格納される(save成功時は何も格納されない。格納されるのはvalidationErrors[フィールド名][0])
    //エラーメッセージは、デフォルトメッセージ'This field cannot be left blank'以外が格納される（任意のメッセージが格納される）
    public function test_password_8_or_more_characters_validation_message_inputing(){
        
        // テストデータを準備し、テスト対象メソッドを呼び出す
        // save失敗：passwordに7文字の文字列を設定
        $this->User->create();
        $this->User->save($this->get_user_param_password_7_characters());
        
        // 期待される結果が得られたか？
        debug($this->User->validationErrors['password'][0]);
        $this->assertTrue(is_array($this->User->validationErrors['password']) And ($this->User->validationErrors['password'][0] <> 'This field cannot be left blank'));
        
    }
    //ここまでにテストプログラムを記述する
}
