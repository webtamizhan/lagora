<?php


namespace Webtamizhan\Lagora\Tests\Feature;

use Webtamizhan\Lagora\Exceptions\AgoraConfigurationNotFoundException;
use Webtamizhan\Lagora\Exceptions\InvalidChannelNameException;
use Webtamizhan\Lagora\Exceptions\RoleNotFoundException;
use Webtamizhan\Lagora\LagoraRTM;
use Webtamizhan\Lagora\Tests\TestCase;

class AgoraRtmTest extends TestCase
{
    /** @test
     */
    public function check_rtm_throws_exception_if_config_was_empty()
    {
        //manually clear the configuration for testing
        config(['lagora.rtm.app_id' => '']);
        config(['lagora.rtm.app_certificate' => '']);

        try {
            $service = new LagoraRTM();
        } catch (AgoraConfigurationNotFoundException $exception) {
            $this->assertEquals("Missing Agora APP_ID and/or APP_CERTIFICATE, please check on config/lagora.php!", $exception->getMessage());
        }
    }

    /*
     * @test
     */
    public function test_what_if_invalid_role_was_given()
    {
        try {
            $service = new LagoraRTM();
            $service->setRole(45);
        } catch (RoleNotFoundException $exception) {
            $this->assertEquals("Given role 45 was not found!", $exception->getMessage());
        }
    }

    /*
     * @test
     */
    public function test_what_if_channel_name_was_not_given()
    {
        $channel = '';

        try {
            $service = new LagoraRTM();
            $service
                ->setUserID(0)
                ->setMinutes(10)
                ->setSeconds(10)
                ->setChannelName($channel)
                ->setRole(1)
                ->getToken();
        } catch (InvalidChannelNameException $exception) {
            $this->assertEquals("Given channel {$channel} was invalid or empty!", $exception->getMessage());
        }
    }

    /**
     * @test
     */
    public function test_can_generate_token()
    {
        $channel = 'NewTestChannel';
        $service = new LagoraRTM();
        $service
            ->setUserID(0)
            ->setMinutes(10)
            ->setSeconds(10)
            ->setChannelName($channel)
            ->setRole(1)
            ->getToken();
        ray($service->token);
        $this->assertNotEmpty($service->token);
    }
}
