<?php
	/**
	 * 访问量统计类
	 *
	 * @author：黄乐
	 * @version：1.0
	 * @lastupdate：2010-8-11
	 *
	 */


/*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    SQL:
 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
 	DROP TABLE IF EXISTS hl_counter;
  	CREATE TABLE `hl_counter` (
    `id` int(11) NOT NULL auto_increment,
    `ip` varchar(50) NOT NULL COMMENT &#39;IP地址&#39;,
    `counts` varchar(50) NOT NULL COMMENT &#39;统计访问次数&#39;,
 	`date` datetime NOT NULL COMMENT &#39;访问时间&#39;,
    PRIMARY KEY  (`id`)
 	)ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=gb2312;
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

/**
 +----------------------------------------------------------------------
    使用实例：
 +----------------------------------------------------------------------
    $counts_visits = new counter(&#39;hl_counter&#39;);	实例化对象
 +----------------------------------------------------------------------
    记录访问数：
    $counts_visits-&gt;record_visits();
 +----------------------------------------------------------------------
 	获取访问数据：
 	$counts_visits-&gt;get_sum_visits();			获取总访问量
 	$counts_visits-&gt;get_sum_ip_visits(); 		获取总IP访问量
 	$counts_visits-&gt;get_month_visits();			获取当月访问量
  	$counts_visits-&gt;get_month_ip_visits();		获取当月IP访问量
    $counts_visits-&gt;get_date_visits();			获取当日访问量
    $counts_visits-&gt;get_date_ip_visits(); 		获取当日IP访问量
 +----------------------------------------------------------------------
    上述仅为逻辑演示,本类可灵活使用
 +----------------------------------------------------------------------
 */

	class counts_visits{

		/*
		 * 获取表名
		 *
		 * @private String
		 */
			private $table;


		/**
		 * 构造函数
		 *
		 * @access public
	 	 * @parameter string $table 表名
		 * @return void
		 */
		public function __construct($table){
			$this-&gt;table = $table;
		}

		/**
		 * 获得客户端真实的IP地址
		 *
		 * @access public
		 * @return void
		 */
		public function getip(){
			if(getenv(&quot;HTTP_CLIENT_IP&quot;) &amp;&amp; strcasecmp(getenv(&quot;HTTP_CLIENT_IP&quot;), &quot;unknown&quot;)){
				$ip = getenv(&quot;HTTP_CLIENT_IP&quot;);
			}else if(getenv(&quot;HTTP_X_FORWARDED_FOR&quot;) &amp;&amp; strcasecmp(getenv(&quot;HTTP_X_FORWARDED_FOR&quot;), &quot;unknown&quot;)){
				$ip = getenv(&quot;HTTP_X_FORWARDED_FOR&quot;);
			}else if(getenv(&quot;REMOTE_ADDR&quot;) &amp;&amp; strcasecmp(getenv(&quot;REMOTE_ADDR&quot;), &quot;unknown&quot;)){
				$ip = getenv(&quot;REMOTE_ADDR&quot;);
			}else if(isset ($_SERVER[&#39;REMOTE_ADDR&#39;]) &amp;&amp; $_SERVER[&#39;REMOTE_ADDR&#39;] &amp;&amp; strcasecmp($_SERVER[&#39;REMOTE_ADDR&#39;], &quot;unknown&quot;)){
				$ip = $_SERVER[&#39;REMOTE_ADDR&#39;];
			}else{
				$ip = &quot;unknown&quot;;
			}
			return ($ip);
		}

		/**
		 * 记录访问数（默认一个IP每天只统计一次）
		 *
		 * @access public
		 * @return void
		 */
		public function record_visits(){
			$ip = $this-&gt;getip(); //获得客户端真实的IP地址
			$result = mysql_query(&quot;select * from $this-&gt;table where ip = &#39;$ip&#39;&quot;);
		 	$row = mysql_fetch_array($result);
		 	if(is_array($row)){
		 		if(!$_COOKIE[&#39;visits&#39;]){
					mysql_query(&quot;UPDATE $this-&gt;table SET `counts` =  &#39;&quot;.($row[counts]+1).&quot;&#39; WHERE `ip` = &#39;$ip&#39; LIMIT 1 ;&quot;);
		 		}
		 	}else{
		 		mysql_query(&quot;INSERT INTO $this-&gt;table(`id`,`ip`,`counts`,`date`)VALUES (NULL,&#39;$ip&#39;,&#39;1&#39;,Now());&quot;);
		 		setcookie(&#39;visits&#39;,$ip,time()+3600*24);
		 	}
		}

		/*
		 * 获取总访问量、月访问量、日访问量的共有方法
		 *
		 * @access private
		 * @parameter string $condition  sql语句条件
		 * @return integer
		 */
		private function get_visits($condition = &#39;&#39;){
			if($condition == &#39;&#39;){
				$query = mysql_query(&quot;select sum(counts) as counts from $this-&gt;table&quot;);
			}else{
				$query = mysql_query(&quot;select sum(counts) as counts from $this-&gt;table where $condition&quot;);
			}
			return mysql_result($query,0,&#39;counts&#39;);
		}

		/*
		 * 获取IP访问量的共有方法
		 *
		 * @access private
		 * @parameter string $condition  sql语句条件
		 * @return integer
		 */
		private function get_ip_visits($condition = &#39;&#39;){
			if($condition == &#39;&#39;){
				$query = mysql_query(&quot;select * from $this-&gt;table&quot;);
			}else{
				$query = mysql_query(&quot;select * from $this-&gt;table where $condition&quot;);
			}
			while($row = mysql_fetch_array($query)){
				$ip_visits_arr[] = $row[&#39;ip&#39;];
			}
			$ip_visits = count($ip_visits_arr);
			return $ip_visits;
		}

		/**
		 * 获取总访问量
		 *
		 * @access public
		 * @return integer
		 */
		public function get_sum_visits(){
			return $this-&gt;get_visits();
		}

		/**
		 * 获取总IP访问量
		 *
		 * @access public
		 * @return integer
		 */
		public function get_sum_ip_visits(){
			return $this-&gt;get_ip_visits();
		}

		/**
		 * 获取当月访问量
		 *
		 * @access public
		 * @return integer
		 */
		public function get_month_visits(){
			return $this-&gt;get_visits(&quot;DATE_FORMAT(date,&#39;%Y-%m&#39;) = &#39;&quot;.substr(date(&#39;Y-m-d&#39;),0,7).&quot;&#39;&quot;);
		}

		/**
		 * 获取当月IP访问量
		 *
		 * @access public
		 * @return integer
		 */
		public function get_month_ip_visits(){
			return $this-&gt;get_ip_visits(&quot;DATE_FORMAT(date,&#39;%Y-%m&#39;) = &#39;&quot;.substr(date(&#39;Y-m-d&#39;),0,7).&quot;&#39;&quot;);
		}

		/**
		 * 获取当日访问量
		 *
		 * @access public
		 * @return integer
		 */
		public function get_date_visits(){
			return $this-&gt;get_visits(&quot;DATE_FORMAT(date,&#39;%Y-%m-%d&#39;) = &#39;&quot;.date(&#39;Y-m-d&#39;).&quot;&#39;&quot;);
		}

		/**
		 * 获取当日IP访问量
		 *
		 * @access public
		 * @return integer
		 */
		public function get_date_ip_visits(){
			return $this-&gt;get_ip_visits(&quot;DATE_FORMAT(date,&#39;%Y-%m-%d&#39;) = &#39;&quot;.date(&#39;Y-m-d&#39;).&quot;&#39;&quot;);
		}
		
	}
?>
