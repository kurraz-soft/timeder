@extends('layouts.app')

@section('meta_title', 'Dashboard')

@push('scripts')
<script>
var USER_LIST = @json($users);
</script>
@endpush

@section('content')
<router-view></router-view>
@endsection