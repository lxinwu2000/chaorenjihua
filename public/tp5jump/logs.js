//layui
layui.use(['element','layer'], function(){
  var $ = layui.jquery;
  var element = layui.element;
  layer=layui.layer;  
});
//table-全选日志
$(function(){
	var active_class = 'active';
	$('#simple-table > thead > tr > th input[type=checkbox]').eq(0).on('click', function(){
		var th_checked = this.checked;	
		$(this).closest('table').find('tbody > tr').each(function(){
			var row = this;
			if(th_checked) {
				$(row).addClass(active_class).find('input[type=checkbox]').eq(0).prop('checked', true);
			}			
			else {
				$(row).removeClass(active_class).find('input[type=checkbox]').eq(0).prop('checked', false);
			}				
		});
	});
});
//批量删除日志
function pldel(){
	var cq = $(".ace");
	var checkedList = new Array(); 	
	for (var i = 0; i < cq.length; i++) {		
		if (cq.eq(i).prop("checked")) {
			checkedList[i]=cq.eq(i).val();			
		}		
	}	
	layer.confirm('您确定要批量删除吗？', {
		btn : [ '确定', '取消' ]
	}, function() {
		$.ajax({
			url : Root+"admin/Log/pldel",
			type : "post",
			data : {
				'checkedList' : checkedList,				
			},
			dataType : "json",			
			success : function(data) {
				if (data.state == 1) {				
					location.href =location.href;
					layer.msg(data.msg, {
						icon : 6
					});
				} else {
					layer.msg(data.msg, {
						icon : 5
					});
				}
			},
			error : function() {
				layer.msg('服务异常！');
			}
		});
	});
}

