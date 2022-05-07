<?php
class Actionlog_year_graph_manager extends CI_Model{
	/**
	 * アクションロググラフ情報を取得する
	 */
	public function getActionLogYearGraph($userId,$actionlogfilterParams){
		$this->load->model(array('system_manager','Actionlog_manager'));

		$actionStatus = Actionlog_manager::ACTION_STATUS_COMPLETE;

		// ActionId
		$actionIdSql = "";
		if($actionlogfilterParams->actionId !== null){
			$actionIdSql = "and t_action_log.action_id = {$actionlogfilterParams->actionId}";
		}
		// 年
		$ymdFrom = $actionlogfilterParams->ymd . "/01/01";

		if($actionlogfilterParams->ymdType === Actionlogfilter_params_entity::YMD_TYPE_YEAR){
		}
		elseif($actionlogfilterParams->ymdType === Actionlogfilter_params_entity::YMD_TYPE_MONTH){
			// 仕様上、指定されない。
			throw new Exception('getActionLogYearGraph エラー: 年グラフで月が指定されました。');
		}
		elseif($actionlogfilterParams->ymdType === Actionlogfilter_params_entity::YMD_TYPE_DAY){
			// 仕様上、指定されない。
			throw new Exception('getActionLogYearGraph エラー: 年グラフで日が指定されました。');
		}

		$sql = <<< SQL
select 
	t_ym.ym ym,
    count(t_action_log.action_log_id) cnt,
	IFNULL(sum(action_time_span),0) sumtime
from 
	(
		select
			date_format(date_add('{$ymdFrom}', interval td.generate_series - 1 month), '%Y/%m') as ym
		from
		(
			SELECT 0 generate_series FROM DUAL WHERE (@num:=1-1)*0
            UNION ALL
			SELECT @num:=@num+1 FROM `information_schema`.COLUMNS LIMIT 12
		) as td
    ) t_ym left join t_action_log
    on t_ym.ym = DATE_FORMAT(action_time_from,'%Y/%m')
    {$actionIdSql}
	and t_action_log.user_id = {$userId}
	and t_action_log.delete_flg = 0
	and t_action_log.action_status = {$actionStatus}
group by t_ym.ym
order by t_ym.ym
SQL;

        $query = $this->db->query($sql);

		if ($query->num_rows() == 0){
			return false;
		}

		$resItems = array();
		foreach ($query->result() as $row)
		{
			$items = array(
				"ym" => $row->ym,
				"cnt" => $row->cnt,
				"sumtime" => $row->sumtime
			);

			array_push($resItems,$items);
		}

		return $resItems;
	}

}