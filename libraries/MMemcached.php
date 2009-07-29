<?php defined('SYSPATH') or die('No direct script access.');


/**
 * undocumented class
 *
 * @version 1.0
 * @package default
 * @author Grzegorz Kazulak
 */
class MMemcached_Core {
  
  /**
   * Array of memcached servers (2-dimensional)
   *
   * @var array
   */
  protected $memcached_servers = array();
  
  /**
   * No. of available memcached servers
   *
   * @var integer
   */
  protected $memcached_servers_count = 0;

  /**
   * Object instance
   */
  static $objectInstance;

  /**
   * Usual singleton stuff. Nothing amazing here.
   *
   * @return [ORM_Cached_Multiconnect object]
   * @author Grzegorz Kazulak
   */
  static function getInstance(){
      # Get all the servers list from Kohana config
      $serversList = Kohana::config('mmemcached.memcached_servers');
    
      self::$objectInstance || self::$objectInstance = new MMemcached_Core($serversList);
      return self::$objectInstance;
  }
  
  /**
   * Constructor. Takes a 2-dimensional array of memcached servers with
   * ip address/hostname as key and corresponding port number as value.
   *
   * @param array $servers 
   * @author Grzegorz Kazulak
   */
  protected function __construct(array $servers){
      if (!$servers){
          #TODO: Raise an Exception
      }
      
      # Loop through all available memcached servers and connect
      # to each on of them.
      for ($i = 0, $n = count($servers); $i < $n; ++$i){
        
          # Yes, I know there is a OOP way to do that but using procedural function simply takes
          # less memory because the web server does not need to allocate memory
          # for each of the objects (connections)
          ( $con = memcache_pconnect(key($servers[$i]), current($servers[$i])) )&& 
              $this->memcached_servers[] = $con;
      }
      
      $this->memcached_servers_count = count($this->memcached_servers);
      if (!$this->memcached_servers_count){
          $this->memcached_servers[0]=null;
      }
  }
  
  /**
   * Returns memcached connection resource for given key
   *
   * @param string $key 
   * @return void
   * @author Grzegorz Kazulak
   */
  protected function getResourceForKey($key){
      # No other choice possible if memcached server count is < 2
      if ( $this->memcached_servers_count <2 ){
          # Save time and select first one
          return $this->memcached_servers[0];
      }
      return $this->memcached_servers[(crc32($key) & 0x7fffffff) % $this->memcached_servers_count];
  }

  /**
   * Flushes all the data from every single memcached server
   *
   * @return void
   * @author Grzegorz Kazulak
   */
  static function flush() {
      $x = self::singleton()->memcached_servers_count;
      for ($i = 0; $i < $x; ++$i){
          $a = self::getInstance()->memcached_servers[$i];
          self::getInstance()->memcached_servers[$i]->flush();
      }
  }

  /**
   * Get the value from memcached server
   *
   * @param string $key 
   * @return void
   * @author Grzegorz Kazulak
   */
  static function get($key) {
      return self::getInstance()
                 ->getResourceForKey($key)
                 ->get($key);
  }

  /**
   * Set the key with specified value
   *
   * @param string $key 
   * @param mixed  $var 
   * @param string $compress 
   * @param string $expire 
   * @return void
   * @author Grzegorz Kazulak
   */
  static function set($key, $var, $compress = false, $expire = 0) {
      return self::getInstance()
                 ->getResourceForKey($key)
                 ->set($key, $var, $compress ? MEMCACHE_COMPRESSED : null, $expire);
  }
  
  /**
   * undocumented function
   *
   * @param string $key 
   * @param string $var 
   * @param string $compress 
   * @param string $expire 
   * @return void
   * @author Grzegorz Kazulak
   */
  static function add($key, $var, $compress = false, $expire = 0) {
      return self::getInstance()
                 ->getResourceForKey($key)
                 ->add($key, $var, $compress ? MEMCACHE_COMPRESSED : null, $expire);
  }

  /**
   * undocumented function
   *
   * @param string $key 
   * @param string $var 
   * @param string $compress 
   * @param string $expire 
   * @return void
   * @author Grzegorz Kazulak
   */
  static function replace($key, $var, $compress = false, $expire = 0) {
      return self::getInstance()
                 ->getResourceForKey($key)
                 ->replace($key, $var, $compress ? MEMCACHE_COMPRESSED : null, $expire);
  }
  
  /**
   * undocumented function
   *
   * @param string $key 
   * @param string $timeout 
   * @return void
   * @author Grzegorz Kazulak
   */
  static function delete($key, $timeout = 0) {
      return self::getInstance()
                 ->getResourceForKey($key)
                 ->delete($key, $timeout);
  }

  /**
   * undocumented function
   *
   * @param string $key 
   * @param string $value 
   * @return void
   * @author Grzegorz Kazulak
   */
  static function increment($key, $value = 1) {
      return self::getInstance()
                 ->getResourceForKey($key)
                 ->increment($key, $value);
  }

  /**
   * Decrement specified key by $value (default: 1)
   *
   * @param string $key 
   * @param integer $value 
   * @return void
   * @author Grzegorz Kazulak
   */
  static function decrement($key, $value = 1) {
      return self::getInstance()
                 ->getResourceForKey($key)
                 ->decrement($key, $value);
  }
}



