@extends('inventory.inventory')

@section('title', 'RS | Dashboard')

@section('header-page')


		
					<div class="col-lg-10">
						<h2>Welcome {{{ Auth::guard('inventory')->user()->first_name }}}!</h2>
					
					</div>
			
	
@endsection

@section('content')





Remote Staff Inventory Management System

@endsection
<!--

@section('scripts')
@endsection-->

