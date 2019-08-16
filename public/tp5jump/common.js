//全局根目录
var Root="";
Root='/';

//layui
layui.use(['element','layer'], function(){
  var $ = layui.jquery;
  var element = layui.element;
  layer=layui.layer;  
});
//弹出层
//var url='/'+'schoolyard'+'/'+'admin';
function x_admin_show(title,url,w,h){
	if (title == null || title == '') {
		title=false;
	};
	if (url == null || url == '') {
		url="404.html";
	};
	if (w == null || w == '') {
		w=800;
	};
	if (h == null || h == '') {
		h=($(window).height() - 50);
	};
	layer.open({
		type: 2,
		area: [w+'px', h +'px'],
		fix: false, //不固定
		maxmin: true,
		shadeClose: true,
		shade:0.4,
		title: title,
		content: url
	});		
}
//全局ajax新增和编辑及删除
//id 表字段的id
//n 操作如add(无)、edit(1)、delete(2)默认是add 
//data 数据
//url 接口

//调用示列 edit ：Ajaxalls(rid,data,1,'admin/Organizeinfo/edit'); 
//      add ：Ajaxalls(null,data,n,'admin/Organizeinfo/add'); 
//      delete ：Ajaxalls(rid,data,2,'admin/Organizeinfo/delete');

//后台示列
//public function edit(){  
//    $request=Request::instance();
//    $info=json_decode($request->post('data'),true);
//    $info['createtime']=date('Y-m-d H:i:s');
//    $info['createuser']=session('user_id');
//    $id=input('post.id');
//    $res=db('organizeinfo')->where('rid', $id)->update($info);
//    if ($res){       
//        $data['state']=1;
//        return json($data);
//    }else {
//        $data['state']=0;
//        return json($data);
//    }
//        
//    }    

var id,data,n,path,skip;
function Ajaxalls(id,data,n,path,skip){
	switch(n)
	{	
	case 1:	
	$.ajax({
			url:Root+path,
			type: "post",
			data:{"data":data,"id":id},
			dataType: "json",			
			success: function(data){
			if(data.state==1){		
			 layer.msg(data.msg,{icon:6,time:1000});
			 if(!skip){
					return false;
				}else{
					return  location.href = Root+skip;
			  }			 
			}else{
			layer.msg(data.msg,{icon:5,time:1000});	
			}
			},
			error:function(){
			layer.msg('服务异常');				
		    }			
		});		
	  break;
	case 2:	
		$.ajax({
			url:Root+path,
			type: "post",
			data:{"id":id},
			dataType: "json",			
			success: function(data){
				if(data.state==1){		
					 layer.msg(data.msg,{icon:6,time:1000});
					 //异步删除结合layui数据表格
					 $('td[data-field=rid]').each(function(){
			    		 if($(this).text()==id){
			    			  var index_id = $(this).parent('tr').attr('data-index');
		                      $('tr[data-index=' + index_id + ']').remove();
			    		 }
			    	 });
					 if(!skip){
							return false;
						}else{
							return  location.href = Root+skip;
					  }		
			 }else{
					layer.msg(data.msg,{icon:5,time:1000});	
					}
					},
					error:function(){
					layer.msg('服务异常');				
				    }			
		});		
	  break;
	default:			
	 $.ajax({
			url:Root+path,
			type: "post",
			data:{"data":data},
			dataType: "json",			
			success: function(data){
				if(data.state==1){		
					 layer.msg(data.msg,{icon:6,time:1000});
					 if(!skip){
							return false;
						}else{
							return  location.href = Root+skip;
					  }		
					}else{
					layer.msg(data.msg,{icon:5,time:1000});	
					}
					},
					error:function(){
					layer.msg('服务异常');				
				    }			
		});		
	}
		
}

//全选、批量删除
//批量删除示列
//1,在需要批量删除的button上加class="deleteinbatches" data-deleteinbatches="[true,url]" 
//true执行批量删除,url 删除的接口
//<button class="deleteinbatches" data-deleteinbatches="[true,deleteall]">批量删除</button>
//全选示列
//<input type="checkbox"   data-select="selectall"/>
//直接加 data-select="selectall"即可

function deleteinbatches(data,paths){
	  $.ajax({
			url: paths,
			type: "post",
			data:{"data":data},
			dataType: "json",			
			success: function(data){
			if(data.state==1){		
		    layer.msg(data.msg,{icon:6,time:1000});	
			location.href =location.href;			
			}else{
		    layer.msg(data.msg,{icon:5,time:1000});	
			}
			},
			error:function(){
		    layer.msg('服务异常');	
			
		}			
		});		
}

function turedel(){	
	  var vessel=$("input[data-select='selectall']").attr('data-select');	
	  var id ='input:not('+'#'+vessel+')';	
	  var checkobj =$(id);	
	  var $selectall=document.getElementById('selectall');	 
	  if($selectall.checked==false){	 
	    for(var i=0;i<checkobj.length;i++){
		  checkobj[i].checked = false;		
		} 		
	  }
	  else if($selectall.checked==true){
	    for(var i=0;i<checkobj.length;i++){
	    	checkobj[i].checked = true;		    	
		}	   
	  } 	  
}
$(function(){
	var vessel=$("input[data-select='selectall']").attr('data-select');	
	$("input[data-select='selectall']").attr('id',vessel);
	var vesselid='#'+$("input[data-select='selectall']").attr('id');	
	$(vesselid).click(function(){		
		turedel();
	});
	var flags=$(".deleteinbatches").attr('data-deleteinbatches');
	if(!flags){
		return false;
	}else{
		flags=flags.replace(/\[|]/g,'');
		var str=flags.split(',');
	}
   if(String(str[0])=='true' && str[1]!=''){
	   $(".deleteinbatches").click(function(){
	      var data=[];
	      var urls=str[1];	      
		  var vessel=$("input[data-select='selectall']").attr('data-select');	
		  var id ='input:not('+'#'+vessel+')';	
		  var selectid =$(id);	
			for (var i = 0; i < selectid.length; i++) {		
				if (selectid.eq(i).prop("checked")) {
					data[i]=selectid.eq(i).val();			
				}		
			}
			if(data.length==0){
		     layer.msg('没有选择要删除的数据',{icon:5,time:1000});	
		 	 return false;
			}else{
				 layer.confirm('真的删除这些数据吗', function(index){ 
					 deleteinbatches(data,urls);
				 });
			}
		
	   });
   }else{	   
   	return false;
   }
});




