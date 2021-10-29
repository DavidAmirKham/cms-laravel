@extends('layouts.base')
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"></script>
<script>
  $( function() {
    $( "#datepicker1, #datepicker2" ).datepicker({ dateFormat: 'yy/mm/dd' });
  } );
</script>
@section('content')
    <div class="news">
        @if ($errors->any())
            <div class="errors">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (isset($success))
            <div class="success text-center">{{ $success }}</div>
        @endif
        @if (isset($fail))
            <div class="danger text-center">{{ $fail }}</div>
        @endif
        <form action="{{ isset($data->id) ? '/news_update/'.$data->id : '/news_insert' }}" method="POST" >
            @csrf
            <table>
                <tr>
                    <td class="left">ID</td>
                    <td><input type="text" name="id" value="{{ isset($id) ? $id:$data->id }}" readonly/></td>
                </tr>
                <tr>
                    <td class="left">PUBLISH FROM</td>
                    <td><input type="text" name="publish_from" id="datepicker1" value="{{ $data->publish_from ?? '' }}" autocomplete="off"/></td>
                </tr>
                <tr>
                    <td class="left">PUBLISH TO</td>
                    <td class="left"><input type="text" name="publish_to" id="datepicker2" value="{{ $data->publish_to ?? '' }}" autocomplete="off"/></td>
                </tr>
                <tr>
                    <td class="left">NEWS</td>
                    <td><textarea name="news"> {{ $data->news ?? '' }}</textarea></td>
                </tr>
                <tr>
                    <td>STATUS</td>
                    <td>
                        <label>Enable<input type="radio" name="status" value="1" checked/></label>
                        <label>Disable<input type="radio" name="status" value="0" {{ (isset($data->status) && $data->status == 0 ? ' checked':'') }}/></label>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="text-center">
                        <input type="submit" name="add" value="ADD"/>
                    </td>
                </tr>
            </table>
        </form>
    </div>
@endsection