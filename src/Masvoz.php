<?php namespace Holaluz\Masvoz;

use Illuminate\Config\Repository;
use Illuminate\Foundation\Application;

class Masvoz {

    protected $accountId;

    protected $user;

    protected $password;

    protected $params;

    public function __construct($config = array())
    {
        if (isset($config['accountId'])) $this->accountId = $config['accountId'];

        if (isset($config['user'])) $this->user = $config['user'];

        if (isset($config['password'])) $this->password = $config['password'];
    }

    public function callATCList($startDate = null, $endDate = null)
    {
        $url = "https://manager.masvoz.es/api/rs/call/detail/ATC?accountId=" . $this->accountId;

        if(! is_null($startDate)) {
            $url .= "&startDate=" . urlencode($startDate);
        }
        if(! is_null($endDate)) {
            $url .= "&endDate=" . urlencode($endDate);
        }

        return $this->callMasVoz($url);
    }

    public function callDestList($startDate = null, $endDate = null)
    {
        $url = "https://manager.masvoz.es/api/rs/call/detail/DEST?accountId=" . $this->accountId;

        if(! is_null($startDate)) {
            $url .= "&startDate=" . urlencode($startDate);
        }
        if(! is_null($endDate)) {
            $url .= "&endDate=" . urlencode($endDate);
        }

        return $this->callMasVoz($url);
    }

    public function callATCStats($startDate, $endDate, $groupMode)
    {
        $url = "https://manager.masvoz.es/api/rs/call/detail/DEST?accountId=" . $this->accountId .
            "&startDate=" . urlencode($startDate) .
            "&endDate=" . urlencode($endDate) .
            "&groupMode=" . $groupMode;

        return $this->callMasVoz($url);
    }

    public function queueStatisticsInfo($startDate = null, $endDate = null, $nodeId = null)
    {
        $url = "https://manager.masvoz.es/api/rs/queue/stats?accountId=" . $this->accountId;

        if(! is_null($startDate)) {
            $url .= "&startDate=" . $startDate;
        }

        if(! is_null($endDate)) {
            $url .= "&endDate=" . $endDate;
        }

        if(! is_null($nodeId)) {
            $url .= "&nodeId=" . $nodeId;
        }

        return $this->callMasVoz($url);

    }

    public function nodes($nodeId = null)
    {
        $url = "https://manager.masvoz.es/api/rs/uac/nodes";

        if(! is_null($nodeId)) {
            $url .= "/" . $nodeId;
        }

        $url .= "?accountId=" . $this->accountId;

        return $this->callMasVoz($url);
    }

    public function account()
    {
        $url = "https://manager.masvoz.es/api/rs/uac/accounts/" . $this->accountId;

        return $this->callMasVoz($url);
    }

    public function agent($agentId)
    {
        $url = "https://panel.masvoz.es/rs/supervisor/agent/$this->accountId/$agentId";

        return $this->callMasVoz($url);
    }

    private function callMasVoz($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "$this->user:$this->password");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json"));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $jsonString = curl_exec($ch);
        curl_close($ch);

        return json_decode($jsonString, true);
    }
}
