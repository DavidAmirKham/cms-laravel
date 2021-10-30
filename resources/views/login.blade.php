@extends('layouts.base')

@section('content')
    <div class="login">
        <div>
            <p class="title">CMS Login</p>
        </div>
        @if ($errors->any())
            <div class="errors">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (isset($fail))
            <div class="danger text-center">{{ $fail }}</div>
        @endif
        <form action="{{ url('/authenticate') }}" method="POST">
            @csrf
            <table>
                <tr>
                    <td>ID/Mail address</td>
                    <td><input type="text" name="id_or_mail" /></td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td><input type="password" name="password" /></td>
                </tr>
                <tr>
                    <td colspan="2" class="text-center"><input type="submit" value="LOGIN" /></td>
                </tr>
            </table>
        </form>
    </div>
@endsection
