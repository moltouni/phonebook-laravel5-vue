@extends('layouts.app')

@section('content')

    <router-view :key="$route.fullPath"></router-view>

@endsection
