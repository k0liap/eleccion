<?php
/**
 * Config file, return as json_encode
 * http://www.aa-team.com
 * =======================
 *
 * @author		Andrei Dinca, AA-Team
 * @version		1.0
 */
echo json_encode(
	array(
		'product_report' => array(
			'version' => '1.0',
			'menu' => array(
				'order' => 5,
				'show_in_menu' => true,
				'title' => 'Product Report',
				'icon' => 'bug'
			),
			'description' => "This module allows you to send a product report when you encounter an issue with a imported product.",
			'module_init' => 'init.php',
			'load_in' => array(
				'backend' => array(
					'post.php',
					'admin-ajax.php'
				),
				'frontend' => false
			),
			'javascript' => array(
				'admin',
				'hashchange',
				'tipsy'
			),
			'css' => array(
				'admin',
				'tipsy'
			)
		)
	)
);
