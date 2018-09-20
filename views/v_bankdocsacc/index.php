<?php
use yii\helpers\Html;
$this->title = 'Банковские документы';
$this->params['breadcrumbs'][] = $this->title;
?>
<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<style>
.tree {
    min-height:20px;
    padding:19px;
    margin-bottom:20px;
    background-color:#fbfbfb;
    border:1px solid #999;
    -webkit-border-radius:4px;
    -moz-border-radius:4px;
    border-radius:4px;
    -webkit-box-shadow:inset 0 1px 1px rgba(0, 0, 0, 0.05);
    -moz-box-shadow:inset 0 1px 1px rgba(0, 0, 0, 0.05);
    box-shadow:inset 0 1px 1px rgba(0, 0, 0, 0.05)
}
.tree li {
    list-style-type:none;
    margin:0;
    padding:10px 5px 0 5px;
    position:relative
}
.tree li::before, .tree li::after {
    content:'';
    left:-20px;
    position:absolute;
    right:auto
}
.tree li::before {
    border-left:1px solid #999;
    bottom:50px;
    height:100%;
    top:0;
    width:1px
}
.tree li::after {
    border-top:1px solid #999;
    height:20px;
    top:25px;
    width:25px
}
.tree li span {
    -moz-border-radius:5px;
    -webkit-border-radius:5px;
    border:1px solid #999;
    border-radius:5px;
    display:inline-block;
    padding:3px 8px;
    text-decoration:none
}
.tree li.parent_li>span, .tree li>span {
    cursor:pointer;
}
.tree>ul>li::before, .tree>ul>li::after {
    border:0
}
.tree li:last-child::before {
    height:30px
}
.tree li>span:hover, .tree li.parent_li>span:hover, .tree li.parent_li>span:hover+ul li span {
    background:#eee;
    border:1px solid #94a0b4;
    color:#000
}
.scroller{
	position:fixed;
	bottom:0;
	left:0;
	width:100%;
	background:#FFF;
	border-top:1px solid #94a0b4;
	cursor:pointer;
	padding:15px 0;
	text-align:center;
	font-size:16px;
}

.actionOptions{
	position:fixed;
	top:0;
	left:0;
	width:100%;
	background:#FFF;
	border-bottom:1px solid #94a0b4;
	cursor:pointer;
	padding:20px 0;
	text-align:center;
	z-index:9998;
	font-size:16px;
}

#treeRelationItemsParams table{
	table-layout:fixed;
}
#treeRelationItemsParams td{
	word-wrap:break-word;
}
.glyphicon{
	font-size:17px;
}
.actionOptions .glyphicon{
	top:4px !important;
}
#modalWraper{
	position:fixed;
	overflow-y:auto;
	top:0;
	left:0;
	height:100%;
	width:100%;
	background-color:rgba(0,0,0,0.8);
	z-index:9999;
}

#modalArea{
	width:100%;
	max-width:550px;
	height:auto;
	padding:20px;
	margin:15% auto 100px auto;
	background-color:rgba(255,255,255,1);
	position:relative;
	font-size:16px;
}

.modalClose{
	position:absolute;
	top:-60px;
	right:0;
	width:50px;
	height:50px;
	background-color:rgba(255,255,255,1);
	cursor:pointer;
}

.input-sm{
	margin-bottom:10px;
}

@media (max-width: 992px){
	.col-md-6{
		padding:0;
	}
	.table-bordered{
		font-size:13px;
	}
	.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
		padding: 7px;
	}
	#modalArea{
		margin:45% auto 100px auto;
	}
}
</style>

<h1><?= Html::encode($this->title) ?></h1>

<div class="tree well col-md-6">
<?php
echo $v_bankdocsacc;
?>
</div>

<div class="col-md-6">
	<div id="treeRelationItems"></div>
	<div id="treeRelationItemsParams"></div>
</div>
<div style="display:none;" id="modalWraper">
	<div id="modalArea">
		<span onclick="closeModal()" class="modalClose">
			<img src="https://ies.unitech-mo.ru/files/upload/images/close_privacy.png"/>
		</span>
		<form id="actionForm"></form>
		<div style="margin-top:10px;" id="errors"></div>
	</div>
</div>
<div id="actionOptions" class="actionOptions" style="display:none;"></div>
<div onclick="scroller()" id="finResScroller" class="scroller" style="display:none;"></div>
<script>
var relation = [], relation_params = [], grand_parent = 0, params_parent = 0;
$(function () {
    $('.tree li:has(ul)').addClass('parent_li').find(' > span').attr('title', 'Развернуть');
    $('.tree li.parent_li > span').on('click', function (e) {
        var children = $(this).parent('li.parent_li').find(' > ul > li');
        if (children.is(":visible")&&!$(this).attr('data-tiny')) {
            children.hide('fast');
            $(this).attr('title', 'Развернуть').find(' > i').addClass('icon-plus-sign').removeClass('icon-minus-sign');
        } else {
            children.show('fast');
            $(this).attr('title', 'Свернуть').removeAttr('data-tiny').find(' > i').addClass('icon-minus-sign').removeClass('icon-plus-sign');
			getTreeRelationItems($(this).attr('data-id'));
        }
        e.stopPropagation();
    });
	$('.tree li > span').on('click', function (e) {
		if(!$(this).parent('li').attr('class')){
			$('li.parent_li').find(' > ul > li').hide();
			$('li.parent_li > span').attr('title', 'Развернуть').find(' > i').addClass('icon-plus-sign').removeClass('icon-minus-sign');
			$(this).parents('li').show();
			$(this).parents('li').find('span').attr('data-tiny', true);
			getTreeRelationItems($(this).attr('data-id'));
		}	
	});
});

function getTreeRelationItems(id){
	relation = [];
	relation_params = [];
	grand_parent = id;
	$('#treeRelationItems').html('<img style="margin:5px 0 10px 42%;" src="https://ies.unitech-mo.ru/files/upload/pages/image/32.gif" alt="Загрузка..." title=""/>');
	$.post("/ppc/web/index.php?r=v_bankdocsacc", {"relation_item":id},function(data){
		var j = 0, itemsHtml = '<table class="table table-striped table-bordered"><thead><tr><th></th><th>Тип</th><th>Номер</th><th style="text-align:center;"><a href="javascript:itemActions(1,1)" title="Добавить" aria-label="Добавить"><span class="glyphicon glyphicon-plus"></span></a></th></tr></thead><tbody id="RelationItemsTable">';	
		if(data!=''){
			console.log(data);	
			$.each(data, function (){	
			itemsHtml+= '<tr data-id="'+data[j]['rn']+'"><td><input onclick="getTreeRelationItemsParams(this)" type="checkbox" name="selection[]" value="'+data[j]['rn']+'"></td><td>'+data[j]['bank_doctype']+'</td><td>'+data[j]['bank_docpref']+data[j]['bank_docnumb']+'</td><td style="text-align:center;vertical-align:middle;"><a title="Опции" aria-label="Опции" href="javascript:showActions(1, '+data[j]['rn']+');"><span class="glyphicon glyphicon-menu-hamburger"></span></a></td></tr>';
			relation[data[j]['rn']+'_id'] = data[j]['rn'];
			relation[data[j]['rn']+'_nc'] = data[j]['company']; 
			relation[data[j]['rn']+'_dt'] = data[j]['bank_doctype'];
			relation[data[j]['rn']+'_dp'] = data[j]['bank_docpref'];	
			relation[data[j]['rn']+'_dn'] = data[j]['bank_docnumb']; 
			relation[data[j]['rn']+'_dd'] = data[j]['bank_docdate'];			
			j++;
			});	
		}else{
			itemsHtml+= '<tr><td colspan="4">Ничего нет.</td></tr>';
		}
		itemsHtml+='</tbody></table>';
		$('#treeRelationItems').html(itemsHtml);
		$('#finResScroller').html('').attr('onclick','scroller()').hide();
		$('#treeRelationItemsParams').html('<table class="table table-striped table-bordered"><thead><tr><th>Родитель</th><th>Раздел</th><th style="text-align:center;"><a style="display:none;" id="addDocParent" href="javascript:itemActions(1,2)" title="Добавить" aria-label="Добавить"><span class="glyphicon glyphicon-plus"></span></a></th></tr></thead><tbody id="RelationItemsParamsTable"></tbody></table><div style="display:none;" id="itemParamsPreload"><img style="margin:5px 0 0 42%;" src="https://ies.unitech-mo.ru/files/upload/pages/image/32.gif" alt="Загрузка..." title=""/></div>');	
	},"json").fail(function(xhr) {
		$('#treeRelationItems').html(xhr.responseText);
	});
}

function getTreeRelationItemsParams(obj){

	var id = obj.value, selectionCount = $('input[name="selection[]"]:checked').length;
	
	if(selectionCount == 0){
		$('#finResScroller').html('').hide();
	}else{
		$('#finResScroller').attr('onclick','scroller()').html('Отмечено ' + selectionCount + ', перейти к просмотру').show();
	}
	
	if(selectionCount == 1){
		$('#addDocParent').show();
		params_parent = $('input[name="selection[]"]:checked').val();
		//console.log(params_parent);
	}else{
		$('#addDocParent').hide();
	}
	
	if(!$(obj).prop('checked')){
		$('[data-parent-id="'+id+'"]').remove();
	}else{
		var j = 0, itemsHtml = '';
		$('#itemParamsPreload').show();
		$.post("/ppc/web/index.php?r=v_bankdocsacc", {"relation_item_params":id},function(data){		
			if(data!=''){
				console.log(data);	
				$.each(data, function (){	
				itemsHtml+= '<tr data-id="'+data[j]['nrn']+'" data-parent-id="'+id+'"><td>'+relation[id+'_dn']+'</td><td>'+data[j]['sunitname']+'</td><td style="text-align:center;vertical-align:middle;"><a title="Опции" aria-label="Опции" href="javascript:showActions(2, '+data[j]['nrn']+');"><span class="glyphicon glyphicon-menu-hamburger"></span></a></td></tr>';
				relation_params[data[j]['nrn']+'_id'] = data[j]['nrn'];
				relation_params[data[j]['nrn']+'_nprn'] = data[j]['nprn'];
				relation_params[data[j]['nrn']+'_nc'] = data[j]['ncompany'];
				relation_params[data[j]['nrn']+'_ec'] = data[j]['seconclass'];
				relation_params[data[j]['nrn']+'_es'] = data[j]['sexpstruct'];
				relation_params[data[j]['nrn']+'_bu'] = data[j]['sbalunit'];
				relation_params[data[j]['nrn']+'_sut'] = data[j]['npay_sum'];
				j++;
				});	
			}else{
				itemsHtml+= '<tr data-parent-id="'+id+'"><td>'+relation[id+'_dn']+'</td><td colspan="2">Ничего нет.</td></tr>';
			}
			
			$('#itemParamsPreload').hide();
			$('#RelationItemsParamsTable').append(itemsHtml);
 	
		},"json").fail(function(xhr) {
			$('#RelationItemsParamsTable').html('<tr><td colspan="3">'+xhr.responseText+'</td></tr>');
			$('#itemParamsPreload').hide();
		});
	}
}

function scroller(){
	$("#treeRelationItems table tbody tr td input").each(function(){
		if(!$(this).prop('checked')){
			var ptd = $(this).parent('td');
			$(ptd).parent('tr').hide();
		}
	});
	$('#finResScroller').attr('onclick','resetSelection()').html('Сбросить фильтр');
	var position = $("#treeRelationItemsParams").offset().top-60;
	$("html, body").animate({ scrollTop: position }, 1000);
}

function resetSelection(){
	$("#RelationItemsTable tr td input").prop('checked', false);
	$("#RelationItemsTable tr").show();
	$('#RelationItemsParamsTable tr').remove();
	relation_params = [];
	$('#finResScroller').attr('onclick','scroller()').hide();
	$('#addDocParent').hide();
	hideActions();
	var position = $("#treeRelationItems").offset().top-60;
	$("html, body").animate({ scrollTop: position }, 1000);
}

function showActions(type, id){
	$('tr').css('background','');
	$('[data-id="'+id+'"]').css('background','#d7f3f7');
	$('#actionOptions').show();
	$('#actionOptions').html('<a href="javascript:itemActions(2,'+type+','+id+')" title="Редактировать" style="color:orange;" aria-label="Редактировать">Редактировать&nbsp;<span class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="javascript:itemActions(3,'+type+','+id+')" title="Удалить" style="color:red;" aria-label="Удалить" data-confirm="Вы уверены что хотите удалить?" data-method="post">Удалить&nbsp;<span class="glyphicon glyphicon-trash"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="javascript:hideActions()" title="Отмена" aria-label="Отмена" data-method="post">Отмена&nbsp;<span class="glyphicon glyphicon-remove"></span></a>');
}

function hideActions(){
	$('#actionOptions').hide();
	$('#actionOptions').html('');
	$('tr').css('background','');
}

function itemActions(action, itemType, itemId = 0){
	itemFormHtml = '', nb_checked = '', nsc_checked = '', nsd_checked = '';
	if(action==1){
		$('#modalWraper').show();
		if(itemType==1){
			itemFormHtml+= '<input type="hidden" name="type" value="1"/><input type="hidden" name="action" value="1"/><input type="hidden" name="nc" value="68878"/><input type="hidden" name="pid" value="'+grand_parent+'"/>Мнемокод: <input class="form-control input-sm" type="text" name="dc" value=""/>Наименование:<input class="form-control input-sm" type="text" name="dn" value=""/>Код классификации:<input class="form-control input-sm" type="text" name="sc" value=""/>';
		}else{
			itemFormHtml+= '<input type="hidden" name="type" value="2"/><input type="hidden" name="action" value="1"/><input type="hidden" name="nc" value="68878"/><input type="hidden" name="pid" value="'+params_parent+'"/><input type="hidden" name="dc" value="0"/>Раздел:<input class="form-control input-sm" type="text" name="dn" value=""/><br/><label><input type="checkbox" name="nb" value="1"/>Основание</label>&nbsp;&nbsp;<label><input type="checkbox" name="nsc" value="1"/>Подтверждение</label>&nbsp;&nbsp;<label><input type="checkbox" name="nsd" value="1"/>Документ</label>';
		}
		itemFormHtml+='<input type="hidden" name="modify" value="1"/><a style="float:right;" class="btn btn-primary" href="javascript:modifyItem()">Применить</a><br clear="all"/>';	
		$('#actionForm').html(itemFormHtml);
	}else if(action==2){
		$('#modalWraper').show();
		if(itemType==1){
			itemFormHtml+= '<input type="hidden" name="type" value="1"/><input type="hidden" name="action" value="2"/><input type="hidden" name="id" value="'+itemId+'"/><input type="hidden" name="nc" value="'+relation[itemId+'_nc']+'"/><input type="hidden" name="nv" value="'+relation[itemId+'_nv']+'"/>Мнемокод: <input class="form-control input-sm" type="text" name="dc" value="'+relation[itemId+'_dc']+'"/>Наименование:<input class="form-control input-sm" type="text" name="dn" value="'+relation[itemId+'_dn']+'"/>Код классификации:<input class="form-control input-sm" type="text" name="sc" value="'+relation[itemId+'_sc']+'"/>';
		}else{
			itemFormHtml+= '<input type="hidden" name="type" value="2"/><input type="hidden" name="action" value="2"/><input type="hidden" name="nc" value="'+relation_params[itemId+'_nc']+'"/><input type="hidden" name="id" value="'+itemId+'"/><input type="hidden" name="pid" value="'+relation_params[itemId+'_nprn']+'"/><input type="hidden" name="dc" value="'+relation_params[itemId+'_sut']+'"/>Раздел:<input class="form-control input-sm" type="text" name="dn" value="'+relation_params[itemId+'_sun']+'"/>';
			if(relation_params[itemId+'_nb']==1){
				nb_checked = 'checked';
			}
			if(relation_params[itemId+'_nsc']==1){
				nsc_checked = 'checked';
			}
			if(relation_params[itemId+'_nsd']==1){
				nsd_checked = 'checked';
			}
			itemFormHtml+='<br/><label><input '+nb_checked+' type="checkbox" name="nb" value="1"/>Основание</label>&nbsp;&nbsp;<label><input '+nsc_checked+' type="checkbox" name="nsc" value="1"/>Подтверждение</label>&nbsp;&nbsp;<label><input '+nsd_checked+' type="checkbox" name="nsd" value="1"/>Документ</label>';
		}
		itemFormHtml+='<input type="hidden" name="modify" value="1"/><a style="float:right;" class="btn btn-primary" href="javascript:modifyItem()">Применить</a><br clear="all"/>';	
		$('#actionForm').html(itemFormHtml);
	}else if(action==3){
		if(itemType==1){
			$('#actionForm').html('<input type="hidden" name="modify" value="1"/><input type="hidden" name="type" value="1"/><input type="hidden" name="action" value="3"/><input type="hidden" name="nc" value="'+relation[itemId+'_nc']+'"/><input type="hidden" name="id" value="'+itemId+'"/>');
			modifyItem();
		}
		if(itemType==2){
			$('#actionForm').html('<input type="hidden" name="modify" value="1"/><input type="hidden" name="type" value="2"/><input type="hidden" name="action" value="3"/><input type="hidden" name="nc" value="'+relation_params[itemId+'_nc']+'"/><input type="hidden" name="id" value="'+itemId+'"/><input type="hidden" name="pid" value="'+relation_params[itemId+'_nprn']+'"/>');
			modifyItem();
		}		
	}
}

function closeModal(){
	$('#modalWraper').hide();
	$('#actionForm').html('');
	$('#errors').html('');
}

function modifyItem(){
	$.post("/ppc/web/index.php?r=v_bankdocsacc", $('#actionForm').serialize(), function(data){
		
		if(data.action == 1 && data.nrn > 0){
			closeModal();
			if(data.type == 1){
				$('#RelationItemsTable').prepend('<tr data-id="'+data.nrn+'"><td><input onclick="getTreeRelationItemsParams(this)" type="checkbox" name="selection[]" value="'+data.nrn+'"></td><td>'+data.dc+'</td><td>'+data.dn+'</td><td style="text-align:center;vertical-align:middle;"><a title="Опции" aria-label="Опции" href="javascript:showActions('+data.type+', '+data.nrn+');"><span class="glyphicon glyphicon-menu-hamburger"></span></a></td></tr>');
				relation[data.nrn+'_id'] = data.nrn;
				relation[data.nrn+'_nc'] = data.nc;
				relation[data.nrn+'_nv'] = data.nv;
				relation[data.nrn+'_sc'] = data.sc;
				relation[data.nrn+'_dc'] = data.dc;
				relation[data.nrn+'_dn'] = data.dn;
			}else{
				//console.log(data);
				$('[data-parent-id="'+data.pid+'"]>td:contains("Ничего нет")').parent('tr').remove();
				$('#RelationItemsParamsTable').prepend('<tr data-id="'+data.nrn+'" data-parent-id="'+data.pid+'"><td>'+relation[data.pid+'_dn']+'</td><td>'+data.dn+'</td><td style="text-align:center;vertical-align:middle;"><a title="Опции" aria-label="Опции" href="javascript:showActions(2, '+data.nrn+');"><span class="glyphicon glyphicon-menu-hamburger"></span></a></td></tr>');
				relation_params[data.nrn+'_id'] = data.nrn;
				relation_params[data.nrn+'_nprn'] = data.pid;
				relation_params[data.nrn+'_nc'] = data.nc;
				relation_params[data.nrn+'_nb'] = data.nb;
				relation_params[data.nrn+'_nsc'] = data.nsc;
				relation_params[data.nrn+'_nsd'] = data.nsd;
				relation_params[data.nrn+'_sut'] = data.dc;
				relation_params[data.nrn+'_sun'] = data.dn;
			}	
		}
		
		if(data.action == 2 && data.success){
			closeModal();
			if(data.type == 1){
				$('[data-id="'+data.id+'"]').html('<td><input onclick="getTreeRelationItemsParams(this)" type="checkbox" name="selection[]" value="'+data.id+'"></td><td>'+data.dc+'</td><td>'+data.dn+'</td><td style="text-align:center;vertical-align:middle;"><a title="Опции" aria-label="Опции" href="javascript:showActions('+data.type+', '+data.id+');"><span class="glyphicon glyphicon-menu-hamburger"></span></a></td>');
				relation[data.id+'_dc'] = data.dc;
				relation[data.id+'_dn'] = data.dn;
				relation[data.id+'_sc'] = data.sc;
			}else{
				$('[data-id="'+data.id+'"]').html('<td>'+relation[data.pid+'_dn']+'</td><td>'+data.dn+'</td><td style="text-align:center;vertical-align:middle;"><a title="Опции" aria-label="Опции" href="javascript:showActions('+data.type+', '+data.id+');"><span class="glyphicon glyphicon-menu-hamburger"></span></a></td>');
				relation_params[data.id+'_nb'] = data.nb;
				relation_params[data.id+'_nsc'] = data.nsc;
				relation_params[data.id+'_nsd'] = data.nsd;
				relation_params[data.id+'_sut'] = data.dc;
				relation_params[data.id+'_sun'] = data.dn;
			}	
			
		}
		
		if(data.action == 3 && data.success){
			closeModal();
			$('[data-id="'+data.id+'"]').remove();
		}
		
		hideActions();
		
	}, 'json').fail(function(xhr) {
		$('#errors').html(xhr.responseText);
	});

}

</script>

