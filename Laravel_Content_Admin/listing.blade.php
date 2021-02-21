@extends('admin.master')
@section('content')
<?php $this->Corefunctions = new \App\customclasses\Corefunctions; ?>
<script>
$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip({
    trigger : 'hover'
  });
});
function statchange(contentid,status){
  swal({
    title: "Are you sure you want to make this content "+status+"? ",
    text: "",
    type: "warning",
    showCancelButton: true,
    confirmButtonClass: "btn-danger",
    confirmButtonText: "OK",
    closeOnConfirm: true,
    showLoaderOnConfirm: true
  },
  function(){
    $.ajax({
      url: '{{ url(ADMIN."/content/changestatus") }}',
      type: "post",
      data: {'contentid':content,'type':'content','status':status,'act':'statchange','_token':$('meta[name="_token"]').attr('content')},
      success: function(data){
      if(data.success==1){
         $("#id_"+contentid).remove();
          var len = $(".listvw").length;
          if(len < 1){
         		window.location.reload();
          }
        }else if(data.error==1){
          swal(data.errormsg, "", "error");
        }
      }
    });
  });
}

function statdelete(contentid){
  swal({
    title: "Are you sure you want to delete this content? ",
    text: "",
    type: "warning",
    showCancelButton: true,
    confirmButtonClass: "btn-danger",
    confirmButtonText: "OK",
    closeOnConfirm: true,
    showLoaderOnConfirm: true
  },
  function(){
    $.ajax({
      url: '{{ url(ADMIN."/content/delete") }}',
      type: "post",
      data: {'contentid':contentid,'type':'content','act':'delete', '_token': $('meta[name="_token"]').attr('content')},
      success: function(data){
        if(data.success==1){
          $("#id_"+contentid).remove();
          var len = $(".listvw").length;
          if(len < 1){
            window.location.reload();
          }
        }else if(data.error==1){
          swal(data.errormsg, "", "error");
        }
      }
    });
  });
}

$(function() {
  $("#sortable").sortable({
    handle: ".drag-icons",
    stop: function(event, ui) {
      updateSortOrder();
    }
  });
});

function updateSortOrder() {
  var cnt = 1;
  var keyArray = [];
  $(".bnumbers").each(function() {
    $(this).text(cnt);
    var bkey = $(this).attr('data');
    keyArray[cnt - 1] = bkey;
    cnt++;
  });
  $.ajax({
    url: '{{ url(ADMIN."/updateContentSortOrder") }}',
    type: "post",
    data: {
      'act': 'updateorder',
      '_token': $('input[name=_token]').val(),
      'contentids': keyArray
    },
    success: function(data) {}
  });
}
</script>
<style>
.search-container .error {
  top: 50px;
}
.drag-icons {
  position: relative;
  width: 24px;
  height: 17px;
  border-top: 3px solid #dddddd;
  border-bottom: 3px solid #dddddd;
  margin-left: 20px;
  margin-top: 25px;
  cursor: move;
}
</style>
	<div class="wrapper">
		<div class="layout-content">
			<header>
				<div class="container-fluid">
					<div class="row">
						<div class="col-12 pr0 pl0-sm">
							<div class="main-heading-container">
								<div class="mobile-menu-container">
									<div class="toggle-menu"></div>
								</div>
								<h2 @if(ENVIRONMENT == 'Staging') style="color:#610218;" @endif>Content @if(ENVIRONMENT == 'Staging')- {{ENVIRONMENT}}@endif</h2>
							</div>
							<div class="create-btn-wrapper">
								<a class="create-btn" href="{{url(ADMIN. '/content/create')}}" data-toggle="tooltip" data-placement="bottom" title="Create"><i class="fas fa-plus"></i></a>
							</div>
						</div>
					</div>
				</div>
			</header>
			<section class="content-body-container">
				<div class="content-container">
					<div class="container-fluid">
						<div class="row">
						  <div class="col-12">
  							<div class="space-card mb20 tc">
  							</div>
						  </div>
						</div>
						<div class="row" id="norecord">
							<div class="col-12">
								<div class="panel panel-default" id="sortable">
                  <div class="panel-heading hidden-sm pro-list-each">
                    <div class="row">
                      <div class="col-12 col-sm-10"><h3>Content Name</h3></div>
                      <div class="col-12 col-sm-2 tr"><h3>Options</h3></div>
                    </div>
                  </div>
                  <?php $count = 1; ?>
                  @forelse ($result as $row)
                    <div class="drag-rows drag-rows-hide panel-body mt10-sm listvw" id="id_{{$row['contentid']}}">
                      <div class="row panel-list hover-transform respnsv-sm-center pro-list-each">
                        <div class="drag-handle">
                          <div data="{{$row['contentid']}}" class="drag-numbers bnumbers">{{$count}}</div>
                          <div class="drag-icons"></div>
                        </div>
                      	<div class="col-12 col-md-10">
  					              <div class=" d-flex align-items-center">
                            <a href="{{ url(ADMIN. '/content/details/'.$row['contentkey'])}}">
                              <h4>{{$row['contentname']}}@if($row['isprivate'] == '1')<span style="font-weight:normal;font-size:10px;"> - Private </span>@endif</h4>
                            </a>
  					              </div>
                      	</div>
                        <div class="col-12 col-md-2" id="options_{{$row['contentid']}}">
                          <a class="option-btn FR FN-sm" href="Javascript:void(0)" onclick="statdelete('{{ $row['contentid'] }}')"  data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
                          <a class="option-btn FR FN-sm" href="{{url(ADMIN.'/content/edit/'.$row['contentkey'])}}" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fas fa-pencil-alt"></i></a>
                          <a class="option-btn FR FN-sm" href="{{'https://player.vimeo.com/video/'.$row['vimeovideoid']}}" target="_blank" data-toggle="tooltip" data-placement="top" title="View Video Link"><i class="fas fa-external-link-alt"></i></a>
                      	</div>
                      </div>
                    </div>
                    <?php $count++; ?>
	                @empty
			               <div class='col-12'><div class='list-card'><div class='alert alert-box mb0 tc'><p>No records found<p></div></div></div>
		              @endforelse
								  <nav class="FR">
									</nav>
			          </div>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>
@stop
