@extends('layouts.base')

@section('content')
    <div class="login">
    <p class="title">CMS Login</p>
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
                <td><input type="submit" value="LOGIN" /></td>
            </tr>
        </table>
    </div>
@endsection