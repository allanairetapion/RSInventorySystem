@extends('layouts.master')

@section('title', 'Remote Staff Inventory System')


@section('header-page')

@endsection

@section('content')
	<div class="row">
		<div class="col-lg-12">
			Welcome {{$client_profile->first_name}} {{$client_profile->last_name}}
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12">
			This is the help desk of Remote Staff Inc.			
		</div>
	</div>
	
	<div class="row">
		<div class="col-lg-6">
			<div class="ibox float-e-margins">
                    
                    <div class="ibox-content">
                        <button class="btn btn-default">Report Problem</button>
                    </div>
                 
                </div>
		</div>
		<div class="col-lg-6">
			<div class="ibox float-e-margins">
                    
                   <div class="ibox-content">
                        <button class="btn btn-default">Check Status</button>
                    </div>
                    
                </div>
		</div>
	</div>
@endsection
