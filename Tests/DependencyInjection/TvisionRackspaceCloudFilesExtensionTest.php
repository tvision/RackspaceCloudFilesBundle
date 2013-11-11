<?php

/**
 * This file is part of the RackspaceCloudFilesBundle package.
 *
 * (c) Claudio D'Alicandro <claudio.dalicandro@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tvision\RackspaceCloudFilesBundle\Tests\DependencyInjection;

use Symfony\Component\Yaml\Parser;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tvision\RackspaceCloudFilesBundle\DependencyInjection\TvisionRackspaceCloudFilesExtension;

class TvisionRackspaceCloudFilesExtensionTest extends \PHPUnit_Framework_TestCase
{
    protected function generateExtention()
    {
        return new TvisionRackspaceCloudFilesExtension();
    }

    protected function getConfig()
    {
        $yaml = <<<EOF
container_prefix: ~
stream_wrapper: ~
auth: ~
EOF;
        $parser = new Parser();
        return $parser->parse($yaml);
    }

    public function testLoad()
    {
        $loader = $this->generateExtention();
        $config = $this->getConfig();
        $alias  = $loader->getAlias();
        $containerBuilder =  new ContainerBuilder();

        $loader->load(array($config), $containerBuilder);

        $this->assertEquals(
            '',
            $containerBuilder->getParameter($alias.'.container_prefix')
        );
        $this->assertEquals(
            '\Tvision\RackspaceCloudFilesStreamWrapper\StreamWrapper\RackspaceCloudFilesStreamWrapper',
            $containerBuilder->getParameter($alias . '.stream_wrapper.class')
        );
        $this->assertEquals(
            'rscf',
            $containerBuilder->getParameter($alias . '.stream_wrapper.protocol_name')
        );
        $this->assertFalse(
            $containerBuilder->getParameter($alias . '.stream_wrapper.register')
        );
        $this->assertEquals(
            'https://lon.identity.api.rackspacecloud.com/v2.0',
            $containerBuilder->getParameter($alias . '.auth.host')
        );
        $this->assertEquals(
            'LON',
            $containerBuilder->getParameter($alias . '.auth.region')
        );
    }
}
