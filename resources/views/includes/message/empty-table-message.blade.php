<tr>
<td colspan="{{ $colspan }}">
	@isset($level)
	<div class="alert alert-{{ $level }} text-center">{{ $message?: 'No record added yet ...'  }}</div>
	@else
		<p>{{ $slot }}</p>
	@endisset
</td>
</tr>