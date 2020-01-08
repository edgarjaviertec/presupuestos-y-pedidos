<?php


class Query_builder
{


	private $statement;

	function __construct()
	{
		$this->statement = "";
	}

	function get_sql_statement()
	{
		return $this->statement;
	}


	function add_select($table, $fields)
	{
		$this->statement = "SELECT ";
		foreach ($fields as $index => $field) {
			$comma = $index === (count($fields) - 1) ? '' : ', ';
			$this->statement .= "{$field}{$comma}";
		}
		$this->statement .= " FROM {$table}";
		return $this;
	}

	function add_order($orderBy, $direction)
	{
		//$direction = $ascending == 1 ? 'ASC' : 'DESC';
		$this->statement .= " ";
		$this->statement .= "ORDER BY {$orderBy} {$direction}";
		return $this;
	}

	function add_limit($offset, $limit)
	{
		//$offset = $limit * ($page - 1);
		$this->statement .= " ";
		$this->statement .= "LIMIT {$offset}, {$limit}";
		return $this;
	}

	function add_filter($query, $fields)
	{
		$this->statement .= " ";

		foreach ($fields as $index => $field) {
			$method = $index ? ' OR' : 'WHERE';
			$this->statement .= "{$method} {$field} LIKE '%{$query}%'";
		}
		return $this;
	}



}

?>



