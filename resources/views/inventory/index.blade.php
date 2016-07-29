@extends('inventory.inventory')

@section('title', 'RS | Dashboard')
@section('sidebarDashboard')
<li class="active"><a href="/inventory/index"><i class="fa fa-th-large"></i> <span
							class="nav-label">Dashboard</span></a></li>
@endsection
@section('header-page')


		
					<div class="col-lg-10">
						<h2>Welcome {{ Auth::guard('inventory')->user()->adminProfile ? Auth::guard('inventory')->user()->adminProfile->first_name.' '.Auth::guard('inventory')->user()->adminProfile->last_name : '' }}!
							</h2>
										
					</div>
			
	
@endsection

@section('content')






</script>
@endsection


