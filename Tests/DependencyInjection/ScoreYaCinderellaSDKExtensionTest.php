<?php

namespace ScoreYa\Cinderella\Bundle\SDKBundle\Tests\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use ScoreYa\Cinderella\Bundle\SDKBundle\DependencyInjection\ScoreYaCinderellaSDKExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

/**
 * @author Alexander Miehe <thelex@beamscore.com>
 *
 * @covers ScoreYa\Cinderella\Bundle\SDKBundle\DependencyInjection\ScoreYaCinderellaSDKExtension
 */
class ScoreYaCinderellaSDKExtensionTest extends AbstractExtensionTestCase
{
    /**
     * @test
     */
    public function setApiKeyForClientBuilder()
    {
        $this->load(array('api_key' => 'dummy_api_key'));

        $this->assertContainerBuilderHasServiceDefinitionWithArgument(
            'score_ya.cinderella.sdk.client_service_builder',
            0,
            'dummy_api_key'
        );
    }

    /**
     * @test
     */
    public function overwriteBaseUrlForSpecificClient()
    {
        $this->load(array('api_key' => 'dummy_api_key', 'clients' => array('template' => array('base_url' => 'uri'))));

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'score_ya.cinderella.sdk.template_client',
            'setBaseUrl',
            array('uri')
        );
    }

    /**
     * Return an array of container extensions you need to be registered for each test (usually just the container
     * extension you are testing.
     *
     * @return ExtensionInterface[]
     */
    protected function getContainerExtensions()
    {
        return array(new ScoreYaCinderellaSDKExtension());
    }

}
