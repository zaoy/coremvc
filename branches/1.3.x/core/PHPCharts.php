<?php
/**
 * PHPCharts类库扩展
 * 
 * 这是CoreMVC类库扩展文件，需要有PHPCharts类库目录。
 * core.php ---> <b>core/PHPCharts.php</b> ---> PHPCharts类库目录
 */

/**
 * 执行(execute)
 */

define("CHARTS_SOURCE", dirname(__FILE__) . '/' . basename(__FILE__ , '.php') . '/'); 
define("BAR_CHART", 1);
define("LINE_CHART", 2);
define("PIE_CHART", 3);
define("BAR_LINE_CHART", 4);

include(CHARTS_SOURCE . "function.php");
include(CHARTS_SOURCE . "gridchartclass.php");
include(CHARTS_SOURCE . "colormanagerclass.php");
include(CHARTS_SOURCE . "chartclass.php");
include(CHARTS_SOURCE . "valueclass.php");
include(CHARTS_SOURCE . "axisclass.php");
include(CHARTS_SOURCE . "colornameclass.php");
include(CHARTS_SOURCE . "pieclass.php");
?>