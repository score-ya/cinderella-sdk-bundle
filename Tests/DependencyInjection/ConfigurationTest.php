<?php

namespace ScoreYa\Cinderella\Bundle\SDKBundle\Tests\DependencyInjection;

use Matthias\SymfonyConfigTest\PhpUnit\AbstractConfigurationTestCase;
use ScoreYa\Cinderella\Bundle\SDKBundle\DependencyInjection\Configuration;

/**
 * @author Alexander Miehe <thelex@beamscore.com>
 *
 * @covers ScoreYa\Cinderella\Bundle\SDKBundle\DependencyInjection\Configuration
 */
class ConfigurationTest extends AbstractConfigurationTestCase
{
    /**
     * @test
     */
    public function setDefaultClients()
    {
        $this->assertProcessedConfigurationEquals(
            array(array('api_key' => 'dummy_api_key')),
            array(
                'api_key' => 'dummy_api_key',
                'clients' => array('template' => array('class' => 'ScoreYa\Cinderella\SDK\Template\TemplateClient'))
            )
        );
    }

    /**
     * @test
     */
    public function requireApiKey()
    {
        $this->assertConfigurationIsInvalid(array(array()), 'api_key');
    }

    /**
     * @test
     */
    public function allowOnlyDefinedClients()
    {
        $this->assertConfigurationIsInvalid(
            array(array('api_key' => 'key', 'clients' => array('other' => array()))),
            'other'
        );
    }

    /**
     * @test
     */
    public function preventFromOverrideClientClass()
    {
        $this->assertConfigurationIsInvalid(
            array(array('api_key' => 'key', 'clients' => array('template' => array('class' => 'clientClass')))),
            'clientClass'
        );
    }

    /**
     * @test
     */
    public function setAlternativeBaseUrlForClient()
    {
        $this->assertConfigurationIsValid(
            array(array('api_key' => 'key', 'clients' => array('template' => array('base_url' => 'other_uri'))))
        );
    }


    /**
     * Return the instance of ConfigurationInterface that should be used by the
     * Configuration-specific assertions in this test-case
     *
     * @return \Symfony\Component\Config\Definition\ConfigurationInterface
     */
    protected function getConfiguration()
    {
        return new Configuration();
    }


}
