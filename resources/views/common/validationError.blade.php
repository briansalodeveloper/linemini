@if($errors->has($key))
    <span class="error invalid-feedback{{ isset($class) ? ' ' . $class : '' }}">{{ $errors->first($key) }}</span>
@endif
