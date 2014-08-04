<?php
$i = 0;
return array (
		array (
				'name' => '首页',
				'children' => array (
						array (
								'id' => ++ $i,
								'name' => '个人桌面',
								'url' => 'Index/index',
								'is_open' => true ,
								'is_hidden'=>true
						),
						array (
								'id' => ++ $i,
								'name' => '基本信息',
								'url' => 'Index/home',
								'is_open' => true
						),
						array (
								'id' => ++ $i,
								'name' => '站点配置',
								'url' => 'Index/setting' 
						) 
				) 
		),
		array (
				'name' => '管理员管理',
				'children' => array (
						array (
								'id' => ++ $i,
								'name' => '管理员管理',
								'url' => 'Admin/index' 
						),
						array (
								'id' => ++ $i,
								'name' => '管理员添加',
								'url' => 'Admin/save',
								'is_hidden' => true 
						),
						array (
								'id' => ++ $i,
								'name' => '管理员编辑',
								'url' => 'Admin/update',
								'is_hidden' => true 
						),
						array (
								'id' => ++ $i,
								'name' => '管理员删除',
								'url' => 'Admin/delete',
								'is_hidden' => true 
						),
						array (
								'id' => ++ $i,
								'name' => '角色管理',
								'url' => 'Role/index' 
						),
						array (
								'id' => ++ $i,
								'name' => '角色添加',
								'url' => 'Role/save',
								'is_hidden' => true 
						),
						array (
								'id' => ++ $i,
								'name' => '角色编辑',
								'url' => 'Role/update',
								'is_hidden' => true 
						),
						array (
								'id' => ++ $i,
								'name' => '角色删除',
								'url' => 'Role/delete',
								'is_hidden' => true 
						) 
				) 
		),
		array (
				'name' => '新闻管理',
				'children' => array (
						array (
								'id' => ++ $i,
								'name' => '新闻管理',
								'url' => 'News/index' 
						),
						array (
								'id' => ++ $i,
								'name' => '新闻添加',
								'url' => 'News/save',
								'is_hidden'=>true
						),
						array (
								'id' => ++ $i,
								'name' => '新闻编辑',
								'url' => 'News/update',
								'is_hidden'=>true
						),
						array (
								'id' => ++ $i,
								'name' => '新闻删除',
								'url' => 'News/delete',
								'is_hidden'=>true
						),
						array (
								'id' => ++ $i,
								'name' => '新闻类别管理',
								'url' => 'NewsCategory/index' 
						) ,
						array (
								'id' => ++ $i,
								'name' => '新闻类别添加',
								'url' => 'NewsCategory/save',
								'is_hidden'=>true
						),
						array (
								'id' => ++ $i,
								'name' => '新闻类别编辑',
								'url' => 'NewsCategory/update',
								'is_hidden'=>true
						),
						array (
								'id' => ++ $i,
								'name' => '新闻类别删除',
								'url' => 'NewsCategory/delete',
								'is_hidden'=>true
						),
				) 
		),
		array (
				'name' => '问答管理',
				'children' => array (
						/*
						array (
								'id' => ++ $i,
								'name' => '问答管理',
								'url' => 'Question/index' 
						),
						array (
								'id' => ++ $i,
								'name' => '问答添加',
								'url' => 'Question/save',
								'is_hidden'=>true
						),
						array (
								'id' => ++ $i,
								'name' => '问答编辑',
								'url' => 'Question/update',
								'is_hidden'=>true
						),
						array (
								'id' => ++ $i,
								'name' => '问答删除',
								'url' => 'Question/delete',
								'is_hidden'=>true
						),
						*/
						array (
								'id' => ++ $i,
								'name' => '问答类别管理',
								'url' => 'QuestionCategory/index' 
						),
						array (
								'id' => ++ $i,
								'name' => '问答类别添加',
								'url' => 'QuestionCategory/save',
								'is_hidden'=>true
						),
						array (
								'id' => ++ $i,
								'name' => '问答类别编辑',
								'url' => 'QuestionCategory/update',
								'is_hidden'=>true
						),
						array (
								'id' => ++ $i,
								'name' => '问答类别删除',
								'url' => 'QuestionCategory/delete',
								'is_hidden'=>true
						)
				) 
		),
		array (
				'name' => '用户管理',
				'children' => array (
						array (
								'id' => ++ $i,
								'name' => '用户管理',
								'url' => 'User/index' 
						),
						array (
								'id' => ++ $i,
								'name' => '用户编辑',
								'url' => 'User/update',
								'is_hidden'=>true
						),
						array (
								'id' => ++ $i,
								'name' => '用户删除',
								'url' => 'User/delete',
								'is_hidden'=>true
						),
				) 
		),
		array (
				'name' => '幻灯片管理',
				'children' => array (
						array (
								'id' => ++ $i,
								'name' => '幻灯片管理',
								'url' => 'Slider/index' 
						),
						array (
								'id' => ++ $i,
								'name' => '幻灯片添加',
								'url' => 'Slider/save',
								'is_hidden'=>true
						),
						array (
								'id' => ++ $i,
								'name' => '幻灯片编辑',
								'url' => 'Slider/update',
								'is_hidden'=>true
						),
						array (
								'id' => ++ $i,
								'name' => '幻灯片删除',
								'url' => 'Slider/delete',
								'is_hidden'=>true
						)
				)
		),
		array (
				'name' => '访问统计',
				'children' => array (
						array (
								'id' => ++ $i,
								'name' => '用户访问日志',
								'url' => 'Tongji/userAccessLog'
						),
						array (
								'id' => ++ $i,
								'name' => '用户访问统计',
								'url' => 'Tongji/userTongji'
						),
						array(
								'id'=>++$i,
								'name'=>'搜索日志',
								'url'=>'Tongji/search',
						),
						array(
								'id'=>++$i,
								'name'=>'搜索日志统计',
								'url'=>'Tongji/searchTongji',
						)
							
				)
		),
		/*
		array (
				'name' => '特殊主题',
				'children' => array (
						array (
								'id' => ++ $i,
								'name' => '特殊主题管理',
								'url' => 'Special/index' 
						),
						array(
								'id'=>++$i,
								'name'=>'编辑特殊主题',
								'url'=>'Special/update',
								'is_hidden'=>true
						)
						 
				) 
		),
		*/
);