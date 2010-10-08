<?php
class main_2_8 {
	static public function main($framework_enable = null, $framework_require = null, $framework_module = null, $framework_action = null, $framework_parameter = null) {
		echo 'main';
		core::main($framework_enable, $framework_require, $framework_module, $framework_action, $framework_parameter);
	}
	static public function main1($framework_enable = null, $framework_require = null, $framework_module = null, $framework_action = null, $framework_parameter = null) {
		echo 'main1';
		core::main($framework_enable, $framework_require, $framework_module, $framework_action, $framework_parameter);
	}
	static public function main2($framework_enable = null, $framework_require = null, $framework_module = null, $framework_action = null, $framework_parameter = null) {
		echo 'main2';
		core::main($framework_enable, $framework_require, $framework_module, $framework_action, $framework_parameter);
	}
}
?>