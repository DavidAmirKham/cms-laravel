@extends('layouts.base')

@section('content')
    <div class="news">
        <form action="{{ url('/search_news') }}" method="GET">
            @csrf
            <div class="search-bar">
                <input type="text" name="search_text" class="search-text" value="{{ $search_text }}" />
                <input type="submit" id="search" class="search-button" value="Search"/>
            </div>
            <table id="items">
                @if(isset($data))
                    @foreach($data as $item)
                        <tr>
                            <td style="width: 100px">ID: {{ $item->id }}</td>
                            <td style="width: 200px">FROM: {{ $item->publish_from }}</td>
                            <td style="width: 300px">TO: {{ $item->publish_to }}</td>
                            <td style="width: 300px">NEWS: {{ $item->news }}</td>
                            <td style="width: 100px"><a href="/news_edit/{{ $item->id }}" class="edit">Edit</a></td>
                            <td style="width: 100px"><a href="/news_delete/{{ $item->id }}/{{ $search_text }}" class="delete" onclick="return confirm('Are you sure to delete?')">Delete</a></td>
                        </tr>
                    @endforeach
                @endif
            </table>
            @if(isset($data))
                {!! $data->onEachSide(1)->links('pagination::bootstrap-4') !!}
            @endif
        </form>
    </div>
@endsection