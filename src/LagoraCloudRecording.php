<?php


namespace Webtamizhan\Lagora;


use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Webtamizhan\Lagora\Exceptions\AgoraConfigurationNotFoundException;
use Webtamizhan\Lagora\Exceptions\CloudRecordingAcquireException;
use Webtamizhan\Lagora\Exceptions\CloudRecordingBucketConfigurationNotFoundException;
use Webtamizhan\Lagora\Exceptions\CloudRecordingConfigurationNotFoundException;
use Webtamizhan\Lagora\Exceptions\CloudRecordingQueryCallStatusException;
use Webtamizhan\Lagora\Exceptions\CloudRecordingStartCallException;
use Webtamizhan\Lagora\Exceptions\CloudRecordingStopCallException;
use Webtamizhan\Lagora\Exceptions\InvalidChannelNameException;
use Webtamizhan\Lagora\Exceptions\InvalidTokenException;
use Webtamizhan\Lagora\Models\CloudRecording;

class LagoraCloudRecording
{
    public string $endPoint;

    public string $app_id;

    public string $customerID;

    public string $customerCertificate;

    public string $token;

    public string $resourceID;

    public string $accessChannel;

    public int $recordingUID;

    public int $vendor;

    public int $region;

    public string $bucket;

    public string $accessKey;

    public string $secretKey;

    public array $fileNamePrefix;

    public string $sid;

    public string $mode;

    public CloudRecording $cloudRecording;

    /**
     * @throws AgoraConfigurationNotFoundException
     * @throws CloudRecordingBucketConfigurationNotFoundException
     * @throws CloudRecordingConfigurationNotFoundException
     */
    public function __construct(){
        $this->app_id = config('lagora.rtc.app_id','');
        if(empty($this->app_id)){
            throw AgoraConfigurationNotFoundException::rtcNotConfigured();
        }
        $this->endPoint = "https://api.agora.io/v1/apps/" . $this->app_id . "/cloud_recording";
        $this->customerID = config('lagora.cloud_recording.customer_id','');
        $this->customerCertificate = config('lagora.cloud_recording.customer_certificate','');
        if(empty($this->customerID) || empty($this->customerCertificate)){
            throw CloudRecordingConfigurationNotFoundException::notFound();
        }

        //Bucket config
        $this->vendor = config('lagora.cloud_recording.storageConfig.vendor','');
        $this->region = config('lagora.cloud_recording.storageConfig.region','');
        $this->bucket = config('lagora.cloud_recording.storageConfig.bucket','');
        $this->accessKey = config('lagora.cloud_recording.storageConfig.accessKey','');
        $this->secretKey = config('lagora.cloud_recording.storageConfig.secretKey','');
        $this->fileNamePrefix = config('lagora.cloud_recording.storageConfig.fileNamePrefix',[]);
        if(empty($this->vendor) || empty($this->region) || empty($this->bucket) || empty($this->accessKey) || empty($this->secretKey)){
            throw CloudRecordingBucketConfigurationNotFoundException::notFound();
        }
    }

    /**
     * @param $channelName
     * @return $this
     */
    public function setAccessChannel($channelName): LagoraCloudRecording
    {
        $this->accessChannel = $channelName;
        return $this;
    }

    /**
     * @param $token
     * @return $this
     */
    public function setToken($token): LagoraCloudRecording
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @param mixed $resourceID
     */
    public function setResourceID($resourceID): LagoraCloudRecording
    {
        $this->resourceID = $resourceID;
        return $this;
    }

    /**
     * @param mixed $sid
     */
    public function setSid($sid): LagoraCloudRecording
    {
        $this->sid = $sid;
        return $this;
    }

    /**
     * @param mixed $recordingUID
     * @return LagoraCloudRecording
     */
    public function setRecordingUID($recordingUID): LagoraCloudRecording
    {
        $this->recordingUID = $recordingUID;
        return $this;
    }

    /**
     * @param string $mode
     * @return LagoraCloudRecording
     */
    public function setMode(string $mode): LagoraCloudRecording
    {
        $this->mode = $mode;
        return $this;
    }

    /**
     * @throws InvalidTokenException
     * @throws InvalidChannelNameException
     * @throws CloudRecordingStartCallException
     * @throws CloudRecordingAcquireException
     */
    public function startRecordingCall()
    {

        if(empty($this->accessChannel)){
            throw InvalidChannelNameException::invalidChannelName($this->accessChannel);
        }
        if(empty($this->token)){
            throw InvalidTokenException::invalidToken($this->token);
        }
        if(empty($this->mode)){
            self::setMode(config('lagora.cloud_recording.recording_mode'));
        }
        if(empty($this->recordingUID)){
            self::setRecordingUID(rand(1111,9999));
        }

        //acquire resource id in-order to start recording call
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->withBasicAuth($this->customerID, $this->customerCertificate)
            ->post($this->endPoint . '/acquire', [
                "cname" => (string)$this->accessChannel,
                "uid" => (string)$this->recordingUID,
                "clientRequest" => [
                    "resourceExpiredHour" => config('lagora.resourceExpiredHour',24)
                ]
            ]);

        if ($response->successful()) {
            //start a call
            self::setResourceID($response->json()['resourceId']);

            $call_array = [
                "cname" => (string)$this->accessChannel,
                "uid" => (string)$this->recordingUID,
                "clientRequest" => [
                    "token" => (string)$this->token,
                    "recordingConfig" => config('lagora.recordingConfig'),
                    "recordingFileConfig" => config('lagora.recordingFileConfig'),
                    "storageConfig" => [
                        "vendor" => (int)$this->vendor,
                        "region" => (int)$this->region,
                        "bucket" => (string)$this->bucket,
                        "accessKey" => $this->accessKey,
                        "secretKey" => $this->secretKey,
                        "fileNamePrefix" => $this->fileNamePrefix
                    ]
                ]
            ];

            //if everything ok, start a call
            $callResponse = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->withBasicAuth($this->customerID, $this->customerCertificate)
                ->bodyFormat('json')
                ->post($this->endPoint . '/resourceid/' . $this->resourceID . "/mode/".$this->mode."/start", $call_array);

            if ($callResponse->successful()) {

                $this->sid = $callResponse->json()['sid'];

                $cloud_record = new CloudRecording();
                $cloud_record->channel_name = $this->accessChannel;
                $cloud_record->token = $this->token;
                $cloud_record->channel_user_id = $this->recordingUID;
                $cloud_record->resource_id = $this->resourceID;
                $cloud_record->sid = $this->sid;
                $cloud_record->save();

                $this->cloudRecording = $cloud_record;
                return $this;
            } else {
                throw CloudRecordingStartCallException::exception($response->body());
            }

        } else {
            throw CloudRecordingAcquireException::acquireException($response->body());
        }
    }


    /**
     * @throws CloudRecordingStopCallException
     */
    public function stopCall()
    {
        //stop call
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->withBasicAuth($this->customerID, $this->customerCertificate)
            ->post($this->endPoint . '/resourceid/' . $this->resourceID . "/sid/".$this->sid."/mode/".$this->mode."/stop", [
                "cname" => (string)$this->accessChannel,
                "uid" => (string)$this->recordingUID,
                "clientRequest" => [
                    "resourceExpiredHour" => config('lagora.resourceExpiredHour',24)
                ]
            ]);
        if ($response->successful()) {
            return $this;
        } else {
            throw CloudRecordingStopCallException::exception($response->body());
        }
    }

    /**
     * @throws CloudRecordingQueryCallStatusException
     */
    public function queryCallStatus()
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->withBasicAuth($this->customerID, $this->customerCertificate)
            ->get($this->endPoint . '/resourceid/' . $this->resourceID . "/sid/".$this->sid."/mode/".$this->mode."/query");
        if ($response->successful()) {
            return $this;
        } else {
            throw CloudRecordingQueryCallStatusException::exception($response->body());
        }
    }
}
