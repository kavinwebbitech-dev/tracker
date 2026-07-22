
@if($user_details)
@foreach($user_details as $key => $value)
<?php
	$project_details = \App\Models\Project::where('added_by', $value->id)->where('created_at', 'like', '%'.$search_date.'%')->get();
?>
@if(count($project_details) > 0)
<h5>{{ $value->name }}</h5>

<table id="example1" class="table table-bordered table-striped">
	<thead>
		<tr>
			<th>S.no</th>
			<th>Project</th>
			<th>Total Amount</th>
		</tr>
	</thead>
	<tbody>
		
		@foreach($project_details as $key1 => $value1)
		<tr>
			<td>{{ $key1 + 1 }}</td>
			<td>{{ $value1->name }}</td>
			<td>{{ $value1->bid_amount }}</td>
		</tr>
		@endforeach
		
	</tbody>
</table>
@endif
@endforeach
@endif