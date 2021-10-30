@extends('layouts.base')

@section('content')
    <div class="home">
        <h1 class="text-center">Idealump Test Page</h1>
        <ul>
            <li><a href="/weather">&#62; Weather</a></li>
            <li><a href="/news">&#62; CMS news</a></li>
        </ul>
        <table>
            <tr>
                <td style="width: 100px"><img src="http://openweathermap.org/img/w/{{ $weather_data['weather'][0]['icon'] }}.png" /></td>
                <td style="width: 100px">{{ date('Y/m') }}</td>
                <td style="width: 150px">{{ $weather_data['name'] }}</td>
                <td style="width: 150px">{{ $weather_data['weather'][0]['description'] }}</td>
                <td style="width: 100px">{{ $weather_data['main']['humidity'] }}℃</td>
            </tr>
            @foreach ($news_data as $item)
                <tr>
                    <td colspan="2">{{ $item->publish_from }}</td>
                    <td colspan="3">{{ $item->news }}</td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
