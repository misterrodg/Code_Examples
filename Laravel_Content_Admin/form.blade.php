<script type="text/javascript">
$(document).ready(function () {
  $('#create').validate({
    rules : {
      contentthumb: {
        required: true
      }
    }
  });
  $('#create,#edit').validate({
    rules : {
    	contentname : 'required',
      description : 'required',
    },
    messages : {
  		contentname :'Please enter the content name.',
  		description : 'Please enter the content description.',
    }
  });
});

function submitClick(){
  if( $("#create,#edit").valid()){
    $("#btndisable").prop('disabled',true);
		$("#create,#edit").submit();
  } else {
 		$("#btndisable").removeAttr( "disabled" );
  }
}
</script>

<section class="content-body-container">
	<div class="content-container">
		<div class="container-fluid">
			<div class="row">
				<div class="col-12 pt30">
					<div class="panel panel-default solidbg">
            <div class="form-container">
              <div class="row col-12">
            		<div class="col-12 col-sm-4 col-md-4">
            			<div class="form-group">
            				<label><sub>*</sub>Content Name</label>
            				<input type="text" name="contentname" class="form-control" value="{{$contentDetails['contentname'] ?? '' }}">
            			</div>
            		</div>
								<div class="col-12 col-sm-4 col-md-4">
									<div class="form-group">
										<label><sub>*</sub>Vimeo Video ID</label>
										<input type="text" name="videoid" class="form-control" value="{{$contentDetails['vimeovideoid'] ?? '' }}">
                    <span style="text-transform:none;font-size:14px;">vimeo.com/video/<span style="color:#FF0000;">430035394</span></span>
									</div>
								</div>
              </div>
            	<div class="row col-12">
                <div class="col-12">
            			<div class="form-group">
            				<label for="description"><sub>*</sub>Description</label>
            				<textarea name="description" class="form-control" style="height:75px;">{{$contentDetails['description'] ?? '' }}</textarea>
            			</div>
            		</div>
              </div>
              <div class="row col-12">
            		<div class="col-sm-4 col-md-4">
            			<div class="form-group mt20">
            				<label for="isprivate">Hidden</label>
            				<input type="checkbox" name="isprivate" class="form-control" value=1 @if(!empty($contentDetails) && ($contentDetails['isprivate'] == 1)) checked @endif>
            			</div>
            		</div>
                <div class="col-sm-4 col-md-4">
                  <div class="form-group">
                    <label>@if(empty($contentDetails))<sub>*</sub>@endif Thumbnail Image</label>
                    @if(!empty($contentDetails))
                      <div style="font-size:10px;">Only add an image if you wish to update it.</div>
                    @endif
                    <input type="file" name="thumbnailimage">
                  </div>
                </div>
							</div>
							<div class="row">
								<div class="col-12 tc pt10 pb10">
									<button type="button" id="btndisable" onclick="submitClick()"class="btn btn-submit" ><a>{{ !empty($contentDetails) ? 'Update' : 'Create'}}</a></button>
									<a class="btn btn-cancel" href="{{url(ADMIN.('/content'))}}">Cancel</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
