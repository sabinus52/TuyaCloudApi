<?php
/**
 * Test de la class TokenPool
 *
 * @author Olivier <sabinus52@gmail.com>
 *
 * @package TuyaCloudApi
 */

use PHPUnit\Framework\TestCase;
use Sabinus\TuyaCloudApi\Tools\TokenPool;


class TokenPoolTest extends TestCase
{

    public function testStoreToken()
    {
        $pool = new TokenPool();
        $pool->clearFromCache();
        $this->assertSame(null, $pool->fetchFromCache());
        $pool->storeInCache([0,1,2]);
        $this->assertSame(null, $pool->fetchFromCache(0));
        $this->assertSame([0,1,2], $pool->fetchFromCache(3)); // Non périmé
        sleep(5);
        $this->assertSame(null, $pool->fetchFromCache(3)); // Périmé
        $pool->clearFromCache();
        $this->assertSame(null, $pool->fetchFromCache(3));
    }


    public function testStoreOtherFolderToken()
    {
        $pool = new TokenPool();
        $pool->setFolder('/var/tmp');
        $pool->clearFromCache();
        $pool->storeInCache([1,2,3]);
        $this->assertSame(null, $pool->fetchFromCache());
        $this->assertSame([1,2,3], $pool->fetchFromCache(3));
        $pool->clearFromCache();
        $this->assertSame(null, $pool->fetchFromCache());
    }

}
