@extends('layouts.base')

@section('content')
    <div class="news">
        <div class="nav">
            <p class="news-title">CMS Login</p>
            <p class="float-right">{{ Session::get('id_or_mail') }}</p>
        </div>
        <div class="main">
            <div class="sidebar">
                <ul>
                    <li><a href="/news_list">News list</a></li>
                    <li><a href="/news_add">News add</a></li>
                    <li><a href="/logout">Logout</a></li>
                </ul>
            </div>
            <div class="content">
                Contents
            </div>
        </div>
    </div>
@endsection
