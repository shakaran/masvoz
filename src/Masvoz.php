<?php namespace Holaluz\Masvoz;

use Illuminate\Config\Repository;
use Illuminate\Foundation\Application;

class Masvoz {

    protected $accountId;

    protected $user;

    protected $password;

    public function __construct($config = array())
    {
        if (isset($config['accountId'])) $this->accountId = $config['accountId'];

        if (isset($config['user'])) $this->user = $config['user'];

        if (isset($config['password'])) $this->password = $config['password'];
    }

    public function callATCList($startDate, $endDate)
    {
        $url = "https://manager.masvoz.es/api/rs/call/detail/ATC?accountId=" . $this->accountId .
            "&startDate=" . urlencode($startDate->format("Y-m-d H:i:s")) .
            "&endDate=" . urlencode($endDate->format("Y-m-d H:i:s"));

        return $this->callMasVoz($url);
    }

    public function queueStatisticsInfo()
    {
        $url = "https://manager.masvoz.es/api/rs/queue/stats?accountId=" . $this->accountId;

        return $this->callMasVoz($url);

    }

    public function nodes($nodeId = null)
    {
        $url = "https://manager.masvoz.es/api/rs/uac/nodes";

        if(! is_null($nodeId)) {
            $url .= "/" . $nodeId;
        }

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
