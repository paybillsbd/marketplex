<tr>
<td colspan="{{ $colspan }}">
	@isset($level)
	<div class="alert alert-{{ $level }} text-center empty-row">{{ $message?: 'No record added yet ...'  }}</div>
	@else
		<p>{{ $slot }}</p>
	@endisset
</td>
</tr>