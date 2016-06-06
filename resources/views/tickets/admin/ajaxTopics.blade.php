
<table class="table table-striped table-bordered topics">
	<thead>
		<tr>
			<th class="text-center">
			<input type="checkbox" class="topicCB">
			</th>
			<th class="text-center">Description</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($topics as $topic)
		<tr>

			<td class="text-center"> @if ($topic->status == 1)
			<input class="topic" type="checkbox" name ="{{$topic->topic_id}}" value="{{$topic->topic_id}}" checked>
			@else
			<input class="topic" type="checkbox" name ="{{$topic->topic_id}}" value="{{$topic->topic_id}}">
			@endif </td>

			<td class="text-center"> {{$topic->description}}</td>
		</tr>
		@endforeach
	</tbody>
</table>

<script>
	$("input.topicCB").change(function() {
				$("input.topic:checkbox").prop('checked', $(this).prop("checked"));
			});
</script>