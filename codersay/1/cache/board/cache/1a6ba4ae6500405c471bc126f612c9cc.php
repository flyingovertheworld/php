<?php if (!defined('THINK_PATH')) exit();?><!doctype html><html><head><title><?php echo C('SITE_TITLE');?></title><meta charset="utf-8" /><script type="text/javascript"> var module = '<?php echo strtolower(MODULE_NAME); ?>'; var action = '<?php echo ACTION_NAME;?>'; var tag_id = '<?php echo ($_GET["tag"]); ?>'; var node = '<?php echo ($_GET["node"]); ?>'; </script><link rel="stylesheet" type="text/css" href="../Public/style/layout.css" /><script type="text/javascript" src="__JS__/jQuery/jquery.js"></script><script type="text/javascript" src="__JS__/jQuery/plugin/cookie.js"></script><script type="text/javascript" src="__JS__/NDlogpublic.js"></script><script type="text/javascript" src="../Public/js/common.js"></script><script charset='utf-8' src='__PUBLIC__/editor/plugins/code/prettify.js'></script><link href="__JS__/jQuery/plugin/artDialog/skins/default.css" rel="stylesheet" /><script type="text/javascript" src="__JS__/jQuery/plugin/artDialog/artDialog.js"></script><script type="text/javascript" src="__JS__/jQuery/plugin/artDialog/artDialog.plugins.js"></script><script charset='utf-8' src='__PUBLIC__/editor/kindeditor.js'></script><script type="text/javascript">			jQuery(document).ready(function($){
				editor = KindEditor.create('#textarea',{
					uploadJson:'<?php echo U("uploadfile/uploadeditor");?>',
					fileManagerJson:'<?php echo U("filemanage/viewlist");?>',
					allowFileManager : true,
					sessionid : '<?php echo session_id(); ?>',
					cssPath : ['__PUBLIC__/editor/plugins/code/prettify.css'],
					afterUpload:function(data){
						// 上传成功后
					}
				});

				//上传缩略图
                $('#upthumb').click(function(){
                	if ( $(this).attr('checked') ){
                		$.frameDialog('<?php echo U("cropZoom/index");?>',{
							width : $(window).width() - 140,
							height : $(window).height() - 180,
							title : '上传缩略图',
							initfun : function(){
								$.removeFrameData('crop_zoom_thumb');
							},
							closefun : function(){
								var value = $.frameData('crop_zoom_thumb');// 读取B页面的数据
								if (value !== undefined && value != '') {
									$('thumb').value = value;
									$("#showThumb").show();
									var append = '?' + new Date().getTime() + 'a' + Math.random();
									$("#thumb_pic").attr("src",value+append);
								}
							}
						});
                	}
				});

				$('#sync_Qzone').click(function(){
                	var _OpenID = '<?php echo ($_SESSION["openid"]); ?>';
                	if ( ( $(this).attr('checked') ) && ( _OpenID == '' ) ){
                		alert('此功能需要同步QQ登录，你可以退出重新使用QQ同步登录。');
                		return false;
                	}

                });

				$('.more_title').click(function(){
					if ( $(this).hasClass('more_open') === false ){
						$(this).addClass('more_open').next('#more_option').slideDown();
					}else{
						$(this).removeClass('more_open').next('#more_option').slideUp();
					}
				});

				$('.attach_img_button').click(function(){
					var _this = $(this);
					if ( $('#attach_img_list').css("display") == 'block' ){
						$('#attach_img_list').slideUp();
					}else{
						if ( $('#attach_img_list li').length > 0 ){
							$('#attach_img_list').slideDown();
						}else{
							getAttachImgUped();
						}
					}
				});

				$('.attach_other_button').click(function(){
					var _this = $(this);
					if ( $('#attach_other_list').css("display") == 'block' ){
						$('#attach_other_list').slideUp();
					}else{
						if ( $('#attach_other_list li').length > 0 ){
							$('#attach_other_list').slideDown();
						}else{
							getAttachOtherUped();
						}
					}
				});

				$('#viewExistImage, #viewExistOther').click(function(){
                    var views = $(this).parent().next('ul');
                    if ( views.css('display') == 'block' ){
                        views.hide();
                    }else{
                        views.show();
                    }
                });

				$('.attach_img_refresh').click(function(){ getAttachImgUped(); });

				$('.editorupedImg, .existImage').live('click',function(){
                    editor.insertHtml('<img src="'+$(this).attr('rel')+'" class="content_img" />');
                });

                $('.editorupedother, .existother').live('click',function(){
                    editor.insertHtml('<a href="'+$(this).attr('rel')+'" target="_blank">'+$(this).text()+'</a>');
                });

				$('.delete_editor_img').live('click',function(){
					var _this = $(this);
					var _imgurl = _this.prev().find('img').attr('rel');
					if ( _imgurl !== null ){
						$.ajax({
							url : '<?php echo U("uploadfile/removeEditorUpedAttach");?>',
							success : function( res ){
								if ( res.status == 0 ){
									alert( res.data ); return false;
								}else{
									_this.parent().remove();
								}
							},
							cache : false,
							type : 'POST',
							data : 'imgurl='+_imgurl+'&type=deleteOne&deltype=url'
						});
					}
				});

				$('.delete_other_attach').live('click',function(){
					var _this = $(this);
					var _type = _this.attr('type');
					var _item = _this.attr('rel');
					if ( _item !== null ){
						$.ajax({
							url : '<?php echo U("uploadfile/removeEditorUpedAttach");?>',
							success : function( res ){
								if ( res.status == 0 ){
									alert( res.data ); return false;
								}else{
									_this.parents('li.other_item').remove();
								}
							},
							cache : false,
							type : 'POST',
							data : 'key='+_item+'&type=deleteOne&deltype='+_type
						});
					}
				});

				$('.deleteAllimg').click(function(){
					var _this = $(this);
					var _confirm = confirm('确认要全部删除吗？');
					if ( _confirm == true ){
						$.ajax({
							url : '<?php echo U("uploadfile/removeEditorUpedAttach");?>',
							success : function( res ){
								if ( res.status == 1 ){
									_this.parent().next('ul').remove();
									_this.parent().remove();
								}
							},
							cache : false,
							type : 'POST',
							data : 'file=image&type=deleteAll'
						});
					}
				});

				$('.deleteAllOther').click(function(){
					var _this = $(this);
					var _confirm = confirm('确认要全部删除吗？');
					if ( _confirm == true ){
						$.ajax({
							url : '<?php echo U("uploadfile/removeEditorUpedAttach");?>',
							success : function( res ){
								if ( res.status == 1 ){
									_this.parent().next('ul').remove();
									_this.parent().remove();
								}
							},
							cache : false,
							type : 'POST',
							data : 'file=other&type=deleteAll'
						});
					}
				});
                
                $('form').submit(function() {
                	var _eltit = $('input[name="title"]');
                    var _title = $.trim( _eltit.val() );
                    if ( _title == '' ){
                        NDlog._showErrorInfo( _eltit, '文档标题必须填写！' );
                        return false;
                    }
                });

                NDlog._checkErrorInput($('.error_input'));
			});
			function getAttachImgUped(){
				$.ajax({
					url : '<?php echo U("uploadfile/getUpedEditorAttach");?>',
					success : function( res ){
						$('#attach_img_list').html( res ).slideDown();
					},
					cache : false,
					type : 'GET',
					data : 'type=image',
					dataType : 'html'
				});
			}
			function getAttachOtherUped(){
				$.ajax({
					url : '<?php echo U("uploadfile/getUpedEditorAttach");?>',
					success : function( res ){
						$('#attach_other_list').html( res ).slideDown();
					},
					cache : false,
					type : 'GET',
					data : 'type=other',
					dataType : 'html'
				});
			}
		</script><style type="text/css">			.t_input		{ padding: 6px; }
			.delete_editor_img{ background: url(../Public/images/icons.png) -12px 0px no-repeat; width: 12px; height: 12px; position: absolute; top:5px; right: 5px; cursor: pointer; }
			.editorupedImg{ cursor: pointer; }
		</style></head><body><div id="wrap"><div id="header"><ul class="nav"><li class="home" rel="index"><a href="__APP__">首页</a></li><?php if(is_array($root_node)): $i = 0; $__LIST__ = $root_node;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$root): $mod = ($i % 2 );++$i; $action = $root['action']; if( empty( $root['action'] ) ){ $action = 'index'; } ?><li class="li <?php echo ($root["module"]); ?>" rel="<?php echo ($root["module"]); ?>"><a href="<?php echo U($root['module'].'/'.$action,array('node'=>$root['id']));?>"><?php echo ($root["name"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?><li class="li logout" rel="logout"><a href="<?php echo U('public/logout');?>">注销</a></li></ul></div><div id="container"><div id="sidebar_switch"></div><div id="content"><div id="c_warp"><h3 class="upedwrap">编辑文档<em class="nv">»</em><?php echo ($vo["title"]); ?></h3><form action="<?php echo U('update');?>" method="post"><input type="hidden" name="id" value="<?php echo ($vo["id"]); ?>" /><input type="hidden" name="type" value="<?php echo ($vo["type"]); ?>" /><table class="table_form" cellpadding="0" cellspacing="0" border="0" width="100%"><tr><td><input type="text" name="title" id="title" class="t_input w480" value="<?php echo ($vo["title"]); ?>" /><p class="remark">*文档标题，必须填写</p></td></tr><tr><td><textarea name="content" id="textarea" style="width:100%;height:400px;"><?php echo ($vo["content"]); ?></textarea></td></tr></table><?php  $attach_array = $_SESSION['editor_upload_list']; $tmp_array = array(); $otp_array = array(); foreach ($attach_array as $key => $value) { $ext = strtolower( end( explode( '.', $value['savename'] ) ) ); if ( $ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'bmp' || $ext == 'gif' ){ $tmp_array[$key] = $value; }else{ $otp_array[$key] = $value; } } unset($key,$value); if( !empty( $tmp_array ) ){ ?><div class="upedwrap" id="attach_img_notice">已上传 <span style="color:red;"><?php echo count($tmp_array); ?></span> 个图片，<a href="javascript:;" class="attach_img_button">查看</a> &nbsp; <a href="javascript:;" class="attach_img_refresh">刷新</a> &nbsp; <a href="javascript:;" class="deleteAllimg">清空</a></div><ul class="upedwrap ke-swfupload-body" style="overflow:auto; height:auto; padding-bottom:15px; margin:10px; display:none;" id="attach_img_list"></ul><?php  } if ( !empty( $otp_array ) ){ ?><div class="upedwrap" id="attach_other_notice">已上传 <span style="color:red;"><?php echo count($otp_array); ?></span> 个附件，<a href="javascript:;" class="attach_other_button">查看</a> &nbsp; <a href="javascript:;" class="attach_other_refresh">刷新</a> &nbsp; <a href="javascript:;" class="deleteAllOther">清空</a></div><ul class="upedwrap attach_other_body" style="display:none;" id="attach_other_list"></ul><?php
 } $attachmodel = M('Attach'); $picattach = $attachmodel->where("(type='jpg' OR type='jpeg' OR type='png' OR type='bmp' OR type='gif') AND cid='".$vo['id']."'")->select(); if ( !empty( $picattach ) ){ ?><p class="upedwrap" id="existImageAttach">本文档已保存的图片 <?php echo count($picattach);?> 个 &nbsp;&nbsp;&nbsp; <a href="javascript:;" id="viewExistImage">查看</a></p><ul class="upedwrap ke-swfupload-body" style="overflow:auto; height:auto; padding-bottom:15px; display:none; margin:10px;" id="exist_attach_img_list"><?php
 $path_ = C('TMPL_PARSE_STRING'); foreach( $picattach as $key => $value ){ ?><li class="ke-item" style="height:90px; overflow:hidden; width:91px; position: relative;"><div class="ke-photo" style="height:78px; overflow:hidden; width:79px;"><img src="<?php echo U('uploadfile/getEditorUploadImage', array('imgurl'=>$path_['__ATTACH__'].'/'.$value['folder'].'/'.$value['name'],'size'=>'75x75'));?>" class="existImage" rel="__ATTACH__/<?php echo ($value['folder']); ?>/<?php echo ($value['name']); ?>" /></div><div class="delete_exist_img" key="<?php echo ($value["id"]); ?>"></div></li><?php } ?><div class="clear"></div></ul><?php  } $otherattach = $attachmodel->where("type<>'jpg' AND type<>'jpeg' AND type<>'png' AND type<>'bmp' AND type<>'gif' AND cid='".$vo['id']."'")->select(); if ( !empty( $otherattach ) ){ ?><p class="upedwrap" id="existOtherAttach">本文档已保存其他附件 <?php echo count($otherattach);?> 个 &nbsp;&nbsp;&nbsp; <a href="javascript:;" id="viewExistOther">查看</a></p><ul class="upedwrap" style="display:none;" id="exist_attach_other_list"><?php unset($key,$value); foreach ($otherattach as $key => $value) { ?><li class="other_item"><table width="100%" cellpadding="0" cellspacing="0" border="0" class="table_list" bgcolor="#ffffff"><tbody><tr><td><a href="javascript:;" class="existother" rel="__ATTACH__/<?php echo ($value['folder']); ?>/<?php echo ($value['name']); ?>"><?php echo ($value['title']); ?></a></td><td align="center" width="40"><a href="javascript:;" class="delete_exist_attach" key="<?php echo ($value["id"]); ?>">删除</a></td></tr></tbody></table></li><?php } unset($key,$value); ?></ul><?php } ?><h4 class="more_title">更多选项</h4><div id="more_option"><table class="table_form" cellpadding="0" cellspacing="0" border="0" width="100%"><tr><td><select name="levelid" class="t_input"><option value="1" <?php if( $vo['levelid'] == 1 ){ echo ' selected="selected"'; } ?>>教程</option><option value="2" <?php if( $vo['levelid'] == 2 ){ echo ' selected="selected"'; } ?>>实例</option><option value="3" <?php if( $vo['levelid'] == 3 ){ echo ' selected="selected"'; } ?>>文档</option><option value="3" <?php if( $vo['levelid'] == 4 ){ echo ' selected="selected"'; } ?>>开源程序</option></select><p class="remark">选择文档级别。</p></td></tr><tr><td><input type="text" name="outurl" class="t_input w480" value="<?php echo ($vo['outurl']); ?>"/><p class="remark">外部链接地址</p></td></tr><tr><td><select name="cid" class="t_input"><?php echo ($option); ?></select><p class="remark">选择分类，不选择将默认记录文档类型，而不归类。</p></td></tr><?php
 $checkbox_o = ''; if ( !empty( $vo['thumb'] ) ){ if ( $_SESSION["crop_zoom"] ){ $thumb_array = explode( '/' , $_SESSION["crop_zoom"]["thumb_path"] ); $thumb_session = $thumb_array[count($thumb_array)-2] . '/thumb_' . $thumb_array[count($thumb_array)-1]; }else{ $thumb_session = $vo['thumb']; } $checkbox_o = ' checked="checked"'; ?><tr id="showThumb"><td><input type="hidden" name="thumb" id="thumb" value="<?php echo ($thumb_session); ?>" class="input_text w480" align="absmiddle" /><img src="__ATTACH__/<?php echo ($thumb_session); ?>" id="thumb_pic" /></td></tr><?php
 }else{ ?><tr id="showThumb" style="display:none;"><td><input type="hidden" name="thumb" id="thumb" value="" class="input_text w480" align="absmiddle" /><img src="__PUBLIC__/images/no_thumb.jpg" id="thumb_pic" /></td></tr><?php
 } ?><tr><td><lable class="option_lable"><input type="checkbox" class="checkbox" id="upthumb"<?php echo ($checkbox_o); ?> /> 自定义文档缩略图</lable><lable class="option_lable"><input type="checkbox" class="checkbox" class="checkbox" id="set_top" name="set_top" value="1"<?php if( $vo['set_top'] == 1 ){ echo ' checked="checked"'; } ?> /> 置顶</lable><lable class="option_lable"><input type="checkbox" class="checkbox" id="recommend" name="recommend" value="1"<?php if( $vo['recommend'] == 1 ){ echo ' checked="checked"'; } ?> /> 推荐</lable><lable class="option_lable"><input type="checkbox" class="checkbox" name="remoteAttach" id="remoteAttach" name="remoteAttach" /> 下载文档包含的远程图片</lable></td></tr><tr><td><input type="text" name="tags" class="t_input w480" value="<?php echo ($vo["tags"]); ?>" /><p class="remark">tags，多个可用空格分开。</p></td></tr><tr><td><input type="text" name="url_name" class="t_input w480" value="<?php echo ($vo["url_name"]); ?>" /><p class="remark">自定义文档的URL名称，用于URL优化，可不填写。</p></td></tr><tr><td><input type="text" name="keyword" id="keyword" class="t_input w480" value="<?php echo ($vo["keyword"]); ?>"><p class="remark">分类关键字，SEO相关，用于页面的 meta keywords，非必填。</p></td></tr><tr><td><textarea name="description" id="description" class="t_input w480" style="height:160px;"><?php echo ($vo["description"]); ?></textarea><p class="remark">分类说明，SEO相关，用于页面的 meta description，非必填。</p></td></tr></table></div><table class="table_form" cellpadding="0" cellspacing="0" border="0" width="100%"><tr><td><input type="submit" class="submit" value="提交表单" />				                	&nbsp;&nbsp;&nbsp;
				                	<input type="button" class="submit" value="返回" onclick="window.location.href='<?php echo U('index',array('node'=>$_GET['node']));?>';" /></td></tr></table></form></div></div><div id="sidebar"><div id="user_info"><div class="avatar"><img src="<?php echo (getuseravatar($_SESSION['AUTH']['id'])); ?>" width="45" /></div><dl class="info"><dt><?php echo ($_SESSION['AUTH']['nickname']); ?><dt><dd><?php echo ($_SESSION['AUTH']['remark']); ?></dd></dl><div class="clear"></div></div><?php if( $sidebar ){ ?><ol class="action_list"><?php if(is_array($sidebar)): $i = 0; $__LIST__ = $sidebar;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sidebar): $mod = ($i % 2 );++$i; if ( !empty( $sidebar['type_name'] ) ){ $json = json_decode($sidebar['type_name'],true); }else{ $json = array(); } $node['node'] = $sidebar['pid']; $node['tag'] = $sidebar['id']; $param = array_merge( $json, $node ); ?><li rel="<?php echo ($sidebar["module"]); ?>_<?php echo ($sidebar["action"]); ?>" tag="<?php echo ($sidebar["id"]); ?>"><a href="<?php echo U($sidebar['module'].'/'.$sidebar['action'],$param);?>"><?php echo ($sidebar["name"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?></ol><?php }else{ ?><div id="a_list"></div><?php } ?></div></div></div></body></html>