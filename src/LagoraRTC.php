<?php

namespace Webtamizhan\Lagora;

use Exception;
use Webtamizhan\Lagora\Agora\RtcTokenBuilder;
use Webtamizhan\Lagora\Exceptions\AgoraRtcConfigurationNotFoundException;
use Webtamizhan\Lagora\Exceptions\InvalidChannelNameException;
use Webtamizhan\Lagora\Exceptions\RoleNotFoundException;

class LagoraRTC
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
     * @throws AgoraRtcConfigurationNotFoundException
     */
    public function __construct()
    {
        $this->app_id = config('lagora.rtc.app_id','');
        $this->app_certificate = config('lagora.rtc.app_certificate','');
        if (empty($this->app_id) || empty($this->app_certificate)) {
            throw AgoraRtcConfigurationNotFoundException::rtcNotConfigured();
        }
    }

    /**
     * @param string $channelName
     * @return LagoraRTC
     */
    public function setChannelName(string $channelName): LagoraRTC
    {
        $this->channelName = $channelName;
        return $this;
    }

    /**
     * @param int $role
     * @return LagoraRTC
     * @throws RoleNotFoundException
     * RoleAttendee = 0; RolePublisher = 1; RoleSubscriber = 2; RoleAdmin = 101;
     */
    public function setRole(int $role = 1): LagoraRTC
    {
        if(!in_array($role,[0,1,2,101])){
            throw RoleNotFoundException::roleNotFound($role);
        }
        $this->role = $role;
        return $this;
    }

    /**
     * @param int $userID
     * @return LagoraRTC
     */
    public function setUserID(int $userID): LagoraRTC
    {
        $this->userID = $userID;
        return $this;
    }

    /**
     * @param int $minutes
     * @return LagoraRTC
     */
    public function setMinutes(int $minutes): LagoraRTC
    {
        $this->minutes = $minutes;
        return $this;
    }

    /**
     * @param int $seconds
     * @return LagoraRTC
     */
    public function setSeconds(int $seconds): LagoraRTC
    {
        $this->seconds = $seconds;
        return $this;
    }

    /**
     * @return $this
     * @throws Exception
     */
    public function getToken() : LagoraRTC
    {
        if(empty($this->channelName)){
            throw InvalidChannelNameException::invalidChannelName($this->channelName);
        }
        $expireInSeconds = $this->minutes * 60 + $this->seconds;
        $currentTimestamp = (new \DateTime("now", new \DateTimeZone(config('app.timezone'))))->getTimestamp();
        $privilegeExpiredTs = $currentTimestamp + $expireInSeconds;

        $this->token = RtcTokenBuilder::buildTokenWithUid(
            $this->app_id, $this->app_certificate, $this->channelName, $this->userID, $this->role, $privilegeExpiredTs
        );
        return $this;
    }

}
