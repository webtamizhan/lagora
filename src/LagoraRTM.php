<?php

namespace Webtamizhan\Lagora;

use Exception;
use Webtamizhan\Lagora\Agora\RtmTokenBuilder;
use Webtamizhan\Lagora\Exceptions\AgoraConfigurationNotFoundException;
use Webtamizhan\Lagora\Exceptions\InvalidChannelNameException;
use Webtamizhan\Lagora\Exceptions\RoleNotFoundException;

class LagoraRTM
{
    public $app_id;

    public $app_certificate;

    public string $channelName;

    public int $role;

    public int $userID;

    public int $minutes;

    public int $seconds;

    public string $token;

    /**
     * @throws AgoraConfigurationNotFoundException
     */
    public function __construct()
    {
        $this->app_id = config('lagora.rtm.app_id', '');
        $this->app_certificate = config('lagora.rtm.app_certificate', '');
        if (empty($this->app_id) || empty($this->app_certificate)) {
            throw AgoraConfigurationNotFoundException::rtcNotConfigured();
        }
    }

    /**
     * @param string $channelName
     * @return LagoraRTM
     */
    public function setChannelName(string $channelName): LagoraRTM
    {
        $this->channelName = $channelName;

        return $this;
    }

    /**
     * @param int $role
     * @return LagoraRTM
     * @throws RoleNotFoundException
     * RoleAttendee = 0; RolePublisher = 1; RoleSubscriber = 2; RoleAdmin = 101;
     */
    public function setRole(int $role = 1): LagoraRTM
    {
        if (! in_array($role, [0,1,2,101])) {
            throw RoleNotFoundException::roleNotFound($role);
        }
        $this->role = $role;

        return $this;
    }

    /**
     * @param int $userID
     * @return LagoraRTM
     */
    public function setUserID(int $userID): LagoraRTM
    {
        $this->userID = $userID;

        return $this;
    }

    /**
     * @param int $minutes
     * @return LagoraRTM
     */
    public function setMinutes(int $minutes): LagoraRTM
    {
        $this->minutes = $minutes;

        return $this;
    }

    /**
     * @param int $seconds
     * @return LagoraRTM
     */
    public function setSeconds(int $seconds): LagoraRTM
    {
        $this->seconds = $seconds;

        return $this;
    }

    /**
     * @return $this
     * @throws Exception
     */
    public function getToken() : LagoraRTM
    {
        if (empty($this->channelName)) {
            throw InvalidChannelNameException::invalidChannelName($this->channelName);
        }
        $expireInSeconds = $this->minutes * 60 + $this->seconds;
        $currentTimestamp = (new \DateTime("now", new \DateTimeZone(config('app.timezone'))))->getTimestamp();
        $privilegeExpiredTs = $currentTimestamp + $expireInSeconds;

        $this->token = RtmTokenBuilder::buildToken($this->app_id, $this->app_certificate, $this->channelName, $this->role, $privilegeExpiredTs);

        return $this;
    }
}
