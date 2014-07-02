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
        //TestCase:usernameValidation
        //メソッドの記述方法：http://phpunit.de/manual/3.7/ja/writing-tests-for-phpunit.html
        public function testUsernameValidationSuccess() {
        // テストデータを準備
        $user = [
            'User' => [
                'username'   => 'test@test.co.jp',
                'password' => 'testpassward',
            ],
        ];
        
        // テスト対象メソッドを呼び出す、戻り値はsetしたデータを返す
        $result = $this->User->save($user);
        
        // 期待される結果が得られたか？
        $this->assertEqual($result['User']['username'],$user['User']['username']);
        
        }
        public function testUsernameValidationFailedNotEmail() {
        // テストデータを準備
        $user = [
            'User' => [
                'username'   => 'test:test.co.jp',
                'password' => 'testpassward',
            ],
        ];

        // テスト対象メソッドを呼び出す
        $result = $this->User->save($user);

        // 期待される結果が得られたか？
        $this->assertFalse($result);
        }

}
