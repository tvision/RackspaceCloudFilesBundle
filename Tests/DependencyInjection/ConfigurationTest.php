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

use Tvision\RackspaceCloudFilesBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\Processor;

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getConfig
     *
     * @param $config
     * @param $endConfig
     * @param bool $expectException
     */
    public function testConfigTreeBuilder($config, $endConfig, $expectException = false)
    {
        $processor = new Processor();
        $configuration = new Configuration(array());

        if($expectException){
            $this->setExpectedException('Symfony\Component\Config\Definition\Exception\InvalidConfigurationException');
            $processor->processConfiguration($configuration, array($config));
        } else {
            $config = $processor->processConfiguration($configuration, array($config));
            $this->assertEquals($endConfig, $config);
        }
    }

    public function getConfig()
    {
        return array(
            array(
                array(),
                array(
                    'container_prefix' => '',
                    'service_class'    => 'Tvision\RackspaceCloudFilesBundle\Service\RSCFService'
                )
            ),
            array(
                array('stream_wrapper' => array()),
                array(
                    'container_prefix' => '',
                    'service_class'    => 'Tvision\RackspaceCloudFilesBundle\Service\RSCFService',
                    'stream_wrapper'   => array(
                        'register'      => false,
                        'protocol_name' => 'rscf',
                        'class'         => '\Tvision\RackspaceCloudFilesStreamWrapper\StreamWrapper\RackspaceCloudFilesStreamWrapper'
                    )
                )
            ),
            array(
                array('auth' => array()),
                array(
                    'container_prefix' => '',
                    'service_class'    => 'Tvision\RackspaceCloudFilesBundle\Service\RSCFService',
                    'auth'             => array(
                        'username'       => null,
                        'api_key'        => null,
                        'container_name' => null,
                        'host'           => 'https://lon.identity.api.rackspacecloud.com/v2.0',
                        'region'         => 'LON'
                    )
                )
            ),
            array(
                array('testError' => 'foo'),
                array(),
                true
            ),
        );
    }
}
