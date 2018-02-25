<div data-name="{{ $name or 'error-board' }}" class="error-summary alert alert-danger hidden">
  <p>{{ 'Please check your provided inputs!' }}<p/>
  {{ $slot }}
</div>