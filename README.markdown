## What is MMemcached ##

MMemcached is a well written module for popular Kohana PHP framework.

## How To ##
### Instalation ###
At the end of your application's `config.php` file you will find few lines like this: 

  $config['modules'] = array
  (
  	// MODPATH.'auth',      // Authentication
  	// MODPATH.'kodoc',     // Self-generating documentation
  	// MODPATH.'gmaps',     // Google Maps integration
  	// MODPATH.'archive',   // Archive utility
  	// MODPATH.'payment',   // Online payments
  );
  
To enable MMcached just add another one:
  
  MODPATH.'mmemcached'
  
To set up the servers you have to add few entries in mmemcached.php config file. Example below:

  $config['memcached_servers'] = array( 
    array('127.0.0.1' => '11211'),
    array('127.0.0.1' => '11111')
  );
  
### Usage ###

Using MMemcached is as simple as using standard Memcache PECL library.
There are 8 methods available:
  
  * MMemcached::flush
  * MMemcached::get($key)
  * MMemcached::set($key, $var, $compress = false, $expire = 0)
  * MMemcached::add($key, $var, $compress = false, $expire = 0)
  * MMemcached::replace($key, $var, $compress = false, $expire = 0)
  * MMemcached::delete($key, $timeout = 0)
  * MMemcached::increment($key, $value = 1)
  * MMemcached::decrement($key, $value = 1)
  
For more information see the source code.  

## Version ##

WARNING: This is currently a RELEASE CANDIDATE. A version of this code is in production use on few sites but the extraction and refactoring process may have introduced bugs and/or performance problems. There are no known major defects at this point, but still.

## Acknowledgments ##

Thanks to

 * Anna for supporting me :-)
 * Envie for paying me for development and allowing me to release this module