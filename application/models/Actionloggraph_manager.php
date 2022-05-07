<?php
class Actionloggraph_manager extends CI_Model{
	/**
	 * アクションロググラフ情報を取得する
	 */
	public function getActionLogGraph($userId,$actionlogfilterParams){
		$this->load->model(array('system_manager','Actionlog_manager'));

		$actionStatus = Actionlog_manager::ACTION_STATUS_COMPLETE;

		// ActionId
		$actionIdSql = "";
		if($actionlogfilterParams->actionId !== null){
			$actionIdSql = "and t_action_log.action_id = {$actionlogfilterParams->actionId}";
		}
		// 日付範囲
		$dateFrom = new DateTime($actionlogfilterParams->ymd . "/01");

		$ymd = $dateFrom->format("Y-m-d");
		$lastDay = $dateFrom->format("t");

		if($actionlogfilterParams->ymdType === Actionlogfilter_params_entity::YMD_TYPE_YEAR){
			// 仕様上、指定されない。
			throw new Exception('getActionLogCalender エラー: カレンダーで年が指定されました。');
		}
		elseif($actionlogfilterParams->ymdType === Actionlogfilter_params_entity::YMD_TYPE_MONTH){
		}
		elseif($actionlogfilterParams->ymdType === Actionlogfilter_params_entity::YMD_TYPE_DAY){
			// 仕様上、指定されない。
			throw new Exception('getActionLogCalender エラー: カレンダーで日が指定されました。');
		}

		$sql = <<< SQL
select 
	t_ymd.ymd ymd,
    count(t_action_log.action_log_id) cnt,
	IFNULL(sum(action_time_span),0) sumtime
from 
	(
		select
			date_format(date_add('{$ymd}', interval td.generate_series - 1 day), '%Y/%m/%d') as ymd
		from
		(
			SELECT 0 generate_series FROM DUAL WHERE (@num:=1-1)*0 UNION ALL
			SELECT @num:=@num+1 FROM `information_schema`.COLUMNS LIMIT {$lastDay}
		) as td
    ) t_ymd left join t_action_log
    on t_ymd.ymd = DATE_FORMAT(action_time_from,'%Y/%m/%d')
    {$actionIdSql}
	and t_action_log.user_id = {$userId}
	and t_action_log.delete_flg = 0
	and t_action_log.action_status = {$actionStatus}
group by t_ymd.ymd
SQL;

        $query = $this->db->query($sql);

		if ($query->num_rows() == 0){
			return false;
		}

		$resItems = array();
		foreach ($query->result() as $row)
		{
			$items = array(
				"ymd" => $row->ymd,
				"cnt" => $row->cnt,
				"sumtime" => $row->sumtime
			);

			array_push($resItems,$items);
		}

		return $resItems;
	}

}