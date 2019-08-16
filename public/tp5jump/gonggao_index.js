//教师信息index
layui.use('table', function(){
  var table = layui.table;
 
  table.render({    //表格渲染 
     elem: '#commontable'
    ,url:Root+'admin/Gonggao/json'
    ,height: 'full-150'   
    ,cellMinWidth: 80 
    ,page: { //支持传入 laypage 组件的所有参数（某些参数除外，如：jump/elem） - 详见文档
     layout: ['limit', 'count', 'prev', 'page', 'next', 'skip'] //自定义分页布局      
     ,limit: 10//默认 值
     ,curr: 1 //设定初始在第 1 页
     ,groups: 5 //只显示 1 个连续页码
     ,first: true //不显示首页false
     ,last: true //不显示尾页false
      
    }    
    ,cols: [[
	   {type:'numbers',title:'记录号',width:80}	  
      ,{field:'id', width:80, title: 'ID', sort: true,align:'center',unresize: true}  
      ,{field:'title', width:190, title: '公告标题',align:'center'}  
      ,{field:'is_show', width:90, title: '是否显示',align:'center',sort: true}
      ,{field:'addtime', width:180, title: '添加/更新时间',align:'center',sort: true}  
      ,{field:'right',Minwidth:190, title: '操作',toolbar:"#barDemob"}
    ]] 
    ,id: 'table_b'//重载表格唯一id
  });
  table.on('checkbox(Idtableb)', function(obj){		 
	 /*  console.log(obj); */
  });
  table.on('tool(Idtableb)', function(obj){
	    var id =obj.data.id;
	    //删除
	   if(obj.event === 'del'){
	      layer.confirm('真的删除这条数据么', function(index){	    		    		    	 
	    	  window.location.href="delete?id="+id;	    
//	    	  obj.del();
	      });
	    } 
	 //编辑
	   else if(obj.event === 'edit'){
		   window.location.href="edit?id="+id;	    	
	    		    	
	    }
	  });
  var $ = layui.$, active = {
		    reload: function(){
		      var demoReload = $('#demoReload');
		      table.reload('table_b', {
		        page: {
		          curr: 1 
		        }
		      //关键词搜索
		        ,where: {
		          key:demoReload.val()         
		        }
		      });
		    }
  //批量获取要删除的id
         
 };	 
  $('.demoTableb .layui-btn').on('click', function(){
    var type = $(this).data('type');
    active[type] ? active[type].call(this) : '';
  });
    
});

