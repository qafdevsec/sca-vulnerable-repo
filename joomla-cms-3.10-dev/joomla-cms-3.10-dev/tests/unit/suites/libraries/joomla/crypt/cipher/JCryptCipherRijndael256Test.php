<?php
/**
 * @package     Joomla.UnitTest
 * @subpackage  Crypt
 *
 * @copyright   (C) 2013 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Test class for JCryptCipherRijndael256.
 *
 * @package     Joomla.UnitTest
 * @subpackage  Crypt
 * @since       3.0.0
 */
class JCryptCipherRijndael256Test extends TestCase
{
	/**
	 * @var    JCryptCipherRijndael256
	 * @since  3.0.0
	 */
	private $_cipher;

	/**
	 * Prepares the environment before running a test.
	 *
	 * @return  void
	 *
	 * @since   3.0.0
	 */
	protected function setUp()
	{
		parent::setUp();

		// Only run the test if mcrypt is loaded.
		if (!extension_loaded('mcrypt'))
		{
			$this->markTestSkipped('The mcrypt extension must be available for this test to run.');
		}

		$this->_cipher = new JCryptCipherRijndael256;

		// Build the key for testing.
		$this->key = new JCryptKey('rijndael256');
		$this->key->private = file_get_contents(__DIR__ . '/stubs/encrypted/rijndael256/key.priv');
		$this->key->public = file_get_contents(__DIR__ . '/stubs/encrypted/rijndael256/key.pub');
	}

	/**
	 * Cleans up the environment after running a test.
	 *
	 * @return  void
	 *
	 * @since   3.0.0
	 */
	protected function tearDown()
	{
		unset($this->_cipher, $this->key);
		parent::tearDown();
	}

	/**
	 * Test...
	 *
	 * @return array
	 */
	public function data()
	{
		return array(
			array(
				'1.txt',
				'c-;3-(Is>{DJzOHMCv_<#yKuN/G`/Us{GkgicWG$M|HW;kI0BVZ^|FY/"Obt53?PNaWwhmRtH;lWkWE4vlG5CIFA!abu&F=Xo#Qw}gAp3;GL\'k])%D}C+W&ne6_F$3P5'),
			array(
				'2.txt',
				'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. ' .
					'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor ' .
					'in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt ' .
					'in culpa qui officia deserunt mollit anim id est laborum.'),
			array('3.txt', 'لا أحد يحب الألم بذاته، يسعى ورائه أو يبتغيه، ببساطة لأنه الألم...'),
			array('4.txt',
				'Широкая электрификация южных губерний даст мощный ' .
					'толчок подъёму сельского хозяйства'),
			array('5.txt', 'The quick brown fox jumps over the lazy dog.')
		);
	}

	/**
	 * Tests JCryptCipherRijndael256Test->decrypt()
	 *
	 * @param   string  $file  @todo
	 * @param   string  $data  @todo
	 *
	 * @return  void
	 *
	 * @dataProvider data
	 * @since   3.0.0
	 */
	public function testDecrypt($file, $data)
	{
		$encrypted = file_get_contents(__DIR__ . '/stubs/encrypted/rijndael256/' . $file);
		$decrypted = $this->_cipher->decrypt($encrypted, $this->key);

		// Assert that the decrypted values are the same as the expected ones.
		$this->assertEquals($data, $decrypted);
	}

	/**
	 * Tests JCryptCipherRijndael256Test->encrypt()
	 *
	 * @param   string  $file  @todo
	 * @param   string  $data  @todo
	 *
	 * @return  void
	 *
	 * @dataProvider data
	 * @since   3.0.0
	 */
	public function testEncrypt($file, $data)
	{
		$encrypted = $this->_cipher->encrypt($data, $this->key);

		// Assert that the encrypted value is not the same as the clear text value.
		$this->assertNotEquals($data, $encrypted);

		// Assert that the encrypted values are the same as the expected ones.
		$this->assertStringEqualsFile(__DIR__ . '/stubs/encrypted/rijndael256/' . $file, $encrypted);
	}

	/**
	 * Tests JCryptCipherRijndael256Test->generateKey()
	 *
	 * @return  void
	 *
	 * @since   3.0.0
	 */
	public function testGenerateKey()
	{
		$key = $this->_cipher->generateKey(array('password' => 'J00ml@R0cks!'));

		// Assert that the key is the correct type.
		$this->assertInstanceOf('JCryptKey', $key);

		// Assert that the private key is 32 bytes long.
		$this->assertEquals(32, strlen($key->private));

		// Assert the key is of the correct type.
		$this->assertAttributeEquals('rijndael256', 'type', $key);
	}
}
