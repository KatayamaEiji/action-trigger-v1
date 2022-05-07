<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * アクションログフィルターのパラメータ
 */
class Actionlogfilter_params_entity 
{
    const LOG_TYPE_LIST = 'list';
    const LOG_TYPE_CALENDAR = 'calendar';
    const LOG_TYPE_GRAPH = 'graph';

    const YMD_TYPE_YEAR = 'year';
    const YMD_TYPE_MONTH = 'month';
    const YMD_TYPE_DAY = 'day';

    public $dispId = null;
    public $reDispId = null;
    public $logType = null;
    public $actionId = null;
    public $actionTitle = null;
    public $actionTriggerId = null;
    public $actionTriggerTitle = null;
    public $ymdType = null;
    public $ymd = null;

    /**
     * ログタイプセット
     */
    public function set_log_type($logType){
        $this->logType = $logType;
    }

    /**
     * DispIdセット
     */
    public function  set_disp_id($dispId,$reDispId){
        $this->dispId = $dispId;
        $this->reDispId = $reDispId;
    }

    /**
     * 現在の年月をセット
     */
    public function set_now_month(){
        $date = new DateTime();
        $yearMonth = $date->format('Y/m');
        
        $this->set_ymd($yearMonth);
    }


    /**
     * 現在の年をセット
     */
    public function set_now_year(){
        $date = new DateTime();
        $yearMonth = $date->format('Y');
        
        $this->set_ymd($yearMonth);
    }


    /**
     * 年月をセット
     */
    public function set_ym($ym){
        $this->set_ymd(substr($ym,0,7));
    }

    /**
     * 年をセット
     */
    public function set_year($year){
        $this->set_ymd(substr($year,0,4));
    }



    /**
     * 年月日をセット
     */
    public function set_ymd($ymd){
        $this->ymd = $ymd;

        // 2010/01
        if(preg_match("/^[0-9]{4}\/[0-9]{2}$/u", $ymd)){
            $this->ymdType = $this::YMD_TYPE_MONTH;
        }
        // 2010/01/01
        else if(preg_match("/^[0-9]{4}\/[0-9]{2}\/[0-9]{2}$/u", $ymd)){
            $this->ymdType = $this::YMD_TYPE_DAY;
        }
        // 2010
        else{
            $this->ymdType = $this::YMD_TYPE_YEAR;
        }
    }

    /**
     * ActionIDをセット
     */
    public function set_action_id($actionManager,$actionId){
        if($actionId === null || $actionId === "" || $actionId === 0){
            $this->actionId = null;
            $this->actionTitle = null;
        }
        else{
            $this->actionId = $actionId;
            $this->actionTitle = $actionManager->getActionTitle($actionId);
        }
    }

    /**
     * ActionTriggerIDをセット
     */
    public function set_action_trigger_id($actiontrigger_manager,$actionTriggerId){

        if($actionTriggerId === null || $actionTriggerId === "" || $actionTriggerId === 0){
            $this->actionTriggerId = null;
            $this->actionTriggerTitle = null;  
        }
        else{
            $this->actionTriggerId = $actionTriggerId;
            $this->actionTriggerTitle = $actiontrigger_manager->getActionTriggerTitle($actionTriggerId);  
    
        }
    }

	/**
	 * アクションログフィルターのパラメータを取得
	 */
	public function get_params(){
		return array(
            "dispId" => $this->dispId,
            "reDispId" => $this->reDispId,
            "logType" => $this->logType,
			"actionId" => $this->actionId,
			"actionTitle" => $this->actionTitle,
			"actionTriggerId" => $this->actionTriggerId,
			"actionTriggerTitle" => $this->actionTriggerTitle,
			"ymdType" => $this->ymdType,
			"ymd" => $this->ymd,
		);
	}
}
