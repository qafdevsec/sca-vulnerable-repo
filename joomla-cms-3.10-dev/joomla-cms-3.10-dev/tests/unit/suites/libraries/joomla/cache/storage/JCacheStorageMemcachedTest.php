<?php
/**
 * @package     Joomla.UnitTest
 * @subpackage  Cache
 *
 * @copyright   (C) 2014 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Test class for JCacheStorageMemcached.
 */
class JCacheStorageMemcachedTest extends TestCaseCache
{
	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @return  void
	 */
	protected function setUp()
	{
		if (!JCacheStorageMemcached::isSupported())
		{
			$this->markTestSkipped('The Memcached cache handler is not supported on this system.');
		}

		parent::setUp();

		// Parse the DSN details for the test server
		$dsn = defined('JTEST_CACHE_MEMCACHED_DSN') ? JTEST_CACHE_MEMCACHED_DSN : getenv('JTEST_CACHE_MEMCACHED_DSN');

		if ($dsn)
		{
			// First let's trim the memcached: part off the front of the DSN if it exists.
			if (strpos($dsn, 'memcached:') === 0)
			{
				$dsn = substr($dsn, 10);
			}

			// Call getConfig once to have the registry object prepared
			JFactory::getConfig();

			// Split the DSN into its parts over semicolons.
			$parts = explode(';', $dsn);

			// Parse each part and populate the options array.
			foreach ($parts as $part)
			{
				list ($k, $v) = explode('=', $part, 2);
				switch ($k)
				{
					case 'host':
						JFactory::$config->set("memcached_server_host", $v);
						break;
					case 'port':
						JFactory::$config->set("memcached_server_port", $v);
						break;
				}
			}
		}
		else
		{
			$this->markTestSkipped('No configuration for Memcached given');
		}

		try
		{
			$this->handler = new JCacheStorageMemcached;
		}
		catch (JCacheExceptionConnecting $e)
		{
			$this->fail('Failed to connect to Memcached');
		}

		// Override the lifetime because the JCacheStorage API multiplies it by 60 (converts minutes to seconds)
		$this->handler->_lifetime = 2;
	}
}
