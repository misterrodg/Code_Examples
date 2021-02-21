@extends('admin.master')
@section('content')
{!! Form::open([
  'url' => [ADMIN.'/content/edit/'.$contentDetails['itemkey']],
  'method' => 'post',
  'role' => 'form',
  'id' => 'edit',
  'autocomplete' => 'off',
  'files' => true
]) !!}
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
								<h2 @if(ENVIRONMENT == 'Staging') style="color:#610218;" @endif>Edit Content @if(ENVIRONMENT == 'Staging')- {{ENVIRONMENT}}@endif</h2>
							</div>
							<div class="create-btn-wrapper">
								<a class="create-btn" href="{{url(ADMIN.'/content')}}" data-toggle="tooltip" data-placement="bottom" title="Back"><i class="fas fa-arrow-left"></i></a>
							</div>
						</div>
					</div>
				</div>
			</header>
			@include('admin.content.form')
		</div>
	</div>
<input type="hidden" name="mode" value="1">
{!! Form::close() !!}
@stop
